<?php

namespace tests\Unit\CQRS\Aggregates;

use CQRS\Aggregates\User;
use CQRS\DomainModels\User as UserDomainModel;
use CQRS\Events\EventFactory;
use CQRS\Repositories\Events\UserRepository as EventRepo;
use CQRS\Repositories\State\UserRepository as StateRepo;
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

    public function let(StateRepo $stateRepo, EventRepo $eventRepo, EventFactory $factory, Dispatcher $dispatcher, UserDomainModel $user)
    {
        $this->beConstructedWith($stateRepo, $eventRepo, $factory, $dispatcher, $user);
    }

    public function it_creates_a_new_user($stateRepo, $user)
    {
        $uuid = $this->uuid->uuid4();
        $name = $this->faker->name();
        $email = $this->faker->email();
        $password = $this->faker->password();

        $user->setName($name)->shouldBeCalled();
        $user->setEmail($email)->shouldBeCalled();
        $user->setPassword($password)->shouldBeCalled();
        $user->getName()->willReturn($name);
        $user->getEmail()->willReturn($email);
        $user->getPassword()->willReturn($password);

        $stateRepo->save($uuid, $name, $email, $password)
            ->shouldBeCalled()
            ->willReturn(1);

        $user->setId(Argument::type('int'))->shouldBeCalled();

        $this->create($uuid, $name, $email, $password);
    }

    public function it_updates_the_password($user, $eventRepo, $stateRepo)
    {
        $uuid = $this->uuid->uuid4();
        $password = $this->faker->password();

        $eventRepo->find($uuid)
            ->shouldBeCalled()
            ->willReturn(collect([]));

        $user->setPassword($password)->shouldBeCalled();

        $stateRepo->update(
            $uuid,
            [
                'password' => $password
            ]
        )->shouldBeCalled()->willReturn(1);

        $user->setId(Argument::type('int'))->shouldBeCalled();

        $this->updatePassword($uuid, $password);
    }
}
