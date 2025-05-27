<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Courier; // Assuming you have a Courier model for random courier selection
use Illuminate\Support\Facades\Auth; // Assuming you might use Auth for customer details

class OrderController extends Controller
{
    public function confirm(Request $request): RedirectResponse|View
    {
        $cartItemsFromSession = Session::get('cart', []); // Get initial cart from session
        $itemPrice = 12000; // Default price for custom cookies

        // Handle POST requests
        if ($request->isMethod('post')) {
            // ACTION 1: Add a new custom cookie
            if ($request->has('shape') && $request->has('color') && !$request->input('intent')) {
                $shape = $request->input('shape');
                $color = $request->input('color');
                $colorImgPath = $request->input('color_img'); // e.g., 'red.png' or 'custom_cookies/red.png'
                $itemName = "Custom Cookie - {$shape} ({$color})";
                $newCartItem = [
                    'cart_item_id' => Str::uuid()->toString(),
                    'id' => 'custom-' . Str::slug($shape . '-' . $color . '-' . Str::random(4)),
                    'name' => $itemName,
                    'quantity' => 1,
                    'price' => $itemPrice,
                    'is_custom' => true,
                    'image_filename' => $colorImgPath ? basename($colorImgPath) : null,
                    'type' => 'custom',
                ];
                $cartItemsFromSession[] = $newCartItem; // Add to the current array
                Session::put('cart', $cartItemsFromSession); // Save updated array to session
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
                // Get a fresh copy from session to modify
                $currentCart = Session::get('cart', []);
                $itemUpdated = false;
                $updatedItemName = 'Item';

                foreach ($currentCart as $index => &$cartItemInLoop) { // Use reference to modify directly
                    if (isset($cartItemInLoop['cart_item_id']) && $cartItemInLoop['cart_item_id'] === $cartItemIdToUpdate) {
                        $updatedItemName = $cartItemInLoop['name'] ?? 'Item';
                        $currentQuantity = $cartItemInLoop['quantity'] ?? 1;
                        $newQuantity = $currentQuantity + $changeAmount;

                        if ($newQuantity < 1) {
                            $cartItemInLoop['quantity'] = 1; // Keep quantity at 1
                            Session::flash('info', 'Quantity for ' . $updatedItemName . ' cannot be less than 1.');
                        } else {
                            $cartItemInLoop['quantity'] = $newQuantity;
                            Session::flash('success_cart_update', 'Quantity for ' . $updatedItemName . ' updated.');
                        }
                        $itemUpdated = true;
                        break; // Exit loop once item is found and updated
                    }
                }

                if ($itemUpdated) {
                    Session::put('cart', $currentCart); // Save the modified cart back to session
                } else {
                    Session::flash('error_cart_update', 'Could not find the item in your cart to update.');
                }
                // Always redirect after a POST action to prevent re-submission on refresh
                return redirect()->route('order.confirm');
            }
            // If it's a POST request but doesn't match known actions,
            // it will fall through to the GET-like behavior below after recalculating cart.
        }

        // This part runs for GET requests or if a POST action wasn't explicitly handled and redirected.
        // Ensure $cartItems reflects the latest state from the session.
        $cartItems = Session::get('cart', []);
        $totalAmount = 0;
        if (!empty($cartItems)) {
            foreach ($cartItems as $item) {
                $quantity = isset($item['quantity']) && is_numeric($item['quantity']) ? (int)$item['quantity'] : 1;
                $price = isset($item['price']) && is_numeric($item['price']) ? (float)$item['price'] : 0;
                $totalAmount += $quantity * $price;
            }
        }

        $pageTitle = 'Confirm Order';
        $headTitle = 'Confirm Order';
        if ($request->route()->getName() == 'cart.show') {
            $pageTitle = 'Your Shopping Cart';
            $headTitle = 'Your Shopping Cart';
        }

        // Logic for "cart is empty" message on GET requests if applicable
        if (empty($cartItems) && $request->isMethod('get') && Session::hasOldInput()) {
            // Avoid showing 'cart is empty' if a specific success/error/info message is already set from a previous action
            if (!Session::has('success_cart_update') && !Session::has('error_cart_update') && !Session::has('info')) {
                Session::flash('info', 'Your cart is now empty.');
            }
        }

        return view('confirm', [
            'layoutTitle' => $pageTitle,
            'headTitle' => $headTitle,
            'cartItems' => $cartItems,
            'totalAmount' => $totalAmount,
            'pageTitle' => $pageTitle, // Pass for <title> tag in layout
        ]);
    }

    public function addItemToCart(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'id' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|string',      // Should be image_filename
            'type' => 'sometimes|string',      // e.g., 'standard', 'custom', 'package'
        ]);

        $cartItems = Session::get('cart', []);
        $cartItem = [
            'cart_item_id' => Str::uuid()->toString(),
            'id' => $validatedData['id'],
            'name' => $validatedData['name'],
            'quantity' => 1,
            'price' => (float)$validatedData['price'],
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
                return false; // Exclude this item
            }
            return true; // Keep this item
        });

        Session::put('cart', array_values($updatedCartItems)); // Re-index array

        if ($itemFoundAndRemoved) {
            if (empty(Session::get('cart', []))) {
                Session::flash('info', 'Your cart is now empty.'); // Set before specific removal message if cart becomes empty
                Session::flash('success_cart_update', $removedItemName . ' removed from your order.');
            } else {
                Session::flash('success_cart_update', $removedItemName . ' removed from your order.');
            }
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
        $randomCourier = $couriers->random();

        $orderTotalAmount = 0;
        $processedOrderItems = [];

        foreach ($cartItemsFromSession as $cartItem) {
            $name = $cartItem['name'] ?? 'Unknown Item';
            $quantity = isset($cartItem['quantity']) && is_numeric($cartItem['quantity']) ? (int)$cartItem['quantity'] : 1;
            $price = isset($cartItem['price']) && is_numeric($cartItem['price']) ? (float)$cartItem['price'] : 0;
            $image = $cartItem['image_filename'] ?? null;
            $type = $cartItem['type'] ?? 'standard';

            $processedOrderItems[] = [
                'name' => $name,
                'quantity' => $quantity,
                'price' => $price,
                'image' => $image,
                'type' => $type,
            ];
            $orderTotalAmount += $quantity * $price;
        }

        if (empty($processedOrderItems)) {
            return redirect()->route('order.confirm')->with('error_cart_update', 'Could not process items in your cart for the order.');
        }

        $placedOrders = Session::get('placed_orders', []);
        $orderId = 'ORD-' . strtoupper(Str::random(6)) . '-' . time();
        
        $newOrder = [
            'id' => $orderId, // Using 'id' for consistency with showOrderDetail
            'order_id' => $orderId, // Keeping for potential backward compatibility or other uses
            'timestamp' => now()->toDateTimeString(),
            'items' => $processedOrderItems,
            'total_amount' => $orderTotalAmount,
            'status' => 'Pending Payment',
            'customer_name' => Auth::check() ? Auth::user()->name : 'Guest',
            'customer_email' => Auth::check() ? Auth::user()->email : null,
            'created_at' => now()->toDateTimeString(), // Added for more standard order data
            'courier_name' => $randomCourier->name, // Store courier name
            'courier_phone' => $randomCourier->phone, // Store courier phone
        ];

        array_unshift($placedOrders, $newOrder);
        Session::put('placed_orders', $placedOrders);
        Session::forget('cart');

        // Assuming you have a route named 'orders.show' for displaying a single order's details
        return redirect()->route('orders.show', ['orderId' => $orderId])
            ->with('success', 'Order placed successfully!');
    }

    public function showOrders(Request $request): View
    {
        $placedOrders = Session::get('placed_orders', []);

        return view('order', [ // Assuming your view for showing orders is 'order.blade.php'
            'pageTitle' => 'Your Orders',
            'headTitle' => 'Your Orders', // For consistency with confirm view
            'placedOrders' => $placedOrders,
        ]);
    }

    public function showOrderDetail($orderId): View|RedirectResponse
    {
        $placedOrders = Session::get('placed_orders', []);
        // Find order by 'id' which was set in placeOrder
        $order = collect($placedOrders)->firstWhere('id', $orderId);

        if (!$order) {
            return redirect()->route('order.index')->with('error', 'Order not found');
        }

        return view('orderDetail', [
            'pageTitle' => 'Order Details - ' . $order['id'],
            'headTitle' => 'Order Details - ' . $order['id'], // For consistency
            'order' => $order,
        ]);
    }

    public function clearOrderHistory(Request $request): RedirectResponse
    {
        Session::forget('placed_orders');
        Session::forget('latest_order'); // Also clear latest_order if you use it
        return redirect()->route('order.index')->with('info', 'Your order history has been cleared.');
    }

    /*
    // The updateQuantity method's logic is now integrated into the confirm() method
    // if the confirm page is the sole place it's used.
    // Keep it if used elsewhere (e.g., API, admin panel).
    public function updateQuantity(Request $request): RedirectResponse
    {
        $request->validate([
            'cart_item_id' => 'required|string',
            'change' => 'required|integer|in:-1,1', // Amount to change quantity by
        ]);

        $cartItemIdToUpdate = $request->input('cart_item_id');
        $changeAmount = (int) $request->input('change');

        $cart = Session::get('cart', []);
        $itemUpdated = false;
        $itemName = 'Item'; // Default item name for messages

        foreach ($cart as $index => &$cartItem) { // Use reference '&' to modify directly
            if (isset($cartItem['cart_item_id']) && $cartItem['cart_item_id'] === $cartItemIdToUpdate) {
                $itemName = $cartItem['name'] ?? 'Item';
                $currentQuantity = $cartItem['quantity'] ?? 1;
                $newQuantity = $currentQuantity + $changeAmount;

                if ($newQuantity < 1) {
                    $cartItem['quantity'] = 1;
                    Session::flash('info', 'Quantity for ' . $itemName . ' cannot be less than 1.');
                } else {
                    $cartItem['quantity'] = $newQuantity;
                    Session::flash('success_cart_update', 'Quantity for ' . $itemName . ' updated.');
                }
                $itemUpdated = true;
                break;
            }
        }

        if ($itemUpdated) {
            Session::put('cart', $cart);
        } else {
            Session::flash('error_cart_update', 'Could not find the item in your cart to update.');
        }

        return redirect()->route('order.confirm');
    }
    */

    /*
    // The updateCart method's logic might also be superseded if confirm() handles all quantity updates
    // from the cart UI. Keep if used for other purposes.
    public function updateCart(Request $request): RedirectResponse
    {
        $cartItems = Session::get('cart', []);
        $updatedCart = [];
        $itemActioned = false;

        foreach ($cartItems as $item) {
            if (isset($item['cart_item_id']) && $item['cart_item_id'] === $request->input('cart_item_id')) {
                $itemActioned = true;
                $itemName = $item['name'] ?? 'Item';
                if ($request->input('action') === 'increase') {
                    $item['quantity'] = ($item['quantity'] ?? 1) + 1;
                    Session::flash('success_cart_update', 'Quantity for ' . $itemName . ' increased.');
                } elseif ($request->input('action') === 'decrease') {
                    $item['quantity'] = ($item['quantity'] ?? 1) - 1;
                    if ($item['quantity'] < 1) {
                        Session::flash('success_cart_update', $itemName . ' removed from cart.');
                        continue; // Skip adding this item back if quantity is 0 or less
                    }
                    Session::flash('success_cart_update', 'Quantity for ' . $itemName . ' decreased.');
                }
            }
            $updatedCart[] = $item;
        }
        if (!$itemActioned && $request->has('cart_item_id')) { // Only flash error if an attempt was made
             Session::flash('error_cart_update', 'Could not find item to update.');
        }

        Session::put('cart', array_values($updatedCart)); // Re-index
        return redirect()->route('order.confirm');
    }
    */
}