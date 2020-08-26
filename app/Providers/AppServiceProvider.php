<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
                'vendor/igorescobar/jquery-mask-plugin' => public_path('vendor/jquery-mask-plugin'),
            ], 'public');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
                $this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
            }
    }
}
