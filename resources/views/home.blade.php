<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Dimora</title>
</head>
<main>
 @vite(['resources/css/home.css'])
 @include('layouts.header')
 <header>
  <div id="home" class="flex flex-col items-center justify-center ">
   <p class="uppercase text-white font-bold tracking-widest">m a k e . y o u r . o w n</p>
   <h6 class="text-white text-6xl">Real Estate</h6>
   <button class="bg-white py-3 px-5 rounded-full font-bold mt-5">
    Find Your Home
   </button>
  </div>
  
 </header>
 <body>
  <div class="pt-10 p-5">
   <h6 class="text-5xl">Latest Listings</h6>

  </div>
 </body>
 
 
</main>
</html>