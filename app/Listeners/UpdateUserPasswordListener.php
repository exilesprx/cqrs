<?php

namespace CQRS\Listeners;

use CQRS\Aggregates\User;
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
     * UserCommandSubscriber constructor.
     * @param Dispatcher $dispatcher
     * @param User $aggregate
     * @param StateRepository $repo
     * @param EventRepository $eventRepo
     */
    public function __construct(Dispatcher $dispatcher, User $aggregate, StateRepository $repo, EventRepository $eventRepo)
    {
        $this->dispatcher = $dispatcher;

        $this->aggregate = $aggregate;

        $this->repo = $repo;

        $this->eventRepo = $eventRepo;
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

            $this->repo->update($this->aggregate);

            $this->dispatcher->dispatch(new BroadcastUserPasswordUpdated($this->aggregate));
        }
        catch(Exception $exception) {
//            Log::info($exception->getMessage());
        }
    }
}