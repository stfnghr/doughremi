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
            <div class="relative flex justify-center items-center m-6">
                <h1 class="text-3xl font-coiny text-center" style="color: #783F12;">Your Orders</h1>

                @if (count($placedOrders) > 0)
                    <a href="{{ route('orders.clearHistory') }}"
                        onclick="return confirm('Are you sure you want to clear your entire order history? This cannot be undone.')"
                        class="absolute right-0 top-1/2 transform -translate-y-1/2 flex items-center gap-1 p-2 rounded-full hover:bg-[#e8d9c5] transition group"
                        title="Clear Order History">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M4 7H20M10 11V17M14 11V17M5 7L6 19C6 19.5304 6.21071 20.0391 6.58579 20.4142C6.96086 20.7893 7.46957 21 8 21H16C16.5304 21 17.0391 20.7893 17.4142 20.4142C17.7893 20.0391 18 19.5304 18 19L19 7M9 7V4C9 3.73478 9.10536 3.48043 9.29289 3.29289C9.48043 3.10536 9.73478 3 10 3H14C14.2652 3 14.5196 3.10536 14.7071 3.29289C14.8946 3.48043 15 3.73478 15 4V7"
                                stroke="#FF0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>

                    </a>
                @endif
            </div>


            @if (count($placedOrders) > 0)
                <div id="order-list">
                    @foreach ($placedOrders as $order)
                        <a href="{{ route('orders.show', ['orderId' => $order->id]) }}" class="block">
                            <div class="order-card">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-lg" style="color: #783F12;">Order
                                        #{{ $order->user_sequence }}</span>
                                    <span class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y H:i') }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="text-gray-700">Total: <span class="font-bold">IDR
                                            {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</span></span>
                                    <span
                                        class="text-xs font-medium px-2 py-0.5 rounded {{ $order->payment_status === 'Unpaid' ? 'bg-yellow-200 text-yellow-800' : ($order->payment_status === 'Paid' ? 'bg-green-200 text-green-800' : 'bg-gray-200 text-gray-800') }}">
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
                        style="background-color: #783F12;" onmouseover="this.style.backgroundColor='#8A6C5A'"
                        onmouseout="this.style.backgroundColor='#783F12'">
                        Browse Our Cookies
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layout>
