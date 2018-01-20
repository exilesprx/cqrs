<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/9/17
 * Time: 8:23 PM
 */

namespace CQRS\Events;

use Ramsey\Uuid\UuidInterface;


/**
 * Interface IEvent
 * @package CQRS\Events
 */
interface IEvent
{
    /**
     * @param UuidInterface $uuid
     * @param iterable $payload
     * @return mixed
     */
    public function handle(UuidInterface $uuid, iterable $payload);

    /**
     * @return string
     */
    public function getShortName() : string;
}