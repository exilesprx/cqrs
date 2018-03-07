<?php

namespace tests\Unit\CQRS\Listeners;

use CQRS\Aggregates\User as Aggregate;
use CQRS\Broadcasts\BroadcastUserCreated;
use CQRS\Commands\CreateUser;
use CQRS\DomainModels\User;
use CQRS\Events\EventFactory;
use CQRS\Events\UserCreated;
use CQRS\Listeners\UserCreatedListener;
use Faker\Factory;
use Illuminate\Contracts\Events\Dispatcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ramsey\Uuid\UuidInterface;

class UserCreatedListenerSpec extends ObjectBehavior
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function let(EventFactory $factory, Dispatcher $dispatcher, UuidInterface $uuid, Aggregate $aggregate)
    {
        $this->beConstructedWith($factory, $dispatcher, $uuid, $aggregate);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(UserCreatedListener::class);
    }

    public function it_handles_the_user_created_command(CreateUser $command, UserCreated $event, $factory, $dispatcher, $uuid, $aggregate)
    {
        $name = $this->faker->name();
        $email = $this->faker->email();
        $password = $this->faker->password();

        $command->getName()->willReturn($name);
        $command->getEmail()->willReturn($email);
        $command->getPassword()->willReturn($password);
        $command->toArray()->willReturn([1]);

        $aggregate->create(
            $uuid,
            $name,
            $email,
            $password
        )->shouldBeCalled()
            ->willReturn(new User());

        $factory->make(
            UserCreated::class,
            $uuid,
            [1]
        )->shouldBeCalled()
            ->willReturn($event);

        $dispatcher->dispatch($event)
            ->shouldBeCalled();

        $dispatcher->dispatch(Argument::type(BroadcastUserCreated::class))
            ->shouldBeCalled();

        $this->handle($command);
    }
}
