<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This method is called when the application boots.
     */
    public function boot(): void
    {
        // Load API routes with /api prefix and 'api' middleware (no CSRF)
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));

        // Load web routes with 'web' middleware (has CSRF)
        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }
}
