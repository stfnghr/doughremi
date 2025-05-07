{{-- File: resources/views/login.blade.php --}}
<x-layout>
    <x-slot:layoutTitle>{{ $pageTitle ?? 'Login' }}</x-slot:layoutTitle>
    <x-slot:headTitle>Login</x-slot:headTitle>

    @push('styles')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Coiny&family=Quicksand:wght@400;500;700&display=swap"
            rel="stylesheet">
        <style>
            body {
                font-family: 'Quicksand', sans-serif;
                background-color: #FAF5F2; /* Light background for the page */
            }
            .login-container {
                max-width: 400px;
                margin: 5rem auto; /* Center the form vertically and horizontally */
                padding: 2rem;
                background-color: #FBF5EF; /* Card background */
                border-radius: 1rem; /* 16px */
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                border: 1px solid #e5dcd4;
            }
            .login-title {
                font-family: 'Coiny', cursive;
                color: #783F12; /* Dough Re-Mi brown */
                text-align: center;
                font-size: 2.5rem; /* 40px */
                margin-bottom: 1.5rem; /* 24px */
            }
            .form-label {
                display: block;
                margin-bottom: 0.5rem; /* 8px */
                color: #8a6c5a; /* Lighter brown for labels */
                font-weight: 500;
            }
            .form-input {
                width: 100%;
                padding: 0.75rem 1rem; /* 12px 16px */
                border: 1px solid #d1c5ba;
                border-radius: 0.5rem; /* 8px */
                box-sizing: border-box;
                margin-bottom: 1rem; /* 16px */
                font-size: 1rem;
            }
            .form-input:focus {
                outline: none;
                border-color: #a07d6a;
                box-shadow: 0 0 0 2px rgba(160, 125, 106, 0.2);
            }
            .submit-btn {
                width: 100%;
                padding: 0.875rem; /* 14px */
                background-color: #a07d6a;
                color: white;
                border: none;
                border-radius: 0.5rem; /* 8px */
                font-weight: bold;
                font-size: 1rem;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }
            .submit-btn:hover {
                background-color: #8a6c5a;
            }
            .extra-links {
                text-align: center;
                margin-top: 1rem;
                font-size: 0.875rem; /* 14px */
            }
            .extra-links a {
                color: #a07d6a;
                text-decoration: underline;
            }
            .extra-links a:hover {
                text-decoration: none;
            }
        </style>
    @endpush

    <div class="login-container">
        <h1 class="login-title">Login</h1>

        @if (session('info'))
            <div class="mb-4 p-3 bg-blue-100 border border-blue-300 text-blue-700 rounded-md text-sm">
                {{ session('info') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-700 rounded-md text-sm">
                {{ session('error') }}
            </div>
        @endif


        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <div>
                <label for="email" class="form-label">Email (or anything)</label>
                <input type="text" name="email" id="email" class="form-input" placeholder="Enter anything for email">
            </div>
            <div>
                <label for="password" class="form-label">Password (or anything)</label>
                <input type="password" name="password" id="password" class="form-input" placeholder="Enter anything for password">
            </div>
            <button type="submit" class="submit-btn">Log In</button>
        </form>

        <div class="extra-links">
            <p>Don't have an account? <a href="#">Sign Up (Mock)</a></p>
            <a href="{{ route('home') }}">← Back to Home</a>
        </div>
    </div>

</x-layout>