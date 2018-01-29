<?php

namespace CQRS\Commands;

use CQRS\Repositories\State\UserRepository;
use Exception;
use Illuminate\Queue\SerializesModels;
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
     * @var UserRepository
     */
    private $repo;

    /**
     * UserCreatedCommand constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * @param iterable $payload
     * @throws Exception
     */
    public function handle(iterable $payload)
    {
        $this->name = array_get($payload, 'name');

        $this->email = array_get($payload, 'email');

        $this->password = array_get($payload, 'password');

        $user = $this->repo->findBy(
            [
                'name' => $this->name,
                'email' => $this->email
            ]
        );

        if($user)
        {
            throw new Exception("User already exists.");
        }
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
