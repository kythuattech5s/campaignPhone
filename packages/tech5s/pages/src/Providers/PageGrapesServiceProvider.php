<?php

namespace Tech5s\PageGrapes\Providers;

use Event;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class PageGrapesServiceProvider extends ServiceProvider
{
    const CONFIG_KEY_START = 'pgc_';
    const CONFIG_ADMIN_KEY = 'sys_';

    public function boot()
    {
        $this->initConfigFile();
        $this->loadViewsFrom(__DIR__ . '/../../views', config(static::CONFIG_KEY_START . 'setting.soure'));
        $this->initProviderAlias();
        $this->pushConfig();
        $this->initRouters();
    }

    private function initProviderAlias()
    {
        $config = include __DIR__ . "/../../config/app.php";
        foreach ($config['providers'] as $key => $value) {
            $this->app->register($value);
        }

        foreach ($config['aliases'] as $key => $value) {
            AliasLoader::getInstance()->alias($key, $value);
        }

        foreach ($config['listeners'] as $listener) {
            Event::subscribe($listener);
        }
    }

    private function initConfigFile()
    {
        $arr = glob(__DIR__ . "/../../config/*.php");
        foreach ($arr as $value) {
            $nameConfig = pathinfo($value)['filename'];
            if ($nameConfig !== 'app') {
                \Config::set(static::CONFIG_KEY_START . $nameConfig, include_once $value);
            }
        }
    }

    private function pushConfig()
    {
        $arrayConfigAdminNeedEdit = ['action'];
        foreach ($arrayConfigAdminNeedEdit as $config_table) {
            $this->mergeConfigFrom(__DIR__ . "/../../config/$config_table.php", static::CONFIG_ADMIN_KEY . $config_table);
        }
    }

    public function initRouters()
    {
        $routes = glob(__DIR__ . "/../../routes/*.php");
        $this->routes(function () use ($routes) {
            foreach ($routes as $route) {
                $name = pathinfo($route)['filename'];
                if ($name == 'web') {
                    $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
                } else {
                    Route::prefix(config(static::CONFIG_KEY_START . 'setting.route') . "/$name")->middleware('web')
                        ->namespace(config(static::CONFIG_KEY_START . 'setting.controller'))
                        ->group($route);
                }
            }
        });
    }
}
