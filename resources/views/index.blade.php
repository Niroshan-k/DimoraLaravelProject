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
 <body>
 <div class="container mx-auto py-8">
 <!-- <pre>{{ print_r($advertisementsModern->toArray(), true) }}</pre> -->
        <h6 class="text-4xl mt-10 mb-8">Latest</h6>
        <!-- Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($advertisementsLatest as $advertisement)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Advertisement Image -->
                    @if (!empty($advertisement['images']) && count($advertisement['images']) > 0)
                        <img 
                            src="{{ asset('storage/appImages/1.jpg') }}" 
                            alt="Advertisement Image" 
                            class="w-full h-48 object-cover">
                    @else
                        <img 
                            src="{{ asset('storage/appImages/1.jpg') }}" 
                            alt="Default Image" 
                            class="w-full h-48 object-cover">
                    @endif

                    <!-- Advertisement Details -->
                    <div class="p-4">
                        <h1 class="text-xl uppercase font-bold">{{ $advertisement['title'] }}</h1>
                        <!-- <p class="text-gray-600 text-sm mt-2">
                            {{ $advertisement['description'] }}
                        </p> -->
                        

                        <!-- Property and House Details -->
                        @if (!empty($advertisement['property']))
                            <div class="mt-4">
                                <p class="text-gray-700 text-sm"><strong>Location:</strong> {{ $advertisement['property']['location'] }}</p>
                                <p class="text-gray-700 text-sm"><strong>Price:</strong> ${{ number_format($advertisement['property']['price'], 2) }}</p>

                                @if (!empty($advertisement['property']['house']))
                                    <div class="mt-4">
                                        <!-- <p class="text-gray-700 text-sm"><strong>Bedrooms:</strong> {{ $advertisement['property']['house']['bedroom'] }}</p>
                                        <p class="text-gray-700 text-sm"><strong>Bathrooms:</strong> {{ $advertisement['property']['house']['bathroom'] }}</p>
                                        <p class="text-gray-700 text-sm"><strong>Parking:</strong> {{ $advertisement['property']['house']['parking'] ? 'Yes' : 'No' }}</p>
                                        <p class="text-gray-700 text-sm"><strong>Pool:</strong> {{ $advertisement['property']['house']['pool'] ? 'Yes' : 'No' }}</p> -->
                                        <p class="text-gray-700 text-sm"><strong>Type:</strong> {{ $advertisement['property']['house']['house_type'] }}</p>
                                        <p class="text-gray-700 text-sm"><strong>Area:</strong> {{ $advertisement['property']['house']['area'] }} sq ft</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <p>No property details available.</p>
                        @endif

                        <!-- Call to Action -->
                        <div class="mt-4">
                            <a href="{{ route('advertisement.show', $advertisement['id']) }}" class="bg-[#523D35] text-white py-2 px-4 rounded hover:bg-[#886658]">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <h6 class="text-4xl mt-20 mb-8">Recommend</h6>

        <h6 class="text-4xl mt-20 mb-8">Luxury Houses</h6>
        <!-- Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($advertisementsLuxury as $advertisement)
                <div class="bg-white rounded shadow-md hover:shadow-lg overflow-hidden">
                    <!-- Advertisement Image -->
                    @if (!empty($advertisement['images']) && count($advertisement['images']) > 0)
                        <img 
                            src="{{ asset('storage/appImages/1.jpg') }}" 
                            alt="Advertisement Image" 
                            class="w-full h-48 object-cover">
                    @else
                        <img 
                            src="{{ asset('storage/appImages/1.jpg') }}" 
                            alt="Default Image" 
                            class="w-full h-48 object-cover">
                    @endif

                    <!-- Advertisement Details -->
                    <div class="p-4">
                        <h1 class="text-xl uppercase font-bold">{{ $advertisement['title'] }}</h1>
                        <!-- <p class="text-gray-600 text-sm mt-2">
                            {{ $advertisement['description'] }}
                        </p> -->

                        <!-- <pre>{{ print_r($advertisementsLuxury->toArray(), true) }}</pre> -->
                        

                        <!-- Property and House Details -->
                        @if (!empty($advertisement['property']))
                            <div class="mt-4">
                                <p class="text-gray-700 text-sm w-max truncate"><strong>Location:</strong> {{ $advertisement['property']['location'] }}</p>
                                <p class="text-gray-700 text-sm"><strong>Price:</strong> ${{ number_format($advertisement['property']['price'], 2) }}</p>

                                @if (!empty($advertisement['property']['house']))
                                    <div class="mt-4">
                                        <!-- <p class="text-gray-700 text-sm"><strong>Bedrooms:</strong> {{ $advertisement['property']['house']['bedroom'] }}</p>
                                        <p class="text-gray-700 text-sm"><strong>Bathrooms:</strong> {{ $advertisement['property']['house']['bathroom'] }}</p>
                                        <p class="text-gray-700 text-sm"><strong>Parking:</strong> {{ $advertisement['property']['house']['parking'] ? 'Yes' : 'No' }}</p>
                                        <p class="text-gray-700 text-sm"><strong>Pool:</strong> {{ $advertisement['property']['house']['pool'] ? 'Yes' : 'No' }}</p> -->
                                        <p class="text-gray-700 text-sm"><strong>Type:</strong> {{ $advertisement['property']['house']['house_type'] }}</p>
                                        <p class="text-gray-700 text-sm"><strong>Area:</strong> {{ $advertisement['property']['house']['area'] }} sq ft</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <p>No property details available.</p>
                        @endif

                        <!-- Call to Action -->
                        <div class="mt-4">
                            <a href="{{ route('advertisement.show', $advertisement['id']) }}" class="bg-[#523D35] text-white py-2 px-4 rounded hover:bg-[#886658]">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <h6 class="text-4xl mt-20 mb-8">Modern Houses</h6>
        <!-- Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($advertisementsModern as $advertisement)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Advertisement Image -->
                    @if (!empty($advertisement['images']) && count($advertisement['images']) > 0)
                        <img 
                            src="{{ asset('storage/appImages/1.jpg') }}" 
                            alt="Advertisement Image" 
                            class="w-full h-48 object-cover">
                    @else
                        <img 
                            src="{{ asset('storage/appImages/1.jpg') }}" 
                            alt="Default Image" 
                            class="w-full h-48 object-cover">
                    @endif

                    <!-- Advertisement Details -->
                    <div class="p-4">
                        <h1 class="text-xl uppercase font-bold">{{ $advertisement['title'] }}</h1>
                        <!-- <p class="text-gray-600 text-sm mt-2">
                            {{ $advertisement['description'] }}
                        </p> -->
                        

                        <!-- Property and House Details -->
                        @if (!empty($advertisement['property']))
                            <div class="mt-4">
                                <p class="text-gray-700 text-sm"><strong>Location:</strong> {{ $advertisement['property']['location'] }}</p>
                                <p class="text-gray-700 text-sm"><strong>Price:</strong> ${{ number_format($advertisement['property']['price'], 2) }}</p>

                                @if (!empty($advertisement['property']['house']))
                                    <div class="mt-4">
                                        <!-- <p class="text-gray-700 text-sm"><strong>Bedrooms:</strong> {{ $advertisement['property']['house']['bedroom'] }}</p>
                                        <p class="text-gray-700 text-sm"><strong>Bathrooms:</strong> {{ $advertisement['property']['house']['bathroom'] }}</p>
                                        <p class="text-gray-700 text-sm"><strong>Parking:</strong> {{ $advertisement['property']['house']['parking'] ? 'Yes' : 'No' }}</p>
                                        <p class="text-gray-700 text-sm"><strong>Pool:</strong> {{ $advertisement['property']['house']['pool'] ? 'Yes' : 'No' }}</p> -->
                                        <p class="text-gray-700 text-sm"><strong>Type:</strong> {{ $advertisement['property']['house']['house_type'] }}</p>
                                        <p class="text-gray-700 text-sm"><strong>Area:</strong> {{ $advertisement['property']['house']['area'] }} sq ft</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <p>No property details available.</p>
                        @endif

                        <!-- Call to Action -->
                        <div class="mt-4">
                            <a href="{{ route('advertisement.show', $advertisement['id']) }}" class="bg-[#523D35] text-white py-2 px-4 rounded hover:bg-[#886658]">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


        <h6 class="text-4xl mt-20 mb-8">Traditional Houses</h6>
        <!-- Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($advertisementsModern as $advertisement)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Advertisement Image -->
                    @if (!empty($advertisement['images']) && count($advertisement['images']) > 0)
                        <img 
                            src="{{ asset('storage/appImages/1.jpg') }}" 
                            alt="Advertisement Image" 
                            class="w-full h-48 object-cover">
                    @else
                        <img 
                            src="{{ asset('storage/appImages/1.jpg') }}" 
                            alt="Default Image" 
                            class="w-full h-48 object-cover">
                    @endif

                    <!-- Advertisement Details -->
                    <div class="p-4">
                        <h1 class="text-xl uppercase font-bold">{{ $advertisement['title'] }}</h1>
                        <!-- <p class="text-gray-600 text-sm mt-2">
                            {{ $advertisement['description'] }}
                        </p> -->
                        

                        <!-- Property and House Details -->
                        @if (!empty($advertisement['property']))
                            <div class="mt-4">
                                <p class="text-gray-700 text-sm"><strong>Location:</strong> {{ $advertisement['property']['location'] }}</p>
                                <p class="text-gray-700 text-sm"><strong>Price:</strong> ${{ number_format($advertisement['property']['price'], 2) }}</p>

                                @if (!empty($advertisement['property']['house']))
                                    <div class="mt-4">
                                        <!-- <p class="text-gray-700 text-sm"><strong>Bedrooms:</strong> {{ $advertisement['property']['house']['bedroom'] }}</p>
                                        <p class="text-gray-700 text-sm"><strong>Bathrooms:</strong> {{ $advertisement['property']['house']['bathroom'] }}</p>
                                        <p class="text-gray-700 text-sm"><strong>Parking:</strong> {{ $advertisement['property']['house']['parking'] ? 'Yes' : 'No' }}</p>
                                        <p class="text-gray-700 text-sm"><strong>Pool:</strong> {{ $advertisement['property']['house']['pool'] ? 'Yes' : 'No' }}</p> -->
                                        <p class="text-gray-700 text-sm"><strong>Type:</strong> {{ $advertisement['property']['house']['house_type'] }}</p>
                                        <p class="text-gray-700 text-sm"><strong>Area:</strong> {{ $advertisement['property']['house']['area'] }} sq ft</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <p>No property details available.</p>
                        @endif

                        <!-- Call to Action -->
                        <div class="mt-4">
                            <a href="{{ route('advertisement.show', $advertisement['id']) }}" class="bg-[#523D35] text-white py-2 px-4 rounded hover:bg-[#886658]">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination
        <div class="mt-8">
            {{ $advertisementsLuxury->links() }}
        </div> -->
    </div>
 </body>
 
 
</main>
</html>