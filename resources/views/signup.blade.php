{{-- File: resources/views/signup.blade.php --}}
<x-layout>
    <x-slot:layoutTitle>{{ $pageTitle }}</x-slot:layoutTitle>
    <x-slot:headTitle>Sign Up</x-slot:headTitle>

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
            .signup-container {
                max-width: 400px;
                margin: 5rem auto; /* Center the form vertically and horizontally */
                padding: 2rem;
                background-color: #FBF5EF; /* Card background */
                border-radius: 1rem; /* 16px */
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                border: 1px solid #e5dcd4;
            }
            .signup-title {
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

    <div class="signup-container">
        <h1 class="signup-title">Sign Up</h1>

        {{-- Display general session errors/info --}}
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

        {{-- Display Laravel Validation Errors --}}
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-700 rounded-md text-sm">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form action="{{ route('signup') }}" method="POST">
            @csrf
            <div>
                <label for="email" class="form-label">Email</label>
                <input 
                    type="text" 
                    name="email" 
                    id="email" 
                    class="form-input @error('email') border-red-500 @enderror" 
                    placeholder="Enter your email"
                    value="{{ old('email') }}" {{-- Retain old input --}}
                    required />
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name" class="form-label">Name</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    class="form-input @error('name') border-red-500 @enderror" 
                    placeholder="Enter your name"
                    value="{{ old('name') }}" {{-- Retain old input --}}
                    required />
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="form-label">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    class="form-input @error('password') border-red-500 @enderror" 
                    placeholder="Make a password (min 8 characters)" {{-- Corrected placeholder --}}
                    required />
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
             {{-- Add Password Confirmation --}}
            <div>
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input
                    type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    class="form-input"
                    placeholder="Confirm your password"
                    required />
            </div>
            <button type="submit" class="submit-btn">Sign Up</button>
        </form>

        <div class="extra-links">
            <p>Already have an account? <a href="/login">Login</a></p>
            <a href="/">← Back to Home</a>
        </div>
    </div>

</x-layout>