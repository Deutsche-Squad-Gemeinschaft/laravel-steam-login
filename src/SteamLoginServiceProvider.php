<?php

namespace kanalumaddela\LaravelSteamLogin;

use Illuminate\Support\ServiceProvider;

class SteamLoginServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__.'/../config/steam-login.php' => config_path('steam-login.php')]);
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('SteamLogin', function () {
            return new SteamLogin($this->app);
        });
    }
}
