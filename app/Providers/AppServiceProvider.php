<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\CreateOTP;
use App\Services\DummyOTPService;

class AppServiceProvider extends ServiceProvider {
    /**
    * Register any application services.
    */

    public function register(): void {
        $this->app->bind( CreateOTP::class, DummyOTPService::class );
    }

    /**
    * Bootstrap any application services.
    */

    public function boot(): void {
        //
    }
}
