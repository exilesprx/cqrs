<?php

namespace tests\Unit\CQRS\Listeners;

use CQRS\Aggregates\User as Aggregate;
use CQRS\Broadcasts\BroadcastFactory;
use CQRS\Broadcasts\BroadcastUserCreated;
use CQRS\Commands\CreateUser;
use CQRS\Listeners\CreateUserListener;
use CQRS\Repositories\State\UserRepository as StateRepository;
use Faker\Factory;
use Illuminate\Contracts\Events\Dispatcher;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\UuidFactory;

class CreateUserListenerSpec extends ObjectBehavior
{
    private $faker;

    private $uuid;

    public function __construct()
    {
        $this->uuid = new UuidFactory();

        $this->faker = Factory::create();
    }

    public function let(Dispatcher $dispatcher, Aggregate $aggregate, StateRepository $repository, BroadcastFactory $factory)
    {
        $this->beConstructedWith($dispatcher, $aggregate, $repository, $factory);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CreateUserListener::class);
    }

    public function it_handles_the_user_created_command(CreateUser $command, BroadcastUserCreated $broadcast, $dispatcher, $aggregate, $repository, $factory)
    {
        $uuid = $this->uuid->uuid4();
        $name = $this->faker->name();
        $email = $this->faker->email();
        $password = $this->faker->password();

        $command->getId()->willReturn($uuid);
        $command->getName()->willReturn($name);
        $command->getEmail()->willReturn($email);
        $command->getPassword()->willReturn($password);
        $command->toArray()->willReturn([]);

        $aggregate->createUser(
            $uuid,
            $name,
            $email,
            $password
        )->shouldBeCalled()
            ->willReturn();

        $repository->save($aggregate)
            ->shouldBeCalled();

        $factory->make(
            BroadcastUserCreated::class,
            [
                'id' => $uuid,
                'name' => $name,
                'email' => $email
            ]
        )->shouldBeCalled()
            ->willReturn($broadcast);

        $dispatcher->dispatch($broadcast)
            ->shouldBeCalled();

        $this->handle($command);
    }
}
