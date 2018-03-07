<?php

namespace CQRS\EventStores;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class EventStore
 * @package CQRS\EventStores
 */
class EventStore extends Model
{
    /**
     * @var string
     */
    protected $connection = "mongodb";
}