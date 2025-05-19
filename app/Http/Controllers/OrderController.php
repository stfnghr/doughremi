<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
// It's good practice to import Auth if you plan to link orders to users later
// use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // --- confirm, addItemToCart, removeItem methods (largely as you have them) ---
    public function confirm(Request $request): View
    {
        $cartItems = Session::get('cart', []);
        $itemPrice = 12000;

        if ($request->isMethod('post') && $request->has('shape') && $request->has('color')) {
            $shape = $request->input('shape');
            $color = $request->input('color');
            $colorImgPath = $request->input('color_img');
            $itemName = "Custom Cookie - {$shape} ({$color})";
            $cartItem = [
                'cart_item_id' => Str::uuid()->toString(),
                'id' => 'custom-' . Str::slug($shape . '-' . $color . '-' . Str::random(4)),
                'name' => $itemName,
                'quantity' => 1, // Default to 1
                'price' => $itemPrice,
                'is_custom' => true,
                'image_filename' => $colorImgPath ? basename($colorImgPath) : null,
            ];
            $cartItems[] = $cartItem;
            Session::put('cart', $cartItems);
            // It's good practice to redirect after POST to prevent re-submission
            return redirect()->route('order.confirm')->with('success_cart_update', $itemName . ' added to your order!');
        }

        $totalAmount = 0;
        if (!empty($cartItems)) {
            foreach ($cartItems as $item) {
                // Ensure quantity and price are numeric and exist before calculation
                $quantity = isset($item['quantity']) && is_numeric($item['quantity']) ? (int)$item['quantity'] : 0;
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
            'quantity' => 1, // Always add 1, quantity can be updated in cart
            'price' => (float)$validatedData['price'],
            'is_custom' => ($validatedData['type'] ?? 'standard') === 'custom', // Example for custom type
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
        $itemFound = false;

        $updatedCartItems = array_filter($cartItems, function ($item) use ($cartItemIdToRemove, &$itemFound) {
            if (isset($item['cart_item_id']) && $item['cart_item_id'] === $cartItemIdToRemove) {
                $itemFound = true;
                return false;
            }
            return true;
        });

        Session::put('cart', array_values($updatedCartItems));

        if ($itemFound) {
            if (empty(Session::get('cart', []))) {
                return redirect()->route('order.confirm')->with('info', 'Your cart is now empty.');
            }
            return redirect()->route('order.confirm')->with('success_cart_update', 'Item removed from your order.');
        } else {
            return redirect()->route('order.confirm')->with('error_cart_update', 'Could not find the item to remove.');
        }
    }

    // --- Relevant methods for "Error loading order details" ---
    public function placeOrder(Request $request): RedirectResponse
    {
        $cartItemsFromSession = Session::get('cart', []);

        if (empty($cartItemsFromSession)) {
            return redirect()->route('custom.index')->with('error', 'Your cart is empty. Cannot place order.');
        }

        $orderTotalAmount = 0;
        $processedOrderItems = []; // Prepare items specifically for the order record

        foreach ($cartItemsFromSession as $cartItem) {
            // Ensure essential data is present and correctly typed for each item
            $name = $cartItem['name'] ?? 'Unknown Item';
            $quantity = isset($cartItem['quantity']) && is_numeric($cartItem['quantity']) ? (int)$cartItem['quantity'] : 1;
            $price = isset($cartItem['price']) && is_numeric($cartItem['price']) ? (float)$cartItem['price'] : 0;
            // You might want to include other relevant details like image or type if needed for display later
            $image = $cartItem['image_filename'] ?? null;
            $type = $cartItem['type'] ?? 'standard';

            $processedOrderItems[] = [
                'name' => $name,
                'quantity' => $quantity,
                'price' => $price, // This is price PER UNIT
                'image' => $image, // Optional
                'type' => $type,   // Optional
            ];
            $orderTotalAmount += $quantity * $price;
        }

        // If $processedOrderItems is empty after processing (e.g., all cart items were malformed),
        // you might want to handle that, though it's less likely with the checks above.
        if (empty($processedOrderItems)) {
             return redirect()->route('order.confirm')->with('error', 'Could not process items in your cart for the order.');
        }

        $placedOrders = Session::get('placed_orders', []);
        $newOrder = [
            'order_id' => 'ORD-' . strtoupper(Str::random(6)) . '-' . time(),
            'timestamp' => now()->toDateTimeString(),
            'items' => $processedOrderItems, // Use the processed items
            'total_amount' => $orderTotalAmount,
            'status' => 'Pending Payment'
        ];

        array_unshift($placedOrders, $newOrder); // Add to the beginning of the array
        Session::put('placed_orders', $placedOrders);
        Session::forget('cart'); // Clear the current cart

        return redirect()->route('order.index')->with('success', 'Order placed successfully! View details below.');
    }

    public function showOrders(Request $request): View // Renamed from showOrder to showOrders for clarity
    {
        $placedOrders = Session::get('placed_orders', []);

        // Optional: Further processing if needed, but the structure from placeOrder should be good.
        // For example, ensuring every order has an 'items' key and it's an array.
        $validPlacedOrders = array_map(function ($order) {
            if (!isset($order['items']) || !is_array($order['items'])) {
                $order['items'] = []; // Ensure 'items' is always an array
            }
            // Ensure 'order_id', 'timestamp', 'total_amount', 'status' exist with defaults
            $order['order_id'] = $order['order_id'] ?? 'N/A';
            $order['timestamp'] = $order['timestamp'] ?? now()->toDateTimeString();
            $order['total_amount'] = $order['total_amount'] ?? 0;
            $order['status'] = $order['status'] ?? 'Unknown';
            return $order;
        }, $placedOrders);

        return view('order', [
            'pageTitle' => 'Your Orders',
            'placedOrders' => $validPlacedOrders, // Pass the potentially sanitized orders
        ]);
    }

    public function clearOrderHistory(Request $request): RedirectResponse
    {
        Session::forget('placed_orders');
        return redirect()->route('order.index')->with('info', 'Your order history has been cleared.');
    }
}