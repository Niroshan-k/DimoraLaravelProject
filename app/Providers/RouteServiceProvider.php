<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This is the path where your application routes are located.
     *
     * @var string
     */
    

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->routes(function () {
            \Log::info('Loading API routes'); // Debug log

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            \Log::info('Loading Web routes'); // Debug log

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}

?>