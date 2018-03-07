<?php

namespace CQRS\Aggregates;

use Ramsey\Uuid\UuidInterface;

/**
 * Trait Replayable
 * @package CQRS\Aggregates
 */
trait Replayable
{
    /**
     * @param UuidInterface $uuid
     * @param iterable $payload
     * @throws \Exception
     */
    protected function replayEvents(UuidInterface $uuid, iterable $payload)
    {
        $this->replay($uuid);

        $this->apply(
            $uuid,
            $payload
        );
    }
}