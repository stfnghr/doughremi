<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // ... confirm, removeItem, placeOrder, showOrders methods remain the same ...

    /**
     * Add a predefined item (Sweet Pick or Joy Box) to the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addItemToCart(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'id' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|string', // This is the image filename
            'type' => 'sometimes|string', // e.g., 'sweet-pick', 'joy-box'
        ]);

        $cartItems = Session::get('cart', []);

        // Create the cart item
        $cartItem = [
            'cart_item_id' => Str::uuid()->toString(), // Unique ID for this specific instance in the cart
            'id' => $validatedData['id'], // Product ID
            'name' => $validatedData['name'],
            'quantity' => 1, // Predefined items are added one at a time this way
            'price' => (float)$validatedData['price'],
            'is_custom' => false, // Mark as not a fully customized cookie from /custom
            'image_filename' => $validatedData['image'], // Already a filename
            'type' => $validatedData['type'] ?? 'standard', // Store type if provided
        ];

        $cartItems[] = $cartItem;
        Session::put('cart', $cartItems);

        // Redirect to the order confirmation page
        return redirect()->route('order.confirm')->with('success_cart_update', $validatedData['name'] . ' added to your order!');
    }


    // --- Existing methods (confirm, removeItem, placeOrder, showOrders) below ---
    public function confirm(Request $request)
    {
        $cartItems = Session::get('cart', []);
        $itemPrice = 12000; // Default for custom items

        if ($request->isMethod('post') && $request->has('shape') && $request->has('color')) {
            $shape = $request->input('shape');
            $color = $request->input('color');
            $colorImgPath = $request->input('color_img');

            $itemName = "Custom Cookie - {$shape} ({$color})";
            
            $cartItem = [
                'cart_item_id' => Str::uuid()->toString(),
                'id' => 'custom-' . Str::slug($shape . '-' . $color . '-' . Str::random(4)),
                'name' => $itemName,
                'quantity' => 1,
                'price' => $itemPrice,
                'is_custom' => true,
                'image_filename' => $colorImgPath ? basename($colorImgPath) : null,
            ];
            $cartItems[] = $cartItem;
            Session::put('cart', $cartItems);

        } 
        // REMOVED: The `elseif ($request->isMethod('get') && $request->has('base'))` block
        // because adding predefined items is now handled by `addItemToCart` route.
        // If you still need the ?base= functionality, you could keep it, but it's cleaner
        // to centralize additions.

        $totalAmount = 0;
        if (!empty($cartItems)) {
            foreach ($cartItems as $item) {
                if (isset($item['quantity'], $item['price']) && is_numeric($item['quantity']) && is_numeric($item['price'])) {
                    $totalAmount += $item['quantity'] * $item['price'];
                }
            }
        } else {
             // If cart is empty and we didn't just add an item from POST (custom cookie)
             if ($request->isMethod('get')) { // Only redirect if it's a GET request to /confirm with an empty cart
                return redirect()->route('custom.index')->with('info', 'Your cart is empty. Please add an item!');
             }
             // If it was a POST that somehow resulted in an empty cart (should not happen with current logic),
             // it will fall through and show confirm page with "empty" message.
        }

        return view('confirm', [
            'pageTitle' => 'Confirm Order',
            'cartItems' => $cartItems,
            'totalAmount' => $totalAmount,
        ]);
    }

    public function removeItem(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|string',
        ]);

        $cartItemIdToRemove = $request->input('cart_item_id');
        $cartItems = Session::get('cart', []);
        $itemFound = false;

        $updatedCartItems = array_filter($cartItems, function ($item) use ($cartItemIdToRemove, &$itemFound) {
            if (isset($item['cart_item_id']) && $item['cart_item_id'] === $cartItemIdToRemove) {
                $itemFound = true;
                return false; 
            }
            return true; 
        });

        if ($itemFound) {
            Session::put('cart', array_values($updatedCartItems));
            return redirect()->route('order.confirm')->with('success_cart_update', 'Item removed from your order.');
        } else {
            return redirect()->route('order.confirm')->with('error_cart_update', 'Could not find the item to remove.');
        }
    }

    public function placeOrder(Request $request)
    {
        $cartItems = Session::get('cart', []);
        
        if (empty($cartItems)) {
            return redirect()->route('custom.index')->with('error', 'Your cart is empty. Cannot place order.');
        }

        $totalAmount = 0;
        foreach ($cartItems as $item) {
             if (isset($item['quantity'], $item['price']) && is_numeric($item['quantity']) && is_numeric($item['price'])) {
                $totalAmount += $item['quantity'] * $item['price'];
             }
        }

        $placedOrders = Session::get('placed_orders', []);
        $newOrder = [
            'order_id' => 'ORD-' . strtoupper(Str::random(6)) . '-' . time(),
            'timestamp' => now()->toDateTimeString(),
            'items' => $cartItems,
            'total_amount' => $totalAmount,
            'status' => 'Pending Payment'
        ];

        array_unshift($placedOrders, $newOrder);
        Session::put('placed_orders', $placedOrders);
        Session::forget('cart');

        return redirect()->route('order.index')->with('success', 'Order placed successfully! View details below.');
    }

    public function showOrders(Request $request)
    {
        $placedOrders = Session::get('placed_orders', []);
        return view('order', [
            'pageTitle' => 'Your Orders',
            'placedOrders' => $placedOrders,
        ]);
    }
}