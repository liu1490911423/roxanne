<?php
/**
 * Created by PhpStorm.
 * User: lishu
 * Date: 2018/7/17
 * Time: 23:40
 */

namespace Roxanne\LaravelBase;

use Illuminate\Support\ServiceProvider;
use Roxanne\LaravelBase\Common\Exception\ExceptionFactory;

class LaravelBaseProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/laravel_base.php' => config_path('laravel_base.php'),
        ]);

    }

    public function register()
    {
        $this->app->singleton("ExceptionFactory", function ($app) {
            return new ExceptionFactory();
        });
    }
}
