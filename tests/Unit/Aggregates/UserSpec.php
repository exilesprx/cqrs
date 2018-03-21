<?php

namespace tests\Unit\CQRS\Aggregates;

use CQRS\Aggregates\User;
use CQRS\Events\EventFactory;
use CQRS\Events\UserCreated;
use CQRS\Events\UserPasswordUpdated;
use CQRS\EventStores\UserStore;
use Faker\Factory;
use Illuminate\Events\Dispatcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidFactory;

class UserSpec extends ObjectBehavior
{
    private $faker;

    private $uuid;

    public function __construct()
    {
        $this->faker = Factory::create();

        $this->uuid = new UuidFactory();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(User::class);
    }

    public function let(EventFactory $factory, Dispatcher $dispatcher)
    {
        $this->beConstructedWith($factory, $dispatcher);
    }

    public function it_creates_a_new_user(UserCreated $event, $factory, $dispatcher)
    {
        $uuid = $this->uuid->uuid4();
        $name = $this->faker->name();
        $email = $this->faker->email();
        $password = $this->faker->password();

        $factory->make(
            UserCreated::class,
            $uuid,
            [
                'name' => $name,
                'email'=> $email,
                'password' => $password
            ]
        )
            ->shouldBeCalled()
            ->willReturn($event);

        $dispatcher->dispatch($event)
            ->shouldBeCalled();

        $this->createUser($uuid, $name, $email, $password);
    }

    public function it_updates_the_password(UserPasswordUpdated $event, $factory, $dispatcher)
    {
        $uuid = $this->uuid->uuid4();
        $password = $this->faker->password();

        $factory->make(
            UserPasswordUpdated::class,
            $uuid,
            [
                'password' => $password
            ]
        )
            ->shouldBeCalled()
            ->willReturn($event);

        $dispatcher->dispatch($event)
            ->shouldBeCalled();

        $this->replayEvents($uuid, collect([]));

        $this->getAggregateId()
            ->shouldReturn($uuid);

        $this->updateUserPassword($password);
    }

    public function it_should_create_the_UserCreated_event_and_apply_the_event(UserCreated $event, UserStore $store, $factory)
    {
        $uuid = $this->uuid->uuid4();
        $name = $this->faker->name();
        $email = $this->faker->email();
        $password = $this->faker->password();
        $events = collect([$store->getWrappedObject()]);

        $store->getAttribute('name')
            ->willReturn(UserCreated::class);

        $store->getAttribute('aggregate_id')
            ->willReturn($uuid->toString());

        $store->getAttribute('data')
            ->willReturn([]);

        $factory->make(
            UserCreated::class,
            Argument::type(Uuid::class),
            Argument::withEntry('payload', [])
        )
            ->shouldBeCalled()
            ->willReturn($event);

        $event->getAggregateId()
            ->shouldBeCalled()
            ->willReturn($uuid);

        $event->getName()
            ->shouldBeCalled()
            ->willReturn($name);

        $event->getEmail()
            ->shouldBeCalled()
            ->willReturn($email);

        $event->getPassword()
            ->shouldBeCalled()
            ->willReturn($password);

        $this->replayEvents($uuid, $events);

        $this->getAggregateId()
            ->shouldReturn($uuid);

        $this->getName()
            ->shouldReturn($name);

        $this->getEmail()
            ->shouldReturn($email);

        $this->getPassword()
            ->shouldReturn($password);
    }

    public function it_should_create_the_UserPasswordUpdated_event_and_apply_the_event(UserPasswordUpdated $event, UserStore $store, $factory)
    {
        $uuid = $this->uuid->uuid4();
        $password = $this->faker->password();
        $events = collect([$store->getWrappedObject()]);

        $store->getAttribute('name')
            ->willReturn(UserPasswordUpdated::class);

        $store->getAttribute('aggregate_id')
            ->willReturn($uuid->toString());

        $store->getAttribute('data')
            ->willReturn([]);

        $factory->make(
            UserPasswordUpdated::class,
            Argument::type(Uuid::class),
            Argument::withEntry('payload', [])
        )
            ->shouldBeCalled()
            ->willReturn($event);

        $event->getPassword()
            ->shouldBeCalled()
            ->willReturn($password);

        $this->replayEvents($uuid, $events);

        $this->getPassword()
            ->shouldReturn($password);
    }
}
