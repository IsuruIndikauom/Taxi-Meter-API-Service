<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\CreateOTP;
use App\Services\DummyOTPService;
use App\Contracts\CalculateDistance;
use App\Services\ManualDistanceCalculator;
use App\Contracts\CalculateSpeed;
use App\Services\ManualSpeedCalculator;
use App\Contracts\CalculateWaiting;
use App\Services\ManualWaitingCalculator;
use App\Contracts\CalculateDistanceTarrif;
use App\Services\ManualCalculateDistanceTarrif;
use App\Contracts\CalculateTotalTarrif;
use App\Services\ManualCalculateTotalTarrif;

class AppServiceProvider extends ServiceProvider {
    /**
    * Register any application services.
    */

    public function register(): void {
        $this->app->bind( CreateOTP::class, DummyOTPService::class );
        $this->app->bind( CalculateDistance::class, ManualDistanceCalculator::class );
        $this->app->bind( CalculateSpeed::class, ManualSpeedCalculator::class );
        $this->app->bind( CalculateWaiting::class, ManualWaitingCalculator::class );
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
