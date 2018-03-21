<?php

namespace CQRS\Events;

use Ramsey\Uuid\UuidInterface;

/**
 * Interface EventContract
 * @package CQRS\Events
 */
interface EventContract
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