<?php

namespace tests\Unit\CQRS\Events;

use CQRS\Events\EventFactory;
use CQRS\Events\UserCreated;
use Illuminate\Container\Container;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EventFactorySpec extends ObjectBehavior
{
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
        $event = UserCreated::SHORT_NAME;

        $container->make($event, ["aggregateId" => "id", "payload" => []])->shouldBeCalled();

        $this->make($event, "id", []);
    }
}
