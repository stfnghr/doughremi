<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class AdminOrderController extends Controller
{
    // Define allowed PAYMENT statuses
    protected $allowedPaymentStatuses = [
        'unpaid',       // Represents "Pending Payment"
        'paid',         // Represents "Paid"
    ];

    // Helper to get display names for statuses
    protected function getPaymentStatusDisplayName($statusKey)
    {
        $names = [
            'unpaid' => 'Pending Payment',
            'paid' => 'Paid',
        ];
        return $names[$statusKey] ?? ucfirst(str_replace('_', ' ', $statusKey));
    }


    /**
     * Display a listing of the orders.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $orders = [];
        if (class_exists(Order::class)) {
            $orders = Order::with(['user', 'orderDetails.menus'])
                           ->latest()
                           ->get();
        }

        $availablePaymentStatusesForView = [];
        foreach ($this->allowedPaymentStatuses as $statusKey) {
            $availablePaymentStatusesForView[$statusKey] = $this->getPaymentStatusDisplayName($statusKey);
        }

        return view('admin.order', [
            'pageTitle' => 'Manage Orders',
            'orders' => $orders,
            'availablePaymentStatuses' => $availablePaymentStatusesForView
        ]);
    }

    /**
     * Show the details of a specific order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\View\View
     */
    public function show(Order $order)
    {
        $order->load(['user', 'orderDetails.menus', 'courier']);

        $availablePaymentStatusesForView = [];
        foreach ($this->allowedPaymentStatuses as $statusKey) {
            $availablePaymentStatusesForView[$statusKey] = $this->getPaymentStatusDisplayName($statusKey);
        }

        return view('admin.orders.show', [
            'pageTitle' => 'Order Details - #' . $order->id,
            'order' => $order,
            'availablePaymentStatuses' => $availablePaymentStatusesForView
        ]);
    }

    /**
     * Update the PAYMENT status of an order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'payment_status' => [
                'required',
                'string',
                Rule::in($this->allowedPaymentStatuses), // Corrected: Validate against the values
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.orders.index')
                        ->withErrors($validator)
                        ->with('error_order_id', $order->id);
        }

        try {
            $order->payment_status = $request->input('payment_status');
            $order->save();

            $statusDisplayName = $this->getPaymentStatusDisplayName($request->input('payment_status'));

            return redirect()->route('admin.orders.index')
                         ->with('success', 'Order #' . $order->id . ' payment status updated to "' . $statusDisplayName . '".');
        } catch (\Exception $e) {
            Log::error("Error updating payment status for order #{$order->id}: " . $e->getMessage());
            return redirect()->route('admin.orders.index')
                         ->with('error', 'Failed to update payment status for order #' . $order->id . '. Please try again.');
        }
    }
}