<?php

namespace CQRS\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;


/**
 * Class UserCreatedEvent
 * @package CQRS\Events
 */
class UserCreatedEvent implements IEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var string
     */
    public const SHORT_NAME = "user-create";

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

    /**
     * @return string
     */
    public static function getShortName() : string
    {
        return self::SHORT_NAME;
    }
}
