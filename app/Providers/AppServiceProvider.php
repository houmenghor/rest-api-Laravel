<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        Schema::defaultStringLength(191);

        // Register the macro to merge input and files
        Request::macro('all', function () {
            $request = Request::instance();  // Get the current request instance

            // Merge input and files
            $input = $request->input();
            $files = $request->allFiles();

            return array_merge($input, $files); // Combine the arrays
        });
        
    }
}
