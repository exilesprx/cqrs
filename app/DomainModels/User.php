<?php

namespace CQRS\DomainModels;


/**
 * Class User
 * @package CQRS\DomainModels
 */
class User
{
    /**
     * @var int
     */
    const NON_EXISTENT_ID = 0;

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
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * User constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $password
     */
    public function initialize(string $name, string $email, string $password)
    {
        $this->id = self::NON_EXISTENT_ID;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password
        ];
    }
}