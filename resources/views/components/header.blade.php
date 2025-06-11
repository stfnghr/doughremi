<div class="sticky top-0 bg-[#FAF5F2] text-[#783F12] z-50 shadow-lg">
    <nav class="container mx-auto px-4">
        <div class="flex justify-between items-center py-5">
            <!-- Logo on the left -->
            <a href="/">
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-coiny">Dough</h1>
                    <img src="{{ asset('images/remi.png') }}" alt="logo" class="w-10 h-10">
                </div>
            </a>

            <!-- Navigation links on the right -->
            <div class="flex items-center space-x-4">
                <a href="/"
                    class="{{ request()->is('/') ? ' font-bold' : '' }} rounded-md px-3 py-2 text-md hover:text-[#F0C672]">
                    HOME
                </a>

                <a href="/menu1"
                    class="{{ request()->is('menu1') ? ' font-bold' : '' }} rounded-md px-3 py-2 text-md hover:text-[#F0C672]">
                    MENUS
                </a>

                <a href="/orders"
                    class="{{ request()->is('orders') ? ' font-bold' : '' }} rounded-md px-3 py-2 text-md hover:text-[#F0C672]">
                    ORDER
                </a>

                <a href="/order/confirm"
                    class="{{ request()->is('cart') ? ' font-bold' : '' }} rounded-md px-3 py-2 text-md hover:text-[#F0C672]">
                    CART
                </a>

                <a href="/profile" class="p-2 rounded-full hover:bg-[#e8d9c5] transition">
                    <svg width="26" height="26" viewBox="0 0 50 50" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M25 0C11.2 0 0 11.2 0 25C0 38.8 11.2 50 25 50C38.8 50 50 38.8 50 25C50 11.2 38.8 0 25 0ZM25 10C29.825 10 33.75 13.925 33.75 18.75C33.75 23.575 29.825 27.5 25 27.5C20.175 27.5 16.25 23.575 16.25 18.75C16.25 13.925 20.175 10 25 10ZM25 45C19.925 45 13.925 42.95 9.65 37.8C14.0296 34.3655 19.4344 32.499 25 32.499C30.5656 32.499 35.9704 34.3655 40.35 37.8C36.075 42.95 30.075 45 25 45Z"
                            fill="#783F12" />
                    </svg>
                </a>
            </div>
        </div>
    </nav>
</div>
