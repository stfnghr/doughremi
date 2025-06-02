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
    <title>Admin - Orders</title>
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #FAF5F2;
            /* Default background */
            color: #783F12;
            /* Default text color */
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .font-coiny {
            font-family: 'Coiny', cursive;
        }

        .content-wrapper {
            flex-grow: 1;
        }
    </style>
</head>

<body>
    <div class="min-h-screen flex flex-col items-center justify-center p-10 w-full overflow-hidden">
        <h1 class="font-bold text-6xl mb-8 text-center">Orders</h1>
        <h2 class="font-bold text-xl mb-8 text-center">Manage Customer Orders</h2>

        <div class="w-full max-w-4xl">
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="py-3 px-4 text-left">Order ID</th>
                        <th class="py-3 px-4 text-left">Customer Name</th>
                        <th class="py-3 px-4 text-left">Menu Item</th>
                        <th class="py-3 px-4 text-left">Quantity</th>
                        <th class="py-3 px-4 text-left">Total Price</th>
                        <th class="py-3 px-4 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                    <tr class="border-b hover:bg-gray-100">
                        <td class="py-3 px-4">{{ $order->id }}</td>
                        <td class="py-3 px-4">{{ $order->customer_name }}</td>
                        <td class="py-3 px-4">{{ $order->menu_item }}</td>
                        <td class="py-3 px-4">{{ $order->quantity }}</td>
                        <td class="py-3 px-4">${{ number_format($order->total_price, 2) }}</td>
                        <td class="py-3 px-4">{{ $order->status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8">
            <a href="#" class="underline text-red-500 font-bold">LOGOUT</a>
        </div>
    </div>
</body>

</html>
