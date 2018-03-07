<?php

namespace CQRS\Listeners;

use CQRS\Aggregates\User as Aggregate;
use CQRS\Broadcasts\BroadcastUserCreated;
use CQRS\Commands\CreateUser;
use CQRS\Events\EventFactory;
use CQRS\Events\UserCreated;
use Exception;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Ramsey\Uuid\UuidInterface;

/**
 * Class UserCreatedListener
 * @package CQRS\Listeners
 */
class UserCreatedListener implements ShouldQueue
{
    public $queue = "commands";

    /**
     * @var EventFactory
     */
    private $factory;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * @var Aggregate
     */
    private $aggregate;

    /**
     * @var UuidInterface
     */
    private $uuid;

    /**
     * UserCommandSubscriber constructor.
     * @param EventFactory $factory
     * @param Dispatcher $dispatcher
     * @param UuidInterface $uuid
     * @param Aggregate $user
     */
    public function __construct(EventFactory $factory, Dispatcher $dispatcher, UuidInterface $uuid, Aggregate $user)
    {
        $this->factory = $factory;

        $this->dispatcher = $dispatcher;

        $this->aggregate = $user;

        $this->uuid = $uuid;
    }

    /**
     * @param CreateUser $command
     */
    public function handle(CreateUser $command)
    {
        // TODO: Basic validation here

        try {
            // Attempt to save
            $user = $this->aggregate->create(
                $this->uuid,
                $command->getName(),
                $command->getEmail(),
                $command->getPassword()
            );

            // If saving of state was successful, fire off event
            $event = $this->factory->make(
                UserCreated::class,
                $this->uuid,
                $command->toArray()
            );

            $this->dispatcher->dispatch($event);

            $this->dispatcher->dispatch(new BroadcastUserCreated($user));
        }
        catch(Exception $exception)
        {
            // Log maybe?
        }
    }
}