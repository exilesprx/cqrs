<?php

namespace CQRS\Repositories\Events;

use CQRS\EventStores\UserStore;
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
     * UserRepository constructor.
     * @param UserStore $store
     */
    public function __construct(UserStore $store)
    {
        $this->store = $store;
    }

    /**
     * @param string $event
     * @param UuidInterface $uuid
     * @param iterable $payload
     * @return bool
     */
    public function save(string $event, UuidInterface $uuid, iterable $payload)
    {
        $this->store->name = $event;

        $this->store->aggregate_id = $uuid->toString();

        $this->store->data = $payload;

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