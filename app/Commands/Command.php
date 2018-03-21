<?php

namespace CQRS\Commands;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class Command
 * @package CQRS\Commands
 */
abstract class Command implements CommandContract, ShouldQueue
{
    use SerializesModels, Queueable, InteractsWithQueue;
}