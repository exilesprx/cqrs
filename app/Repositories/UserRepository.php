<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/9/17
 * Time: 8:47 PM
 */

namespace CQRS\Repositories;

use CQRS\DomainModels\User;
use CQRS\Events\EventFactory;
use CQRS\Events\UserCreatedEvent;
use CQRS\EventStores\UserStore;
use Illuminate\Events\Dispatcher;
use CQRS\User as UserReadModel;

class UserRepository implements IEventRepository
{
    private $dispatcher;

    private $factory;

    public function __construct(Dispatcher $dispatcher, EventFactory $eventFactory)
    {
        $this->dispatcher = $dispatcher;

        $this->factory = $eventFactory;
    }

    public function save(int $version, iterable $payload)
    {

        UserStore::create([
            'aggregate_version' => $version,
            'data' => $payload
        ]);

        $event = $this->factory->make(UserCreatedEvent::class, User::fromPayload($version, $payload));

        $this->dispatcher->dispatch($event);

        return true;
    }

    public function all()
    {

        return UserReadModel::all();
    }

    public function getVersion()
    {


    }
}