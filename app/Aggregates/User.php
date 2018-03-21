<?php

namespace CQRS\Aggregates;

use CQRS\Events\EventFactory;
use CQRS\Events\UserCreated;
use CQRS\Events\UserPasswordUpdated;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Collection;
use Ramsey\Uuid\UuidInterface;

/**
 * Class User
 * @package CQRS\Aggregates
 */
class User extends AggregateRoot implements EventSourceContract
{
    use EventSourced;

    /**
     * @var EventFactory
     */
    private $factory;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * User constructor.
     * @param EventFactory $factory
     * @param Dispatcher $dispatcher
     */
    public function __construct(EventFactory $factory, Dispatcher $dispatcher)
    {
        parent::__construct();

        $this->factory = $factory;

        $this->dispatcher = $dispatcher;
    }

    /**
     * @param UuidInterface $uuid
     * @param string $name
     * @param string $email
     * @param string $password
     * @return void
     */
    public function createUser(UuidInterface $uuid, string $name, string $email, string $password)
    {
        // TODO: Domain validation here
        /**
         * EX: Query state for email existence, if exists:
         * UserExceptionFactory::emailExists($email);
         */

        $this->aggregateId = $uuid;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;

        $event = $this->factory->make(
            UserCreated::class,
            $uuid,
            [
                'name'     => $name,
                'email'    => $email,
                'password' => $password
            ]
        );

        $this->onUserCreated($event);

        $this->dispatcher->dispatch($event);
    }

    /**
     * @param string $password
     * @return void
     */
    public function updateUserPassword(string $password)
    {
        $this->password = $password;

        $event = $this->factory->make(
            UserPasswordUpdated::class,
            $this->aggregateId,
            [
                'password' => $password
            ]
        );

        $this->onUserPasswordUpdated($event);

        $this->dispatcher->dispatch($event);
    }

    /**
     * @param UserCreated $event
     */
    private function onUserCreated(UserCreated $event)
    {
        $this->aggregateId = $event->getAggregateId();
        $this->name = $event->getName();
        $this->email = $event->getEmail();
        $this->password = $event->getPassword();
//        $this->version = $event->getVersion();
    }

//    private function onUserNameUpdated(UserNameUpdated $event)
//    {
//        $this->name = $event->getName();
//        $this->version = $event->getVersion();
//    }

    /**
     * @param UserPasswordUpdated $event
     */
    private function onUserPasswordUpdated(UserPasswordUpdated $event)
    {
        $this->password = $event->getPassword();
    }

    /**
     * @param UuidInterface $uuid
     * @param Collection $events
     * @return mixed|void
     */
    public function replayEvents(UuidInterface $uuid, Collection $events)
    {
        $this->aggregateId = $uuid;

        $this->replay($this, $events, $this->factory);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}