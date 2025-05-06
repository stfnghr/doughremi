<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Make sure Vite is running (npm run dev) --}}
    @vite('resources/css/app.css')
    <title>{{ $pageTitle ?? 'Custom Cookie' }}</title>
    {{-- Include fonts if needed --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Coiny&family=Quicksand:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Apply fonts */
        body { font-family: 'Quicksand', sans-serif; }
        .font-coiny { font-family: 'Coiny', cursive; }
    </style>
</head>

<body class="bg-[#FAF5F2]"> {{-- Use body instead of div for full page bg --}}

    <div class="container mx-auto p-10">
        {{-- Header/Logo --}}
        <a href="/">
            <div class="flex items-center gap-3 pb-10 justify-center md:justify-start">
                <h1 class="text-4xl font-bold text-[#783F12] font-coiny">Dough</h1>
                <img src="{{ asset('images/remi.png') }}" alt="douremi" class="w-15 h-auto"> {{-- Adjust size as needed --}}
                <h1 class="text-4xl font-bold text-[#783F12] font-coiny">Re-Mi</h1>
            </div>
        </a>

        <div class="flex flex-col lg:flex-row gap-10">
            {{-- Left Info Column --}}
            <div class="lg:w-1/3">
                <h1 class="text-3xl font-bold mb-6" style="color: #8a6c5a;">CUSTOM YOUR COOKIE!</h1>
                <h2 class="text-xl font-semibold text-[#A4B38C] mb-4">Choose a cookie base:</h2>
                <p class="text-[#783F12] mb-6">Select one of the bases below. This will be your main cookie flavor. Decorations and toppings can be added later (feature not implemented yet).</p>
                <p class="text-lg font-bold text-[#783F12]">Price per Custom Cookie: IDR {{ number_format($customBasePrice, 0, ',', '.') }}</p>
                 {{-- Display error message if redirected back --}}
                @if (session('error'))
                    <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
            </div>

            {{-- Right Selection Column --}}
            <div class="lg:w-2/3 h-auto bg-[#EFE5D9] rounded-[35px] p-8 shadow-md">
                 <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6 justify-items-center">
                    {{-- Loop through available bases --}}
                    @forelse ($cookieBases as $base)
                        {{-- Create a link for each base --}}
                        <a href="{{ route('order.confirm', ['base' => $base['id']]) }}"
                           class="block group transition-transform duration-200 hover:scale-105">
                            <div class="flex flex-col items-center text-center">
                                <div class="relative w-[150px] h-[150px] mb-2"> {{-- Container for image + background --}}
                                     {{-- Background box --}}
                                     <div class="absolute bottom-0 left-0 w-full h-[100px] bg-[#FBF5EF] rounded-2xl shadow group-hover:shadow-lg"></div>
                                     {{-- Cookie image positioned on top --}}
                                    <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 w-28 h-28 flex items-center justify-center z-10">
                                        <img src="{{ asset('images/' . $base['img']) }}" alt="{{ $base['name'] }}"
                                            class="object-contain max-h-28 drop-shadow-md" />
                                    </div>
                                    {{-- Text inside the box --}}
                                    <div class="absolute bottom-0 left-0 w-full h-[100px] flex items-end justify-center pb-3 px-2">
                                         <p class="text-[#783F12] text-sm font-medium leading-tight">{{ $base['name'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <p class="col-span-full text-center text-gray-600">No cookie bases available at the moment.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</body>
</html>