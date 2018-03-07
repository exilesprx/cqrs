<?php

namespace CQRS\Repositories\Events;

use CQRS\EventStores\UserStore;
use CQRS\Repositories\PayloadHelper;
use Illuminate\Support\Collection;
use Ramsey\Uuid\UuidInterface;

/**
 * Class UserRepository
 * @package CQRS\Repositories\Events
 */
class UserRepository
{
    /**
     * @var UserStore
     */
    private $store;

    /**
     * @var PayloadHelper
     */
    private $payloadHelper;

    /**
     * UserRepository constructor.
     * @param UserStore $store
     * @param PayloadHelper $helper
     */
    public function __construct(UserStore $store, PayloadHelper $helper)
    {
        $this->store = $store;

        $this->payloadHelper = $helper;
    }

    /**
     * @param string $event
     * @param UuidInterface $aggregateId
     * @param iterable $data
     * @return bool
     */
    public function save(string $event, UuidInterface $aggregateId, iterable $data)
    {
        $this->store->name = $event;

        $this->store->aggregate_id = $aggregateId->toString();

        $this->store->data = $data;

        return $this->store->save();
    }

    /**
     * @param UuidInterface $id
     * @return Collection
     */
    public function find(UuidInterface $id)
    {
        return $this->store->where('aggregate_id', $id->toString())->get();
    }
}