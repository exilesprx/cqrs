<?php

namespace CQRS\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;


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
    const SHORT_NAME = "user-create";

    /**
     * @var Uuid
     */
    private $aggregateId;

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
     * @param UuidInterface $aggregateId
     * @param string $name
     * @param string $email
     * @param string $password
     */
    public function handle(UuidInterface $aggregateId, string $name, string $email, string $password)
    {
        $this->aggregateId = $aggregateId;

        $this->name = $name;

        $this->email = $email;

        $this->password = $password;
    }

    /**
     * @return Uuid
     */
    public function getAggregateId(): Uuid
    {
        return $this->aggregateId;
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
