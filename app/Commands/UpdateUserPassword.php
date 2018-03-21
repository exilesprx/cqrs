<?php

namespace CQRS\Commands;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class UpdateUserPassword
 * @package CQRS\Commands
 */
class UpdateUserPassword extends Command
{
    /**
     * @var iterable
     */
    private $payload;

    /**
     * @param iterable $payload
     */
    public function __construct(iterable $payload)
    {
        $this->payload = $payload;
    }

    /**
     * @return UuidInterface
     */
    public function getId()
    {
        return Uuid::fromString(array_get($this->payload, 'id'));
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
            "password" => $this->getPassword()
        ];
    }
}