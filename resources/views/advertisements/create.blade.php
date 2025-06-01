{{-- filepath: resources/views/advertisements/create.blade.php --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<x-app-layout>
    <div class="max-w-xl mx-auto py-10">

        {{-- Progress Bar --}}
        <div id="progressBar" style="display:flex; justify-content:space-between; margin-bottom:30px;" class="mt-20">
            <div class="step" id="step1">1</div>
            <div class="bar"></div>
            <div class="step" id="step2">2</div>
            <div class="bar"></div>
            <div class="step" id="step3">3</div>
            <div class="bar"></div>
            <div class="step" id="step4">4</div>
        </div>

        {{-- Step 1: Advertisement --}}
        <form id="adForm" >
            <h2 class="text-xl font-bold mb-4">Step 1: Advertisement Details</h2>
            <input type="text" name="title" class="input" placeholder="Title" required>
            <input type="text" name="status" class="input" placeholder="Status" required>
            <textarea name="description" class="input" placeholder="Description"></textarea>
            <button type="submit" class="btn">Create Advertisement</button>
        </form>

        {{-- Step 2: Property --}}
        <form id="propertyForm" style="display:none;">
            <h2 class="text-xl font-bold mb-4">Step 2: Property Details</h2>
            <input type="hidden" name="advertisement_id" id="property_advertisement_id">
            <div class="mb-4">
                <label for="location" class="block mb-1">Location (click on map):</label>
                <input type="text" name="location" id="property_location" class="input" placeholder="Location" required readonly>
                <div id="propertyMap" style="height: 250px; border-radius: 8px; margin-top:10px;"></div>
            </div>
            <input type="number" name="price" class="input" placeholder="Price" required>
            <input type="text" name="type" class="input" placeholder="Type" value="house" readonly required>
            <button type="submit" class="btn">Create Property</button>
        </form>

        {{-- Step 3: House --}}
        <form id="houseForm" style="display:none;">
            <h2 class="text-xl font-bold mb-4">Step 3: House Details</h2>
            <input type="number" name="property_id" id="house_property_id">
            <input type="number" name="bedroom" class="input" placeholder="Bedrooms" required>
            <input type="number" name="bathroom" class="input" placeholder="Bathrooms" required>
            <input type="number" name="area" class="input" placeholder="Area (sqft)" required>
            <label><input type="checkbox" name="pool" value="1"> Pool</label>
            <label><input type="checkbox" name="parking" value="1"> Parking</label>
            <select name="house_type" class="input" required>
                <option value="" disabled selected>Select House Type</option>
                <option value="modern">Modern</option>
                <option value="traditional">Traditional</option>
                <option value="luxury">Luxury</option>
            </select>
            <button type="submit" class="btn">Create House</button>
        </form>

        {{-- Step 4: Images --}}
        <form id="imageForm" style="display:none;">
            <h2 class="text-xl font-bold mb-4">Step 4: Upload Images</h2>
            <input type="hidden" name="advertisement_id" id="image_advertisement_id">
            <input type="file" name="images[]" multiple accept="image/*" required>
            <button type="submit" class="btn">Upload Images</button>
        </form>

        {{-- Final Summary --}}
        <div id="finalSummary" style="display:none; margin-top:30px; border:2px solid #4CAF50; padding:20px; border-radius:8px; background:#f6fff6;">
            <h2 style="color:#388e3c;">🎉 Great job! Your advertisement is ready.</h2>
            <div id="fullAdDetails"></div>
            <button id="proceedToPayment" class="btn" style="margin-top:20px;">Proceed to Payment</button>
        </div>

        {{-- Payment Form --}}
        <div id="paymentForm" style="display:none; margin-top:30px; border:2px solid #2196F3; padding:20px; border-radius:8px; background:#f0f8ff;">
            <h2 style="color:#1976d2;">💳 Complete Your Payment</h2>
            <form id="fakePaymentForm" style="max-width:400px;">
                <div style="margin-bottom:12px;">
                    <label>Cardholder Name</label>
                    <input type="text" class="input" required placeholder="Name on Card">
                </div>
                <div style="margin-bottom:12px;">
                    <label>Card Number</label>
                    <input type="text" class="input" required maxlength="19" pattern="\d{16,19}" placeholder="1234 5678 9012 3456">
                </div>
                <div style="display:flex; gap:10px; margin-bottom:12px;">
                    <div style="flex:1;">
                        <label>Expiry</label>
                        <input type="text" class="input" required maxlength="5" pattern="\d{2}/\d{2}" placeholder="MM/YY">
                    </div>
                    <div style="flex:1;">
                        <label>CVC</label>
                        <input type="text" class="input" required maxlength="4" pattern="\d{3,4}" placeholder="123">
                    </div>
                </div>
                <button type="submit" class="btn" style="background:#1976d2;">Pay Now</button>
            </form>
        </div>

        {{-- Payment Success --}}
        <div id="paymentSuccess" style="display:none; margin-top:30px; border:2px solid #4CAF50; padding:20px; border-radius:8px; background:#e8f5e9; text-align:center;">
            <h2 style="color:#388e3c;">✅ Payment Successful!</h2>
            <p>Your advertisement is now live. Thank you for your payment.</p>
            <a href="{{ route('dashboard') }}" class="btn" style="margin-top:20px; display:inline-block;">Go to Dashboard</a>
        </div>
    </div>

    {{-- JS for steps and progress --}}
    <script>
    const API_TOKEN = "{{ session('api_token') }}";

    function setStep(step) {
        for (let i = 1; i <= 4; i++) {
            document.getElementById('step' + i).classList.remove('active');
        }
        document.getElementById('step' + step).classList.add('active');
    }
    setStep(1);

    let adData = {}, propertyData = {}, houseData = {}, imageData = {};

    function showError(message) {
        let err = document.getElementById('formError');
        if (!err) {
            err = document.createElement('div');
            err.id = 'formError';
            err.style.color = 'red';
            err.style.marginBottom = '10px';
            document.querySelector('.max-w-xl').prepend(err);
        }
        err.textContent = message;
    }

    function clearError() {
        let err = document.getElementById('formError');
        if (err) err.textContent = '';
    }

    async function safeFetch(url, options) {
        try {
            let res = await fetch(url, options);
            let text = await res.text();
            try {
                let json = JSON.parse(text);
                if (!res.ok) {
                    showError(json.message || 'Server error');
                    console.error('API error:', json);
                    return null;
                }
                return json;
            } catch (e) {
                showError('Server returned an unexpected response.');
                console.error('Non-JSON response:', text);
                return null;
            }
        } catch (err) {
            showError('Network error: ' + err.message);
            console.error('Fetch error:', err);
            return null;
        }
    }

    document.getElementById('adForm').onsubmit = async function(e) {
        e.preventDefault();
        clearError();
        setStep(1);
        let form = e.target;
        let data = new FormData(form);
        let json = await safeFetch('/api/advertisement', {
            method: 'POST',
            body: data,
            headers: { 'Authorization': 'Bearer ' + API_TOKEN }
        });
        if (json && json.advertisement_id) {
            adData = json.advertisement;
            console.log('Advertisement created:', adData);
            document.getElementById('adForm').style.display = 'none';
            document.getElementById('propertyForm').style.display = 'block';
            document.getElementById('property_advertisement_id').value = json.advertisement_id;
            document.getElementById('image_advertisement_id').value = json.advertisement_id;
            setStep(2);
        }
    };

    document.getElementById('propertyForm').onsubmit = async function(e) {
        e.preventDefault();
        clearError();
        setStep(2);
        let form = e.target;
        let data = new FormData(form);
        let json = await safeFetch('/api/property', {
            method: 'POST',
            body: data,
            headers: { 'Authorization': 'Bearer ' + API_TOKEN }
        });
        if (json && json.property_id) {
            propertyData = json.property;
            console.log('Property created:', propertyData);
            document.getElementById('propertyForm').style.display = 'none';
            document.getElementById('houseForm').style.display = 'block';
            document.getElementById('house_property_id').value = json.property_id;
            setStep(3);
        }
    };

    document.getElementById('houseForm').onsubmit = async function(e) {
        e.preventDefault();
        clearError();
        setStep(3);
        let form = e.target;

        // Ensure unchecked checkboxes are sent as 0
        ['pool', 'parking'].forEach(name => {
            if (!form[name].checked) {
                let existing = form.querySelector(`input[type="hidden"][name="${name}"]`);
                if (existing) existing.remove();
                let hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = name;
                hidden.value = '0';
                form.appendChild(hidden);
            }
        });

        let data = new FormData(form);
        let houseInfo = {};
        for (let [key, value] of data.entries()) {
            houseInfo[key] = value;
        }
        console.log('House form data before submit:', houseInfo);

        let json = await safeFetch('/api/house', {
            method: 'POST',
            body: data,
            headers: { 'Authorization': 'Bearer ' + API_TOKEN }
        });
        if (json && json.house_id) {
            houseData = json.house;
            console.log('House created:', houseData);
            document.getElementById('houseForm').style.display = 'none';
            document.getElementById('imageForm').style.display = 'block';
            setStep(4);
        }
    };

    document.getElementById('imageForm').onsubmit = async function(e) {
        e.preventDefault();
        clearError();
        setStep(4);
        let form = e.target;
        let data = new FormData(form);
        let json = await safeFetch('/api/image', {
            method: 'POST',
            body: data,
            headers: { 'Authorization': 'Bearer ' + API_TOKEN }
        });
        if (json && json.images) {
            imageData = json.images;
            document.getElementById('imageForm').style.display = 'none';
            showFinalSummary();
        }
    };

    function showFinalSummary() {
        let html = `
            <h3>Advertisement</h3>
            <ul>
                <li><strong>Title:</strong> ${adData.title}</li>
                <li><strong>Status:</strong> ${adData.status}</li>
                <li><strong>Description:</strong> ${adData.description}</li>
            </ul>
            <h3>Property</h3>
            <ul>
                <li><strong>Location:</strong> ${propertyData.location}</li>
                <li><strong>Price:</strong> ${propertyData.price}</li>
                <li><strong>Type:</strong> ${propertyData.type}</li>
            </ul>
            <h3>House</h3>
            <ul>
                <li><strong>Bedrooms:</strong> ${houseData.bedroom}</li>
                <li><strong>Bathrooms:</strong> ${houseData.bathroom}</li>
                <li><strong>Area:</strong> ${houseData.area} sqft</li>
                <li><strong>Pool:</strong> ${houseData.pool ? 'Yes' : 'No'}</li>
                <li><strong>Parking:</strong> ${houseData.parking ? 'Yes' : 'No'}</li>
                <li><strong>House Type:</strong> ${houseData.house_type}</li>
            </ul>
            <h3>Images</h3>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                ${Array.isArray(imageData) ? imageData.map(img => `<img src="/storage/${img.data}" style="width:100px;height:100px;object-fit:cover;border-radius:6px;border:1px solid #ccc;">`).join('') : ''}
            </div>
        `;
        document.getElementById('fullAdDetails').innerHTML = html;
        document.getElementById('finalSummary').style.display = 'block';
        setStep(4);
    }

    // Payment flow
    document.getElementById('proceedToPayment').onclick = function() {
        document.getElementById('finalSummary').style.display = 'none';
        document.getElementById('paymentForm').style.display = 'block';
    };

    document.getElementById('fakePaymentForm').onsubmit = function(e) {
        e.preventDefault();
        document.getElementById('paymentForm').style.display = 'none';
        document.getElementById('paymentSuccess').style.display = 'block';
    };
    </script>

    <style>
    .input { display:block; margin-bottom:10px; padding:8px; width:100%; border:1px solid #ccc; border-radius:4px; }
    .btn { background:#959D90; color:white; padding:8px 16px; border:none; border-radius:4px; cursor:pointer; }
    .btn:hover { background:#6b7a5b; }
    .step {
        width: 50px;
        height: 50px;
        background: #2dfd5a;
        border-radius: 50%;
        text-align: center;
        font-weight: bold;
        font-size: 20px;
        border: 2px solid black;
        transition: background 0.3s, border 0.3s;
        margin-bottom: 5px;
        padding-top: 6px;
    }
    .step.active {
        background: #21a83f;
        color: #fff;
        border: 2px solid #388e3c;
    }
    .bar {
        flex: 1;
        height: 4px;
        background: #bdbdbd;
        align-self: center;
        margin: 0 4px;
        border-radius: 2px;
    }
    </style>
</x-app-layout>