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

        .font-coiny {
            font-family: 'Coiny', cursive;
        }

        /* Form styling */
        .form-container {
            background-color: #FBF5EF;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5dcd4;
            width: 100%;
            max-width: 500px;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #8a6c5a;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .form-input,
        .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1c5ba;
            border-radius: 0.5rem;
            box-sizing: border-box;
            margin-bottom: 1rem;
            font-size: 1rem;
            color: #783F12;
        }

        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: #a07d6a;
            box-shadow: 0 0 0 2px rgba(160, 125, 106, 0.2);
        }

        .submit-btn {
            width: 100%;
            padding: 0.875rem;
            background-color: #a07d6a;
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #8a6c5a;
        }

        .cancel-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            color: #a07d6a;
            text-decoration: underline;
            font-size: 0.875rem;
        }

        .cancel-link:hover {
            color: #783F12;
        }

        /* Image upload styling */
        .image-upload-container {
            border: 2px dashed #d1c5ba;
            border-radius: 0.5rem;
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .image-upload-container:hover {
            border-color: #a07d6a;
            background-color: rgba(250, 245, 242, 0.5);
        }

        .image-upload-container input[type="file"] {
            display: none;
        }

        .image-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
        }

        .upload-icon {
            font-size: 2rem;
            color: #a07d6a;
            margin-bottom: 0.5rem;
        }

        .image-preview {
            max-width: 100%;
            max-height: 200px;
            margin-top: 1rem;
            border-radius: 0.5rem;
            display: none;
        }
    </style>
</head>

<body>
    <div class="min-h-screen flex flex-col items-center justify-center p-6 sm:p-10 w-full">
        <h1 class="font-coiny font-bold text-4xl sm:text-5xl mb-8 text-center">{{ $pageTitle ?? 'Add Menu Item' }}</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 w-full max-w-md"
                role="alert">
                <strong class="font-bold">Oops! Something went wrong.</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-container">
            <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Category Dropdown -->
                <div class="mb-4">
                    <label for="categories" class="form-label">Category</label>
                    <select id="categories" name="categories" required class="form-select" onchange="updatePrice()">
                        <option value="" disabled selected>Select a category</option>
                        <option value="Sweet Pick" {{ old('categories') == 'Sweet Pick' ? 'selected' : '' }}>Sweet Pick
                        </option>
                        <option value="Joy Box" {{ old('categories') == 'Joy Box' ? 'selected' : '' }}>Joy Box</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="name" class="form-label">Menu Item Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="form-input">
                </div>

                <div class="mb-4">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" id="price" name="price" value="{{ old('price') }}" step="1000"
                        min="0" required class="form-input" readonly>
                </div>

                <!-- Image Upload -->
                <div class="mb-6">
                    <label class="form-label">Menu Image</label>
                    <div class="image-upload-container">
                        <label class="image-upload-label">
                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M20 21.25H2.5L6.875 15.625L10 19.375L14.375 13.75L15 14.5875V10H10V7.5H2.5C1.83696 7.5 1.20107 7.76339 0.732233 8.23223C0.263392 8.70107 0 9.33696 0 10L0 22.5C0 23.163 0.263392 23.7989 0.732233 24.2678C1.20107 24.7366 1.83696 25 2.5 25H20C20.663 25 21.2989 24.7366 21.7678 24.2678C22.2366 23.7989 22.5 23.163 22.5 22.5V15H15.3125L20 21.25Z"
                                    fill="#783F12" />
                                <path d="M20 5V0H17.5V5H12.5V7.5H17.5V12.5H20V7.5H25V5H20Z" fill="#783F12" />
                            </svg>

                            <span>Click to upload an image</span>
                            <span class="text-sm text-gray-500">(JPEG, PNG, JPG max 2MB)</span>
                            <img id="image-preview" class="image-preview" alt="Preview">
                            <input type="file" id="image" name="image" accept="image/jpeg,image/png"
                                onchange="previewImage(this)">
                        </label>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    Add Menu Item
                </button>
            </form>
            <a href="{{ route('admin.menu.index') }}" class="cancel-link">Cancel</a>
        </div>
    </div>

    <script>
        function updatePrice() {
            const categorySelect = document.getElementById('categories');
            const priceInput = document.getElementById('price');

            if (categorySelect.value === 'Sweet Pick') {
                priceInput.value = 10000;
            } else if (categorySelect.value === 'Joy Box') {
                priceInput.value = 250000;
            } else {
                priceInput.value = '';
            }
        }

        // Initialize price when page loads if category is already selected
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('categories');
            if (categorySelect.value) {
                updatePrice();
            }
        });
    </script>
</body>

</html>
