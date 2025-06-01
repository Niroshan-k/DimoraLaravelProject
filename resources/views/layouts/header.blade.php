<!DOCTYPE html>
<html lang="en">
@vite(['resources/css/app.css'])
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Dimora</title>    
</head>
<body>
    <!-- Header -->
    <header class="bg-[#00000088] flex z-10 justify-between shadow-md font-inter p-3 fixed top-0 left-0 right-0">
        <div>
            <a href="{{ route('index') }}">
            <img src="{{ asset('storage/appImages/logo.png') }}" alt="logo" class="w-24">
            </a>
        </div>

        <div class="flex items-center gap-4 relative">
            <!-- Guest Links -->
            @guest
                <a href="{{ route('login') }}" class="bg-[#959D90] text-white font-bold py-2 px-4 rounded hover:bg-green-600">
                    Sign in
                </a>
            @else

                <!-- Search Bar -->
                <form action="{{ url('/search') }}" method="GET" class="mr-4">
                    <div class="relative">
                        <input
                            type="text"
                            name="q"
                            placeholder="Search by title or location..."
                            class="rounded-full pl-10 text-sm pr-4 px-3 py-2 bg-gray-100 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                        <span class="absolute left-3 top-2.5 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                </form>

                <!-- Notification Bell Button -->
                <a href="{{ url('/notifications') }}" class="hidden md:flex relative group mr-2" title="Notifications">
                    <i class="fa-regular fa-bell text-2xl text-white group-hover:text-blue-600 transition"></i>
                </a>

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
                        <a href="{{ route('userWishlist.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Wishlist
                        </a>
                        <a href="{{ url('/notifications') }}" class="md:hidden block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <p>Notifications</p>
                        </a>
                        @if(Auth::user()->user_role === 'seller')
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Dashboard
                            </a>
                        @endif
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