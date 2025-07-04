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
                background-color: #FAF5F2;
            }

            .signup-container {
                max-width: 400px;
                margin: 5rem auto;
                /* Center the form vertically and horizontally */
                padding: 2rem;
                background-color: #FBF5EF;
                /* Card background */
                border-radius: 1rem;
                /* 16px */
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                border: 1px solid #e5dcd4;
            }

            .signup-title {
                font-family: 'Coiny', cursive;
                color: #783F12;
                /* Dough Re-Mi brown */
                text-align: center;
                font-size: 2.5rem;
                /* 40px */
                margin-bottom: 1.5rem;
                /* 24px */
            }

            .form-label {
                display: block;
                margin-bottom: 0.5rem;
                /* 8px */
                color: #8a6c5a;
                /* Lighter brown for labels */
                font-weight: 500;
            }

            .form-input {
                width: 100%;
                padding: 0.75rem 1rem;
                /* 12px 16px */
                border: 1px solid #d1c5ba;
                border-radius: 0.5rem;
                /* 8px */
                box-sizing: border-box;
                margin-bottom: 1rem;
                /* 16px */
                font-size: 1rem;
            }

            .form-input:focus {
                outline: none;
                border-color: #a07d6a;
                box-shadow: 0 0 0 2px rgba(160, 125, 106, 0.2);
            }

            .submit-btn {
                width: 100%;
                padding: 0.875rem;
                /* 14px */
                background-color: #783F12;
                color: white;
                border: none;
                border-radius: 0.5rem;
                /* 8px */
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
                font-size: 0.875rem;
                /* 14px */
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
                <input type="text" name="email" id="email"
                    class="form-input @error('email') border-red-500 @enderror" placeholder="Enter your email"
                    value="{{ old('email') }}" {{-- Retain old input --}} required />
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name"
                    class="form-input @error('name') border-red-500 @enderror" placeholder="Enter your name"
                    value="{{ old('name') }}" {{-- Retain old input --}} required />
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="form-label">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password"
                        class="form-input @error('password') border-red-500 @enderror pr-10"
                        placeholder="Make a password (min 8 characters)" required />
                    <button type="button"
                        class="absolute inset-y-0 right-0 pr-3 pb-4 flex items-center text-yellow-700 hover:text-yellow-900 focus:outline-none"
                        onclick="togglePasswordVisibility('password', this)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd"
                                d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4 relative">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="form-input pr-10" placeholder="Confirm your password" required />
                    <button type="button"
                        class="absolute inset-y-0 right-0 pr-3 pb-4 flex items-center text-yellow-700 hover:text-yellow-900 focus:outline-none"
                        onclick="togglePasswordVisibility('password_confirmation', this)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd"
                                d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <script>
                function togglePasswordVisibility(fieldId, button) {
                    const passwordInput = document.getElementById(fieldId);

                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        button.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" />
                                <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
                            </svg>
                        `;
                    } else {
                        passwordInput.type = 'password';
                        button.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                            </svg>
                        `;
                    }
                }
            </script>

            <button type="submit" class="submit-btn">Sign Up</button>
        </form>

        <div class="extra-links">
            <p>Already have an account? <a href="/login">Login</a></p>
            <a href="/">← Back to Home</a>
        </div>
    </div>

</x-layout>
