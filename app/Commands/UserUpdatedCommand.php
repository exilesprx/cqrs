<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/29/17
 * Time: 5:46 PM
 */

namespace CQRS\Commands;


/**
 * Class UserUpdatedCommand
 * @package CQRS\Events
 */
class UserUpdatedCommand implements ICommand
{
    const SHORT_NAME = "user-update-command";

    /**
     * @var int
     */
    private $id;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
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