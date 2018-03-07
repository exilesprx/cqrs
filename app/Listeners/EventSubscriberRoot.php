<?php

namespace CQRS\Listeners;

use Illuminate\Events\Dispatcher;

/**
 * Class EventSubscriberRoot
 * @package CQRS\Listeners
 */
abstract class EventSubscriberRoot
{
    const SUBSCRIBERS_PREFIX = "@on";

    /**
     * @var array
     */
    protected $subscribes = [];

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        foreach($this->subscribes as $key => $event)
        {
            $events->listen($event, $this->getListeningMethod($event));
        }
    }

    /**
     * @param string $event
     * @return string
     */
    private function getListeningMethod(string $event)
    {
        $parts = explode("\\", $event);

        return static::class . self::SUBSCRIBERS_PREFIX . array_pop($parts);
    }
}