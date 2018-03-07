<?php

namespace CQRS\Commands;


use Illuminate\Container\Container;

class CommandFactory
{
    /**
     * @var Container
     */
    private $container;

    /**
     * CommandFactory constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $command
     * @param iterable $payload
     * @return ICommand
     */
    public function make(string $command, iterable $payload)
    {
        return $this->container->make($command, ["payload" => $payload]);
    }
}