<?php

namespace CQRS\Events;

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
     * @param string $name
     * @param string $email
     * @param string $password
     */
    public function handle(string $name, string $email, string $password)
    {
        $this->name = $name;

        $this->email = $email;

        $this->password = $password;
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
}
