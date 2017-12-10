<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/9/17
 * Time: 10:14 PM
 */

namespace CQRS\EventStores;


use Jenssegers\Mongodb\Eloquent\Model;

class EventStore extends Model
{
    protected $connection = "mongodb";
}