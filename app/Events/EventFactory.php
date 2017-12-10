<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/9/17
 * Time: 8:21 PM
 */

namespace CQRS\Events;


use CQRS\DomainModels\IDomainModel;

class EventFactory
{
    public static function make(string $class, IDomainModel $dependency)
    {
        if (!class_exists($class)) {
            throw new \Exception("Class does not exist");
        }

        return new $class($dependency);
    }
}