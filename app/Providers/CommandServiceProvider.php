<?php

namespace CQRS\Providers;

use CQRS\Commands\CreateUser;
use CQRS\Commands\UpdateUserPassword;
use CQRS\Listeners\CreateUserListener;
use CQRS\Listeners\UpdateUserPasswordListener;
use Illuminate\Support\ServiceProvider;

/**
 * Class CommandServiceProvider
 * @package CQRS\Providers
 */
class CommandServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $listen = [
        CreateUser::class => [
            CreateUserListener::class
        ],
        UpdateUserPassword::class => [
            UpdateUserPasswordListener::class
        ]
    ];
}