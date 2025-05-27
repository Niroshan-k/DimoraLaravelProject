{{-- filepath: resources/views/components/advertisement-card.blade.php --}}
<div class="bg-white rounded-lg shadow overflow-hidden group transform transition-transform duration-300 hover:-translate-y-2 hover:shadow-xl">
    <!-- Advertisement Image -->
    @if (!empty($advertisement['images']) && count($advertisement['images']) > 0)
        <img 
            src="{{ asset('storage/' . $advertisement['images'][0]['data']) }}" 
            alt="Advertisement Image" 
            class="w-full h-48 object-cover transition-transform duration-500 hover:scale-110">
    @else
        <img 
            src="{{ asset('storage/appImages/1.jpg') }}" 
            alt="Default Image" 
            class="w-full h-48 object-cover">
    @endif
    <div class="absolute top-0 left-0">
        @if ($advertisement['status'] === 'active')
            <span class="text-white bg-green-500 px-2 py-1 rounded-br font-bold">Active</span>
        @else
            <span class="text-white bg-red-500 px-2 py-1 rounded-br font-bold">Sold</span>
        @endif
    </div>
    
    <!-- Advertisement Details -->
    <div class="p-4">
        <h1 class="text-xl uppercase font-bold">{{ $advertisement['title'] }}</h1>
        @if (!empty($advertisement['property']))
            <div class="mt-4">
                <p class="text-gray-700 text-sm truncate"><strong>Location:</strong> <i class="fa fa-location"></i> {{ $advertisement['property']['location'] }}</p>
                <p class="text-green-500 font-bold text-xl">Price: ${{ number_format($advertisement['property']['price'], 2) }}</p>
                @if (!empty($advertisement['property']['house']))
                    <div class="mt-4">
                        <p class="text-gray-700 text-sm"><strong>Type:</strong> {{ $advertisement['property']['house']['house_type'] }}</p>
                        <p class="text-gray-700 text-sm"><strong>Area:</strong> {{ $advertisement['property']['house']['area'] }} sq ft</p>
                    </div>
                @endif
            </div>
        @else
            <p>No property details available.</p>
        @endif

        <!-- Call to Action -->
        <div class="flex items-center justify-between">
            <div class="mt-4">
                <a href="/advertisement/{{ $advertisement['id'] }}" class="bg-[#523D35] text-white py-2 px-4 rounded hover:bg-[#886658]">
                    View Details
                </a>
            </div>
            <div>
                <!-- Share Options -->
                <div class="mt-2 flex gap-2">
                    <!-- WhatsApp Share -->
                    <a href="https://wa.me/?text={{ urlencode(url('/advertisement/' . $advertisement['id'])) }}" 
                    target="_blank" 
                    title="Share on WhatsApp"
                    class="flex text-2xl font-bold text-green-500 items-center">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <!-- Instagram (copy link) -->
                    <button type="button"
                        onclick="navigator.clipboard.writeText('{{ url('/advertisement/' . $advertisement['id']) }}'); alert('Link copied! Paste it in Instagram DM or story.');"
                        title="Copy link for Instagram"
                        class="flex text-2xl font-bold text-purple-500 items-center">
                        <i class="fab fa-instagram w-6 h-6"></i>
                    </button>
                    <!-- Copy URL -->
                    <button type="button"
                        onclick="navigator.clipboard.writeText('{{ url('/advertisement/' . $advertisement['id']) }}'); alert('Link copied to clipboard!');"
                        title="Copy link"
                        class="flex text-2xl font-bold items-center">
                        <i class="fa fa-copy"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    
</div>