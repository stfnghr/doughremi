{{-- File: resources/views/confirm.blade.php --}}

<x-layout>
    <x-slot:layoutTitle>{{ $layoutTitle ?? 'Confirm Order' }}</x-slot:layoutTitle>
    <x-slot:headTitle>{{ $headTitle ?? 'Confirm Order' }}</x-slot:headTitle>

    @push('styles')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Coiny&family=Quicksand:wght@400;500;700&display=swap"
            rel="stylesheet">
        <style>
            body {
                font-family: 'Quicksand', sans-serif;
            }

            .font-coiny {
                font-family: 'Coiny', cursive;
            }

            .empty-cart-card {
                background-color: #FBF5EF;
                padding: 2rem;
                border-radius: 1rem;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                text-align: center;
                border: 1px solid #e5dcd4;
            }

            .empty-cart-card h2 {
                font-size: 1.5rem;
                /* 24px */
                color: #8a6c5a;
                margin-bottom: 1rem;
            }

            .empty-cart-card p {
                color: #6b4f4f;
                margin-bottom: 1.5rem;
            }

            .empty-cart-card .action-button {
                display: inline-block;
                text-white: ;
                /* Tailwind class equivalent */
                color: white;
                font-weight: bold;
                padding: 0.75rem 1.5rem;
                /* py-3 px-6 */
                border-radius: 0.5rem;
                /* rounded-lg */
                transition: background-color 0.3s ease;
                background-color: #a07d6a;
                text-decoration: none;
            }

            .empty-cart-card .action-button:hover {
                background-color: #8a6c5a;
            }
        </style>
    @endpush

    <div class="container mx-auto px-4 py-8 min-h-screen">
        {{-- Display Flash Messages for cart updates --}}
        @if (session('success_cart_update'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success_cart_update') }}</span>
            </div>
        @endif
        @if (session('error_cart_update'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error_cart_update') }}</span>
            </div>
        @endif
        @if (session('info'))
            {{-- General info message, like cart is now empty --}}
            <div class="mb-6 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative"
                role="alert">
                <strong class="font-bold">Info:</strong>
                <span class="block sm:inline">{{ session('info') }}</span>
            </div>
        @endif


        @if (isset($cartItems) && count($cartItems) > 0)
            {{-- CART HAS ITEMS --}}
            <div class="flex flex-col md:flex-row gap-8 max-w-4xl mx-auto">
                {{-- Left Column: Order Items Summary --}}
                <div class="md:w-2/3 bg-[#FBF5EF] p-6 rounded-2xl shadow-md border border-gray-200">
                    <h2 class="text-xl font-semibold mb-4 border-b pb-2" style="color: #8a6c5a;">Your Current Order</h2>
                    <ul class="space-y-4">
                        @foreach ($cartItems as $item)
                            <li
                                class="flex justify-between items-start border-b border-gray-300/50 pb-3 last:border-b-0">
                                <div class="flex-grow flex items-center">
                                    @if (isset($item['image_filename']))
                                        <img src="{{ asset('images/' . $item['image_filename']) }}"
                                            alt="{{ $item['name'] ?? 'Item' }}"
                                            class="w-16 h-16 object-contain mr-4 rounded">
                                    @endif
                                    <div class="flex">
                                        <div>
                                            <span class="font-medium block"
                                                style="color: #6b4f4f;">{{ $item['name'] ?? 'Unknown Item' }}</span>
                                            <span class="text-sm text-gray-600 block">Quantity:
                                                {{ $item['quantity'] ?? 1 }}</span>
                                        </div>

                                        {{-- add quantity button here --}}
                                    </div>

                                </div>
                                <div class="text-right ml-4">
                                    <span class="font-semibold text-lg block" style="color: #6b4f4f;">
                                        @php
                                            $subtotal =
                                                isset($item['price'], $item['quantity']) &&
                                                is_numeric($item['price']) &&
                                                is_numeric($item['quantity'])
                                                    ? $item['price'] * $item['quantity']
                                                    : 0;
                                        @endphp
                                        IDR {{ number_format($subtotal, 0, ',', '.') }}
                                    </span>
                                    <form action="{{ route('cart.remove') }}" method="POST" class="mt-1">
                                        @csrf
                                        <input type="hidden" name="cart_item_id" value="{{ $item['cart_item_id'] }}">
                                        <button type="submit" class="text-xs text-red-500 hover:text-red-700 underline"
                                            onclick="return confirm('Are you sure you want to remove this item?');">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Right Column: Total Payment Summary --}}
                <div
                    class="md:w-1/3 bg-[#FBF5EF] p-6 rounded-2xl shadow-md border border-gray-200 h-fit md:sticky md:top-8">
                    <h2 class="text-xl font-semibold mb-4 border-b pb-2" style="color: #8a6c5a;">Payment Summary</h2>
                    <div class="border-t border-gray-300/50 pt-4 mt-4">
                        <div class="flex justify-between items-center text-xl font-bold" style="color: #6b4f4f;">
                            <span>Total:</span>
                            <span>IDR {{ number_format($totalAmount ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <form action="{{ route('order.place') }}" method="POST" class="mt-6">
                        @csrf
                        <button type="submit"
                            class="w-full text-white font-bold py-3 px-4 rounded-lg transition duration-300 hover:shadow-lg"
                            style="background-color: #a07d6a;" onmouseover="this.style.backgroundColor='#8a6c5a'"
                            onmouseout="this.style.backgroundColor='#a07d6a'">
                            Place Order
                        </button>
                    </form>
                    <div class="text-center mt-4">
                        <a href="{{ route('custom.index') }}" class="text-sm"
                            style="color: #a07d6a; text-decoration: underline;"
                            onmouseover="this.style.textDecoration='none'"
                            onmouseout="this.style.textDecoration='underline'">
                            ← Back to Customization
                        </a>
                    </div>
                    <div class="text-center mt-2"> {{-- Added another link to browse menus --}}
                        <a href="/menu1" class="text-sm" style="color: #a07d6a; text-decoration: underline;"
                            onmouseover="this.style.textDecoration='none'"
                            onmouseout="this.style.textDecoration='underline'">
                            Browse Menus
                        </a>
                    </div>
                </div>
            </div>
        @else
            {{-- CART IS EMPTY --}}
            <div class="max-w-lg mx-auto">
                <div class="empty-cart-card">
                    <h2>Your Cart is Empty!</h2>
                    <p>Looks like you haven't added any delicious cookies to your cart yet.</p>
                    <a href="/menu1" class="action-button">Browse Our Menus</a>
                    <p class="mt-4 text-sm">Or <a href="{{ route('custom.index') }}"
                            class="text-[#a07d6a] underline hover:text-[#8a6c5a]">create a custom cookie!</a></p>
                </div>
            </div>
        @endif
    </div>
</x-layout>
