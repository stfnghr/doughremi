<x-layout>
    <x-slot:layoutTitle>{{ $pageTitle ?? 'Your Orders' }}</x-slot:layoutTitle>
    <x-slot:headTitle>Your Orders</x-slot:headTitle>

    @push('styles')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Coiny&family=Quicksand:wght@400;500;700&display=swap"
            rel="stylesheet">
        <style>
            body {
                font-family: 'Quicksand', sans-serif;
                background-color: #FAF5F2;
            }

            .order-card {
                background-color: #FBF5EF;
                border: 1px solid #e5dcd4;
                border-radius: 0.75rem;
                padding: 1.5rem;
                margin-bottom: 1.5rem;
                transition: all 0.2s ease;
            }

            .order-card:hover {
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
                border-color: #a07d6a;
            }

            .order-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1rem;
            }

            .order-status {
                padding: 0.25rem 0.5rem;
                border-radius: 0.25rem;
                font-size: 0.75rem;
                font-weight: 600;
            }

            .status-pending {
                background-color: #FEF3C7;
                color: #92400E;
            }

            .status-completed {
                background-color: #D1FAE5;
                color: #065F46;
            }

            .order-details-link {
                display: inline-block;
                margin-top: 1rem;
                color: #a07d6a;
                font-weight: 600;
                text-decoration: underline;
            }
        </style>
    @endpush

    <div class="container mx-auto px-4 py-8 min-h-screen">
        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-2xl font-bold mb-6" style="color: #8a6c5a;">Your Orders</h1>

            @if (count($placedOrders) > 0)
                <div id="order-list">
                    @foreach ($placedOrders as $order)
                        <a href="{{ route('orders.show', ['orderId' => $order->id]) }}" class="block">
                            <div class="order-card">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-lg" style="color: #6b4f4f;">Order
                                        #{{ $order->id ?? 'Unknown' }}</span>
                                    <span class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y H:i') }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="text-gray-700">Total: <span class="font-bold">IDR
                                            {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</span></span>
                                    <span
                                        class="text-xs font-medium px-2 py-0.5 rounded {{ $order->payment_status === 'Pending Payment' ? 'bg-yellow-200 text-yellow-800' : ($order->payment_status === 'Completed' ? 'bg-green-200 text-green-800' : 'bg-gray-200 text-gray-800') }}">
                                        {{ $order->payment_status ?? 'Unknown' }}
                                    </span>
                                </div>
                                <div class="clickable-hint">Click to view details</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 bg-[#FBF5EF] rounded-lg shadow">
                    <p class="text-gray-600 text-lg mb-4">You haven't placed any orders yet.</p>
                    <a href="{{ route('menu.sweetpick') }}"
                        class="inline-block text-white font-bold py-2 px-4 rounded-lg transition duration-300"
                        style="background-color: #a07d6a;" onmouseover="this.style.backgroundColor='#8a6c5a'"
                        onmouseout="this.style.backgroundColor='#a07d6a'">
                        Browse Our Cookies
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layout>
