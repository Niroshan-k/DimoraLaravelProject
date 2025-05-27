<x-app-layout>
    <x-slot name="header">
        <h1 class="text-3xl mt-20 font-bold text-gray-800">
            Search Results for <span class="text-blue-600">"{{ $query }}"</span>
        </h1>
    </x-slot>

    <div class="mx-auto mt-8 px-40">
        <div class="grid gap-8 md:grid-cols-4">
            @forelse($results as $item)
                <x-advertisement-card :advertisement="$item" />
            @empty
                <div class="col-span-2 text-center text-gray-500 bg-white p-8 rounded shadow">
                    No results found.
                </div>
            @endforelse
        </div>
    </div>
@include('components.footer')
</x-app-layout>