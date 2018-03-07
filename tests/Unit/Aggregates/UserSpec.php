<?php

namespace tests\Unit\CQRS\Aggregates;

use CQRS\Aggregates\User;
use CQRS\Events\EventFactory;
use CQRS\Events\UserCreated;
use CQRS\Events\UserPasswordUpdated;
use CQRS\Repositories\Events\UserRepository as EventStore;
use Faker\Factory;
use Illuminate\Bus\Dispatcher;
use PhpSpec\ObjectBehavior;
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

    public function it_creates_a_new_event(UserCreated $event, $eventStore, $factory, $dispatcher, $user)
    {
        $uuid = $this->uuid->uuid4();
        $name = $this->faker->name();
        $email = $this->faker->email();
        $password = $this->faker->password();

        $this->initialize($uuid, $name, $email, $password);

        $this->getAggregateId()->shouldReturn($uuid);

        $user->getName()->willReturn($name);
        $user->getEmail()->willReturn($email);
        $user->getPassword()->willReturn($password);

        $payload = [
            'name' => $name,
            'email' => $email,
            'password' => $password
        ];

        $event->getShortName()->willReturn(UserCreated::SHORT_NAME);

        $event->chain([new \CQRS\Broadcasts\BroadcastUserCreated($user->getWrappedObject())])->shouldBeCalled()->willReturn($event);

//        $factory->make(UserCreated::SHORT_NAME, $uuid, $payload)->shouldBeCalled()->willReturn($event);

        $eventStore->save($uuid, UserCreated::SHORT_NAME, $payload)->shouldBeCalled();

        $dispatcher->dispatch($event)->shouldBeCalled();

        $this->create();
    }

    public function it_updates(UserPasswordUpdated $event, $eventStore, $factory, $dispatcher, $user)
    {
        $uuid = $this->uuid->uuid4();
        $name = $this->faker->name();
        $email = $this->faker->email();
        $password = $this->faker->password();
        $updateName = $this->faker->name();
        $updateEmail = $this->faker->email();

        $this->initialize($uuid, $name, $email, $password);
        $this->getAggregateId()->shouldReturn($uuid);

        $updateData = [
            'name' => $updateName,
            'email' => $updateEmail
        ];

        $payload = [
            'name' => $name,
            'email' => $email,
            'password' => $password
        ];

        $event->getShortName()->willReturn(UserPasswordUpdated::SHORT_NAME);

        $mergedData = array_merge($payload, $updateData);

        $factory->make(UserPasswordUpdated::SHORT_NAME, $uuid, $mergedData)->shouldBeCalled()->willReturn($event);

        $user->toArray()->willReturn($payload);

        $eventStore->save($uuid, UserPasswordUpdated::SHORT_NAME, $mergedData)->shouldBeCalled();

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
