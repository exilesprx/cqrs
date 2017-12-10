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

class UserRepository implements IEventRepository
{
    private $dispatcher;

    private $factory;

    public function __construct(Dispatcher $dispatcher, EventFactory $eventFactory)
    {
        $this->dispatcher = $dispatcher;

        $this->factory = $eventFactory;
    }

    public function save(User $user)
    {

        UserStore::create([
            'aggregate_version' => $user->getAggregateVersion(),
            'data' => json_encode([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'remember_token' => $user->getRememberToken()
            ])
        ]);

        $event = $this->factory->make(UserCreatedEvent::class, $user);

        $this->dispatcher->dispatch($event);

        return true;
    }

    public function all()
    {

        return \CQRS\User::all();
    }

    public function getVersion()
    {


    }
}