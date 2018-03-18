<?php

namespace CQRS\Repositories\State;

use CQRS\Aggregates\User;
use CQRS\User as UserQueryModel;
use Ramsey\Uuid\UuidInterface;

/**
 * Class UserRepository
 * @package CQRS\Repositories\State
 */
class UserRepository
{
    /**
     * @var UserQueryModel
     */
    private $model;

    /**
     * UserRepository constructor.
     * @param UserQueryModel $user
     */
    public function __construct(UserQueryModel $user)
    {
        $this->model = $user;
    }

    /**
     * @param User $user
     * @return int
     */
    public function save(User $user)
    {
        $payload = $this->createPayload($user);

        $user = $this->model->create($payload);

        return $user;
    }

    /**
     * @param User $user
     * @return int
     */
    public function update(User $user)
    {
        $payload = $this->createPayload($user);

        return $this->model->where('aggregate_id', $user->getAggregateId())->update($payload);
    }

    /**
     * @param UuidInterface $aggregateId
     * @return
     */
    public function findByAggregateId(UuidInterface $aggregateId)
    {
        return $this->model->where('aggregate_id', $aggregateId)->first();
    }

    /**
     * @param int $id
     * @return
     */
    public function find(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * @param iterable $conditions
     * @return mixed
     */
    public function findBy(iterable $conditions)
    {
        return $this->model->where($conditions)->first();
    }

    private function createPayload(User $user)
    {
        return [
            'aggregate_id' => $user->getAggregateId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword()
        ];
    }
}