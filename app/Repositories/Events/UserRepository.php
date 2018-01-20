<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/9/17
 * Time: 8:47 PM
 */

namespace CQRS\Repositories\Events;

use CQRS\EventStores\UserStore;
use CQRS\Repositories\PayloadHelper;
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
     * @param UuidInterface $aggregateId
     * @param string $event
     * @param iterable $payload
     */
    public function save(UuidInterface $aggregateId, string $event, iterable $payload)
    {
        $payload = $this->payloadHelper->filterNullValues($payload);

        $this->store->create(
            [
                'name' => $event,
                'aggregate_id' => $aggregateId->toString(),
                'data' => $payload
            ]
        );
    }

    /**
     * @param UuidInterface $id
     * @return UserStore
     */
    public function find(UuidInterface $id)
    {
        return $this->store->where('aggregate_id', $id->toString())->orderBy('created_at', 'DESC')->get();
    }
}