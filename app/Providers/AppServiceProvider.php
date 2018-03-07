<?php

namespace CQRS\Providers;

use CQRS\Commands\CreateUser;
use CQRS\Events\UserCreated;
use CQRS\Commands\UpdateUserPassword;
use CQRS\Events\UserPasswordUpdated;
use Illuminate\Support\ServiceProvider;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

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
        $this->app->bind(UuidInterface::class, function() {
            return Uuid::uuid4();
        });
    }
}
