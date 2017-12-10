<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/9/17
 * Time: 8:54 PM
 */

namespace CQRS\EventStores;


use Illuminate\Database\Eloquent\Model;

/**
 * Class UserStore
 * @package CQRS\EventStores
 */
class UserStore extends Model
{
    /**
     * @var string
     */
    protected $table = "user_events";

    protected $fillable = ["aggregate_version", "data"];
}