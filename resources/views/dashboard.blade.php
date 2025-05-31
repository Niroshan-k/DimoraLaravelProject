<x-app-layout>
    <div class="px-4 md:px-0 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-20">
            <div class="flex gap-4">
                <a href="{{ route('advertisements.create') }}" class="bg-[#959D90] text-white font-bold py-2 px-4 rounded hover:bg-green-600">
                    Create Advertisement
                </a>
                <button onclick="scrollToBlogs()" class="bg-[#523D35] text-white font-bold py-2 px-4 rounded hover:bg-[#886658]">
                    Blog
                </button>
            </div>
            <div class="mt-10">
                <h2 class="text-2xl font-bold mb-4">My Advertisements</h2>
                @if($advertisements->count())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($advertisements as $ad)
                            <div class="border rounded p-4 shadow">
                                <h3 class="font-semibold text-lg">{{ $ad->title }}</h3>
                                {{-- Images --}}
                                @if($ad->images->count())
                                    @if($ad->images->count() === 1)
                                        <img src="{{ asset('storage/' . $ad->images->first()->data) }}" class="w-full h-40 object-cover rounded mt-2" alt="">
                                    @else
                                        <div 
                                            x-data="{
                                                active: 0,
                                                images: {{ $ad->images->pluck('data')->map(fn($d) => asset('storage/'.$d)) }},
                                                interval: null,
                                                start() {
                                                    this.interval = setInterval(() => {
                                                        this.active = (this.active + 1) % this.images.length;
                                                    }, 2500);
                                                },
                                                stop() {
                                                    clearInterval(this.interval);
                                                }
                                            }"
                                            x-init="start()" 
                                            x-on:mouseenter="stop()" 
                                            x-on:mouseleave="start()" 
                                            class="relative w-50 h-40 mt-2 overflow-hidden rounded"
                                        >
                                            <template x-for="(img, idx) in images" :key="idx">
                                                <img 
                                                    x-show="active === idx" 
                                                    :src="img" 
                                                    class="absolute inset-0 w-full h-full object-cover transition-all duration-500" 
                                                    x-transition:enter="transition-opacity ease-out duration-500"
                                                    x-transition:enter-start="opacity-0"
                                                    x-transition:enter-end="opacity-100"
                                                    x-transition:leave="transition-opacity ease-in duration-500"
                                                    x-transition:leave-start="opacity-100"
                                                    x-transition:leave-end="opacity-0"
                                                    alt=""
                                                >
                                            </template>
                                            {{-- Dots --}}
                                            <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 flex space-x-1">
                                                <template x-for="(img, idx) in images" :key="idx">
                                                    <button 
                                                        class="w-2 h-2 rounded-full"
                                                        :class="active === idx ? 'bg-green-700' : 'bg-gray-300'"
                                                        @click="active = idx"
                                                    ></button>
                                                </template>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                                {{-- Description --}}
                                <p class="font-bold mt-2">Description:</p>
                                <div class="min-h-20 mt-2 overflow-scroll border p-3 text-sm">
                                    <p class="text-gray-600 mb-1 max-h-20 ">{{ $ad->description }}</p>
                                </div>
                                <span class="text-sm text-gray-500">Status: {{ $ad->status }}</span>
                                
                                {{-- Property --}}
                                @if($ad->property)
                                    <div class="mt-2 text-sm">
                                        <strong>Location:</strong> {{ $ad->property->location }}<br>
                                        <strong>Price:</strong> {{ $ad->property->price }}<br>
                                        <strong>Type:</strong> {{ $ad->property->type }}
                                    </div>
                                    {{-- House --}}
                                    @if($ad->property->house)
                                        <div class="mt-2 text-sm">
                                            <strong>Bedrooms:</strong> {{ $ad->property->house->bedroom }}<br>
                                            <strong>Bathrooms:</strong> {{ $ad->property->house->bathroom }}<br>
                                            <strong>Area:</strong> {{ $ad->property->house->area }} sqft<br>
                                            <strong>Pool:</strong> {{ $ad->property->house->pool ? 'Yes' : 'No' }}<br>
                                            <strong>Parking:</strong> {{ $ad->property->house->parking ? 'Yes' : 'No' }}<br>
                                            <strong>House Type:</strong> {{ ucfirst($ad->property->house->house_type) }}
                                        </div>
                                    @endif
                                @endif
                                <div class="flex items-center justify-between   mt-2">
                                    <div>
                                        <button class="bg-[#959D90] text-white font-bold py-1 px-3 rounded hover:bg-green-600">
                                            <a href="{{ route('advertisements.edit', $ad->id) }}">Edit</a>
                                        </button>
                                        <button class="bg-[#523D35] text-white font-bold py-1 px-3 rounded hover:bg-[#886658]">
                                            <a href="{{ route('advertisements.show', $ad->id) }}">View</a>
                                        </button>
                                    </div>
                                    <form action="{{ route('advertisements.destroy', $ad->id) }}" method="POST" class="inline-block delete-ad-form" data-id="{{ $ad->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white font-bold py-1 px-3 rounded hover:bg-red-600">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-500 py-10">
                        No listing found.
                    </div>
                @endif
            </div>

            {{-- Seller's Blogs --}}
            <div id="blog-section" class="mt-16">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold">My Blogs</h2>
                    <a href="{{ route('blogs.create') }}" class="bg-[#523D35] text-white font-bold py-2 px-4 rounded hover:bg-[#886658]">
                        Create Blog
                    </a>
                </div>
                @if($blogs->count())
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($blogs as $blog)
                            <div class="border rounded p-4 shadow bg-white hover:shadow-lg transition">
                                <h3 class="font-semibold text-lg mb-2">{{ $blog->title }}</h3>
                                <div class="text-sm text-gray-500 mb-2">
                                    {{ $blog->created_at?->toFormattedDateString() }}
                                </div>
                                @if($blog->images && count($blog->images))
                                    <img src="{{ asset('storage/' . $blog->images[0]) }}" class="w-full h-32 object-cover rounded mb-2" alt="">
                                @endif
                                <p class="text-gray-700 mb-2">{{ Str::limit($blog->content, 120) }}</p>
                                <div class="flex gap-2 mt-2">
                                    <a href="{{ route('blogs.edit', $blog->_id) }}" class="bg-[#959D90] text-white px-3 rounded py-1 font-bold">Edit</a>
                                    <form action="{{ route('blogs.destroy', $blog->_id) }}" method="POST" onsubmit="return confirm('Delete this blog?')" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 px-3 py-1 font-bold text-white rounded">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-500 py-10">
                        You haven't written any blogs yet.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" style="display:none; position:fixed; left:50%; bottom:40px; transform:translateX(-50%); background:#fecaca; color:#b91c1c; padding:16px 32px; border-radius:8px; font-weight:bold; z-index:9999; box-shadow:0 2px 8px rgba(0,0,0,0.2);">
        Advertisement deleted.
        <svg style="display:inline;vertical-align:middle;margin-left:8px;" width="18" height="18" fill="#b91c1c" viewBox="0 0 16 16">
            <circle cx="8" cy="8" r="8"/>
        </svg>
    </div>

    <script>
    document.querySelectorAll('.delete-ad-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if(!confirm('Are you sure you want to delete this advertisement?')) return;

            const adCard = form.closest('.border.rounded.p-4.shadow');
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            })
            .then(res => {
                if(res.ok) {
                    // Remove the card from the DOM
                    if(adCard) adCard.remove();
                    // Show toast
                    const toast = document.getElementById('toast');
                    toast.style.display = 'block';
                    toast.textContent = 'Advertisement deleted.';
                    setTimeout(() => { toast.style.display = 'none'; }, 2500);
                } else {
                    return res.json().then(data => { throw new Error(data.message || 'Delete failed'); });
                }
            })
            .catch(err => {
                const toast = document.getElementById('toast');
                toast.style.display = 'block';
                toast.style.background = '#e53e3e';
                toast.textContent = err.message;
                setTimeout(() => { toast.style.display = 'none'; toast.style.background = '#38a169'; }, 2500);
            });
        });
    });

    function scrollToBlogs() {
        const blogsSection = document.getElementById('blog-section');
        if(blogsSection) {
            blogsSection.scrollIntoView({ behavior: 'smooth' });
        }
    }
    </script>
</x-app-layout>
