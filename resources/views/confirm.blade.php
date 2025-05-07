{{-- File: resources/views/confirm.blade.php --}}

<x-layout>
    <x-slot:layoutTitle>{{ $pageTitle ?? 'Confirm Order' }}</x-slot:layoutTitle>
    <x-slot:headTitle>Confirm Order</x-slot:headTitle>

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
        </style>
    @endpush

    <div class="container mx-auto px-4 py-8 min-h-screen">
        {{-- Display Flash Messages --}}
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

        <div class="flex flex-col md:flex-row gap-8 max-w-4xl mx-auto">
            {{-- Left Column: Order Items Summary --}}
            <div class="md:w-2/3 bg-[#FBF5EF] p-6 rounded-2xl shadow-md border border-gray-200">
                <h2 class="text-xl font-semibold mb-4 border-b pb-2" style="color: #8a6c5a;">Your Current Order</h2>

                {{-- Display all items currently in the cart --}}
                @if (isset($cartItems) && count($cartItems) > 0)
                    <ul class="space-y-4">
                        @foreach ($cartItems as $item)
                            <li
                                class="flex justify-between items-start border-b border-gray-300/50 pb-3 last:border-b-0">
                                <div class="flex-grow">
                                    <span class="font-medium block"
                                        style="color: #6b4f4f;">{{ $item['name'] ?? 'Unknown Item' }}</span>
                                    <span class="text-sm text-gray-600 block">Quantity:
                                        {{ $item['quantity'] ?? 1 }}</span>
                                    {{-- Display image if available --}}
                                    @if (isset($item['image_filename']))
                                    <img src="{{ asset('images/' . $item['image_filename']) }}" alt="{{ $item['name'] ?? 'Item' }}" class="w-16 h-16 object-contain mt-1 rounded">
                                    @endif
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
                                    {{-- Remove Item Form --}}
                                    <form action="{{ route('cart.remove') }}" method="POST" class="mt-1">
                                        @csrf
                                        <input type="hidden" name="cart_item_id" value="{{ $item['cart_item_id'] }}">
                                        <button type="submit"
                                            class="text-xs text-red-500 hover:text-red-700 underline"
                                            onclick="return confirm('Are you sure you want to remove this item?');">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    {{-- This part should ideally not be reached due to controller redirect --}}
                    <p class="text-gray-600 text-center py-4">Your order is currently empty.</p>
                    <div class="text-center mt-4">
                        <a href="{{ route('custom.index') }}"
                            class="inline-block text-white font-bold py-2 px-4 rounded-lg transition duration-300"
                            style="background-color: #a07d6a;" onmouseover="this.style.backgroundColor='#8a6c5a'"
                            onmouseout="this.style.backgroundColor='#a07d6a'">
                            Start Customizing
                        </a>
                    </div>
                @endif
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

                {{-- Form to Place the Order --}}
                @if (isset($cartItems) && count($cartItems) > 0)
                    <form action="{{ route('order.place') }}" method="POST" class="mt-6">
                        @csrf
                        <button type="submit"
                            class="w-full text-white font-bold py-3 px-4 rounded-lg transition duration-300 hover:shadow-lg"
                            style="background-color: #a07d6a;" onmouseover="this.style.backgroundColor='#8a6c5a'"
                            onmouseout="this.style.backgroundColor='#a07d6a'">
                            Place Order
                        </button>
                    </form>
                @endif
                <div class="text-center mt-4">
                    <a href="{{ route('custom.index') }}" class="text-sm"
                        style="color: #a07d6a; text-decoration: underline;"
                        onmouseover="this.style.textDecoration='none'"
                        onmouseout="this.style.textDecoration='underline'">
                        ← Back to Customization
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layout>