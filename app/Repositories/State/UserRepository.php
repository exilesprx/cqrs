<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/23/17
 * Time: 10:39 PM
 */

namespace CQRS\Repositories\State;

use CQRS\Repositories\PayloadHelper;
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
     * @var PayloadHelper
     */
    private $payloadHelper;

    /**
     * UserRepository constructor.
     * @param UserQueryModel $user
     * @param PayloadHelper $helper
     */
    public function __construct(UserQueryModel $user, PayloadHelper $helper)
    {
        $this->model = $user;

        $this->payloadHelper = $helper;
    }

    /**
     * @param UuidInterface $aggregateId
     * @param string $name
     * @param string $email
     * @param string $password
     */
    public function save(UuidInterface $aggregateId, string $name, string $email, string $password)
    {
        // Do some validation here

        $this->model->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'aggregate_id' => $aggregateId->toString()
        ]);
    }

    /**
     * @param UuidInterface $aggregateId
     * @param iterable $payload
     */
    public function update(UuidInterface $aggregateId, iterable $payload)
    {
        $payload = $this->payloadHelper->filterNullValues($payload);

        $this->model->where('aggregate_id', $aggregateId->toString())->update($payload);
    }

    /**
     * @param UuidInterface $aggregateId
     * @return mixed
     */
    public function findByAggregateId(UuidInterface $aggregateId)
    {
        return $this->model->where('aggregate_id', $aggregateId)->first();
    }

    /**
     * @param int $id
     * @return UserQueryModel
     */
    public function find(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return $this->model->all();
    }
}