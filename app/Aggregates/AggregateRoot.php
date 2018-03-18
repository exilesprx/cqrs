<?php

namespace CQRS\Aggregates;

use CQRS\Events\IEvent;
use CQRS\EventStores\EventStore;
use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Ramsey\Uuid\UuidInterface;

abstract class AggregateRoot
{
    protected $aggregateId;

    protected $version;

    protected $container;

    protected function __construct(Container $container)
    {
        $this->version = 1;

        $this->container = $container;
    }

    public abstract function replayEvents(UuidInterface $uuid, Collection $events);

    public abstract function getAggregateId();

    /**
     * This will replay the events on the aggregate.
     * @param Collection $events
     */
    protected function replay(Collection $events)
    {
        $events->each(function(EventStore $event) {
            $this->version++;

            $event = $this->container->makeWith(
                $event->name,
                [
                    'uuid' => $event->aggregate_id,
                    'payload' => $event->data
                ]
            );

            $this->apply($event);
        });
    }

    /**
     * @param IEvent $event
     * @throws \Exception
     */
    protected function apply(IEvent $event)
    {
        $name = array_pop(explode('\\', get_class($event)));

        $method = "on{$name}";

        if(!method_exists($this, $method)) {
            throw new \Exception();
        }

        $this->$method($event);
    }
}