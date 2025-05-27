<x-layout>
    <x-slot:layoutTitle>{{ $pageTitle }}</x-slot:layoutTitle>
    <x-slot:headTitle>Order Detail</x-slot:headTitle>

    @push('styles')
        <style>
            .invoice-container {
                background-color: #FBF5EF;
                border-radius: 1rem;
                padding: 2rem;
                max-width: 800px;
                margin: 2rem auto;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                border: 1px solid #e5dcd4;
            }
            .invoice-header {
                text-align: center;
                margin-bottom: 2rem;
                border-bottom: 1px solid #e5dcd4;
                padding-bottom: 1rem;
            }
            .invoice-section {
                display: flex;
                flex-wrap: wrap;
                gap: 2rem;
                margin-bottom: 2rem;
            }
            .invoice-column {
                flex: 1;
                min-width: 300px;
            }
            .section-title {
                font-weight: 600;
                color: #8a6c5a;
                margin-bottom: 1rem;
                font-size: 1.1rem;
            }
            .item-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 0.5rem;
                padding-bottom: 0.5rem;
                border-bottom: 1px dashed #d1c5ba;
            }
            .item-name {
                font-weight: 500;
            }
            .back-link {
                display: inline-block;
                margin-top: 1rem;
                color: #a07d6a;
                text-decoration: underline;
            }
            .total-row {
                font-weight: bold;
                font-size: 1.1rem;
                margin-top: 1rem;
                padding-top: 1rem;
                border-top: 2px solid #d1c5ba;
            }
        </style>
    @endpush

    <div class="container mx-auto px-4 py-8">
        <div class="invoice-container">
            <div class="invoice-header">
                <h1 class="text-2xl font-bold" style="color: #6b4f4f;">ORDER INVOICE</h1>
                <p class="text-gray-600 mt-2">Order #{{ $order['order_id'] ?? $order['id'] }}</p>
                <p class="text-sm text-gray-500">
                    {{ \Carbon\Carbon::parse($order['timestamp'])->format('M d, Y H:i') }}
                </p>
            </div>

            <div class="invoice-section">
                <div class="invoice-column">
                    <h2 class="section-title">Customer Information</h2>
                    <div class="mb-4">
                        <p><strong>Name:</strong> {{ $order['customer_name'] ?? 'Guest' }}</p>
                        <p><strong>Email:</strong> {{ $order['customer_email'] ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="invoice-column">
                    <h2 class="section-title">Courier Information</h2>
                    <div class="mb-4">
                        <p><strong>Name:</strong> {{ $order['customer_name'] ?? 'Guest' }}</p>
                        <p><strong>Phone:</strong> {{ $order['customer_email'] ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="invoice-column">
                    <h2 class="section-title">Order Status</h2>
                    <div class="mb-4">
                        <span class="px-2 py-1 rounded {{ $order['status'] === 'Pending Payment' ? 'bg-yellow-200 text-yellow-800' : ($order['status'] === 'Completed' ? 'bg-green-200 text-green-800' : 'bg-gray-200 text-gray-800') }}">
                            {{ $order['status'] ?? 'Unknown' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="invoice-section">
                <div class="invoice-column">
                    <h2 class="section-title">Order Items</h2>
                    <div class="mb-4">
                        @foreach ($order['items'] ?? [] as $item)
                            <div class="item-row">
                                <span class="item-name">
                                    {{ $item['name'] }} × {{ $item['quantity'] }}
                                </span>
                                <span>IDR {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                        <div class="total-row">
                            <span>Total:</span>
                            <span>IDR {{ number_format($order['total_amount'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('orders.index') }}" class="back-link">← Back to Orders</a>
        </div>
    </div>
</x-layout>