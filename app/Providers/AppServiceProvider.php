<?php

namespace CQRS\Providers;

use CQRS\Events\UserCreatedCommand;
use CQRS\Events\UserCreatedEvent;
use CQRS\Events\UserUpdatedCommand;
use CQRS\Events\UserUpdateEvent;
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
        $this->app->bind(UserCreatedCommand::SHORT_NAME, UserCreatedCommand::class);

        $this->app->bind(UserUpdatedCommand::SHORT_NAME, UserUpdatedCommand::class);

        $this->app->bind(UserCreatedEvent::SHORT_NAME, UserCreatedEvent::class);

        $this->app->bind(UserUpdateEvent::SHORT_NAME, UserUpdateEvent::class);
    }
}
