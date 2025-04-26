<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="/Dimora/public/css/tailwind.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Inter:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="bg-[#EFEFE9] flex justify-between shadow-md font-inter p-3 fixed top-0 left-0 right-0 z-1">
        <div>
            <img src="{{ asset('storage/appImages/logo.png') }}" alt="logo" class="w-24">
        </div>

        <div class="flex items-center gap-2">
            <a href="index.php" class="text-gray-600 hover:text-brown-600">Home</a>

            @guest
                <!-- Show Sign In button if the user is not authenticated -->
                <a href="{{ route('login') }}">
                    <button class="bg-[#959D90] text-white font-bold py-2 px-4 rounded hover:bg-green-600">
                        Sign in
                    </button>
                </a>
            @else
                <!-- Show user name and logout button if the user is authenticated -->
                <span class="text-gray-600">Hello, {{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white font-bold py-2 px-4 rounded hover:bg-red-600">
                        Logout
                    </button>
                </form>
            @endguest
        </div>
    </header>
</body>
</html>
