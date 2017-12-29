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
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $password;

    /**
     * @param int $id
     * @param string $name
     * @param string $password
     */
    public function handle(int $id, string $name, string $password)
    {
        $this->id = $id;

        $this->name = $name;

        $this->password = $password;
    }

    /**
     * @return int
     */
    public function getId() : int
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