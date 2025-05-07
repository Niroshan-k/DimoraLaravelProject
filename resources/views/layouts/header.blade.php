<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="/Dimora/public/css/tailwind.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <title>Dimora</title>    
</head>
<body>
    <!-- Header -->
    <header class="bg-[#EFEFE9] flex z-10 justify-between shadow-md font-inter p-3 fixed top-0 left-0 right-0">
        <div>
            <img src="{{ asset('storage/appImages/logo.png') }}" alt="logo" class="w-24">
        </div>

        <div class="flex items-center gap-4 relative">
            <!-- Guest Links -->
            @guest
                <!-- Show Sign In button if the user is not authenticated -->
                <a href="{{ route('login') }}" class="bg-[#959D90] text-white font-bold py-2 px-4 rounded hover:bg-green-600">
                    Sign in
                </a>
            @else
                <!-- Authenticated User Dropdown -->
                <div class="relative">
                    <!-- Dropdown Trigger -->
                    <button id="dropdownTrigger" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            @endguest
        </div>
    </header>

    <!-- JavaScript for Dropdown -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdownTrigger = document.getElementById('dropdownTrigger');
            const dropdownMenu = document.getElementById('dropdownMenu');

            // Toggle the dropdown menu when the trigger is clicked
            dropdownTrigger.addEventListener('click', function (e) {
                e.stopPropagation(); // Prevent click from propagating to document
                dropdownMenu.classList.toggle('hidden'); // Show/Hide dropdown
            });

            // Close the dropdown if the user clicks outside of it
            document.addEventListener('click', function (e) {
                if (!dropdownMenu.contains(e.target) && !dropdownTrigger.contains(e.target)) {
                    dropdownMenu.classList.add('hidden'); // Hide dropdown
                }
            });
        });
    </script>
</body>
</html>