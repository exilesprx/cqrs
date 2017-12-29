<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/23/17
 * Time: 10:39 PM
 */

namespace CQRS\Repositories\State;

use CQRS\User as UserReadModel;

/**
 * Class UserRepository
 * @package CQRS\Repositories\State
 */
class UserRepository
{

    /**
     * @var UserReadModel
     */
    private $model;

    /**
     * UserRepository constructor.
     * @param UserReadModel $user
     */
    public function __construct(UserReadModel $user)
    {
        $this->model = $user;
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $password
     */
    public function save(string $name, string $email, string $password)
    {
        // Do some validation here

        $this->model->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'aggregate_version' => 1
        ]);
    }

    /**
     * @param int $id
     * @param iterable $payload
     */
    public function update(int $id, iterable $payload)
    {
        $this->model->find($id)->update($payload);
    }

    /**
     * @param int $id
     * @return UserReadModel
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