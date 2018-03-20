<?php


namespace CQRS\Aggregates;


use CQRS\Events\IEvent;
use CQRS\EventStores\EventStore;
use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

trait EventSourced
{
    /**
     * This will replay the events on the aggregate.
     * @param AggregateRoot $context
     * @param Collection $events
     * @param Container $container
     * @param int $version
     */
    protected static function replay(AggregateRoot $context, Collection $events, Container $container, int $version)
    {
        $events->each(function(EventStore $event) use($container, $context) {

            // $version = $event->version;

            $event = $container->makeWith(
                $event->name,
                [
                    'aggregateId' => Uuid::fromString($event->aggregate_id),
                    'payload' => $event->data
                ]
            );

            self::apply($context, $event);
        });
    }

    /**
     * @param AggregateRoot $context
     * @param IEvent $event
     * @throws \Exception
     */
    protected static function apply(AggregateRoot $context, IEvent $event)
    {
        $parts = explode('\\', get_class($event));

        $name = array_pop($parts);

        $method = "on{$name}";

        if(!method_exists($context, $method)) {
            throw new \Exception();
        }

        $context->$method($event);
    }
}