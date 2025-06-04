{{-- File: resources/views/confirm.blade.php --}}

<x-layout>
    <x-slot name="layoutTitle">
        {{ $layoutTitle ?? 'Confirm Order' }}
    </x-slot>

    <x-slot name="headTitle">
        {{ $headTitle ?? 'Confirm Order' }}
    </x-slot>

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
                color: #783F12;
                margin-bottom: 1rem;
            }

            .empty-cart-card p {
                color: #783F12;
                margin-bottom: 1.5rem;
            }

            .empty-cart-card .action-button {
                display: inline-block;
                color: white;
                font-weight: bold;
                padding: 0.75rem 1.5rem;
                border-radius: 0.5rem;
                transition: background-color 0.3s ease;
                background-color: #783F12;
                text-decoration: none;
            }

            .empty-cart-card .action-button:hover {
                background-color: #8a6c5a;
            }

            input[type=number]::-webkit-inner-spin-button,
            input[type=number]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            input[type=number] {
                -moz-appearance: textfield;
            }

            /* Custom cookie preview styles */
            .cookie-preview {
                position: relative;
                width: 64px;
                height: 64px;
                margin-right: 1rem;
            }

            .cookie-layer {
                position: absolute;
                width: 100%;
                height: 100%;
                object-fit: contain;
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
                    <h2 class="text-xl font-semibold mb-4 border-b pb-2" style="color: #783F12;">Your Current Order</h2>
                    <ul class="space-y-4">
                        @foreach ($cartItems as $item)
                            @php
                                $itemQuantity = $item['quantity'] ?? 1;
                                $itemPrice = $item['price'] ?? 0;
                            @endphp
                            <li
                                class="flex justify-between items-start border-b border-gray-300/50 pb-3 last:border-b-0">
                                <div class="flex-grow flex items-center">
                                    {{-- Custom Cookie Preview --}}
                                    @if ($item['type'] === 'custom' && isset($item['shape_img']) && isset($item['color_img']) && isset($item['topping_img']))
                                        <div class="cookie-preview">
                                            <img src="{{ asset('images/' . $item['shape_img']) }}" class="cookie-layer"
                                                alt="Cookie Shape">
                                            <img src="{{ asset('images/' . $item['color_img']) }}" class="cookie-layer"
                                                alt="Cookie Color">
                                            <img src="{{ asset('images/' . $item['topping_img']) }}"
                                                class="cookie-layer" alt="Cookie Topping">
                                        </div>
                                    @elseif (isset($item['image_filename']) && !empty($item['image_filename']))
                                        {{-- Regular menu item image --}}
                                        <img src="{{ asset('images/' . $item['image_filename']) }}"
                                            alt="{{ $item['name'] ?? 'Item' }}"
                                            class="w-16 h-16 object-contain mr-4 rounded">
                                    @else
                                        <div
                                            class="w-16 h-16 bg-gray-200 mr-4 rounded flex items-center justify-center text-xs text-gray-500">
                                            No Img
                                        </div>
                                    @endif

                                    <div>
                                        <span class="font-medium block"
                                            style="color: #783F12;">{{ $item['name'] ?? 'Unknown Item' }}</span>

                                        <div class="flex items-center space-x-1 mt-1">
                                            <span class="text-xs text-gray-500 mr-1">Qty:</span>
                                            <div class="inline-flex items-center">
                                                <form action="{{ route('order.confirm') }}" method="POST"
                                                    class="contents">
                                                    @csrf
                                                    <input type="hidden" name="intent" value="update_quantity">
                                                    <input type="hidden" name="cart_item_id"
                                                        value="{{ $item['cart_item_id'] }}">
                                                    <input type="hidden" name="change" value="-1">
                                                    <button type="submit"
                                                        class="px-1.5 py-0.5 text-xs font-semibold border rounded-l bg-slate-200 hover:bg-slate-300 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed"
                                                        {{ $itemQuantity <= 1 ? 'disabled' : '' }}>-</button>
                                                </form>

                                                {{-- quantity update --}}
                                                <form action="{{ route('order.confirm') }}" method="POST"
                                                    class="contents">
                                                    @csrf
                                                    <input type="hidden" name="intent" value="update_quantity">
                                                    <input type="hidden" name="cart_item_id"
                                                        value="{{ $item['cart_item_id'] }}">
                                                    <input type="number" name="quantity" value="{{ $itemQuantity }}"
                                                        min="1"
                                                        class="w-8 text-center border-t border-b text-xs p-0.5 bg-white">
                                                </form>

                                                <form action="{{ route('order.confirm') }}" method="POST"
                                                    class="contents">
                                                    @csrf
                                                    <input type="hidden" name="intent" value="update_quantity">
                                                    <input type="hidden" name="cart_item_id"
                                                        value="{{ $item['cart_item_id'] }}">
                                                    <input type="hidden" name="change" value="1">
                                                    <button type="submit"
                                                        class="px-1.5 py-0.5 text-xs font-semibold border rounded-r bg-slate-200 hover:bg-slate-300 focus:outline-none">+</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right ml-4">
                                    <span class="font-semibold text-lg block" style="color: #6b4f4f;">
                                        @php
                                            $subtotal = $itemPrice * $itemQuantity;
                                        @endphp
                                        IDR {{ number_format($subtotal, 0, ',', '.') }}
                                    </span>
                                    <form action="{{ route('cart.remove') }}" method="POST" class="mt-1">
                                        @csrf
                                        <input type="hidden" name="cart_item_id" value="{{ $item['cart_item_id'] }}">
                                        <button type="submit"
                                            class="text-xs text-red-500 hover:text-red-700 underline">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Right Column: Payment Summary --}}
                <div
                    class="md:w-1/3 bg-[#FBF5EF] p-6 rounded-2xl shadow-md border border-gray-200 h-fit md:sticky md:top-8">
                    <h2 class="text-xl font-semibold mb-4 border-b pb-2" style="color: #783F12;">Payment Summary</h2>
                    <div class="border-t border-gray-300/50 pt-4 mt-4">
                        <div class="flex justify-between items-center text-xl font-bold" style="color: #783F12;">
                            <span>Total:</span>
                            <span>IDR {{ number_format($totalAmount ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <form action="{{ route('order.place') }}" method="POST" class="mt-6">
                        @csrf
                        <button type="submit"
                            class="w-full text-white font-bold py-3 px-4 rounded-lg transition duration-300 hover:shadow-lg"
                            style="background-color: #783F12;" onmouseover="this.style.backgroundColor='#8a6c5a'"
                            onmouseout="this.style.backgroundColor='#783F12'">
                            Place Order
                        </button>
                    </form>
                    <div class="text-center mt-4">
                        <a href="{{ route('custom.index') }}" class="text-sm"
                            style="color: #783F12; text-decoration: underline;"
                            onmouseover="this.style.textDecoration='none'"
                            onmouseout="this.style.textDecoration='underline'">
                            ← Back to Customization
                        </a>
                    </div>
                    <div class="text-center mt-2">
                        <a href="{{ route('menu.sweetpick') }}" class="text-sm"
                            style="color: #783F12; text-decoration: underline;"
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
                    <a href="{{ route('menu.sweetpick') }}" class="action-button">Browse Our Menus</a>
                    <p class="mt-4 text-sm">Or <a href="{{ route('start.customization') }}"
                            class="text-[#783F12] underline hover:text-[#8a6c5a]">create a custom cookie!</a></p>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        {{-- No specific JavaScript needed for this quantity update approach --}}
    @endpush
</x-layout>
