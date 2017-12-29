<?php

namespace CQRS\Providers;

use CQRS\Events\UserCreatedEvent;
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
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserCreatedEvent::SHORT_NAME, UserCreatedEvent::class);
    }
}
