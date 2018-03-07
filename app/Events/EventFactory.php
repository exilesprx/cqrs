<?php

namespace CQRS\Events;

use Illuminate\Container\Container;
use Ramsey\Uuid\UuidInterface;

/**
 * Class EventFactory
 * @package CQRS\Events
 */
class EventFactory
{
    /**
     * @var Container
     */
    private $container;

    /**
     * EventFactory constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $event
     * @param UuidInterface $uuid
     * @param iterable $payload
     * @return IEvent
     */
    public function make(string $event, UuidInterface $uuid, iterable $payload)
    {
        return $this->container->make($event, ["aggregateId" => $uuid, "payload" => $payload]);
    }

}