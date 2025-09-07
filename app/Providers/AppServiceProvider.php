<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use APP\Services\GroqAnomalyDetector;
use APP\Services\GroqGeneralService;
use App\Services\GroqReadmissionPredictor;
use App\Services\ShiftScheduler;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
      public function register()
    {
        $this->app->singleton(GroqAnomalyDetector::class, function ($app) {
            return new GroqAnomalyDetector();
        });
        $this->app->singleton(GroqGeneralService::class, function ($app) {
        return new GroqGeneralService();
    });
    $this->app->singleton(ShiftScheduler::class, function ($app) {
            return new ShiftScheduler();

             $this->app->singleton(GroqReadmissionPredictor::class, function ($app) {
            return new GroqReadmissionPredictor();
        });
        });

        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
