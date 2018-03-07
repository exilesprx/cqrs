<?php

namespace CQRS\Events;

use Ramsey\Uuid\UuidInterface;

/**
 * Interface IEvent
 * @package CQRS\Events
 */
interface IEvent
{
    /**
     * @return UuidInterface
     */
    public function getAggregateId();

    /**
     * @return array
     */
    public function toArray();
}