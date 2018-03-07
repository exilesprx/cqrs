<?php

namespace tests\Unit\CQRS\Events;

use CQRS\Events\EventFactory;
use CQRS\Events\UserCreated;
use Illuminate\Container\Container;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\UuidInterface;

class EventFactorySpec extends ObjectBehavior
{
    private $uuid;

    public function __construct()
    {
        $this->uuid = new UuidFactory();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(EventFactory::class);
    }

    public function let(Container $container)
    {
        $this->beConstructedWith($container);
    }

    public function it_calls_make_with_event_name($container)
    {
        $event = UserCreated::class;

        $container->make(
            $event,
            Argument::containing(
                Argument::type(UuidInterface::class)
            )
        )->shouldBeCalled();

        $this->make(
            $event,
            $this->uuid->uuid4(),
            []
        );
    }
}
