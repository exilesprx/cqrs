<?php

namespace CQRS\Commands;

/**
 * Interface ICommand
 * @package CQRS\Events
 */
interface ICommand
{
    /**
     * @return array
     */
    public function toArray();
}