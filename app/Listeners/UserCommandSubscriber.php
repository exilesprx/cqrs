<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/29/17
 * Time: 5:50 PM
 */

namespace CQRS\Listeners;


use CQRS\Aggregates\User;
use CQRS\Events\UserCreatedCommand;
use CQRS\Events\UserUpdatedCommand;
use CQRS\Repositories\State\UserRepository;
use Illuminate\Events\Dispatcher;

/**
 * Class UserCommandSubscriber
 * @package CQRS\Listeners
 */
class UserCommandSubscriber
{
    /**
     * @var User
     */
    public $aggregate;

    /**
     * @var UserRepository
     */
    public $repo;

    /**
     * UserCommandSubscriber constructor.
     * @param User $user
     * @param UserRepository $repository
     */
    public function __construct(User $user, UserRepository $repository)
    {
        $this->aggregate = $user;

        $this->repo = $repository;
    }

    public function onUserCreated(UserCreatedCommand $command)
    {
        $this->aggregate->initialize($command->getName(), $command->getEmail(), $command->getPassword());

        $this->aggregate->create();
    }

    /**
     * @param UserUpdatedCommand $command
     */
    public function onUserUpdate(UserUpdatedCommand $command)
    {
        $user = $this->repo->find($command->getId());

        $this->aggregate->initialize($user->name, $user->email, $user->password, $user->id);

        $this->aggregate->update($user->id, $command->getName(), $command->getPassword());
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            UserCreatedCommand::class,
            'onUserCreated'
        );

        $events->listen(
    UserUpdatedCommand::class,
    'onUserUpdated'
        );
    }
}