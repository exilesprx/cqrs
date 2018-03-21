<?php

namespace CQRS\Commands;

/**
 * Interface CommandContract
 * @package CQRS\Commands
 */
interface CommandContract
{
    /**
     * @return array
     */
    public function toArray();
}