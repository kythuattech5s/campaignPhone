<?php
namespace CustomTable\Providers;

use Illuminate\Support\ServiceProvider;

class CustomTableProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->initConfigFile();
    }
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/route.php');
    }

    private function initConfigFile()
    {
        $configs = glob(__DIR__ . "/../../config/*.php");
        foreach ($configs as $path) {
            $nameConfig = pathinfo($path)['filename'];
            if ($nameConfig !== 'app') {
                $this->mergeConfigFrom($path, 'sys_' . $nameConfig);
            }
        }
    }
}
