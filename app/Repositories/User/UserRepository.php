<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Retrieve all records of the resource
     */
    public function findAll(): Collection
    {
        return User::all();
    }

    /**
     * Retrieve a record of the resource with specific ID
     */
    public function findById($id): ?User
    {
        return User::find($id);
    }

    /**
     * Update current resource with specific data
     *
     * @param  User  $model
     */
    public function update(Model $model, $data): bool|null
    {
        $model->fill($data);

        return $this->save($model);
    }

    /**
     * Save current resource
     *
     * @param  User  $model
     */
    public function save(Model $model): bool|null
    {
        return $model->save();
    }

    /**
     * Delete current resource
     *
     * @param  User  $model
     */
    public function delete(Model $model): bool|null
    {
        return $model->delete();
    }
}
