<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Coiny&family=Quicksand:wght@400;500;700&display=swap"
        rel="stylesheet">
    <title>{{ $pageTitle ?? 'Admin - Orders' }}</title>
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #FAF5F2;
            color: #783F12;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .font-coiny {
            font-family: 'Coiny', cursive;
        }

        .container-custom {
            width: 100%;
            max-width: 1300px;
            margin-left: auto;
            margin-right: auto;
            padding: 2.5rem;
        }

        .table-action-link {
            color: #a07d6a;
            text-decoration: underline;
            font-weight: 500;
        }

        .table-action-link:hover {
            color: #783F12;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 1.5rem;
            color: #a07d6a;
            font-weight: 500;
        }

        .back-link:hover {
            color: #783F12;
        }

        .item-details-list {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 0.875rem;
        }

        .item-details-list li {
            padding-bottom: 0.25rem;
        }

        .item-details-list li:last-child {
            padding-bottom: 0;
        }

        .status-update-form {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 250px;
        }

        .status-select {
            flex-grow: 1;
            padding: 0.375rem 0.5rem;
            border: 1px solid #d1c5ba;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            background-color: #fff;
            color: #783F12;
            min-width: 150px;
        }

        .status-select:focus {
            outline: none;
            border-color: #a07d6a;
            box-shadow: 0 0 0 2px rgba(160, 125, 106, 0.2);
        }

        .status-update-btn {
            padding: 0.375rem 0.75rem;
            background-color: #8a6c5a;
            color: white;
            border: none;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .status-update-btn:hover {
            background-color: #783F12;
        }
    </style>
</head>

<body>
    <div class="container-custom">
        <a href="{{ route('admin.home') }}" class="back-link">&larr; Back</a>

        <div class="flex justify-center items-center my-6">
            <h1 class="font-coiny font-bold text-4xl sm:text-5xl" style="color: #783F12;">{{ $pageTitle ?? 'Orders' }}
            </h1>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                @if (session('error_order_id'))
                    <span class="block sm:inline"> (Order ID: #{{ session('error_order_id') }})</span>
                @endif
            </div>
        @endif
        @if ($errors->any() && session('error_order_id'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong>Error updating Payment Status for Order ID #{{ session('error_order_id') }}:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="w-full bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-200">
                    <tr class="text-gray-700">
                        <th class="py-3 px-4 text-center text-sm font-semibold">Order ID</th>
                        <th class="py-3 px-4 text-center text-sm font-semibold">Customer</th>
                        <th class="py-3 px-4 text-center text-sm font-semibold">Items Ordered</th>
                        <th class="py-3 px-4 text-center text-sm font-semibold">Total Price</th>
                        <th class="py-3 px-4 text-center text-sm font-semibold">Payment Status</th>
                        <th class="py-3 px-4 text-center text-sm font-semibold">Order Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($orders as $order)
                        <tr class="divide-x divide-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-4 text-center text-sm">#{{ $order->id }}</td>
                            <td class="py-3 px-4 text-center text-sm">{{ $order->user->name ?? 'N/A (Guest)' }}</td>
                            <td class="py-3 px-4 text-center text-sm">
                                @if ($order->orderDetails && $order->orderDetails->count() > 0)
                                    <ul class="item-details-list">
                                        @foreach ($order->orderDetails as $detail)
                                            <li>
                                                @if ($detail->menu_id && $detail->menus)
                                                    {{ $detail->menus->name }}
                                                @elseif ($detail->custom_name)
                                                    {{ $detail->custom_name }} (Custom)
                                                @else
                                                    Unknown Item
                                                @endif
                                                &times; {{ $detail->amount }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    No items information.
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right text-sm">IDR
                                {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</td>

                            {{-- Payment Status Update Form --}}
                            <td class="py-3 px-4 text-center text-sm">
                                @php
                                    $currentPaymentStatusKey = ($order->payment_status ?? 'Unpaid');
                                @endphp
                                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST"
                                    class="status-update-form">
                                    @csrf
                                    @method('PUT')
                                    <select name="payment_status" class="status-select">
                                        @foreach ($availablePaymentStatuses as $statusKey => $displayName)
                                            <option value="{{ $statusKey }}"
                                                {{ $currentPaymentStatusKey == $statusKey ? 'selected' : '' }}>
                                                {{ $displayName }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="status-update-btn">Update</button>
                                </form>
                                <div
                                    class="mt-1 text-xs
                                    @switch($currentPaymentStatusKey)
                                        @case('Unpaid') @break
                                        @case('Paid') text-green-600 @break
                                        @default
                                    @endswitch
                                ">
                                    Current:
                                    {{ $availablePaymentStatuses[$currentPaymentStatusKey] ?? ucfirst(str_replace('_', ' ', $currentPaymentStatusKey)) }}
                                </div>
                            </td>

                            <td class="py-3 px-4 text-center text-sm">
                                {{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('M d, Y H:i') : ($order->created_at ? $order->created_at->format('M d, Y H:i') : 'N/A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 px-4 text-center text-gray-500">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
