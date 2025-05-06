<x-layout>
    <x-slot name="headTitle">Profile</x-slot>
    <x-slot name="layoutTitle">My Profile</x-slot>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <h1 class="text-center text-5xl font-bold text-[#783F12] mb-10">My Profile</h1>
        
        <div class="bg-[#EFE5D9] rounded-[35px] p-16 relative overflow-hidden">
            <div class="max-w-md">
                <div class="mb-10">
                    <h2 class="text-3xl font-bold text-[#783F12] mb-2">Name</h2>
                    <p class="text-2xl text-[#783F12]">Igny Romy</p>
                </div>
                
                <div class="mb-10">
                    <h2 class="text-3xl font-bold text-[#783F12] mb-2">Phone Number</h2>
                    <p class="text-2xl text-[#783F12]">0813456789</p>
                </div>
                
                <div>
                    <h2 class="text-3xl font-bold text-[#783F12] mb-2">Email</h2>
                    <p class="text-2xl text-[#783F12]">iromy@gmail.com</p>
                </div>
            </div>
            
            <!-- Cookie image with splatter effect -->
            <div class="absolute right-16 top-1/2 transform -translate-y-1/2">
                <div class="relative">
                    <!-- Cookie image instead of circle -->
                    <img src="{{ asset('images/brown_cookie.png') }}" alt="cookie" class="w-52 h-52 object-contain">
                    
                    <!-- Splatter effects -->
                    <div class="absolute -top-5 -right-5 w-12 h-12 bg-[#EFE5D9] rounded-full"></div>
                    <div class="absolute top-2 -right-10 w-8 h-8 bg-[#EFE5D9] rounded-full"></div>
                    <div class="absolute -top-8 right-5 w-6 h-6 bg-[#EFE5D9] rounded-full"></div>
                    <div class="absolute -top-3 right-10 w-4 h-4 bg-[#EFE5D9] rounded-full"></div>
                </div>
            </div>
        </div>
    </div>
</x-layout>