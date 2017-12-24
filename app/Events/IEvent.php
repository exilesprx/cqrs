<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/9/17
 * Time: 8:23 PM
 */

namespace CQRS\Events;


/**
 * Interface IEvent
 * @package CQRS\Events
 */
interface IEvent
{
    /**
     * @return string
     */
    public function getShortName() : string;
}