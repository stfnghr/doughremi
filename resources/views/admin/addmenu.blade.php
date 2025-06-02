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
    <title>Admin - Add Menu</title>
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
        <h1 class="font-bold text-6xl mb-8 text-center">Add Menu Item</h1>
        <form action="/admin/addmenu" method="POST" class="w-full max-w-md">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium mb-2">Menu Item Name</label>
                <input type="text" id="name" name="name" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium mb-2">Price</label>
                <input type="number" id="price" name="price" required step="0.01"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit"
                class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors duration-300">Add
                Menu Item</button>
        </form>
    </div>
</body>

</html>
