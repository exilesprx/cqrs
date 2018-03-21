<?php

namespace CQRS\Providers;

use CQRS\Listeners\UserEventSubscriber;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class EventServiceProvider
 * @package CQRS\Providers
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $subscribe = [
        UserEventSubscriber::class
    ];
}
