<?php

namespace CQRS\Events;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Class Event
 * @package CQRS\Events
 */
abstract class Event implements IEvent, ShouldQueue
{
    use Dispatchable, SerializesModels, Queueable, InteractsWithQueue;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @return string
     */
    public function getShortName() : string
    {
        return static::SHORT_NAME;
    }
}
