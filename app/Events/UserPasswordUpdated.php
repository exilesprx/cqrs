<?php

namespace CQRS\Events;

use Ramsey\Uuid\UuidInterface;

class UserPasswordUpdated extends Event implements EventContract
{
    /**
     * @var UuidInterface
     */
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

    /**
     * @return UuidInterface
     */
    public function getAggregateId()
    {
        return $this->aggregateId;
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
            'password' => $this->getPassword()
        ];
    }
}