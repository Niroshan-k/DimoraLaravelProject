<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<main>
@vite(['resources/css/home.css'])
@include('layouts.header')
<header id="home">
  <div class="flex bg-[#0000005d] h-screen justify-between p-10">
    <div class="flex flex-col justify-end">
      <p class="uppercase text-white font-bold tracking-widest">discover your</p>
      <h6 class="text-white text-[100px]">Perfect Home</h6>
      <div>
        <button class="border-4 text-white border-white hover:text-black hover:bg-white py-3 px-5 font-bold mt-5">
          Get Started
        </button>
      </div>
    </div>
    <div class="flex mt-[100px] gap-5 justify-end">
      @php
        $homes = [
          ['image' => '1.jpg', 'type' => 'Luxury House', 'desc' => 'Experience elegance and comfort.'],
          ['image' => '2.jpg', 'type' => 'Traditional House', 'desc' => 'Classic charm meets cozy living.'],
          ['image' => '3.jpg', 'type' => 'Modern House', 'desc' => 'Sleek designs for modern life.'],
        ];
      @endphp

      @foreach ($homes as $home)
      <div class="relative w-[300px] h-full group overflow-hidden   border-2">
        <img src="{{ asset('storage/appImages/' . $home['image']) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110 z-0" alt="">

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4 text-white">
          <h6 class="text-xl">{{ $home['type'] }}</h6>
          <p class="text-sm">{{ $home['desc'] }}</p>
          <button class="mt-2 self-start bg-white text-black px-4 py-1 text-sm font-semibold hover:bg-gray-200 transition">
            View Listings
          </button>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  
</header>
 <div class="container mx-auto py-8">
 <!-- <pre>{{ print_r($advertisementsModern->toArray(), true) }}</pre> -->
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <h6 class="text-4xl mt-10 mb-8">Latest</h6>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($advertisementsLatest as $advertisement)
                @include('components.advertisement-card', ['advertisement' => $advertisement])
            @endforeach
        </div>

        <h6 class="text-4xl mt-20 mb-8">Luxury Houses</h6>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($advertisementsLuxury as $advertisement)
                @include('components.advertisement-card', ['advertisement' => $advertisement])
            @endforeach
        </div>

        <h6 class="text-4xl mt-20 mb-8">Modern Houses</h6>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($advertisementsModern as $advertisement)
                @include('components.advertisement-card', ['advertisement' => $advertisement])
            @endforeach
        </div>

        <h6 class="text-4xl mt-20 mb-8">Traditional Houses</h6>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($advertisementsTraditional as $advertisement)
                @include('components.advertisement-card', ['advertisement' => $advertisement])
            @endforeach
        </div>
    </div>
</main>
</body>
@include('components.footer')
</html>