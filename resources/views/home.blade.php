{{-- File: resources/views/home.blade.php --}}
<x-layout>
    <x-slot:layoutTitle>{{ $pageTitle }}</x-slot:layoutTitle>
    <x-slot:headTitle>Home</x-slot:headTitle>

    {{-- 1st content section --}}
    <div class="relative w-full h-[80vh] min-h-[300px] overflow-hidden">
        <svg class="absolute w-full h-full" viewBox="0 0 1440 1021" fill="none" xmlns="http://www.w3.org/2000/svg"
            preserveAspectRatio="none">
            <path d="M0 0H1440V936C1440 936 1342 1021 1085 1021C780.5 1021 600 936 355 936C128 936 0 1021 0 1021V0Z"
                fill="#DBBF93" />
        </svg>

        <div class="absolute inset-0 flex items-center justify-center gap-50">
            <div>
                <h1 class="text-6xl font-bold text-[#783F12] mb-7">Dough Re-Mi</h1>
                <p class="text-md text-[#783F12]">
                    Looking for something special for your special moment?</br>
                    Create your own custom cookie here!
                </p>

                {{-- MODIFIED "TRY IT" BUTTON --}}
                <a href="{{ route('start.customization') }}"
                    class="inline-block bg-[#A4B38C] hover:bg-[#98A97B] text-[#FAF5F2] font-bold shadow-md py-2 px-6 rounded-4xl mt-5 transition-colors duration-300">
                    TRY IT
                </a>
            </div>

            <img src="{{ asset('images/douremi_cookies.png') }}" alt="cookie" class="w-100">
        </div>
    </div>

    {{-- 2nd content section --}}
    <div class="container mx-auto px-4 pt-25 text-center">
        <h1 class="pb-20 text-5xl text-[#F0C672] font-bold">What makes us SPECIAL?</h1>

        <div class="flex justify-center gap-50 pxy-4">
            {{-- image 1 --}}
            <div class="w-70">
                <div class="relative aspect-square w-full overflow-hidden rounded-2xl mb-4">
                    <img src="{{ asset('images/special1.png') }}" alt="special1"
                        class="absolute h-full w-full object-cover">
                </div>

                <div class="text-center pt-5">
                    <p class="font-bold text-[#783F12]">Freshly Made Daily</p>
                    <p class="text-sm text-[#783F12]">All our baked goods are prepared</p>
                    <p class="text-sm text-[#783F12]">fresh every day—no shortcuts, no</p>
                    <p class="text-sm text-[#783F12]">preservatives—just that straight-</p>
                    <p class="text-sm text-[#783F12]">from-the-oven goodness.</p>
                </div>
            </div>

            {{-- image 2 --}}
            <div class="w-70">
                <div class="relative aspect-square w-full overflow-hidden rounded-2xl mb-4">
                    <img src="{{ asset('images/special2.png') }}" alt="special2"
                        class="absolute h-full w-full object-cover">
                </div>

                <div class="text-center pt-5">
                    <p class="font-bold text-[#783F12]">Custom Everything</p>
                    <p class="text-sm text-[#783F12]">We let you choose the flavors,</p>
                    <p class="text-sm text-[#783F12]"> shapes, and decorations to make</p>
                    <p class="text-sm text-[#783F12]">every bite your own.</p>
                </div>
            </div>

            {{-- image 3 --}}
            <div class="w-70">
                <div class="relative aspect-square w-full overflow-hidden rounded-2xl mb-4">
                    <img src="{{ asset('images/special3.png') }}" alt="special3"
                        class="absolute h-full w-full object-cover">
                </div>

                <div class="text-center pt-5">
                    <p class="font-bold text-[#783F12]">We’re Here for Every Celebration</p>
                    <p class="text-sm text-[#783F12]">From birthdays to milestones and</p>
                    <p class="text-sm text-[#783F12]">everything in between, we’re honored</p>
                    <p class="text-sm text-[#783F12]">to be part of your special moments.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- 3rd content section --}}
    <div class="relative w-screen min-h-screen">
        <!-- Full-screen background image -->
        <img src="{{ asset('images/section3.png') }}" alt="Cookie order section"
            class="absolute inset-0 w-full h-full object-cover">

        <!-- Content container -->
        <div class="absolute inset-0 flex flex-col items-end px-10 py-20">
            <!-- "How to order" heading section (right-aligned) -->
            <div class="text-right text-[#783F12] mt-30 mx-20">
                <h1 class="text-5xl font-bold">how</h1>
                <h1 class="text-5xl font-bold">to order a</h1>
                <h1 class="text-5xl font-bold">customizable</h1>
                <h1 class="text-5xl font-bold">cookie?</h1>
            </div>

            <!-- Image cards container (centered below heading) -->
            <div class="w-full flex justify-center gap-50 mt-auto">
                <!-- Image 1 -->
                <div class="w-60 ">
                    <div class="relative aspect-square w-full overflow-hidden rounded-full mb-4">
                        <img src="{{ asset('images/special1.png') }}" alt="special1"
                            class="absolute h-full w-full object-cover">
                    </div>
                    <div class="text-center pt-2">
                        <p class="font-bold text-[#783F12]">Freshly Made Daily</p>
                        <p class="text-sm text-[#783F12]">All our baked goods are prepared fresh every day—no shortcuts,
                            no preservatives—just that straight-from-the-oven goodness.</p>
                    </div>
                </div>

                <!-- Image 2 -->
                <div class="w-60 ">
                    <div class="relative aspect-square w-full overflow-hidden rounded-full mb-4">
                        <img src="{{ asset('images/special2.png') }}" alt="special1"
                            class="absolute h-full w-full object-cover">
                    </div>
                    <div class="text-center pt-2">
                        <p class="font-bold text-[#783F12]">Freshly Made Daily</p>
                        <p class="text-sm text-[#783F12]">All our baked goods are prepared fresh every day—no shortcuts,
                            no preservatives—just that straight-from-the-oven goodness.</p>
                    </div>
                </div>

                <!-- Image 3 -->
                <div class="w-60 ">
                    <div class="relative aspect-square w-full overflow-hidden rounded-full mb-4">
                        <img src="{{ asset('images/special3.png') }}" alt="special1"
                            class="absolute h-full w-full object-cover">
                    </div>
                    <div class="text-center pt-2">
                        <p class="font-bold text-[#783F12]">Freshly Made Daily</p>
                        <p class="text-sm text-[#783F12]">All our baked goods are prepared fresh every day—no shortcuts,
                            no preservatives—just that straight-from-the-oven goodness.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- 4th content section --}}
    <div class="relative w-full min-h-[50vh] py-12 flex flex-col items-center justify-center bg-[#FAF5F2]">
        <h1 class="text-[#A4B38C] font-bold text-5xl mb-8 mt-10 z-10">PRICELIST</h1>

        <div class="relative w-full max-w-[700px] mx-auto px-4 h-[500px]">
            <svg class="absolute w-full h-full" viewBox="0 0 778 607" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="21" y="24" width="757" height="583" rx="55" fill="#F0C672" />
                <rect width="757" height="583" rx="55" fill="#EFE5D9" />
            </svg>

            <div class="absolute inset-0 overflow-y-auto p-8 flex flex-col items-center justify-center gap-6">
                <!-- Price Item 1 -->
                <div class="text-center max-w-[500px] w-full">
                    <h2 class="text-3xl font-bold text-[#783F12] mb-2">Sweet Pick - 10k</h2>
                    <p class="text-md text-[#783F12]">Single cookie (non-custom, based on templates)</p>
                </div>

                <!-- Price Item 2 -->
                <div class="text-center max-w-[500px] w-full">
                    <h2 class="text-3xl font-bold text-[#783F12] mb-2">Cookie Me! - 12k</h2>
                    <p class="text-md text-[#783F12]">Single custom cookie</p>
                </div>

                <!-- Price Item 3 -->
                <div class="text-center max-w-[500px] w-full">
                    <h2 class="text-3xl font-bold text-[#783F12] mb-2">Joy Box - 250k</h2>
                    <p class="text-md text-[#783F12]">Hamper (non-custom, based on templates)</p>
                </div>

                <!-- Price Item 4 -->
                <div class="text-center max-w-[500px] w-full">
                    <h2 class="text-3xl font-bold text-[#783F12] mb-2">The Signature Hamper - 300k</h2>
                    <p class="text-md text-[#783F12]">Custom cookie hamper</p>
                </div>
            </div>
        </div>
    </div>

    {{-- 5th content section --}}
    <div class="flex h-screen">
        <!-- SVG Section (Left Half) with Text -->
        <div class="w-1/2 h-full bg-[#DBBF93] relative">
            <svg class="absolute w-full h-full" viewBox="0 0 720 938" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <rect width="720" height="938" fill="#DBBF93" />
            </svg>

            <!-- Text Content at Bottom Left -->
            <div class="absolute bottom-0 left-0 p-8 md:p-40 text-left">
                <div class="max-w-md space-y-4 md:space-y-6">
                    <h1 class="text-4xl text-[#FAF5F2] md:text-5xl font-bold leading-tight">
                        It's not just a<br>
                        package, it's a<br>
                        cookie party in<br>
                        a box!
                    </h1>

                    <p class="text-lg text-[#783F12] md:text-xl space-y-2">
                        From the moment it leaves our hands<br>
                        to the moment it reaches yours,<br>
                        every delivery is handled with care.
                    </p>
                </div>
            </div>
        </div>

        <!-- Image Section (Right Half) -->
        <div class="w-1/2 h-full">
            <img src="{{ asset('images/special4.png') }}" alt="Special cookie package"
                class="w-full h-full object-cover">
        </div>
    </div>
</x-layout>