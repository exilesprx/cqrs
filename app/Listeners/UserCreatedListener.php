<?php

namespace CQRS\Listeners;

use CQRS\Aggregates\User;
use CQRS\Broadcasts\BroadcastUserCreated;
use CQRS\Commands\CreateUser;
use CQRS\Repositories\State\UserRepository as StateRepository;
use Exception;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Ramsey\Uuid\Uuid;

/**
 * Class UserCreatedListener
 * @package CQRS\Listeners
 */
class UserCreatedListener implements ShouldQueue
{
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
     * UserCommandSubscriber constructor.
     * @param Dispatcher $dispatcher
     * @param User $aggregate
     * @param StateRepository $repo
     */
    public function __construct(Dispatcher $dispatcher, User $aggregate, StateRepository $repo)
    {
        $this->dispatcher = $dispatcher;

        $this->aggregate = $aggregate;

        $this->repo = $repo;
    }

    /**
     * @param CreateUser $command
     */
    public function handle(CreateUser $command)
    {
        // TODO: Basic validation here

        try {
            $this->aggregate->createUser(
                Uuid::uuid4(),
                $command->getName(),
                $command->getEmail(),
                $command->getPassword()
            );

            $this->repo->save($this->aggregate);

            $this->dispatcher->dispatch(new BroadcastUserCreated($this->aggregate));
        }
        catch(Exception $exception) {
            // Log maybe?
        }
    }
}