<?php

namespace CQRS\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'CQRS\Events\Event' => [
            'CQRS\Listeners\EventListener',
        ],
        'CQRS\Events\UserCreatedCommand' => [
            'CQRS\Listeners\UserCreateCommandListener'
        ],
        'CQRS\Events\UserCreatedEvent' => [
            'CQRS\Listeners\UserCreateEventListener'
        ]
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
