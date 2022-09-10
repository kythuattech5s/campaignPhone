<?php

namespace Tech5s\LoginSocial\Providers;

use Illuminate\Support\ServiceProvider;

class LoginSocialProvider extends ServiceProvider
{
    const VIEW_LINK = 'TLS';
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViewsFrom(__DIR__ . '/../../views', self::VIEW_LINK);
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/services.php', 'services');
    }
}
