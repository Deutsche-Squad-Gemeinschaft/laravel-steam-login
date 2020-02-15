<?php

namespace skyraptor\LaravelSteamLogin;

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
        if (strpos(get_class($this->app), 'Lumen') === false) {
            $this->publishLaravelConfig();
        } else {
            $this->publishLumenConfig();
        }
    }

    protected function publishLaravelConfig()
    {
        $this->publishes([
            __DIR__.'/Config/steam-login.php' => config_path('steam-login.php'),
        ]);
    }

    protected function publishLumenConfig()
    {
        if (!file_exists(config_path('steam-login.php'))) {
            if (!file_exists(config_path())) {
                mkdir(config_path());
            }

            copy(__DIR__.'/Config/steam-login.php', config_path('steam-login.php'));
        }
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SteamLogin::class, function ($app) {
            return new SteamLogin($app);
        });
    }
}
