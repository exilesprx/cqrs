<?php

namespace tests\Unit\CQRS\Listeners;

use CQRS\Events\UserCreated;
use CQRS\Events\UserPasswordUpdated;
use CQRS\Listeners\UserEventSubscriber;
use CQRS\Repositories\Events\UserRepository;
use Faker\Factory;
use Illuminate\Events\Dispatcher;
use PhpSpec\ObjectBehavior;
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

    public function it_is_initializable()
    {
        $this->shouldHaveType(UserEventSubscriber::class);
    }

    public function it_handles_the_user_created_event(UserCreated $event, $userRepository)
    {
        $uuid = $this->uid->uuid4();

        $event->getAggregateId()
            ->willReturn($uuid);

        $event->toArray()
            ->willReturn([4]);

        $userRepository->save(
            get_class($event->getWrappedObject()),
            $uuid,
            [4]
        )->shouldBeCalled();

        $this->onUserCreated($event);
    }

    public function it_handles_the_user_password_updated_event(UserPasswordUpdated $event, $userRepository)
    {
        $uuid = $this->uid->uuid4();

        $event->getAggregateId()
            ->willReturn($uuid);

        $event->toArray()
            ->willReturn([5]);

        $userRepository->save(
            get_class($event->getWrappedObject()),
            $uuid,
            [5]
        )->shouldBeCalled();

        $this->onUserPasswordUpdated($event);
    }

    public function it_subscribes_to_events(Dispatcher $dispatcher)
    {
        $dispatcher->listen(UserCreated::class, UserEventSubscriber::class . "@onUserCreated")
            ->shouldBeCalled();

        $dispatcher->listen(UserPasswordUpdated::class, UserEventSubscriber::class . "@onUserPasswordUpdated")
            ->shouldBeCalled();

        $this->subscribe($dispatcher);
    }
}
