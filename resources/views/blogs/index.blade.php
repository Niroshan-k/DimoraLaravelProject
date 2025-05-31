<x-app-layout>
    <div class="max-w-4xl p-5 mx-auto py-10">
        <h1 class="text-4xl font-reverie mt-20">Blogs</h1>
        @forelse($blogs as $blog)
            <div class="border mt-10 rounded-lg flex flex-col md:flex-row bg-white shadow mb-8 overflow-hidden hover:shadow-lg transition">
                @if($blog->images && count($blog->images))
                    <div class="md:w-1/3 w-full flex items-center justify-center bg-gray-50">
                        <img src="{{ asset('storage/' . $blog->images[0]) }}" class="object-cover w-full h-48 md:h-full" alt="">
                    </div>
                @endif
                <div class="md:w-2/3 w-full p-6 flex flex-col justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold mb-2 text-[#523D35]">{{ $blog->title }}</h2>
                        <div class="text-sm text-gray-500 mb-2">
                            By <span class="font-medium text-[#536860]">{{ $blog->seller_name ?? 'Unknown Seller' }}</span>
                        </div>
                        <p class="text-gray-700 mb-4">{{ $blog->content }}</p>
                    </div>
                    <div class="text-xs text-gray-400 mt-4">
                        {{ $blog->created_at?->toFormattedDateString() }}
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 py-20">No blogs found.</div>
        @endforelse
    </div>
</x-app-layout>