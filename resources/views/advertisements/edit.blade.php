<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-storage-compat.js"></script>

<x-app-layout>
    <div class="max-w-2xl mx-auto py-10">
        <h2 class="text-2xl font-bold mb-6">Edit Advertisement</h2>
        <form action="{{ route('advertisements.update', $advertisement->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Advertisement Fields --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Title</label>
                <input type="text" name="title" class="input w-full" value="{{ old('title', $advertisement->title) }}" required>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Status</label>
                <select name="status" id="status" class="input w-full">
                    <option value="active" {{ old('status', $advertisement->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $advertisement->status) == 'inactive' ? 'selected' : '' }}>Sold</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Description</label>
                <textarea name="description" class="input w-full" rows="3">{{ old('description', $advertisement->description) }}</textarea>
            </div>

            {{-- Property Fields --}}
            @if($advertisement->property)
            <div class="mb-4">
                <label class="block font-semibold mb-1">Location (click on map):</label>
                <input type="text" name="location" id="property_location" class="input w-full" value="{{ old('location', $advertisement->property->location ?? '') }}" required readonly>
                <div id="propertyMap" style="height: 250px; border-radius: 8px; margin-top:10px;"></div>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Price</label>
                <input type="number" name="price" class="input w-full" value="{{ old('price', $advertisement->property->price) }}">
            </div>
            <div class="mb-4 hidden">
                <label class="block font-semibold mb-1">Type</label>
                <input type="text" name="type" class="input w-full" value="{{ old('type', $advertisement->property->type) }}">
            </div>
            @endif

            {{-- House Fields --}}
            @if($advertisement->property && $advertisement->property->house)
            <div class="mb-4">
                <label class="block font-semibold mb-1">Bedrooms</label>
                <input type="number" name="bedroom" class="input w-full" value="{{ old('bedroom', $advertisement->property->house->bedroom) }}">
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Bathrooms</label>
                <input type="number" name="bathroom" class="input w-full" value="{{ old('bathroom', $advertisement->property->house->bathroom) }}">
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Area (sqft)</label>
                <input type="number" name="area" class="input w-full" value="{{ old('area', $advertisement->property->house->area) }}">
            </div>
            <div class="mb-4 flex gap-4">
                <label><input type="checkbox" name="pool" value="1" {{ old('pool', $advertisement->property->house->pool) ? 'checked' : '' }}> Pool</label>
                <label><input type="checkbox" name="parking" value="1" {{ old('parking', $advertisement->property->house->parking) ? 'checked' : '' }}> Parking</label>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">House Type</label>
                <select name="house_type" class="input w-full">
                    <option value="modern" {{ old('house_type', $advertisement->property->house->house_type) == 'modern' ? 'selected' : '' }}>Modern</option>
                    <option value="traditional" {{ old('house_type', $advertisement->property->house->house_type) == 'traditional' ? 'selected' : '' }}>Traditional</option>
                    <option value="luxury" {{ old('house_type', $advertisement->property->house->house_type) == 'luxury' ? 'selected' : '' }}>Luxury</option>
                </select>
            </div>
            @endif

            {{-- Images --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Images</label>
                <div class="flex gap-2 mt-2 flex-wrap" id="image-preview-list">
                    @foreach($advertisement->images as $img)
                        <div class="relative group" style="display:inline-block;">
                            <img src="{{ $img->data }}" class="w-20 h-20 object-cover rounded" alt="">
                            <button type="button"
                                class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-80 hover:opacity-100 delete-image-btn"
                                data-image-id="{{ $img->id }}"
                                title="Delete image">
                                &times;
                            </button>
                        </div>
                    @endforeach
                </div>
                @php
                    $maxImages = 4;
                    $remaining = $maxImages - $advertisement->images->count();
                @endphp

                <input type="file" name="images[]" multiple accept="image/*" class="input w-full mt-2" {{ $remaining <= 0 ? 'disabled' : '' }} {{ $remaining > 0 ? 'max="'.$remaining.'"' : '' }}>
                <small class="text-gray-500">
                    You can upload up to {{ $remaining }} more image{{ $remaining == 1 ? '' : 's' }} (max 4 images total).
                </small>
            </div>

            {{-- Hidden Seller ID --}}
            <input type="hidden" name="seller_id" value="{{ $advertisement->seller_id }}">

            <button type="submit" class="bg-[#523D35] text-white font-bold py-2 px-6 rounded hover:bg-[#886658]">Update Advertisement</button>
        </form>
    </div>

    <!-- Toast Notification for image delete -->
    <div id="image-toast" style="display:none; position:fixed; left:50%; bottom:40px; transform:translateX(-50%); background:#fecaca; color:#b91c1c; padding:12px 28px; border-radius:8px; font-weight:bold; z-index:9999; box-shadow:0 2px 8px rgba(0,0,0,0.2);">
        Image deleted.
    </div>

    <!-- Custom Confirm Dialog -->
    <div id="image-confirm" style="display:none; position:fixed; left:50%; bottom:100px; transform:translateX(-50%); background:#fff; color:#222; padding:18px 32px; border-radius:8px; font-weight:bold; z-index:10000; box-shadow:0 2px 8px rgba(0,0,0,0.2); border:1px solid #b91c1c;">
        <span>Are you sure you want to delete this image?</span>
        <button id="confirm-yes" class="ml-4 bg-red-500 text-white px-3 py-1 rounded">Yes</button>
        <button id="confirm-no" class="ml-2 bg-gray-300 text-gray-800 px-3 py-1 rounded">No</button>
    </div>

    <script>
     const API_TOKEN = "{{ session('api_token') }}";
     let pendingDeleteBtn = null;

     document.querySelectorAll('.delete-image-btn').forEach(btn => {
         btn.addEventListener('click', function() {
             pendingDeleteBtn = this;
             document.getElementById('image-confirm').style.display = 'block';
         });
     });

     const firebaseConfig = {
        apiKey: "AIzaSyCM-ILTwPwjaE9ccQ3rg9f68S7MlObLRng",
        authDomain: "dimora-55e52.firebaseapp.com",
        projectId: "dimora-55e52",
        storageBucket: "dimora-55e52.firebasestorage.app", // <-- fix here!
        messagingSenderId: "1069659225426",
        appId: "1:1069659225426:web:1ec863f1b33df957210e8b",
        measurementId: "G-HCBFJRHKY4"
     };
     firebase.initializeApp(firebaseConfig);
     const storage = firebase.storage();

     async function uploadToFirebase(file) {
        const storageRef = storage.ref('images/' + Date.now() + '-' + file.name);
        await storageRef.put(file);
        return await storageRef.getDownloadURL();
     }

     document.getElementById('confirm-yes').onclick = function() {
         const btn = pendingDeleteBtn;
         const imageId = btn.getAttribute('data-image-id');
         fetch('/api/image/' + imageId, {
             method: 'DELETE',
             headers: {
                 'Authorization': 'Bearer ' + API_TOKEN,
                 'Accept': 'application/json'
             }
         })
         .then(res => {
             const toast = document.getElementById('image-toast');
             document.getElementById('image-confirm').style.display = 'none';
             if(res.ok) {
                 btn.closest('.group').remove();
                 toast.textContent = 'Image deleted.';
                 toast.style.background = '#fecaca';
                 toast.style.color = '#b91c1c';
                 toast.style.display = 'block';
                 setTimeout(() => { toast.style.display = 'none'; }, 2000);
             } else {
                 toast.textContent = 'Failed to delete image.';
                 toast.style.background = '#fee2e2';
                 toast.style.color = '#b91c1c';
                 toast.style.display = 'block';
                 setTimeout(() => { toast.style.display = 'none'; }, 2000);
             }
         });
     };

     document.getElementById('confirm-no').onclick = function() {
         document.getElementById('image-confirm').style.display = 'none';
         pendingDeleteBtn = null;
     };

     document.addEventListener('DOMContentLoaded', function () {
         if(document.getElementById('propertyMap')) {
             // Default to Sri Lanka or existing coordinates
             let defaultLat = 7.8731;
             let defaultLng = 80.7718;
             let zoom = 7;
             let locationInput = document.getElementById('property_location');
             let marker;

             // Try to set initial marker if location is an address or lat,lng
             let initialValue = locationInput.value;
             let initialLatLng = null;

             // If the value looks like "lat,lng", use it
             if (/^-?\d+\.\d+,\s*-?\d+\.\d+$/.test(initialValue)) {
                 let parts = initialValue.split(',').map(Number);
                 initialLatLng = [parts[0], parts[1]];
             }

             var map = L.map('propertyMap').setView(initialLatLng || [defaultLat, defaultLng], initialLatLng ? 13 : zoom);

             L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                 maxZoom: 19,
                 attribution: '© OpenStreetMap'
             }).addTo(map);

             if (initialLatLng) {
                 marker = L.marker(initialLatLng).addTo(map);
                 // Optionally, reverse geocode to show address for initial marker
                 fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${initialLatLng[0]}&lon=${initialLatLng[1]}`)
                     .then(response => response.json())
                     .then(data => {
                         if (data.display_name) {
                             locationInput.value = data.display_name;
                         }
                     });
             }

             map.on('click', function(e) {
                 var latlng = e.latlng;
                 if (marker) {
                     marker.setLatLng(latlng);
                 } else {
                     marker = L.marker(latlng).addTo(map);
                 }
                 // Reverse geocode to get address
                 fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latlng.lat}&lon=${latlng.lng}`)
                     .then(response => response.json())
                     .then(data => {
                         if (data.display_name) {
                             locationInput.value = data.display_name;
                         } else {
                             locationInput.value = latlng.lat.toFixed(6) + ',' + latlng.lng.toFixed(6);
                         }
                     })
                     .catch(() => {
                         locationInput.value = latlng.lat.toFixed(6) + ',' + latlng.lng.toFixed(6);
                     });
             });
         }
     });

     document.querySelector('form').addEventListener('submit', async function(e) {
         const fileInput = this.querySelector('input[type="file"][name="images[]"]');
         if (fileInput && fileInput.files.length > 0) {
             e.preventDefault();

             // Upload all new files to Firebase
             let urls = [];
             for (let file of fileInput.files) {
                 const url = await uploadToFirebase(file);
                 urls.push(url);
             }

             // Create hidden inputs for each Firebase URL
             for (let url of urls) {
                 const input = document.createElement('input');
                 input.type = 'hidden';
                 input.name = 'firebase_images[]';
                 input.value = url;
                 this.appendChild(input);
             }

             // Remove the file input so it doesn't try to upload to your server
             fileInput.value = '';

             // Submit the form again (now with Firebase URLs)
             this.submit();
         }
     });
    </script>
</x-app-layout>