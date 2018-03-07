<?php

namespace tests\Unit\CQRS\Listeners;

use CQRS\Events\UserCreated;
use CQRS\Events\UserPasswordUpdated;
use CQRS\Listeners\UserEventSubscriber;
use CQRS\Repositories\State\UserRepository;
use Faker\Factory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ramsey\Uuid\UuidFactory;

class UserEventSubscriberSpec extends ObjectBehavior
{
    private $faker;
    private $uid;

    public function __construct()
    {
        $this->uid = new UuidFactory();
        $this->faker = Factory::create();
    }

    public function let(UserRepository $userRepository)
    {
        $this->beConstructedWith($userRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UserEventSubscriber::class);
    }

    public function it_should_call_save_on_repo(UserCreated $event, $userRepository)
    {
        $aggregateId = $this->uid->uuid4();
        $name = $this->faker->name();
        $email = $this->faker->email();
        $password = $this->faker->password();

        $event->getAggregateId()->willReturn($aggregateId);
        $event->getName()->willReturn($name);
        $event->getEmail()->willReturn($email);
        $event->getPassword()->willReturn($password);

        $userRepository->save($aggregateId, $name, $email, $password)->shouldBeCalled();

        $this->onUserCreated($event);
    }

//    public function it_should_call_update_on_repo(UserUpdateEvent $event, $userRepository)
//    {
//        $aggregateId = $this->uid->uuid4();
//        $name = $this->faker->name();
//        $password = $this->faker->password();
//
//        $event->getAggregateId()->willReturn($aggregateId);
//        $event->getName()->willReturn($name);
//        $event->getPassword()->willReturn($password);
//
//        $userRepository->update(
//            $aggregateId,
//            [
//                'name' => $name,
//                'password' => $password
//            ]
//        )->shouldBeCalled();
//
//        $this->onUpdateEvent($event);
//    }
}
