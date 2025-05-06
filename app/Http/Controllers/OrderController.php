<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str; // Import Str facade for unique IDs

class OrderController extends Controller
{
    /**
     * Show the order confirmation page with the current cart contents.
     * Adds a newly selected item if 'base' parameter exists.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function confirm(Request $request)
    {
        // --- Get the current cart from session or initialize empty array ---
        $cartItems = Session::get('cart', []);
        $totalAmount = 0;
        $customBasePrice = 12000;

        // --- Check if a new custom base was selected via query parameter ---
        if ($request->has('base')) {
            $selectedBaseId = $request->query('base');
            $availableBases = [ // Ideally fetch from DB/config
                'chocolate-chip' => ['name' => 'Custom Chocolate Chip Cookie', 'img' => 'chocochip_cookie.png'],
                'double-chocolate' => ['name' => 'Custom Double Chocolate Cookie', 'img' => 'chocolate_cookie.png'],
                'vanilla-bean' => ['name' => 'Custom Vanilla Bean Cookie', 'img' => 'vanilla_cookie.png'],
                'strawberry-cream' => ['name' => 'Custom Strawberry Cream Cookie', 'img' => 'strawberry_cookie.png'],
                'matcha-green-tea' => ['name' => 'Custom Matcha Green Tea Cookie', 'img' => 'matcha_cookie.png'],
                'salted-caramel' => ['name' => 'Custom Salted Caramel Cookie', 'img' => 'saltedcaramel_cookie.png'],
                'lotus-biscoff' => ['name' => 'Custom Lotus Biscoff Cookie', 'img' => 'biscoff_cookie.png'],
            ];

            if (array_key_exists($selectedBaseId, $availableBases)) {
                 // --- Create the cart item for the selected base ---
                 // Add a unique cart_item_id for potential future removal/update logic
                 $cartItem = [
                    'cart_item_id' => Str::uuid()->toString(), // Unique ID for this specific item in the cart
                    'id' => $selectedBaseId,
                    'name' => $availableBases[$selectedBaseId]['name'],
                    'quantity' => 1, // Always 1 when adding from custom page
                    'price' => $customBasePrice,
                    'is_custom' => true,
                    'image' => $availableBases[$selectedBaseId]['img']
                 ];

                 // --- Add the new item to the existing cart array ---
                 $cartItems[] = $cartItem; // Append the new item

                 // --- Store the UPDATED cart back into the session ---
                 Session::put('cart', $cartItems);

            } else {
                // Handle invalid base selection
                return redirect()->route('custom.index')->with('error', 'Invalid cookie base selected.');
            }
        }

        // --- Recalculate total amount for the potentially updated cart ---
        $totalAmount = 0; // Reset total before recalculating
        if (!empty($cartItems)) {
            foreach ($cartItems as $item) {
                if (isset($item['quantity'], $item['price']) && is_numeric($item['quantity']) && is_numeric($item['price'])) {
                    $totalAmount += $item['quantity'] * $item['price'];
                }
            }
        } else {
             // If cart is still empty (e.g., direct access without adding item), redirect
             return redirect()->route('custom.index')->with('info', 'Your cart is empty. Please add a cookie!');
        }

        // --- Return the Confirmation View with all cart items ---
        return view('confirm', [
            'pageTitle' => 'Confirm Order',
            'cartItems' => $cartItems,
            'totalAmount' => $totalAmount,
        ]);
    }

    /**
     * Process the order placement. Saves the current cart as an order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function placeOrder(Request $request)
    {
        // 1. Get the current cart from the session
        $cartItems = Session::get('cart', []);
        $totalAmount = 0;

        // 2. Basic validation: Ensure cart is not empty
        if (empty($cartItems)) {
            return redirect()->route('custom.index')->with('error', 'Your cart is empty. Cannot place order.');
        }

        // 3. Calculate total amount for the order
        foreach ($cartItems as $item) {
             if (isset($item['quantity'], $item['price']) && is_numeric($item['quantity']) && is_numeric($item['price'])) {
                $totalAmount += $item['quantity'] * $item['price'];
             }
        }

        // 4. Get existing placed orders or initialize an empty array
        $placedOrders = Session::get('placed_orders', []);

        // 5. Create the new order data
        $newOrder = [
            'order_id' => 'ORD-' . strtoupper(Str::random(6)) . '-' . time(),
            'timestamp' => now()->toDateTimeString(),
            'items' => $cartItems, // Save the whole cart as items for this order
            'total_amount' => $totalAmount,
            'status' => 'Pending Payment' // Or 'Placed', 'Processing' etc.
        ];

        // 6. Add the new order to the beginning of the list
        array_unshift($placedOrders, $newOrder);

        // 7. Store the updated list back into the session
        Session::put('placed_orders', $placedOrders);

        // 8. Clear the current cart
        Session::forget('cart');

        // 9. Redirect to the Order History page with a success message
        // The confirmation is simply seeing the order here
        return redirect()->route('order.index')->with('success', 'Order placed successfully! View details below.');
    }

    /**
     * Display the order history page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function showOrders(Request $request)
    {
        $placedOrders = Session::get('placed_orders', []);
        return view('order', [
            'pageTitle' => 'Your Orders',
            'placedOrders' => $placedOrders,
        ]);
    }
}