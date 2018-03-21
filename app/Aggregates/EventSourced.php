<?php

namespace CQRS\Aggregates;

use CQRS\Events\EventContract;
use CQRS\Events\EventFactory;
use CQRS\EventStores\EventStore;
use Exception;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

trait EventSourced
{
    /**
     * This will replay the events on the aggregate.
     * @param AggregateRoot $context
     * @param Collection $events
     * @param EventFactory $factory
     */
    protected function replay(AggregateRoot $context, Collection $events, EventFactory $factory)
    {
        $events->each(function(EventStore $store) use ($factory, $context) {

            $event = $factory->make(
                $store->name,
                Uuid::fromString($store->aggregate_id),
                [
                    'payload' => $store->data
                ]
            );

            $method = self::getMethodName($store->name);

            $this->apply($context, $event, $method);
        });
    }

    /**
     * @param AggregateRoot $context
     * @param EventContract $event
     * @param string $method
     * @throws Exception
     */
    protected function apply(AggregateRoot $context, EventContract $event, string $method)
    {
        if(!method_exists($context, $method)) {
            throw new Exception("Method not found.");
        }

        $context->$method($event);
    }

    /**
     * @param string $class
     * @return string
     */
    protected static function getMethodName(string $class)
    {
        $parts = explode('\\', $class);

        $name = array_pop($parts);

        $method = "on{$name}";

        return $method;
    }
}