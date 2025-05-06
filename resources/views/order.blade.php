{{-- File: resources/views/order.blade.php --}}

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
            }

            .font-coiny {
                font-family: 'Coiny', cursive;
            }

            .order-card {
                background-color: #FBF5EF;
                border: 1px solid #e5dcd4;
                border-radius: 0.75rem;
                padding: 1rem;
                margin-bottom: 1rem;
                cursor: pointer;
                transition: box-shadow 0.2s ease-in-out, border-color 0.2s ease-in-out;
                /* Added border-color transition */
            }

            .order-card:hover {
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            }

            .order-card.active {
                /* Style for when details are open */
                border-color: #a07d6a;
                /* Highlight border */
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
            }

            .order-details {
                background-color: #fff;
                border: 1px dashed #d1c5ba;
                border-radius: 0.5rem;
                padding: 1rem;
                margin-top: 0.75rem;
                display: none;
                /* Initially hidden */
                /* Add transition for smoother appearance if desired */
                /* transition: all 0.3s ease-out; */
                /* opacity: 0; */
                /* max-height: 0; */
                /* overflow: hidden; */
            }

            .order-details.active {
                display: block;
                /* Show when active */
                /* opacity: 1; */
                /* max-height: 1000px; */
                /* Adjust as needed */
            }

            .clickable-hint {
                font-size: 0.75rem;
                /* 12px */
                color: #a07d6a;
                text-align: center;
                margin-top: 0.5rem;
                /* 8px */
            }
        </style>
    @endpush

    <div class="container mx-auto px-4 py-8 min-h-screen">

        {{-- Display Flash Messages --}}
        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        @if (session('info'))
            <div class="mb-6 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative"
                role="alert">
                <strong class="font-bold">Info:</strong>
                <span class="block sm:inline">{{ session('info') }}</span>
            </div>
        @endif


        <div class="max-w-3xl mx-auto">
            @if (isset($placedOrders) && count($placedOrders) > 0)
                <h2 class="text-2xl font-semibold mb-6 text-center" style="color: #8a6c5a;">Your Order History</h2>
                <div id="order-list">
                    @foreach ($placedOrders as $index => $order)
                        {{-- Order Summary Card (Clickable) --}}
                        <div class="order-card" data-order-index="{{ $index }}"
                            data-order-details="{{ htmlspecialchars(json_encode($order['items']), ENT_QUOTES, 'UTF-8') }}">

                            {{-- Order Header --}}
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-lg"
                                    style="color: #6b4f4f;">{{ $order['order_id'] ?? 'Unknown Order' }}</span>
                                <span
                                    class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($order['timestamp'])->format('M d, Y H:i') }}</span>
                            </div>
                            {{-- Order Footer --}}
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-gray-700">Total: <span class="font-bold">IDR
                                        {{ number_format($order['total_amount'] ?? 0, 0, ',', '.') }}</span></span>
                                <span
                                    class="text-xs font-medium px-2 py-0.5 rounded {{ $order['status'] === 'Pending Payment' ? 'bg-yellow-200 text-yellow-800' : 'bg-gray-200 text-gray-800' }}">
                                    {{ $order['status'] ?? 'Unknown' }}
                                </span>
                            </div>
                            <div class="clickable-hint">Click to view details</div> {{-- Added Hint --}}

                            {{-- Details Container (Initially Hidden) --}}
                            <div class="order-details" id="details-{{ $index }}">
                                {{-- Details will be injected here by JavaScript --}}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 bg-[#FBF5EF] rounded-lg shadow">
                    <p class="text-gray-600 text-lg mb-4">You haven't placed any orders yet.</p>
                    <a href="{{ route('custom.index') }}"
                        class="inline-block text-white font-bold py-2 px-4 rounded-lg transition duration-300"
                        style="background-color: #a07d6a;" onmouseover="this.style.backgroundColor='#8a6c5a'"
                        onmouseout="this.style.backgroundColor='#a07d6a'">
                        Start Customizing
                    </a>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const orderList = document.getElementById('order-list');

                if (orderList) { // Check if the list exists
                    orderList.addEventListener('click', function(event) {
                        // Find the clicked order card by traversing up from the event target
                        const clickedCard = event.target.closest('.order-card');

                        if (!clickedCard) {
                            return; // Exit if the click wasn't inside a card
                        }

                        const index = clickedCard.dataset.orderIndex;
                        const detailsContainer = document.getElementById(`details-${index}`);
                        const orderDetailsJson = clickedCard.dataset.orderDetails;

                        // --- Toggle Logic ---
                        const isCurrentlyActive = detailsContainer.classList.contains('active');

                        // --- Hide all currently active details first ---
                        document.querySelectorAll('.order-details.active').forEach(activeDetail => {
                            activeDetail.classList.remove('active');
                            activeDetail.innerHTML = ''; // Clear content when hiding
                        });
                        document.querySelectorAll('.order-card.active').forEach(activeCard => {
                            activeCard.classList.remove('active');
                        });

                        // --- If the clicked card wasn't the one already active, show its details ---
                        if (!isCurrentlyActive) {
                            try {
                                const items = JSON.parse(orderDetailsJson);
                                let detailsHtml =
                                    '<h4 class="text-md font-semibold mb-3 border-b pb-1" style="color: #8a6c5a;">Order Items:</h4>';
                                detailsHtml += '<ul class="space-y-2 text-sm">';

                                if (items && items.length > 0) {
                                    items.forEach(item => {
                                        const name = item.name || 'Unknown Item';
                                        const quantity = item.quantity || 1;
                                        const price = item.price || 0;
                                        const subtotal = (quantity * price);
                                        // Format currency using Intl.NumberFormat for better localization
                                        const formattedSubtotal = new Intl.NumberFormat('id-ID', {
                                            style: 'currency',
                                            currency: 'IDR',
                                            minimumFractionDigits: 0,
                                            maximumFractionDigits: 0
                                        }).format(subtotal);

                                        detailsHtml += `<li class="flex justify-between items-center">
                                                        <span>${name} (Qty: ${quantity})</span>
                                                        <span class="font-medium" style="color: #6b4f4f;">${formattedSubtotal}</span>
                                                    </li>`;
                                    });
                                } else {
                                    detailsHtml += '<li>No items found for this order.</li>';
                                }

                                detailsHtml += '</ul>';
                                detailsContainer.innerHTML = detailsHtml;
                                detailsContainer.classList.add('active'); // Show details
                                clickedCard.classList.add('active'); // Highlight card

                            } catch (e) {
                                console.error("Error parsing order details JSON:", e);
                                detailsContainer.innerHTML =
                                    '<p class="text-red-600">Error loading order details.</p>';
                                detailsContainer.classList.add('active');
                                clickedCard.classList.add('active');
                            }
                        }
                        // If it was already active, it's now hidden because we hid all active details at the start.
                    });
                }
            });
        </script>
    @endpush

</x-layout>
