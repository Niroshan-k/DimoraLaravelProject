{{-- filepath: resources/views/master.blade.php --}}
@vite(['resources/css/home.css'])
@include('layouts.header')

    {{-- Image Slider --}}
    @if($advertisement->images->count())
        <div 
            x-data="{
                active: 0,
                images: {{ $advertisement->images->pluck('data')->map(fn($d) => asset('storage/'.$d)) }},
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
            class="relative h-[400px] md:w-full md:h-[800px] mb-8 rounded-b overflow-hidden shadow"
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
            <div class="absolute bottom-3 left-1/2 transform -translate-x-1/2 flex space-x-1">
                <template x-for="(img, idx) in images" :key="idx">
                    <button 
                        class="w-3 h-3 rounded-full border-2"
                        :class="active === idx ? 'bg-green-700 border-green-700' : 'bg-gray-100 border-gray-400'"
                        @click="active = idx"
                    ></button>
                </template>
            </div>
        </div>
    @endif
<div class="flex flex-col px-4 md:px-0 items-center min-h-screen bg-gray-50 py-8">
    <div class="w-full max-w-4xl flex flex-col gap-8">
        {{-- Advertisement Details --}}
        <div class="bg-white shadow-md rounded-lg p-6">
            <h6 class="text-5xl font-reverie mb-4">{{ $advertisement->title }}</h6>
            <p class="">Description :</p>
            <p class="text-gray-600 mb-2">{{ $advertisement->description }}</p>
            <div class="flex items-center mb-4">
                <p class="mr-2">Status :</p>
                @if($advertisement->status === 'active')
                    <span class="text-green-600">Active🟢</span>
                @else
                    <span class="text-red-600">Sold🔴</span>
                @endif
            </div>
        </div>

        {{-- Property & House Details --}}
        @if($advertisement->property)
            <div class="grid gap-8">
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h1 class="text-4xl font-bold mb-4">Property Details</h1>
                    <p><strong>Location:</strong> {{ $advertisement->property->location }}</p>
                    <p class="text-2xl text-green-500 font-bold mb-2"><strong>Price:</strong> ${{ number_format($advertisement->property->price, 2) }}</p>
                    <p><strong>Type:</strong> {{ $advertisement->property->type }}</p>
                </div>
                @if($advertisement->property->house)
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h1 class="text-4xl font-bold mb-4">House Details</h1>
                    <div class="grid gap-5 grid-cols-3">
                        <div class="flex flex-col items-center bg-gray-100 rounded p-5 justify-center">
                            <p><i class="fa-solid fa-bed"></i> {{ $advertisement->property->house->bedroom }}</p>
                            <p><strong>Bedrooms</strong></p>
                        </div>
                        <div class="flex flex-col items-center bg-gray-100 rounded p-5 justify-center">
                            <p><i class="fa-solid fa-bath"></i> {{ $advertisement->property->house->bathroom }}</p>
                            <p><strong>Bathrooms</strong></p>
                        </div>
                        <div class="flex flex-col items-center bg-gray-100 rounded p-5 justify-center">
                            <p><i class="fa-solid fa-ruler-combined"></i> {{ $advertisement->property->house->area }} sqft</p>
                            <p><strong>Area</strong></p>
                        </div>
                        <div class="flex flex-col items-center bg-gray-100 rounded p-5 justify-center">
                            <p><i class="fa-solid fa-water-ladder"></i> {{ $advertisement->property->house->pool ? 'Yes' : 'No' }}</p>
                            <p><strong>Pool</strong></p>
                        </div>
                        <div class="flex flex-col items-center bg-gray-100 rounded p-5 justify-center">
                            <p><i class="fa-solid fa-car"></i> {{ $advertisement->property->house->parking ? 'Yes' : 'No' }}</p>
                            <p><strong>Parking</strong></p>
                        </div>
                        <div class="flex flex-col items-center bg-gray-100 rounded p-5 justify-center">
                            <p><i class="fa-solid fa-house"></i> {{ ucfirst($advertisement->property->house->house_type) }}</p>
                            <p><strong>Type</strong></p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        @endif

        {{-- Map & Contact --}}
        <div class="grid gap-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="font-bold text-4xl mb-4">Location Map</h1>
                <div id="map" style="height: 300px; border-radius: 8px;" class="w-full"></div>
            </div>
            {{-- Contact Seller --}}
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="font-bold text-4xl mb-4">Contact Seller</h1>
                @php
                    $seller = $advertisement->seller;
                @endphp

                @if($seller)
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('storage/' . $seller->profile_photo_path) }}" alt="Seller Image" class="w-12 h-12 rounded-full object-cover">
                        <span class="font-bold text-lg">{{ $seller->name }}</span>
                    </div>
                @endif
                @auth
                    <form id="whatsapp-contact-form" class="bg-gray-50 rounded shadow p-6 space-y-4">
                        @csrf
                        <div>
                            <label class="block font-semibold mb-1">Your Name</label>
                            <input type="text" name="name" id="wa_name" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" />
                        </div>
                        <div>
                            <label class="block font-semibold mb-1">Phone Number</label>
                            <input type="text" name="number" id="wa_number" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" />
                        </div>
                        <div>
                            <label class="block font-semibold mb-1">Email</label>
                            <input type="email" name="email" id="wa_email" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" />
                        </div>
                        <div>
                            <label class="block font-semibold mb-1">Message</label>
                            <textarea name="message" rows="4" id="wa_message" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring"></textarea>
                        </div>
                        <button type="button" onclick="sendWhatsAppMessage()" class="bg-[#523D35] text-white px-6 py-2 rounded hover:bg-[#886658] font-bold w-full">
                            Send Message
                        </button>
                    </form>
                @endauth

                @guest
                    <div class="text-center">
                        <a href="{{ route('login') }}" class="bg-[#523D35] text-white px-6 py-2 rounded hover:bg-[#886658] font-bold w-full inline-block">
                            Sign In to Contact Seller
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</div>

@include('components.footer') {{-- Optional: your custom footer --}}

{{-- Leaflet.js --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>

    function sendWhatsAppMessage() {
        const name = document.getElementById('wa_name').value;
        const number = document.getElementById('wa_number').value;
        const email = document.getElementById('wa_email').value;
        const message = document.getElementById('wa_message').value;

        // Compose the WhatsApp message
        const text = `Name: ${name}%0APhone: ${number}%0AEmail: ${email}%0AMessage: ${message}`;
        // Replace the number below with the seller's WhatsApp number, or leave blank for user to choose
        const sellerNumber = "+94788672025"; // e.g., "1234567890"
        const waUrl = `https://wa.me/${sellerNumber}?text=${text}`;

        window.open(waUrl, '_blank');
    }

    document.addEventListener('DOMContentLoaded', function () {
        var address = @json($advertisement->property->location ?? '');
        var mapDiv = document.getElementById('map');
        if (!address || !mapDiv) return;

        // Use Nominatim to geocode the address
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    var lat = parseFloat(data[0].lat);
                    var lon = parseFloat(data[0].lon);
                    var map = L.map('map').setView([lat, lon], 13);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(map);

                    L.marker([lat, lon]).addTo(map)
                        .bindPopup(address)
                        .openPopup();
                } else {
                    mapDiv.innerHTML = '<div class="text-red-500 p-4">Location not found on map.</div>';
                }
            })
            .catch(() => {
                mapDiv.innerHTML = '<div class="text-red-500 p-4">Failed to load map.</div>';
            });
    });
</script>
<script src="//unpkg.com/alpinejs" defer></script>