<?php

namespace tests\Unit\CQRS\Events;

use CQRS\Events\CommandFactory;
use CQRS\Events\UserCreatedCommand;
use Illuminate\Container\Container;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CommandFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(CommandFactory::class);
    }

    public function let(Container $container)
    {
        $this->beConstructedWith($container);
    }

    public function it_calls_make_with_command_name($container)
    {
        $command = UserCreatedCommand::SHORT_NAME;

        $container->make($command)->shouldBeCalled();

        $this->make($command);
    }
}
