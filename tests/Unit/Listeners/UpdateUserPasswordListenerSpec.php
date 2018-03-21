<?php

namespace tests\Unit\CQRS\Listeners;

use CQRS\Aggregates\User as Aggregate;
use CQRS\Broadcasts\BroadcastFactory;
use CQRS\Broadcasts\BroadcastUserPasswordUpdated;
use CQRS\Commands\UpdateUserPassword;
use CQRS\Events\UserPasswordUpdated;
use CQRS\Listeners\UpdateUserPasswordListener;
use CQRS\Repositories\State\UserRepository as StateRepository;
use CQRS\Repositories\Events\UserRepository as EventRepository;
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

    public function let(Dispatcher $dispatcher, Aggregate $aggregate, StateRepository $repository, EventRepository $eventRepo, BroadcastFactory $broadcastFactory)
    {
        $this->beConstructedWith($dispatcher, $aggregate, $repository, $eventRepo, $broadcastFactory);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(UpdateUserPasswordListener::class);
    }

    public function it_handles_the_user_update_password_command(UpdateUserPassword $command, UserPasswordUpdated $broadcast, $dispatcher, $aggregate, $repository, $eventRepo, $broadcastFactory)
    {
        $uuid = $this->uuid->uuid4();
        $password = $this->faker->password();
        $events = collect([]);

        $command->getId()->willReturn($uuid);
        $command->getPassword()->willReturn($password);
        $command->toArray()->willReturn([]);

        $eventRepo->find($uuid)
            ->shouldBeCalled()
            ->willReturn($events);

        $aggregate->replayEvents($uuid, $events)
            ->shouldBeCalled();

        $aggregate->updateUserPassword($password)
            ->shouldBeCalled();

        $aggregate->getAggregateId()
            ->shouldBeCalled()
            ->willReturn($uuid);

        $repository->update(
            $uuid,
            [
                'password' => $password
            ]
        )
            ->shouldBeCalled();

        $broadcastFactory->make(
            BroadcastUserPasswordUpdated::class,
            [
                'id' => $uuid
            ]
        )
            ->shouldBeCalled()
            ->willReturn($broadcast);

        $this->handle($command);
    }
}
