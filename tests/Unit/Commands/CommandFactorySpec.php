<?php

namespace tests\Unit\CQRS\Commands;

use CQRS\Commands\CommandFactory;
use CQRS\Commands\CreateUser;
use Illuminate\Container\Container;
use PhpSpec\ObjectBehavior;

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
        $command = CreateUser::class;

        $container->make($command, ["payload" => []])
            ->shouldBeCalled();

        $this->make($command, []);
    }
}
