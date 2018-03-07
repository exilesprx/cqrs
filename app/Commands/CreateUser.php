<?php

namespace CQRS\Commands;

use Illuminate\Queue\SerializesModels;

/**
 * Class CreateUser
 * @package CQRS\Events
 */
class CreateUser implements ICommand
{
    use SerializesModels;

    /**
     * @var iterable
     */
    private $payload;

    /**
     * UserCreatedCommand constructor.
     * @param iterable $payload
     */
    public function __construct(iterable $payload)
    {
        $this->payload = $payload;
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
