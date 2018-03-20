<?php


namespace CQRS\Aggregates;


use Illuminate\Support\Collection;
use Ramsey\Uuid\UuidInterface;

/**
 * Interface EventSourceContract
 * @package CQRS\Aggregates
 */
interface EventSourceContract
{
    /**
     * @param UuidInterface $uuid
     * @param Collection $events
     * @return mixed
     */
    public function replayEvents(UuidInterface $uuid, Collection $events);
}