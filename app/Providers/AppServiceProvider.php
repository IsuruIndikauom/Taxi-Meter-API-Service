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
        $this->app->bind( CalculateDistance::class, ManualDistanceCalculator::class );
        $this->app->bind( CalculateSpeed::class, ManualSpeedCalculator::class );
        $this->app->bind( CalculateWatingTime::class, ManualTimeCalculator::class );
        $this->app->bind( CalculateDistanceTarrif::class, ManualCalculateDistanceTarrif::class );
        $this->app->bind( CalculateTotalTarrif::class, ManualCalculateTotalTarrif::class );
    }

    /**
    * Bootstrap any application services.
    */

    public function boot(): void {
        //
    }
}
