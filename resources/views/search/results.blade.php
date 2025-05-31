<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
@vite(['resources/css/home.css'])
@include('layouts.header')
<body class="bg-gray-100">
    <main class="container px-4 md:px-0 mx-auto py-8">
        <h1 class="text-3xl mt-20 font-bold text-gray-800">
            Search Results for <span class="text-blue-600">"{{ $query }}"</span>
        </h1> 
        <div class="mt-5 md:mt-20 grid gap-8 md:grid-cols-4">
            @forelse($results as $item)
                <x-advertisement-card :advertisement="$item" />
            @empty
                <div class="col-span-2 text-center text-gray-500 bg-white p-8 rounded shadow">
                    No results found.
                </div>
            @endforelse
        </div>
    </main>

    @include('components.footer')
</body>
</html>