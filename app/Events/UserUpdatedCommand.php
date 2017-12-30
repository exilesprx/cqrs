<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/29/17
 * Time: 5:46 PM
 */

namespace CQRS\Events;


/**
 * Class UserUpdatedCommand
 * @package CQRS\Events
 */
class UserUpdatedCommand implements ICommand
{
    public const SHORT_NAME = "user-update-command";

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
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
    public function getId()
    {
        return $this->id;
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