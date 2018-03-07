<?php

namespace CQRS\Providers;

use CQRS\Commands\CreateUser;
use CQRS\Commands\UpdateUserPassword;
use CQRS\Listeners\UpdateUserPasswordListener;
use CQRS\Listeners\UserCreatedListener;
use CQRS\Listeners\UserEventSubscriber;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        CreateUser::class => [
            UserCreatedListener::class
        ],
        UpdateUserPassword::class => [
            UpdateUserPasswordListener::class
        ]
    ];

    protected $subscribe = [
        UserEventSubscriber::class
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
