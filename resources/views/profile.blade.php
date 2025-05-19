<x-layout>
    <x-slot:layoutTitle>{{ $pageTitle ?? 'Profile' }}</x-slot:layoutTitle>
    <x-slot:headTitle>Your Profile</x-slot:headTitle>

    @push('styles')
        {{-- Styles remain the same --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Coiny&family=Quicksand:wght@400;500;700&display=swap"
            rel="stylesheet">
        <style>
            body {
                font-family: 'Quicksand', sans-serif;
                background-color: #FAF5F2;
            }
            .profile-container {
                max-width: 600px;
                margin: 3rem auto;
                padding: 2rem;
                background-color: #FBF5EF;
                border-radius: 1rem;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                border: 1px solid #e5dcd4;
                text-align: center;
            }
            .profile-title {
                font-family: 'Coiny', cursive;
                color: #783F12;
                font-size: 2.25rem; /* 36px */
                margin-bottom: 1.5rem;
            }
            .profile-info p {
                font-size: 1.125rem; /* 18px */
                color: #6b4f4f;
                margin-bottom: 0.75rem; /* 12px */
            }
            .profile-info strong {
                color: #8a6c5a;
            }
            .logout-btn {
                display: inline-block;
                margin-top: 1.5rem; /* 24px */
                padding: 0.75rem 1.5rem; /* 12px 24px */
                background-color: #c0392b; /* A reddish color for logout */
                color: white;
                border: none;
                border-radius: 0.5rem;
                font-weight: bold;
                text-decoration: none;
                transition: background-color 0.3s ease;
                cursor: pointer; /* Add cursor pointer for button */
            }
            .logout-btn:hover {
                background-color: #a93226;
            }
             .orders-link-btn {
                display: inline-block;
                margin-top: 1rem;
                margin-right: 0.5rem; /* Spacing from logout button if on same line */
                padding: 0.75rem 1.5rem;
                background-color: #a07d6a; /* Theme color */
                color: white;
                border: none;
                border-radius: 0.5rem;
                font-weight: bold;
                text-decoration: none;
                transition: background-color 0.3s ease;
            }
            .orders-link-btn:hover {
                background-color: #8a6c5a;
            }
        </style>
    @endpush

    <div class="profile-container">
        {{-- Use object syntax -> and check if user exists --}}
        <h1 class="profile-title">Welcome, {{ $user->name ?? 'User' }}!</h1>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-700 rounded-md text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Check if $user is not null before accessing properties --}}
        @if($user)
            <div class="profile-info">
                {{-- Use object syntax -> --}}
                <p><strong>Name:</strong> {{ $user->name ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $user->email ?? 'N/A' }}</p>
                {{-- You can add more profile details here using $user->property --}}
            </div>
        @else
             <div class="profile-info">
                <p>Could not load user information.</p>
             </div>
        @endif

        <a href="{{ route('order.index') }}" class="orders-link-btn">View My Orders</a>

        {{-- Correct the route name in the form action --}}
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>

    </div>
</x-layout>