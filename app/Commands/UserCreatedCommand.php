<?php

namespace CQRS\Commands;

use CQRS\DomainModels\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

/**
 * Class UserCreatedCommand
 * @package CQRS\Events
 */
class UserCreatedCommand implements ICommand
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public const SHORT_NAME = "user-create-command";

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @param iterable $payload
     */
    public function handle(iterable $payload)
    {
        $this->name = array_get($payload, 'name');

        $this->email = array_get($payload, 'email');

        $this->password = array_get($payload, 'password');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public static function getShortName(): string
    {
        return self::SHORT_NAME;
    }
}
