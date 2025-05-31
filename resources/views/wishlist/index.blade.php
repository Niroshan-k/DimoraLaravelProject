{{-- filepath: resources/views/wishlist/index.blade.php --}}
<x-app-layout>
    <div class="max-w-5xl mx-auto py-10">
        <h1 class="text-5xl font-reverie mt-10 mb-6">My Wishlist</h1>
        @if($advertisements->count())
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="wishlist-grid">
                @foreach($advertisements as $ad)
                    <div class="border rounded p-4 shadow bg-white flex flex-col gap-2 relative" id="wishlist-card-{{ $ad->id }}">
                        <div class="flex justify-between items-center">
                            <h3 class="font-semibold text-lg mb-2">{{ $ad->title }}</h3>
                            <button
                                type="button"
                                class="text-blue-600 hover:text-red-600 text-2xl remove-wishlist-btn"
                                data-id="{{ $ad->id }}"
                                title="Remove from wishlist"
                            >
                                <i class="fa-solid fa-bookmark"></i>
                            </button>
                        </div>
                        @if($ad->images && count($ad->images))
                            <img src="{{ asset('storage/' . ($ad->images[0]->data ?? $ad->images[0])) }}" class="w-full h-32 object-cover rounded mb-2" alt="">
                        @endif
                        <p class="text-gray-700 mb-2">{{ Str::limit($ad->description, 120) }}</p>
                        <a href="/advertisement/{{ $ad['id'] }}" class="bg-[#523D35] text-white py-2 px-4 rounded hover:bg-[#886658]">View Details</a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-gray-500 py-10">
                Your wishlist is empty.
            </div>
        @endif
    </div>
    <script>
        const API_TOKEN = "{{ session('api_token') }}";
        document.querySelectorAll('.remove-wishlist-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (!confirm('Remove from wishlist?')) return;
                const adId = this.getAttribute('data-id');
                fetch(`/api/wishlist/${adId}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + API_TOKEN,
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if(res.ok) {
                        document.getElementById('wishlist-card-' + adId).remove();
                    }
                });
            });
        });
    </script>
</x-app-layout>