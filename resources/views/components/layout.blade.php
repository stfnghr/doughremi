<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>{{ $headTitle }}</title>
</head>

<body>
    <div class="min-h-full">

        <x-navigation></x-navigation>
        <x-header>{{ $layoutTitle }}</x-header>

        <main>
            <div class="mx-auto">
                {{ $slot }} {{-- $slot: views --}}
            </div>
        </main>

        <x-footer></x-footer>
    </div>
</body>

</html>
