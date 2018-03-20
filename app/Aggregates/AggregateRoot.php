<?php

namespace CQRS\Aggregates;

use Illuminate\Container\Container;
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
     * @var Container
     */
    protected $container;

    /**
     * AggregateRoot constructor.
     * @param Container $container
     */
    protected function __construct(Container $container)
    {
        $this->version = 1;

        $this->container = $container;
    }

    /**
     * @return string
     */
    public function getAggregateId()
    {
        return $this->aggregateId->toString();
    }
}