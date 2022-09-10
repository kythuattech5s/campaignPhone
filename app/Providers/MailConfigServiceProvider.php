<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use vanhenry\helpers\helpers\SettingHelper;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        SettingHelper::setConfigEmail();
    }
}
