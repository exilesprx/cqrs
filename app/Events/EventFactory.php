<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/29/17
 * Time: 5:19 PM
 */

namespace CQRS\Events;


use Illuminate\Container\Container;

/**
 * Class EventFactory
 * @package CQRS\Events
 */
class EventFactory
{
    /**
     * @var Container
     */
    private $container;

    /**
     * EventFactory constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $event
     * @return IEvent
     */
    public function make(string $event)
    {
        return $this->container->make($event);
    }

}