<?php

namespace CQRS\Exceptions;

/**
 * Class UserExceptionFactory
 * @package CQRS\Exceptions
 */
class UserExceptionFactory
{
    /**
     *
     */
    const USER_EMAIL_EXISTS = "Email already exists: %s";

    /**
     * @param string $email
     * @return UserException
     */
    public static function emailExists(string $email)
    {
        return new UserException(printf(self::USER_EMAIL_EXISTS, $email));
    }
}