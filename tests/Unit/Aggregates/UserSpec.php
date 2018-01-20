<?php

namespace tests\Unit\CQRS\Aggregates;

use CQRS\Aggregates\User;
use CQRS\Events\EventFactory;
use CQRS\Events\UserCreatedEvent;
use CQRS\Events\UserUpdateEvent;
use CQRS\Repositories\Events\UserRepository as EventStore;
use Faker\Factory;
use Illuminate\Events\Dispatcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
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

    public function let(EventStore $eventStore, EventFactory $factory, Dispatcher $dispatcher, \CQRS\DomainModels\User $user)
    {
        $this->beConstructedWith($eventStore, $factory, $dispatcher, $user);
    }

    public function it_initializes($user)
    {
        $uuid = $this->uuid->uuid4();
        $name = $this->faker->name();
        $email = $this->faker->email();
        $password = $this->faker->password();

        $user->initialize($name, $email, $password)->shouldBeCalled();

        $this->initialize($uuid, $name, $email, $password);
    }

    public function it_creates_a_new_event(UserCreatedEvent $event, $eventStore, $factory, $dispatcher, $user)
    {
        $uuid = $this->uuid->uuid4();
        $name = $this->faker->name();
        $email = $this->faker->email();
        $password = $this->faker->password();

        $this->initialize($uuid, $name, $email, $password);
        $this->getAggregateId()->shouldReturn($uuid);

        $eventName = UserCreatedEvent::SHORT_NAME;
        $factory->make($eventName)->shouldBeCalled()->willReturn($event);


        $event->getShortName()->willReturn($eventName);
        $user->getName()->willReturn($name);
        $user->getEmail()->willReturn($email);
        $user->getPassword()->willReturn($password);

        $payload = [
            'name' => $name,
            'email' => $email,
            'password' => $password
        ];

        $eventStore->save($uuid, UserCreatedEvent::SHORT_NAME, $payload)->shouldBeCalled();

        $event->handle($uuid, $payload)->shouldBeCalled();

        $dispatcher->dispatch($event)->shouldBeCalled();

        $this->create();
    }

    public function it_updates(UserUpdateEvent $event, $eventStore, $factory, $dispatcher, $user)
    {
        $uuid = $this->uuid->uuid4();
        $name = $this->faker->name();
        $email = $this->faker->email();
        $password = $this->faker->password();
        $updateName = $this->faker->name();
        $updateEmail = $this->faker->email();

        $this->initialize($uuid, $name, $email, $password);
        $this->getAggregateId()->shouldReturn($uuid);

        $factory->make(UserUpdateEvent::SHORT_NAME)->shouldBeCalled()->willReturn($event);

        $event->getShortName()->shouldBeCalled()->willReturn(UserUpdateEvent::SHORT_NAME);

        $updateData = [
            'name' => $updateName,
            'email' => $updateEmail
        ];

        $payload = [
            'name' => $name,
            'email' => $email,
            'password' => $password
        ];

        $mergedData = array_merge($payload, $updateData);

        $user->toArray()->willReturn($payload);

        $eventStore->save($uuid, UserUpdateEvent::SHORT_NAME, $mergedData)->shouldBeCalled();

        $event->handle($uuid, $mergedData)->shouldBeCalled();

        $dispatcher->dispatch($event)->shouldBeCalled();

        $this->update($updateData);
    }

    public function it_applies($user)
    {
        $uuid = $this->uuid->uuid4();
        $name = $this->faker->name();
        $email = $this->faker->email();
        $password = $this->faker->password();

        $user->setName($name)->shouldBeCalled();
        $user->setEmail($email)->shouldBeCalled();
        $user->setPassword($password)->shouldBeCalled();

        $this->apply(
            $uuid,
            [
                'name' => $name,
                'email' => $email,
                'password' => $password
            ]
        );

        $this->getAggregateId()->shouldReturn($uuid);
    }
}
