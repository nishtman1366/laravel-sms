<?php

namespace Nishtman\Sms\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([
            __DIR__ . '/../Config' => base_path('config'),
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
//        App::bind('Sms', function () {
//            return new \Nishtman\Sms\Sms();
//        });
    }
}
