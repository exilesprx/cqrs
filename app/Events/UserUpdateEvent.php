<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/29/17
 * Time: 6:03 PM
 */

namespace CQRS\Events;


use Ramsey\Uuid\UuidInterface;

class UserUpdateEvent extends Event implements IEvent
{
    const SHORT_NAME = "user-update";

    /**
     * @var UuidInterface
     */
    private $aggregateId;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $password;

    /**
     * @param UuidInterface $aggregateId
     * @param iterable $payload
     */
    public function handle(UuidInterface $aggregateId, iterable $payload)
    {
        $this->aggregateId = $aggregateId;

        $this->name = array_get($payload, 'name');

        $this->password = array_get($payload, 'password');
    }

    /**
     * @return UuidInterface
     */
    public function getAggregateId() : UuidInterface
    {
        return $this->aggregateId;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getShortName(): string
    {
        return self::SHORT_NAME;
    }
}