<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/23/17
 * Time: 10:17 PM
 */

namespace CQRS\Aggregates;


use CQRS\Events\UserCreatedEvent;
use CQRS\Repositories\Events\UserRepository as EventStoreRepo;
use Illuminate\Events\Dispatcher;

/**
 * Class User
 * @package CQRS\Aggregates
 */
class User
{
    /**
     * @var EventStoreRepo
     */
    private $repo;

    /**
     * @var
     */
    private $event;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * @var
     */
    private $name;

    /**
     * @var
     */
    private $email;

    /**
     * @var
     */
    private $password;

    /**
     * User constructor.
     * @param EventStoreRepo $repository
     * @param UserCreatedEvent $event
     * @param Dispatcher $dispatcher
     */
    public function __construct(EventStoreRepo $repository, UserCreatedEvent $event, Dispatcher $dispatcher)
    {
        $this->repo = $repository;

        $this->event = $event;

        $this->dispatcher = $dispatcher;
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $password
     */
    public function applyNew(string $name, string $email, string $password)
    {
        $this->name = $name;

        $this->email = $email;

        $this->password = $password;
    }

    /**
     *
     */
    public function save()
    {
        $this->repo->save($this);

        $this->event->handle($this->getName(), $this->getEmail(), $this->getPassword());

        $this->dispatcher->dispatch($this->event);
    }

    /**
     * @return string
     */
    public function getEvent()
    {
        return $this->event->getShortName();
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
        ];
    }
}