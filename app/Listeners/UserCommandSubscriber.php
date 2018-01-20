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
use CQRS\EventStores\EventStore;
use CQRS\Repositories\Events\UserRepository;
use CQRS\Repositories\State\UserRepository as QueryRepository;
use Illuminate\Events\Dispatcher;
use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\UuidFactoryInterface;

/**
 * Class UserCommandSubscriber
 * @package CQRS\Listeners
 */
class UserCommandSubscriber
{
    /**
     * @var User
     */
    private $aggregate;

    /**
     * @var UserRepository
     */
    private $eventRepo;

    /**
     * @var QueryRepository
     */
    private $queryRepo;

    /**
     * @var UuidFactoryInterface
     */
    private $uuid;

    /**
     * UserCommandSubscriber constructor.
     * @param User $user
     * @param UserRepository $repository
     * @param QueryRepository $userRepository
     * @param UuidFactory $uuid
     */
    public function __construct(User $user, UserRepository $repository, QueryRepository $userRepository, UuidFactory $uuid)
    {
        $this->aggregate = $user;

        $this->eventRepo = $repository;

        $this->queryRepo = $userRepository;

        $this->uuid = $uuid;
    }

    /**
     * @param UserCreatedCommand $command
     */
    public function onUserCreated(UserCreatedCommand $command)
    {
        $this->aggregate->initialize($this->uuid->uuid4(), $command->getName(), $command->getEmail(), $command->getPassword());

        $this->aggregate->create();
    }

    /**
     * @param UserUpdatedCommand $command
     */
    public function onUserUpdate(UserUpdatedCommand $command)
    {
        $user = $this->queryRepo->find($command->getId());

        $aggregateId = $this->uuid->fromString($user->getAttribute('aggregate_id'));

        $events = $this->eventRepo->find($aggregateId);

        $events->each(function(EventStore $event) use($aggregateId) {

            $data = $event->getAttribute('data');

            $this->aggregate->apply($aggregateId, $data);
        });

        $this->aggregate->update(
            [
                'name' => $command->getName(),
                'password' => $command->getPassword()
            ]
        );
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            UserCreatedCommand::class,
            self::class . '@onUserCreated'
        );

        $events->listen(
    UserUpdatedCommand::class,
    self::class . '@onUserUpdate'
        );
    }
}