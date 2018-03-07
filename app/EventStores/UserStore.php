<?php
namespace CQRS\EventStores;


/**
 * Class UserStore
 * @package CQRS\EventStores
 */
class UserStore extends EventStore
{
    /**
     * @var string
     */
    protected $table = "user_events";

    /**
     * @var array
     */
    protected $fillable = ["name", "aggregate_id", "data"];
}