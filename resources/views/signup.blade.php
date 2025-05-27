{{-- File: resources/views/signup.blade.php --}}
<x-layout>
    <x-slot:layoutTitle>{{ $pageTitle }}</x-slot:layoutTitle>
    <x-slot:headTitle>Sign Up</x-slot:headTitle>

    @push('styles')
        {{-- ... your existing styles ... --}}
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