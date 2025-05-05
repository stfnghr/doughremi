<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Custom a Cookie</title>
</head>

<body>
    <div class="h-screen w-screen bg-[#FAF5F2] p-10 flex">
        <div class="w-1/3 px-10 py-10">
            <a href="/">
                <div class="flex items-center gap-3 pb-10">
                    <h1 class="text-4xl font-bold text-[#783F12]">Dough</h1>
                    <img src="{{ asset('images/remi.png') }}" alt="douremi" class="w-15">
                </div>
            </a>

            <h1 class="text-xl font-bold text-[#A4B38C]">Choose a cookie base:</h1>
        </div>

        <div class="w-2/3 h-auto bg-[#EFE5D9] rounded-[35px] pt-20 flex items-center justify-center p-4">
        </div>
    </div>
</body>

</html>
