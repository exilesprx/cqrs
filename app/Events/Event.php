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
abstract class Event implements EventContract, ShouldQueue
{
    use SerializesModels, Queueable, InteractsWithQueue;
}
