<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/29/17
 * Time: 6:03 PM
 */

namespace CQRS\Events;


class UserUpdateEvent extends Event implements IEvent
{

    public const SHORT_NAME = "user-update";

    /**
     * @var int
     */
    private $id;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|nul
     */
    private $password;

    /**
     * @param int $id
     * @param iterable $payload
     */
    public function handle(int $id, iterable $payload)
    {
        $this->id = $id;

        $this->name = array_get($payload, 'name');

        $this->password = array_get($payload, 'password');
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
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
    public static function getShortName(): string
    {
        return self::SHORT_NAME;
    }
}