<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/9/17
 * Time: 10:12 PM
 */

namespace CQRS\Repositories;

use CQRS\DomainModels\User;

/**
 * Class IEventRepository
 * @package CQRS\Repositories
 */
interface IEventRepository
{
    public function save(User $user);
}