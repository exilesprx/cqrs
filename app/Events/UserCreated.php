<?php

namespace CQRS\Events;

use Ramsey\Uuid\UuidInterface;


/**
 * Class UserCreatedEvent
 * @package CQRS\Events
 */
class UserCreated extends Event implements IEvent
{
    private $aggregateId;

    /**
     * @var iterable
     */
    private $payload;

    /**
     * @param UuidInterface $aggregateId
     * @param iterable $payload
     */
    public function __construct(UuidInterface $aggregateId, iterable $payload)
    {
        $this->aggregateId = $aggregateId;

        $this->payload = $payload;
    }

    public function getAggregateId()
    {
        return $this->aggregateId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return array_get($this->payload, 'name');
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return array_get($this->payload, 'email');
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return array_get($this->payload, 'password');
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            "name" => $this->getName(),
            "email" => $this->getEmail(),
            "password" => $this->getPassword()
        ];
    }
}
