<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * Display the admin order management page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.order.index', ['pageTitle' => 'Admin Order Management']);
    }

    /**
     * Show the details of a specific order.
     *
     * @param  int  $orderId
     * @return \Illuminate\View\View
     */
    public function show($orderId)
    {
        // Logic to fetch order details by $orderId
        return view('admin.order.show', ['pageTitle' => 'Order Details', 'orderId' => $orderId]);
    }

    /**
     * Update the status of an order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $orderId
     * @return \Illuminate\Http\RedirectResponse
     */

    public function updateStatus(Request $request, $orderId)
    {
        // Validate the request data
        $request->validate([
            'status' => 'required|string|max:255',
        ]);

        // Logic to update the order status by $orderId
        // For example, Order::find($orderId)->update(['status' => $request->status]);

        return redirect()->route('admin.orders.index')->with('success', 'Order status updated successfully.');
    }
}
