<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Coiny&family=Quicksand:wght@400;500;700&display=swap"
        rel="stylesheet">
    {{-- Use the $pageTitle variable passed from the controller --}}
    <title>{{ $pageTitle ?? 'Admin - Add Menu' }}</title>
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #FAF5F2;
            color: #783F12;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .font-coiny { font-family: 'Coiny', cursive; }

        /* Basic form styling to match your theme */
        .form-container {
            background-color: #FBF5EF; /* Card background from your theme */
            padding: 2rem;
            border-radius: 1rem; /* 16px */
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5dcd4;
            width: 100%;
            max-width: 500px; /* Adjust as needed */
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem; /* 8px */
            color: #8a6c5a; /* Lighter brown for labels */
            font-weight: 500;
            font-size: 0.875rem; /* 14px */
        }
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem; /* 12px 16px */
            border: 1px solid #d1c5ba;
            border-radius: 0.5rem; /* 8px */
            box-sizing: border-box;
            margin-bottom: 1rem; /* 16px */
            font-size: 1rem;
            color: #783F12;
        }
        .form-input:focus {
            outline: none;
            border-color: #a07d6a;
            box-shadow: 0 0 0 2px rgba(160, 125, 106, 0.2);
        }
        .submit-btn {
            width: 100%;
            padding: 0.875rem; /* 14px */
            background-color: #a07d6a; /* Theme color */
            color: white;
            border: none;
            border-radius: 0.5rem; /* 8px */
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .submit-btn:hover {
            background-color: #8a6c5a; /* Darker theme color */
        }
        .cancel-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            color: #a07d6a;
            text-decoration: underline;
            font-size: 0.875rem;
        }
        .cancel-link:hover{
            color: #783F12;
        }
    </style>
</head>

<body>
    <div class="min-h-screen flex flex-col items-center justify-center p-6 sm:p-10 w-full">
        <h1 class="font-coiny font-bold text-4xl sm:text-5xl mb-8 text-center">{{ $pageTitle ?? 'Add Menu Item' }}</h1>

        {{-- Display Validation Errors --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 w-full max-w-md" role="alert">
                <strong class="font-bold">Oops! Something went wrong.</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-container">
            {{-- The form now submits to a named route 'admin.menu.store' --}}
            <form action="{{ route('admin.menu.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="form-label">Menu Item Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="form-input">
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label">Description (Optional)</label>
                    <textarea id="description" name="description" rows="3"
                        class="form-input">{{ old('description') }}</textarea>
                </div>

                <div class="mb-6">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" id="price" name="price" value="{{ old('price') }}" required step="0.01" min="0"
                        class="form-input">
                </div>
                {{-- You can add more fields here, like category, image upload, etc. --}}

                <button type="submit" class="submit-btn">
                    Add Menu Item
                </button>
            </form>
            <a href="{{ route('admin.home') }}" class="cancel-link">Cancel</a>
        </div>
    </div>
</body>
</html>