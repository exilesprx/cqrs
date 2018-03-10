<?php

namespace CQRS\Listeners;

use CQRS\Aggregates\User as Aggregate;
use CQRS\Broadcasts\BroadcastUserPasswordUpdated;
use CQRS\Commands\UpdateUserPassword;
use CQRS\Events\EventFactory;
use CQRS\Events\UserPasswordUpdated;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Log;

/**
 * Class UpdateUserPasswordListener
 * @package CQRS\Listeners
 */
class UpdateUserPasswordListener implements ShouldQueue
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
     * UserCommandSubscriber constructor.
     * @param EventFactory $factory
     * @param Dispatcher $dispatcher
     * @param Aggregate $user
     */
    public function __construct(EventFactory $factory, Dispatcher $dispatcher, Aggregate $user)
    {
        $this->factory = $factory;

        $this->dispatcher = $dispatcher;

        $this->aggregate = $user;
    }

    /**
     * @param UpdateUserPassword $command
     */
    public function handle(UpdateUserPassword $command)
    {
        // TODO: Basic validation here

        try {
            // Attempt to save
            $user = $this->aggregate->updatePassword($command->getId(), $command->getPassword());

            // If saving of state was successful, fire off event
            $event = $this->factory->make(
                UserPasswordUpdated::class,
                $command->getId(),
                $command->toArray()
            );

            $this->dispatcher->dispatch($event);

            $this->dispatcher->dispatch(new BroadcastUserPasswordUpdated($user));
        }
        catch(Exception $exception) {
//            Log::info($exception->getMessage());
        }
    }
}