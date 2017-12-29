<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/29/17
 * Time: 6:20 PM
 */

namespace CQRS\Events;


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
     * @param string $event
     * @return ICommand
     */
    public function make(string $event)
    {
        return $this->container->make($event);
    }
}