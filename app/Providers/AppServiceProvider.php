<?php

namespace App\Providers;

use App\LPJ;
use App\Beasiswa;
use App\Observers\BeasiswaObserver;
use App\PermohonanLPJ;
use App\Observers\LPJObserver;
use App\Observers\PermohonanLPJObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Beasiswa::observe(BeasiswaObserver::class);
        PermohonanLPJ::observe(PermohonanLPJObserver::class);
        LPJ::observe(LPJObserver::class);
    }
}
