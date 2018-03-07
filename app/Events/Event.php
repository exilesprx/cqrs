<?php

namespace CQRS\Events;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class Event
 * @package CQRS\Events
 */
abstract class Event implements IEvent, ShouldQueue
{
    use SerializesModels, Queueable, InteractsWithQueue;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
}
