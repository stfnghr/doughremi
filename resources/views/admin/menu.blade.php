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
    <title>{{ $pageTitle ?? 'Admin - Menus' }}</title>
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #FAF5F2;
            color: #783F12;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .font-coiny {
            font-family: 'Coiny', cursive;
        }

        .container-custom {
            width: 100%;
            max-width: 1300px;
            margin-left: auto;
            margin-right: auto;
            padding: 2.5rem;
        }

        .table-action-link {
            color: #a07d6a;
            text-decoration: underline;
            font-weight: 500;
        }

        .table-action-link:hover {
            color: #783F12;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 1.5rem;
            color: #a07d6a;
            font-weight: 500;
        }

        .back-link:hover {
            color: #783F12;
        }

        .item-details-list {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 0.875rem;
        }

        .item-details-list li {
            padding-bottom: 0.25rem;
        }

        .item-details-list li:last-child {
            padding-bottom: 0;
        }

        .status-update-form {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 250px;
        }

        .status-select {
            flex-grow: 1;
            padding: 0.375rem 0.5rem;
            border: 1px solid #d1c5ba;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            background-color: #fff;
            color: #783F12;
            min-width: 150px;
        }

        .status-select:focus {
            outline: none;
            border-color: #a07d6a;
            box-shadow: 0 0 0 2px rgba(160, 125, 106, 0.2);
        }

        .status-update-btn {
            padding: 0.375rem 0.75rem;
            background-color: #8a6c5a;
            color: white;
            border: none;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .status-update-btn:hover {
            background-color: #783F12;
        }
    </style>
</head>

<body>
    <div class="container-custom">
        <a href="{{ route('admin.home') }}" class="back-link">&larr; Back</a>

        <div class="flex flex-col justify-center items-center my-6 text-center">
            <h1 class="font-coiny text-4xl sm:text-5xl mb-4" style="color: #783F12;">
                {{ $pageTitle ?? 'Menus' }}</h1>
        </div>

        <div class="container mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Sweet Pick Column -->
                <div class="flex-1 bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-semibold mb-4 text-yellow-700 border-b pb-2 text-center">Sweet Pick</h2>
                    <ul class="space-y-2">
                        @foreach ($menus->where('categories', 'Sweet Pick') as $menu)
                            <li class="flex items-center justify-between p-2 hover:bg-yellow-50 rounded">
                                <span>{{ $menu->name }}</span>
                                <span class="text-sm text-gray-500">Rp{{ number_format($menu->price, 0, '.', ',') }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Joy Box Column -->
                <div class="flex-1 bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-semibold mb-4 text-blue-700 border-b pb-2 text-center">Joy Box</h2>
                    <ul class="space-y-2">
                        @foreach ($menus->where('categories', 'Joy Box') as $menu)
                            <li class="flex items-center justify-between p-2 hover:bg-blue-50 rounded">
                                <span>{{ $menu->name }}</span>
                                <span class="text-sm text-gray-500">Rp{{ number_format($menu->price, 0, '.', ',') }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="flex justify-center items-center p-10">
                <a href="{{ route('admin.menu.add') }}"
                    class="bg-green-600 hover:bg-green-800 text-yellow-50 px-6 py-2 rounded-lg text-sm">
                    ADD MENU ITEM
                </a>
            </div>
        </div>
    </div>
</body>

</html>
