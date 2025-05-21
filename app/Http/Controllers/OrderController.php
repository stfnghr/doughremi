<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
// It's good practice to import Auth if you plan to link orders to users later
// use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function confirm(Request $request): RedirectResponse|View
    {
        $cartItems = Session::get('cart', []);
        $itemPrice = 12000; // Default price for custom cookies

        // Logic for adding a custom cookie directly from confirm page (if form submitted)
        if ($request->isMethod('post') && $request->has('shape') && $request->has('color')) {
            $shape = $request->input('shape');
            $color = $request->input('color');
            $colorImgPath = $request->input('color_img');
            $itemName = "Custom Cookie - {$shape} ({$color})";
            $cartItem = [
                'cart_item_id' => Str::uuid()->toString(), // Unique ID for this line item in the cart
                'id' => 'custom-' . Str::slug($shape . '-' . $color . '-' . Str::random(4)), // Product-like ID
                'name' => $itemName,
                'quantity' => 1, // Default to 1
                'price' => $itemPrice,
                'is_custom' => true,
                'image_filename' => $colorImgPath ? basename($colorImgPath) : null,
                'type' => 'custom', // Explicitly set type for custom items
            ];
            $cartItems[] = $cartItem;
            Session::put('cart', $cartItems);
            return redirect()->route('order.confirm')->with('success_cart_update', $itemName . ' added to your order!');
        }

        $totalAmount = 0;
        if (!empty($cartItems)) {
            foreach ($cartItems as $item) {
                $quantity = isset($item['quantity']) && is_numeric($item['quantity']) ? (int)$item['quantity'] : 1; // Default to 1 if not set
                $price = isset($item['price']) && is_numeric($item['price']) ? (float)$item['price'] : 0;
                $totalAmount += $quantity * $price;
            }
        }

        $pageTitle = 'Confirm Order';
        $headTitle = 'Confirm Order';
        // Differentiate titles if accessing via /cart alias
        if ($request->route()->getName() == 'cart.show') {
            $pageTitle = 'Your Shopping Cart';
            $headTitle = 'Your Shopping Cart';
        }

        // Check if cart is empty after potential updates and no other specific message is set
        if (empty($cartItems) && $request->isMethod('get') && Session::hasOldInput()) {
            // This logic tries to avoid setting 'cart is empty' if a success/error message for an action (like remove/update) is already present
            if (!Session::has('success_cart_update') && !Session::has('error_cart_update') && !Session::has('info')) {
                Session::flash('info', 'Your cart is now empty.');
            }
        }

        return view('confirm', [
            'layoutTitle' => $pageTitle,
            'headTitle' => $headTitle,
            'cartItems' => $cartItems,
            'totalAmount' => $totalAmount,
        ]);
    }

    public function addItemToCart(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'id' => 'required|string',         // Product ID or unique identifier for the menu item
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|string',      // Should be image_filename
            'type' => 'sometimes|string',      // e.g., 'standard', 'custom', 'package'
        ]);

        $cartItems = Session::get('cart', []);
        $cartItem = [
            'cart_item_id' => Str::uuid()->toString(), // Unique identifier for this cart line item
            'id' => $validatedData['id'],             // The actual product/menu item ID
            'name' => $validatedData['name'],
            'quantity' => 1, // Always add 1, quantity can be updated in cart
            'price' => (float)$validatedData['price'],
            'is_custom' => ($validatedData['type'] ?? 'standard') === 'custom',
            'image_filename' => $validatedData['image'], // Assuming 'image' carries the filename
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
                 // Set 'cart is empty' first, then specific item removal, so 'cart is empty' shows if applicable
                Session::flash('info', 'Your cart is now empty.');
                Session::flash('success_cart_update', $removedItemName . ' removed from your order.');
            } else {
                Session::flash('success_cart_update', $removedItemName . ' removed from your order.');
            }
        } else {
            Session::flash('error_cart_update', 'Could not find the item to remove.');
        }
        return redirect()->route('order.confirm');
    }

    /**
     * Update the quantity of an item in the cart.
     */
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

        foreach ($cart as $index => &$cartItem) { // Use reference '&' to modify directly in the session array
            if (isset($cartItem['cart_item_id']) && $cartItem['cart_item_id'] === $cartItemIdToUpdate) {
                $itemName = $cartItem['name'] ?? 'Item';
                $currentQuantity = $cartItem['quantity'] ?? 1; // Assume 1 if not set (should always be set)
                $newQuantity = $currentQuantity + $changeAmount;

                if ($newQuantity < 1) {
                    // Option: Keep quantity at 1 (as implemented in your blade with disabled button)
                    // This server-side check is a fallback or can enforce strictness
                    $cartItem['quantity'] = 1;
                    Session::flash('info', 'Quantity for ' . $itemName . ' cannot be less than 1.');
                    // If you wanted to remove it instead when quantity hits 0:
                    // unset($cart[$index]);
                    // Session::flash('success_cart_update', $itemName . ' removed from cart.');
                    // $cart = array_values($cart); // Re-index array
                } else {
                    // You can add a max quantity check here if needed
                    // e.g., if ($newQuantity > MAX_ALLOWED_PER_ITEM) { $cartItem['quantity'] = MAX_ALLOWED_PER_ITEM; ... }
                    $cartItem['quantity'] = $newQuantity;
                    Session::flash('success_cart_update', 'Quantity for ' . $itemName . ' updated.');
                }
                $itemUpdated = true;
                break; // Exit loop once item is found and updated
            }
        }

        if ($itemUpdated) {
            Session::put('cart', $cart); // Save the updated cart back to session
        } else {
            Session::flash('error_cart_update', 'Could not find the item in your cart to update.');
        }

        return redirect()->route('order.confirm');
    }


    public function placeOrder(Request $request): RedirectResponse
    {
        $cartItemsFromSession = Session::get('cart', []);

        if (empty($cartItemsFromSession)) {
            // Redirect to confirm page or menu, as custom.index might not be the best place if cart is just empty
            return redirect()->route('order.confirm')->with('error_cart_update', 'Your cart is empty. Cannot place order.');
        }

        $orderTotalAmount = 0;
        $processedOrderItems = [];

        foreach ($cartItemsFromSession as $cartItem) {
            $name = $cartItem['name'] ?? 'Unknown Item';
            $quantity = isset($cartItem['quantity']) && is_numeric($cartItem['quantity']) ? (int)$cartItem['quantity'] : 1;
            $price = isset($cartItem['price']) && is_numeric($cartItem['price']) ? (float)$cartItem['price'] : 0;
            $image = $cartItem['image_filename'] ?? null;
            $type = $cartItem['type'] ?? 'standard'; // Capture type

            $processedOrderItems[] = [
                'name' => $name,
                'quantity' => $quantity,
                'price' => $price, // Price per unit
                'image' => $image,
                'type' => $type,
            ];
            $orderTotalAmount += $quantity * $price;
        }

        if (empty($processedOrderItems)) {
             return redirect()->route('order.confirm')->with('error_cart_update', 'Could not process items in your cart for the order.');
        }

        $placedOrders = Session::get('placed_orders', []);
        $newOrder = [
            'order_id' => 'ORD-' . strtoupper(Str::random(6)) . '-' . time(),
            'timestamp' => now()->toDateTimeString(),
            'items' => $processedOrderItems,
            'total_amount' => $orderTotalAmount,
            'status' => 'Pending Payment' // Default status
        ];

        array_unshift($placedOrders, $newOrder);
        Session::put('placed_orders', $placedOrders);
        Session::forget('cart'); // Clear the current cart

        return redirect()->route('order.index')->with('success', 'Order placed successfully! View details below.');
    }

    public function showOrders(Request $request): View
    {
        $placedOrders = Session::get('placed_orders', []);

        $validPlacedOrders = array_map(function ($order) {
            $order['order_id'] = $order['order_id'] ?? 'N/A-' . Str::random(4); // Ensure unique fallback
            $order['timestamp'] = $order['timestamp'] ?? now()->toDateTimeString();
            $order['items'] = isset($order['items']) && is_array($order['items']) ? $order['items'] : [];
            // Ensure items within the order also have default values for display
            $order['items'] = array_map(function ($item) {
                return [
                    'name' => $item['name'] ?? 'Unknown Item',
                    'quantity' => $item['quantity'] ?? 0,
                    'price' => $item['price'] ?? 0,
                    'image' => $item['image'] ?? null, // or a default image placeholder
                    'type' => $item['type'] ?? 'standard',
                ];
            }, $order['items']);
            $order['total_amount'] = $order['total_amount'] ?? 0;
            $order['status'] = $order['status'] ?? 'Unknown';
            return $order;
        }, $placedOrders);

        return view('order', [ // Assuming your view for showing orders is 'order.blade.php'
            'layoutTitle' => 'Your Orders', // Changed from pageTitle to match confirm view's convention
            'headTitle' => 'Your Orders',
            'placedOrders' => $validPlacedOrders,
        ]);
    }

    public function clearOrderHistory(Request $request): RedirectResponse
    {
        Session::forget('placed_orders');
        return redirect()->route('order.index')->with('info', 'Your order history has been cleared.');
    }
}