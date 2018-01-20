<?php

namespace tests\Unit\CQRS\Listeners;

use CQRS\Aggregates\User;
use CQRS\User as UserModel;
use CQRS\EventStores\UserStore;
use CQRS\Events\UserCreatedCommand;
use CQRS\Events\UserUpdatedCommand;
use CQRS\Listeners\UserCommandSubscriber;
use CQRS\Repositories\Events\UserRepository;
use CQRS\Repositories\State\UserRepository as QueryRepository;
use Faker\Factory;
use Illuminate\Support\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidFactory;

class UserCommandSubscriberSpec extends ObjectBehavior
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function let(User $user, UserRepository $repository, QueryRepository $queryRepo, UuidFactory $uid)
    {
        $this->beConstructedWith($user, $repository, $queryRepo, $uid);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UserCommandSubscriber::class);
    }

    public function it_should_call_create(UserCreatedCommand $command, $user, $uid)
    {
        $id = Uuid::uuid4();
        $name = $this->faker->name();
        $email = $this->faker->email();
        $password = $this->faker->password();

        $command->getName()->willReturn($name);
        $command->getEmail()->willReturn($email);
        $command->getPassword()->willReturn($password);

        $uid->uuid4()->shouldBeCalled()->willReturn($id);

        $user->initialize($id, $name, $email, $password)->shouldBeCalled();
        $user->create()->shouldBeCalled();

        $this->onUserCreated($command);
    }

    public function it_should_call_update(UserUpdatedCommand $command, UserModel $model, UserStore $store, $user, $repository, $queryRepo, $uid)
    {
        $id = 1;
        $modelAggregateId = 2;
        $aggregateId = Uuid::uuid4();
        $data = new Collection([]);
        $name = $this->faker->name();
        $password = $this->faker->password();

        $command->getId()->willReturn($id);
        $command->getName()->willReturn($name);
        $command->getPassword()->willReturn($password);

        $queryRepo->find($id)->shouldBeCalled()->willReturn($model);

        $model->getAttribute('aggregate_id')->willReturn($modelAggregateId);

        $uid->fromString($modelAggregateId)->shouldBeCalled()->willReturn($aggregateId);

        $store->getAttribute('data')->shouldBeCalled()->willReturn($data);

        $repository->find($aggregateId)->shouldBeCalled()->willReturn(
            new Collection([
                $store->getWrappedObject() //need to call getWrappedObject when in a collection
            ])
        );

        $user->apply($aggregateId, $data)->shouldBeCalled();

        $user->update(
            [
                'name' => $name,
                'password' => $password
            ]
        )->shouldBeCalled();

        $this->onUserUpdate($command);
    }
}
