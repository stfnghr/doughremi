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
    <title>Admin - Home</title>
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
        <h1 class="font-bold text-6xl mb-8 text-center">Hi, Admin01!</h1>
        <h2 class="font-bold text-xl mb-8 text-center">What would you like to do?</h2>
    
        <div class="flex flex-col gap-6 w-full max-w-md"> 
            <a href="{{ route('admin.menu.index') }}"
                class="bg-white shadow-md rounded-lg p-6 hover:bg-gray-100 transition-colors duration-300 text-center">
                <h3 class="font-bold text-xl mb-2">Add Menu</h3>
                <p class="text-gray-700">Create a new menu item.</p>
            </a>
            <a href="{{ route('admin.order.index') }}"
                class="bg-white shadow-md rounded-lg p-6 hover:bg-gray-100 transition-colors duration-300 text-center">
                <h3 class="font-bold text-xl mb-2">Orders</h3>
                <p class="text-gray-700">View and manage customer orders.</p>
            </a>
        </div>
    
        <div class="mt-8">
            <a href="#" class="underline text-red-500 font-bold">LOGOUT</a>
        </div>
    </div>
</body>

</html>
