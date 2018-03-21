<?php

namespace CQRS\Listeners;

use CQRS\Aggregates\User;
use CQRS\Broadcasts\BroadcastFactory;
use CQRS\Broadcasts\BroadcastUserCreated;
use CQRS\Broadcasts\BroadcastUserError;
use CQRS\Commands\CreateUser;
use CQRS\Repositories\State\UserRepository as StateRepository;
use Exception;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Ramsey\Uuid\Uuid;

/**
 * Class CreateUserListener
 * @package CQRS\Listeners
 */
class CreateUserListener implements ShouldQueue
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
     * @var BroadcastFactory
     */
    private $factory;

    /**
     * UserCommandSubscriber constructor.
     * @param Dispatcher $dispatcher
     * @param User $aggregate
     * @param StateRepository $repo
     * @param BroadcastFactory $factory
     */
    public function __construct(Dispatcher $dispatcher, User $aggregate, StateRepository $repo, BroadcastFactory $factory)
    {
        $this->dispatcher = $dispatcher;

        $this->aggregate = $aggregate;

        $this->repo = $repo;

        $this->factory = $factory;
    }

    /**
     * @param CreateUser $command
     */
    public function handle(CreateUser $command)
    {
        // TODO: Basic validation here

        try {
            $this->aggregate->createUser(
                $command->getId(),
                $command->getName(),
                $command->getEmail(),
                $command->getPassword()
            );

            $this->repo->save($this->aggregate);

            $broadcast = $this->factory->make(
                BroadcastUserCreated::class,
                [
                    'id' => $command->getId(),
                    'name' => $command->getName(),
                    'email' => $command->getEmail()
                ]
            );
        }
        catch(Exception $exception) {

            $broadcast = $this->factory->make(
                BroadcastUserError::class,
                [
                    'message' => "Error creating user."
                ]
            );
        }

        $this->dispatcher->dispatch($broadcast);
    }
}