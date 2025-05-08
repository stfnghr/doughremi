<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    public function confirm(Request $request): View // Only View is returned now from this method directly
    {
        $cartItems = Session::get('cart', []);
        $itemPrice = 12000; // Default for custom items

        // Scenario 1: Adding a fully custom cookie from custom.blade.php (POST request)
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
        // Adding predefined items is handled by addItemToCart, which then redirects to this route.

        // Calculate total amount
        $totalAmount = 0;
        if (!empty($cartItems)) {
            foreach ($cartItems as $item) {
                if (isset($item['quantity'], $item['price']) && is_numeric($item['quantity']) && is_numeric($item['price'])) {
                    $totalAmount += $item['quantity'] * $item['price'];
                }
            }
        }

        // REMOVED THE REDIRECT LOGIC FOR INITIALLY EMPTY CART ON GET REQUEST
        // The view (confirm.blade.php) will now handle displaying an "empty cart" message
        // if $cartItems is empty, regardless of how the user arrived.
        /*
        if (empty($cartItems) && $request->isMethod('get') && !$request->session()->has('success_cart_update') && !$request->session()->has('error_cart_update')) {
            return redirect()->route('custom.index')->with('info', 'Your cart is empty. Please add an item!');
        }
        */

        $pageTitle = 'Confirm Order';
        $headTitle = 'Confirm Order';
        if ($request->route()->getName() == 'cart.show') { // If accessing via /cart route
            $pageTitle = 'Your Shopping Cart';
            $headTitle = 'Your Shopping Cart';
        }

        return view('confirm', [
            'layoutTitle' => $pageTitle,
            'headTitle' => $headTitle,
            'cartItems' => $cartItems, // This can be an empty array
            'totalAmount' => $totalAmount,
        ]);
    }

    // ... addItemToCart, removeItem, placeOrder, showOrders methods remain the same ...
    // Ensure removeItem still correctly handles redirecting to 'order.confirm'
    // and can set an 'info' flash message if the cart becomes empty.
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

        Session::put('cart', array_values($updatedCartItems)); // Update session immediately

        if ($itemFound) {
            if (empty(Session::get('cart', []))) { // Check if cart is now empty
                return redirect()->route('order.confirm')->with('info', 'Your cart is now empty.');
            }
            return redirect()->route('order.confirm')->with('success_cart_update', 'Item removed from your order.');
        } else {
            return redirect()->route('order.confirm')->with('error_cart_update', 'Could not find the item to remove.');
        }
    }

    // Other methods (addItemToCart, placeOrder, showOrders)
    // remain as they were in the previous correct version.
    // Make sure addItemToCart redirects to 'order.confirm' after adding an item.

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
            'quantity' => 1,
            'price' => (float)$validatedData['price'],
            'is_custom' => false,
            'image_filename' => $validatedData['image'],
            'type' => $validatedData['type'] ?? 'standard',
        ];
        $cartItems[] = $cartItem;
        Session::put('cart', $cartItems);
        return redirect()->route('order.confirm')->with('success_cart_update', $validatedData['name'] . ' added to your order!');
    }

    public function placeOrder(Request $request): RedirectResponse
    {
        $cartItems = Session::get('cart', []);
        if (empty($cartItems)) {
            // This redirect is fine, as you can't place an empty order.
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

    public function showOrders(Request $request): View
    {
        $placedOrders = Session::get('placed_orders', []);
        return view('order', [
            'pageTitle' => 'Your Orders',
            'placedOrders' => $placedOrders,
        ]);
    }

    public function clearOrderHistory(Request $request): RedirectResponse
    {
        Session::forget('placed_orders');

        return redirect()->route('order.index')->with('info', 'Your order history has been cleared.');
    }

}