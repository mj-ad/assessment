<?php

namespace App\Providers;

use Illuminate\Support\Facades\Request;
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
        Request::macro('contentSize', function () {
            return strlen($this->getContent());
        });
    
        \Log::info('Request Size: ' . request()->contentSize() . ' bytes');
    }
}
