<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/9/17
 * Time: 8:05 PM
 */

namespace CQRS\DomainModels;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class User implements IDomainModel
{

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
     * @var integer
     */
    private $aggregateVersion;

    /**
     * @var string
     */
    private $rememberToken;

    /**
     * User constructor.
     * @param string $name
     * @param string $email
     * @param string $password
     * @param int|null $version
     * @param string|null $rememberToken
     */
    public function __construct(string $name, string $email, string $password, ?int $version = null, ?string $rememberToken = "")
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->aggregateVersion = $version;
        $this->rememberToken = $rememberToken;
    }

    /**
     * Factory method to create a user from request data.
     * @param Request $request
     * @return User
     */
    public static function fromRequest(Request $request)
    {
        $name = $request->get('name');
        $email = $request->get('email');
        $password = Hash::make($request->get('password'));
        $version = $request->get('version', 1);

        return new self($name, $email, $password, $version);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return int
     */
    public function getAggregateVersion(): int
    {
        return $this->aggregateVersion;
    }

    /**
     * @param int $aggregateVersion
     */
    public function setAggregateVersion(int $aggregateVersion)
    {
        $this->aggregateVersion = $aggregateVersion;
    }

    /**
     * @return string
     */
    public function getRememberToken(): string
    {
        return $this->rememberToken;
    }

    /**
     * @param string $rememberToken
     */
    public function setRememberToken(string $rememberToken)
    {
        $this->rememberToken = $rememberToken;
    }
}