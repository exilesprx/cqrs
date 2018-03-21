<?php

namespace CQRS\Listeners;

use CQRS\Aggregates\User;
use CQRS\Broadcasts\BroadcastFactory;
use CQRS\Broadcasts\BroadcastUserError;
use CQRS\Broadcasts\BroadcastUserPasswordUpdated;
use CQRS\Commands\UpdateUserPassword;
use CQRS\Repositories\State\UserRepository as StateRepository;
use CQRS\Repositories\Events\UserRepository as EventRepository;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;

/**
 * Class UpdateUserPasswordListener
 * @package CQRS\Listeners
 */
class UpdateUserPasswordListener implements ShouldQueue
{
    /**
     * @var string
     */
    public $queue = "commands";

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * @var User
     */
    private $aggregate;

    /**
     * @var StateRepository
     */
    private $repo;

    /**
     * @var EventRepository
     */
    private $eventRepo;

    /**
     * @var BroadcastFactory
     */
    private $factory;

    /**
     * UserCommandSubscriber constructor.
     * @param Dispatcher $dispatcher
     * @param User $aggregate
     * @param StateRepository $repo
     * @param EventRepository $eventRepo
     * @param BroadcastFactory $factory
     */
    public function __construct(Dispatcher $dispatcher, User $aggregate, StateRepository $repo, EventRepository $eventRepo, BroadcastFactory $factory)
    {
        $this->dispatcher = $dispatcher;

        $this->aggregate = $aggregate;

        $this->repo = $repo;

        $this->eventRepo = $eventRepo;

        $this->factory = $factory;
    }

    /**
     * @param UpdateUserPassword $command
     */
    public function handle(UpdateUserPassword $command)
    {
        // TODO: Basic validation here

        try {
            $events = $this->eventRepo->find($command->getId());

            $this->aggregate->replayEvents($command->getId(), $events);

            $this->aggregate->updateUserPassword($command->getPassword());

            $this->repo->update(
                $this->aggregate->getAggregateId(),
                [
                    'password' => $command->getPassword()
                ]
            );

            $broadcast = $this->factory->make(
                BroadcastUserPasswordUpdated::class,
                [
                    'id' => $command->getId()
                ]
            );
        }
        catch(Exception $exception) {
            $this->factory->make(
                BroadcastUserError::class,
                [
                    'message' => "Error updating password",
                    'id' => $command->getId()
                ]
            );
        }

        $this->dispatcher->dispatch($broadcast);
    }
}