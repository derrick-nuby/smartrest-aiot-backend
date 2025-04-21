<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
<<<<<<< HEAD
=======
use Illuminate\Support\Facades\Route;
>>>>>>> f945b34 (Initial commit with Docker support)

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
<<<<<<< HEAD
        //
=======
        // Load API routes
        Route::prefix('api')//'api'
            ->middleware('api')//'api'
            ->namespace('App\Http\Controllers\Api')
            ->group(base_path('routes/api.php'));

        // Load Web routes
        Route::middleware('web')
            ->namespace('App\Http\Controllers')
            ->group(base_path('routes/web.php'));
>>>>>>> f945b34 (Initial commit with Docker support)
    }
}
