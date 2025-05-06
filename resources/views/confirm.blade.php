{{-- File: resources/views/confirm.blade.php --}}

<x-layout>
    <x-slot:layoutTitle>{{ $pageTitle ?? 'Confirm Order' }}</x-slot:layoutTitle>
    <x-slot:headTitle>Confirm Order</x-slot:headTitle>

    @push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Coiny&family=Quicksand:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Quicksand', sans-serif; }
        .font-coiny { font-family: 'Coiny', cursive; }
    </style>
    @endpush

    <div class="container mx-auto px-4 py-8 min-h-screen">
        <div class="flex flex-col md:flex-row gap-8 max-w-4xl mx-auto">
            {{-- Left Column: Order Items Summary --}}
            <div class="md:w-2/3 bg-[#FBF5EF] p-6 rounded-2xl shadow-md border border-gray-200">
                <h2 class="text-xl font-semibold mb-4 border-b pb-2" style="color: #8a6c5a;">Your Current Order</h2>

                {{-- Display all items currently in the cart --}}
                @if(isset($cartItems) && count($cartItems) > 0)
                    <ul class="space-y-4">
                        @foreach($cartItems as $item)
                            <li class="flex justify-between items-center border-b border-gray-300/50 pb-3 last:border-b-0">
                                <div>
                                    <span class="font-medium block" style="color: #6b4f4f;">{{ $item['name'] ?? 'Unknown Item' }}</span>
                                    <span class="text-sm text-gray-600 block">Quantity: {{ $item['quantity'] ?? 1 }}</span>
                                </div>
                                <span class="font-semibold text-lg" style="color: #6b4f4f;">
                                    @php
                                        $subtotal = (isset($item['price'], $item['quantity']) && is_numeric($item['price']) && is_numeric($item['quantity']))
                                                    ? $item['price'] * $item['quantity']
                                                    : 0;
                                    @endphp
                                    IDR {{ number_format($subtotal, 0, ',', '.') }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    {{-- This part should ideally not be reached due to controller redirect --}}
                    <p class="text-gray-600 text-center py-4">Your order is currently empty.</p>
                    <div class="text-center mt-4">
                         <a href="{{ route('custom.index') }}" class="inline-block text-white font-bold py-2 px-4 rounded-lg transition duration-300" style="background-color: #a07d6a;" onmouseover="this.style.backgroundColor='#8a6c5a'" onmouseout="this.style.backgroundColor='#a07d6a'">
                             Start Customizing
                        </a>
                    </div>
                @endif
            </div>

            {{-- Right Column: Total Payment Summary --}}
            <div class="md:w-1/3 bg-[#FBF5EF] p-6 rounded-2xl shadow-md border border-gray-200 h-fit md:sticky md:top-8">
                <h2 class="text-xl font-semibold mb-4 border-b pb-2" style="color: #8a6c5a;">Payment Summary</h2>
                <div class="border-t border-gray-300/50 pt-4 mt-4">
                    <div class="flex justify-between items-center text-xl font-bold" style="color: #6b4f4f;">
                        <span>Total:</span>
                        <span>IDR {{ number_format($totalAmount ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Form to Place the Order --}}
                @if(isset($cartItems) && count($cartItems) > 0)
                {{-- Removed onsubmit JS confirmation --}}
                <form action="{{ route('order.place') }}" method="POST" class="mt-6">
                    @csrf
                    {{-- Renamed button text --}}
                    <button type="submit" class="w-full text-white font-bold py-3 px-4 rounded-lg transition duration-300 hover:shadow-lg" style="background-color: #a07d6a;" onmouseover="this.style.backgroundColor='#8a6c5a'" onmouseout="this.style.backgroundColor='#a07d6a'">
                        Place Order
                    </button>
                </form>
                @endif
                 <div class="text-center mt-4">
                    <a href="{{ route('custom.index') }}" class="text-sm" style="color: #a07d6a; text-decoration: underline;" onmouseover="this.style.textDecoration='none'" onmouseout="this.style.textDecoration='underline'">
                        ← Back to Customization
                    </a>
                 </div>
            </div>
        </div>
    </div>

    {{-- No specific JS needed here anymore for confirmation --}}

</x-layout>