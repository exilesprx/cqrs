<?php

namespace tests\Unit\CQRS\Listeners;

use CQRS\Aggregates\User as Aggregate;
use CQRS\Broadcasts\BroadcastUserPasswordUpdated;
use CQRS\Commands\UpdateUserPassword;
use CQRS\Entities\User;
use CQRS\Events\EventFactory;
use CQRS\Events\UserPasswordUpdated;
use CQRS\Listeners\UpdateUserPasswordListener;
use Faker\Factory;
use Illuminate\Events\Dispatcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ramsey\Uuid\UuidFactory;

class UpdateUserPasswordListenerSpec extends ObjectBehavior
{
    private $faker;

    private $uuid;

    public function __construct()
    {
        $this->faker = Factory::create();

        $this->uuid = new UuidFactory();
    }

    public function let(EventFactory $factory, Dispatcher $dispatcher, Aggregate $aggregate)
    {
        $this->beConstructedWith($factory, $dispatcher, $aggregate);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(UpdateUserPasswordListener::class);
    }

    public function it_handles_the_user_update_password_command(UpdateUserPassword $command, UserPasswordUpdated $event, $factory, $dispatcher, $aggregate)
    {
        $uuid = $this->uuid->uuid4();
        $password = $this->faker->password();

        $command->getId()->willReturn($uuid);
        $command->getPassword()->willReturn($password);
        $command->toArray()->willReturn([2]);

        $aggregate->updatePassword($uuid, $password)
            ->shouldBeCalled()
            ->willReturn(new User());

        $factory->make(
            UserPasswordUpdated::class,
            $uuid,
            [2]
        )->shouldBeCalled()
            ->willReturn($event);

        $dispatcher->dispatch($event)
            ->shouldBeCalled();

        $dispatcher->dispatch(Argument::type(BroadcastUserPasswordUpdated::class))
            ->shouldBeCalled();

        $this->handle($command);
    }
}
