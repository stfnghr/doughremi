<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Courier; // Assuming you have a Courier model for random courier selection
use App\Models\Order; // Assuming you have an Order model for order management
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth; // Make sure this is present

class OrderController extends Controller
{
    /**
     * Handles the initial request to start customizing a cookie.
     * Checks for authentication and redirects accordingly.
     * This method fulfills the initial requirement.
     */
    public function startCustomizationProcess(): RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Please log in to start customizing your cookie!');
        }
        // If authenticated, proceed to the customization page
        // This assumes 'custom.index' is the route name for your cookie customization UI page
        return redirect()->route('custom.index');
    }

    public function confirm(Request $request): RedirectResponse|View
    {
        $cartItemsFromSession = Session::get('cart', []); // Get initial cart from session
        $itemPrice = 12000; // Default price for custom cookies

        // Handle POST requests
        if ($request->isMethod('post')) {
            // ACTION 1: Add a new custom cookie (from customization page submission)
            // In the confirm method, when handling POST request for custom cookie
            if ($request->has('shape') && $request->has('color') && $request->has('topping') && !$request->input('intent')) {
                $shape = $request->input('shape');
                $color = $request->input('color');
                $topping = $request->input('topping');
                $colorImgPath = $request->input('color_img');
                $shapeImgPath = $request->input('shape_img'); // Add this
                $toppingImgPath = $request->input('topping_img'); // Add this

                $itemName = "Custom Cookie - {$shape} ({$color}) - {$topping}";
                $newCartItem = [
                    'cart_item_id' => Str::uuid()->toString(),
                    'id' => 'custom-' . Str::slug($shape . '-' . $color . '-' . $topping . '-' . Str::random(4)),
                    'name' => $itemName,
                    'quantity' => 1,
                    'price' => $itemPrice,
                    'is_custom' => true,
                    'image_filename' => $colorImgPath ? basename($colorImgPath) : null,
                    'shape_img' => $shapeImgPath ? basename($shapeImgPath) : null, // Add this
                    'color_img' => $colorImgPath ? basename($colorImgPath) : null, // Add this
                    'topping_img' => $toppingImgPath ? basename($toppingImgPath) : null, // Add this
                    'type' => 'custom',
                ];
                $cartItemsFromSession[] = $newCartItem;
                Session::put('cart', $cartItemsFromSession);
                return redirect()->route('order.confirm')->with('success_cart_update', $itemName . ' added to your order!');
            }
            // ACTION 2: Update quantity of an existing item
            elseif ($request->input('intent') === 'update_quantity' && $request->has('cart_item_id') && $request->has('change')) {
                $validated = $request->validate([
                    'cart_item_id' => 'required|string',
                    'change' => 'required|integer|in:-1,1',
                ]);

                $cartItemIdToUpdate = $validated['cart_item_id'];
                $changeAmount = (int) $validated['change'];
                $currentCart = Session::get('cart', []);
                $itemUpdated = false;
                $updatedItemName = 'Item';

                foreach ($currentCart as &$cartItemInLoop) { // Use reference
                    if (isset($cartItemInLoop['cart_item_id']) && $cartItemInLoop['cart_item_id'] === $cartItemIdToUpdate) {
                        $updatedItemName = $cartItemInLoop['name'] ?? 'Item';
                        $currentQuantity = $cartItemInLoop['quantity'] ?? 1;
                        $newQuantity = $currentQuantity + $changeAmount;

                        if ($newQuantity < 1) {
                            $cartItemInLoop['quantity'] = 1;
                            Session::flash('info', 'Quantity for ' . $updatedItemName . ' cannot be less than 1.');
                        } else {
                            $cartItemInLoop['quantity'] = $newQuantity;
                            Session::flash('success_cart_update', 'Quantity for ' . $updatedItemName . ' updated.');
                        }
                        $itemUpdated = true;
                        break;
                    }
                }
                unset($cartItemInLoop); // Unset reference

                if ($itemUpdated) {
                    Session::put('cart', $currentCart);
                } else {
                    Session::flash('error_cart_update', 'Could not find the item in your cart to update.');
                }
                return redirect()->route('order.confirm');
            }
        }

        // GET request logic or fall-through from unhandled POST
        $cartItems = Session::get('cart', []);
        $totalAmount = 0;
        if (!empty($cartItems)) {
            foreach ($cartItems as $item) {
                $quantity = isset($item['quantity']) && is_numeric($item['quantity']) ? (int) $item['quantity'] : 1;
                $price = isset($item['price']) && is_numeric($item['price']) ? (float) $item['price'] : 0;
                $totalAmount += $quantity * $price;
            }
        }

        $pageTitle = 'Confirm Order';
        $headTitle = 'Confirm Order';
        if ($request->route()->getName() == 'cart.show') {
            $pageTitle = 'Your Shopping Cart';
            $headTitle = 'Your Shopping Cart';
        }

        if (
            empty($cartItems) && $request->isMethod('get') && Session::hasOldInput() &&
            !Session::has('success_cart_update') && !Session::has('error_cart_update') && !Session::has('info')
        ) {
            Session::flash('info', 'Your cart is now empty.');
        }

        return view('confirm', [
            'layoutTitle' => $pageTitle,
            'headTitle' => $headTitle,
            'cartItems' => $cartItems,
            'totalAmount' => $totalAmount,
            'pageTitle' => $pageTitle,
        ]);
    }

    public function addItemToCart(Request $request): RedirectResponse
    {
        // Auth check you added
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Please log in to add items to your cart!');
        }

        $validatedData = $request->validate([
            'id' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|string',
            'type' => 'sometimes|string',
        ]);

        $cartItems = Session::get('cart', []);
        $cartItem = [
            'cart_item_id' => Str::uuid()->toString(),
            'id' => $validatedData['id'],
            'name' => $validatedData['name'],
            'quantity' => 1,
            'price' => (float) $validatedData['price'],
            'is_custom' => ($validatedData['type'] ?? 'standard') === 'custom',
            'image_filename' => $validatedData['image'],
            'type' => $validatedData['type'] ?? 'standard',
        ];
        $cartItems[] = $cartItem;
        Session::put('cart', $cartItems);
        return redirect()->route('order.confirm')->with('success_cart_update', $validatedData['name'] . ' added to your order!');
    }

    public function removeItem(Request $request): RedirectResponse
    {
        $request->validate(['cart_item_id' => 'required|string']);
        $cartItemIdToRemove = $request->input('cart_item_id');
        $cartItems = Session::get('cart', []);
        $itemFoundAndRemoved = false;
        $removedItemName = 'Item';

        $updatedCartItems = array_filter($cartItems, function ($item) use ($cartItemIdToRemove, &$itemFoundAndRemoved, &$removedItemName) {
            if (isset($item['cart_item_id']) && $item['cart_item_id'] === $cartItemIdToRemove) {
                $removedItemName = $item['name'] ?? 'Item';
                $itemFoundAndRemoved = true;
                return false;
            }
            return true;
        });

        Session::put('cart', array_values($updatedCartItems));

        if ($itemFoundAndRemoved) {
            $message = $removedItemName . ' removed from your order.';
            if (empty(Session::get('cart', []))) {
                Session::flash('info', 'Your cart is now empty.');
            }
            Session::flash('success_cart_update', $message);
        } else {
            Session::flash('error_cart_update', 'Could not find the item to remove.');
        }
        return redirect()->route('order.confirm');
    }

    public function placeOrder(Request $request): RedirectResponse
    {
        $cartItemsFromSession = Session::get('cart', []);

        if (empty($cartItemsFromSession)) {
            return redirect()->route('order.confirm')->with('error_cart_update', 'Your cart is empty. Cannot place order.');
        }

        $couriers = Courier::all();
        if ($couriers->isEmpty()) {
            return redirect()->route('order.confirm')->with('error_cart_update', 'Sorry, no couriers are available at the moment. Please try again later.');
        }
        $randomCourier = $couriers->random();

        $orderTotalAmount = 0;
        foreach ($cartItemsFromSession as $cartItem) {
            $quantity = $cartItem['quantity'] ?? 1;
            $price = $cartItem['price'] ?? 0;
            $orderTotalAmount += $quantity * $price;
        }

        $order = new Order;
        $order->user_id = Auth::id();
        $order->order_date = now();
        $order->total_price = $orderTotalAmount;
        $order->payment_status = 'Unpaid';
        $order->payment_date = null;
        $order->courier_id = $randomCourier->id;
        $order->save();

        foreach ($cartItemsFromSession as $cartItem) {
            $orderDetailData = [
                'order_id' => $order->id,
                'amount' => $cartItem['quantity'] ?? 1,
                'price' => $cartItem['price'] ?? 0,
                'delivery_date' => now()->addDays(3)->toDateString(),
                'delivery_status' => 'Pending',
                'courier_id' => $randomCourier->id,
            ];

            if ($cartItem['type'] === 'custom') {
                $orderDetailData['custom_name'] = $cartItem['name'];

                // Parse the custom cookie details from the name
                $nameParts = explode(' - ', $cartItem['name']);
                if (count($nameParts) >= 3) {
                    $orderDetailData['shape'] = str_replace('Custom Cookie - ', '', $nameParts[0]);
                    $colorPart = $nameParts[1];
                    $orderDetailData['color'] = trim(explode('(', $colorPart)[0]);
                    $orderDetailData['topping'] = $nameParts[2];
                }
            } else {
                $orderDetailData['menu_id'] = $cartItem['id'];
            }

            OrderDetail::create($orderDetailData);
        }

        Session::forget('cart');

        return redirect()->route('orders.show', ['orderId' => $order->id])
            ->with('success', 'Order placed successfully!');
    }

    public function showOrders(Request $request): View|RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Please log in to view your orders!');
        }

        $placedOrders = Order::with(['orderDetails.menus', 'courier'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $sequence = $placedOrders->count();
        $placedOrders->each(function ($order) use (&$sequence) {
            $order->user_sequence = $sequence--;
        });

        return view('order', [
            'pageTitle' => 'Your Orders',
            'headTitle' => 'Your Orders',
            'placedOrders' => $placedOrders,
        ]);
    }

    public function showOrderDetail($orderId): View|RedirectResponse
    {
        // Change 'orderDetail' to 'orderDetails' (plural) to match the relationship name
        $order = Order::with(['orderDetails.menus', 'courier'])
            ->find($orderId);

        if (!$order || $order->user_id !== Auth::id()) {
            return redirect()->route('orders.index')->with('error', 'Order not found');
        }

        // Calculate the user's sequence number for this order
        $userSequence = Order::where('user_id', Auth::id())
            ->where('created_at', '<=', $order->created_at)
            ->count();

        $order->user_sequence = $userSequence;

        return view('orderDetail', [
            'pageTitle' => 'Order Details - ' . $order->user_sequence,
            'headTitle' => 'Order Details - ' . $order->user_sequence,
            'order' => $order,
        ]);
    }

    public function clearOrderHistory(Request $request): RedirectResponse
    {
        // Delete only the current user's orders
        Order::where('user_id', Auth::id())->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Your order history has been cleared successfully.');
    }
}