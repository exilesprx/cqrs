<?php

namespace CQRS\Aggregates;

use Ramsey\Uuid\UuidInterface;

/**
 * Class AggregateRoot
 * @package CQRS\Aggregates
 */
abstract class AggregateRoot
{
    /**
     * @var UuidInterface
     */
    protected $aggregateId;

    /**
     * @var int
     */
    protected $version;

    /**
     * AggregateRoot constructor.
     */
    protected function __construct()
    {
        $this->version = 1;
    }

    /**
     * @return UuidInterface
     */
    public function getAggregateId()
    {
        return $this->aggregateId;
    }
}