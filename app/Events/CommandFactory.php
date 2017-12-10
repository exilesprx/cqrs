<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/9/17
 * Time: 8:23 PM
 */

namespace CQRS\Events;

use CQRS\DomainModels\IDomainModel;

/**
 * Class CommandFactory
 * @package CQRS\Events
 */
class CommandFactory
{
    /**
     * @param string $class
     * @param IDomainModel $dependency
     * @return ICommand
     * @throws \Exception
     */
    public function make(string $class, IDomainModel $dependency) : ICommand
    {
        if (!class_exists($class)) {
            throw new \Exception("Class does not exist");
        }

        return new $class($dependency);
    }
}