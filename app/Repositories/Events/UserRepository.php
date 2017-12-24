<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/9/17
 * Time: 8:47 PM
 */

namespace CQRS\Repositories\Events;

use CQRS\Aggregates\User;
use CQRS\EventStores\UserStore;

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
     * @param User $user
     */
    public function save(User $user)
    {
        $this->store->save([
            'event' => $user->getEvent(),
            'data' => $user->toArray()
        ]);
    }

    /**
     *
     */
    public function all()
    {
        // TODO: Replay events
    }

    /**
     *
     */
    public function get()
    {

    }
}