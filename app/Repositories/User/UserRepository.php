<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class UserRepository implements UserRepositoryInterface
{

    /**
     * Retrieve all records of the resource
     * @return Collection
     */
    public function findAll(): Collection
    {
        return User::all();
    }

    /**
     * Retrieve a record of the resource with specific ID
     * @param $id
     * @return User|null
     */
    public function findById($id): ?User
    {
        return User::find($id);
    }

    /**
     * Update current resource with specific data
     * @param User $model
     * @return bool|null
     */
    public function update(Model $model, $data): bool|null
    {
        $model->fill($data);
        return $this->save($model);
    }

    /**
     * Save current resource
     * @param User $model
     * @return bool|null
     */
    public function save(Model $model): bool|null
    {
        return $model->save();
    }

    /**
     * Delete current resource
     * @param User $model
     * @return bool|null
     */
    public function delete(Model $model): bool|null
    {
        return $model->delete();
    }
}
