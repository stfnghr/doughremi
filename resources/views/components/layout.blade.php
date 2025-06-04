{{-- Example: resources/views/components/layout.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $layoutTitle ?? 'Dough Re-Mi' }}</title>
    @vite('resources/css/app.css') {{-- Make sure Vite is running: npm run dev --}}
    {{-- Global Styles and Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Coiny&family=Quicksand:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #FAF5F2; /* Default background */
            color: #783F12; /* Default text color */
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .font-coiny { font-family: 'Coiny', cursive; }
        .content-wrapper { flex-grow: 1; }

        /* Navbar Styling */
        .navbar {
            background-color: #FBF5EF;
            padding: 1rem 2rem; /* Adjust padding as needed */
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e5dcd4;
            position: sticky; /* Makes navbar sticky */
            top: 0;           /* Stick to the top */
            z-index: 1000;    /* Ensure it's above other content */
            width: 100%;
            box-sizing: border-box;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        .navbar-brand img {
            height: 40px; /* Adjust as needed */
            /* margin-right: 0.5rem; Removed margin here, will add to text if needed */
        }
        .navbar-brand .logo-text {
            font-family: 'Coiny', cursive;
            font-size: 1.75rem; /* 28px */
            color: #783F12;
            margin-left: 0.5rem; /* Added margin to space text from image */
        }
        .navbar-links { /* This will be the container for Home, Menus, Order, Profile/Login */
            display: flex;
            align-items: center;
            gap: 1.5rem; /* Space between nav items */
        }
        .navbar-links a, .navbar-links .menu-button-container button, .navbar-links .auth-link-container form button {
            color: #8a6c5a;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease-in-out;
            display: flex; /* For vertical alignment if needed */
            align-items: center; /* For vertical alignment if needed */
        }
        .navbar-links a:hover,
        .navbar-links .menu-button-container button:hover,
        .navbar-links .auth-link-container form button:hover,
        .navbar-links a.active,
        .navbar-links .menu-button-container button.active {
            color: #783F12;
            font-weight: 700;
        }
        .navbar-profile-icon img {
            height: 32px;
            width: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #d1c5ba;
        }

        /* Menu Dropdown Specific */
        .menu-button-container {
            position: relative; /* For Alpine dropdown positioning */
        }
        .menu-button-container button svg { /* Style for the dropdown arrow */
            margin-left: 0.25rem; /* Space between "MENUS" and arrow */
        }
        .menu-dropdown {
            origin-top-right: ;
            position: absolute;
            right: 0;
            margin-top: 0.5rem; /* Space below the MENUS button */
            width: 12rem; /* 192px or as needed */
            border-radius: 0.375rem; /* rounded-md */
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); /* shadow-lg */
            background-color: white;
            ring-width: 1px;
            ring-color: rgba(0,0,0,0.05);
            z-index: 50;
        }
        .menu-dropdown a {
            display: block;
            padding: 0.5rem 1rem; /* px-4 py-2 */
            font-size: 0.875rem; /* text-sm */
            color: #4A5568; /* text-gray-700 */
            margin-left: 0 !important; /* Override general navbar-links margin */
        }
        .menu-dropdown a:hover {
            background-color: #F7FAFC; /* hover:bg-gray-100 */
            color: #2D3748; /* Ensure hover text color contrasts */
        }
        .menu-dropdown a.font-bold { /* For active item in dropdown */
            font-weight: 700;
            color: #783F12;
        }


         /* Footer styling */
        .footer {
            background-color: #DBBF93;
            color: #783F12;
            text-align: center;
            padding: 1.5rem 1rem;
            /* margin-top: auto; /* Pushes footer to bottom if content is short */
        }
        .footer p { margin: 0.25rem 0; }
        .footer a { color: #783F12; text-decoration: underline; }
        .footer a:hover { text-decoration: none; }

    </style>
    @stack('styles')
</head>
<body>
    <x-navigation></x-navigation>
    <x-header>{{ $layoutTitle }}</x-header>

    <main>
        <div class="mx-auto">
            {{ $slot }}
        </div>
    </main>

    <x-footer></x-footer>

    <script src="//unpkg.com/alpinejs" defer></script>
    @stack('scripts')
</body>
</html>