<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 @vite(['resources/css/home.css'])
</head>
<main>

@include('layouts.header')
<header id="home">
  <div class="flex bg-[#0000005d] h-screen justify-between p-10">
    <div class="flex flex-col justify-end">
      <p class="uppercase text-white font-bold tracking-widest">discover your</p>
      <h6 class="text-white text-[100px]">Perfect Home</h6>
      <div>
        <button onclick="handleGetStarted()" class="border-4 text-white border-white hover:text-black hover:bg-white py-3 px-5 font-bold mt-5">
          Get Started
        </button>
      </div>
    </div>
    <div class="md:flex mt-[100px] gap-5 justify-end hidden">
      @php
        $homes = [
          ['image' => 'https://firebasestorage.googleapis.com/v0/b/dimora-55e52.firebasestorage.app/o/images%2F03_cabo.jpg?alt=media&token=def7bc81-0f21-48d2-baf5-b2cd8137ce28', 'type' => 'Luxury House', 'desc' => 'Experience elegance and comfort.'],
          ['image' => 'https://firebasestorage.googleapis.com/v0/b/dimora-55e52.firebasestorage.app/o/images%2F07_cabo.jpg?alt=media&token=117404fc-4f31-498c-a562-358cd2cbff96', 'type' => 'Traditional House', 'desc' => 'Classic charm meets cozy living.'],
          ['image' => 'https://firebasestorage.googleapis.com/v0/b/dimora-55e52.firebasestorage.app/o/images%2F18_cabo.jpg?alt=media&token=423c9f86-6838-4d66-8090-69b016057cbc', 'type' => 'Modern House', 'desc' => 'Sleek designs for modern life.'],
        ];
      @endphp

      @foreach ($homes as $home)
      <div class="relative w-[300px] h-full group overflow-hidden border-2">
        <img src="{{ $home['image'] }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110 z-0" alt="">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4 text-white">
            <h6 class="text-xl">{{ $home['type'] }}</h6>
            <p class="text-sm">{{ $home['desc'] }}</p>
            <button 
                onclick="scrollToSection('{{ strtolower(explode(' ', $home['type'])[0]) }}')" 
                class="mt-2 self-start bg-white text-black px-4 py-1 text-sm font-semibold hover:bg-gray-200 transition">
                View Listings
            </button>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  
</header>
 <div class="container px-4 md:px-0 mx-auto py-8">
 <!-- <pre>{{ print_r($advertisementsModern->toArray(), true) }}</pre> -->
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <h6 id="latest-section" class="text-4xl mt-10 mb-8">Latest</h6>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($advertisementsLatest->take(8) as $advertisement)
                @include('components.advertisement-card', ['advertisement' => $advertisement, 'wishlistedIds' => $wishlistedIds])
            @endforeach
        </div>

        <h6 id="luxury-section" class="text-4xl mt-20 mb-8">Luxury Houses</h6>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($advertisementsLuxury->take(4) as $advertisement)
                @include('components.advertisement-card', ['advertisement' => $advertisement, 'wishlistedIds' => $wishlistedIds])
            @endforeach
        </div>
        <div class="flex justify-end mt-10">
          <a href="{{ url('/search?q=Luxury') }}">
              <button class="bg-[#536860] text-white px-6 py-2 rounded hover:bg-blue-600 transition">More</button>
          </a>
        </div>

        <h6 id="modern-section" class="text-4xl mt-20 mb-8">Modern Houses</h6>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($advertisementsModern->take(4) as $advertisement)
                @include('components.advertisement-card', ['advertisement' => $advertisement, 'wishlistedIds' => $wishlistedIds])
            @endforeach
        </div>
        <div class="flex justify-end mt-10">
          <a href="{{ url('/search?q=Modern') }}">
              <button class="bg-[#536860] text-white px-6 py-2 rounded hover:bg-blue-600 transition">More</button>
          </a>
        </div>

        <h6 id="traditional-section" class="text-4xl mt-20 mb-8">Traditional Houses</h6>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($advertisementsTraditional->take(4) as $advertisement)
                @include('components.advertisement-card', ['advertisement' => $advertisement, 'wishlistedIds' => $wishlistedIds])
            @endforeach
        </div>
        <div class="flex justify-end mt-10">
          <a href="{{ url('/search?q=Traditional') }}">
              <button class="bg-[#536860] text-white px-6 py-2 rounded hover:bg-blue-600 transition">More</button>
          </a>
        </div>
    </div>
</main>
</body>
@include('components.footer')
</html>

<script>
    const API_TOKEN = "{{ session('api_token') }}";

    document.querySelectorAll('.wishlist-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            fetch('/api/wishlist', {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'Authorization': 'Bearer ' + API_TOKEN,
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if(res.ok) {
                    // Find the toast div next to this form
                    const toast = form.parentElement.querySelector('[id^="wishlist-toast"]');
                    if (toast) {
                        toast.style.display = 'block';
                        setTimeout(() => { toast.style.display = 'none'; }, 2000);
                    }
                }
            });
        });
    });

    function scrollToSection(type) {
        let sectionId = '';
        if(type === 'luxury') sectionId = 'luxury-section';
        else if(type === 'modern') sectionId = 'modern-section';
        else if(type === 'traditional') sectionId = 'traditional-section';
        else sectionId = 'latest-section';

        const section = document.getElementById(sectionId);
        if(section) {
            section.scrollIntoView({ behavior: 'smooth' });
        }
    }
    function handleGetStarted() {
        if (!window.isAuthenticated) {
            window.location.href = window.loginUrl;
        } else {
            scrollToSection('latest');
        }
    }

    window.isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
    window.loginUrl = "{{ route('login') }}";
</script>