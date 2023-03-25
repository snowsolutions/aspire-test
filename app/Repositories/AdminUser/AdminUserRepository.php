<?php

namespace App\Repositories\AdminUser;

use App\Models\AdminUser;
use Illuminate\Support\Collection;

class AdminUserRepository implements AdminUserRepositoryInterface
{

    /**
     * Retrieve all records of the resource
     * @return Collection
     */
    public function findAll(): Collection
    {
        return AdminUser::all();
    }

    /**
     * Retrieve a record of the resource with specific ID
     * @param $id
     * @return AdminUser|null
     */
    public function findById($id): ?AdminUser
    {
        return AdminUser::find($id);
    }

    /**
     * Update current resource with specific data
     * @param AdminUser $model
     * @return bool|null
     */
    public function update(AdminUser $model, $data): bool|null
    {
        $model->fill($data);
        return $this->save($model);
    }

    /**
     * Save current resource
     * @param AdminUser $model
     * @return bool|null
     */
    public function save(AdminUser $model): bool|null
    {
        return $model->save();
    }

    /**
     * Delete current resource
     * @param AdminUser $model
     * @return bool|null
     */
    public function delete(AdminUser $model): bool|null
    {
        return $model->delete();
    }
}
