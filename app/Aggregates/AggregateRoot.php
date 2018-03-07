<?php

namespace CQRS\Aggregates;

use CQRS\EventStores\UserStore;
use CQRS\Repositories\Events\UserRepository as EventRepo;
use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class AggregateRoot
{
    use Replayable;

    /**
     * @var UuidInterface
     */
    protected $aggregateId;

    /**
     * @var EventRepo
     */
    protected $eventRepo;

    /**
     * AggregateRoot constructor.
     * @param EventRepo $eventRepo
     */
    public function __construct(EventRepo $eventRepo)
    {
        $this->eventRepo = $eventRepo;
    }

    /**
     * @param UuidInterface $uuid
     * @param iterable $payload
     * @return mixed
     */
    abstract protected function apply(UuidInterface $uuid, iterable $payload);

    /**
     * This will replay the events on the aggregate.
     * @param UuidInterface $uuid
     * @throws Exception
     */
    protected function replay(UuidInterface $uuid)
    {
        $events = $this->eventRepo->find($uuid);

        $events->each(function(UserStore $event) use ($uuid)
        {
            if ($event->aggregate_id != $uuid)
            {
                return;
            }

            $this->apply(
                Uuid::fromString($event->aggregate_id),
                $event->data
            );
        });
    }

    /**
     * @param UuidInterface $uuid
     */
    protected function restore(UuidInterface $uuid)
    {
//      $this->factory->make($event->name, $this->aggregateId, $event->data);
//
//      $this->dispatcher->dispatch($event);
    }

    /**
     * @return mixed
     */
    protected function getAggregateId()
    {
        return $this->aggregateId;
    }
}