<x-layout>
    <x-slot:layoutTitle>{{ $pageTitle }}</x-slot:layoutTitle>
    <x-slot:headTitle>Sweet Pick Menus</x-slot:headTitle>

    <div class="bg-[#FAF5F2] p-8">
        <!-- Header Section -->
        <div class="flex justify-center items-center space-x-5 relative">
            <h1 class="text-4xl font-bold text-[#783F12]">Sweet Pick</h1>

            <!-- Dropdown Trigger -->
            <div class="relative inline-block text-left">
                <button type="button" class="focus:outline-none" id="dropdown-button" aria-expanded="false"
                    aria-haspopup="true">
                    <svg width="30" height="30" viewBox="0 0 43 43" fill="none"
                        xmlns="http://www.w3.org/2000/svg" class="cursor-pointer">
                        <circle cx="21.5" cy="21.5" r="21.5" fill="#EFE5D9" />
                        <path
                            d="M31.4436 17.2703C31.1506 16.9774 30.7533 16.8128 30.339 16.8128C29.9246 16.8128 29.5273 16.9774 29.2343 17.2703L21.4999 25.0047L13.7655 17.2703C13.4708 16.9857 13.0761 16.8282 12.6665 16.8318C12.2568 16.8353 11.8649 16.9996 11.5752 17.2893C11.2855 17.579 11.1212 17.9709 11.1176 18.3806C11.114 18.7903 11.2715 19.185 11.5561 19.4797L20.3952 28.3188C20.6882 28.6117 21.0856 28.7762 21.4999 28.7762C21.9142 28.7762 22.3116 28.6117 22.6046 28.3188L31.4436 19.4797C31.7366 19.1867 31.9011 18.7893 31.9011 18.375C31.9011 17.9607 31.7366 17.5633 31.4436 17.2703Z"
                            fill="#783F12" />
                    </svg>
                </button>

                <!-- Dropdown Panel (hidden by default) -->
                <div class="hidden absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
                    role="menu" aria-orientation="vertical" aria-labelledby="dropdown-button" tabindex="-1">
                    <div class="py-1" role="none">
                        <a href="/menu2" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            role="menuitem" tabindex="-1" id="menu-item-0">Joy Box</a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Toggle dropdown visibility
            document.getElementById('dropdown-button').addEventListener('click', function() {
                const dropdown = this.nextElementSibling;
                const isExpanded = this.getAttribute('aria-expanded') === 'true';

                // Toggle visibility
                dropdown.classList.toggle('hidden');
                this.setAttribute('aria-expanded', !isExpanded);
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const dropdownButton = document.getElementById('dropdown-button');
                const dropdown = dropdownButton.nextElementSibling;

                if (!dropdownButton.contains(event.target) && !dropdown.contains(event.target)) {
                    dropdown.classList.add('hidden');
                    dropdownButton.setAttribute('aria-expanded', 'false');
                }
            });
        </script>

        <div class="justify-items-center my-8 text-[#783F12]">
            <p>Single cookie (non-custom, based on templates) - 10k</p>
        </div>

        <!-- Cookie Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 justify-items-center">
            @php
                $cookies = [
                    ['img' => 'chocochip_cookie.png', 'name' => 'Chocolate Chip'],
                    ['img' => 'chocolate_cookie.png', 'name' => 'Double Chocolate'],
                    ['img' => 'vanilla_cookie.png', 'name' => 'Vanilla Bean'],
                    ['img' => 'strawberry_cookie.png', 'name' => 'Strawberry Cream'],
                    ['img' => 'matcha_cookie.png', 'name' => 'Matcha Green Tea'],
                    ['img' => 'saltedcaramel_cookie.png', 'name' => 'Salted Caramel'],
                    ['img' => 'biscoff_cookie.png', 'name' => 'Lotus Biscoff'],
                ];
            @endphp

            @foreach ($cookies as $cookie)
                <div class="flex flex-col items-center pt-10">
                    <div class="relative w-full max-w-[200px]">
                        <div
                            class="absolute -top-15 left-1/2 transform -translate-x-1/2 w-40 h-40 flex items-center justify-center z-10">
                            <img src="{{ asset('images/' . $cookie['img']) }}" alt="{{ $cookie['name'] }} Cookie"
                                class="object-contain max-h-36" />
                        </div>
                        <div
                            class="w-[180px] h-[150px] bg-[#EFE5D9] rounded-[35px] pt-20 flex items-center justify-center p-4">
                            <p class="text-[#783F12] text-center">{{ $cookie['name'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach

            <a href="/custom">
                <div class="flex flex-col items-center pt-10">
                    <div class="relative w-full max-w-[200px]">
                        <div
                            class="absolute -top-15 left-1/2 transform -translate-x-1/2 w-40 h-40 flex items-center justify-center z-10">
                            <img src="{{ asset('images/customkuki.png') }}" alt="Custom a Cookie"
                                class="object-contain max-h-36" />
                        </div>

                        <div
                            class="w-[180px] h-[150px] bg-[#EFE5D9] rounded-[35px] pt-20 flex items-center justify-center p-4">
                            <p class="text-[#783F12] text-center">custom a cookie?</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-layout>
