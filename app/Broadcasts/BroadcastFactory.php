<?php

namespace CQRS\Broadcasts;

use Illuminate\Container\Container;

class BroadcastFactory
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function make(string $name, iterable $payload)
    {
        return $this->container->makeWith($name, $payload);
    }
}