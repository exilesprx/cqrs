<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/9/17
 * Time: 8:54 PM
 */

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