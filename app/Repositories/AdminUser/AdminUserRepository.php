<?php

namespace App\Repositories\AdminUser;

use App\Models\AdminUser;
use Illuminate\Support\Collection;

class AdminUserRepository implements AdminUserRepositoryInterface
{
    /**
     * Retrieve all records of the resource
     */
    public function findAll(): Collection
    {
        return AdminUser::all();
    }

    /**
     * Retrieve a record of the resource with specific ID
     */
    public function findById($id): ?AdminUser
    {
        return AdminUser::find($id);
    }

    /**
     * Update current resource with specific data
     */
    public function update(AdminUser $model, $data): bool|null
    {
        $model->fill($data);

        return $this->save($model);
    }

    /**
     * Save current resource
     */
    public function save(AdminUser $model): bool|null
    {
        return $model->save();
    }

    /**
     * Delete current resource
     */
    public function delete(AdminUser $model): bool|null
    {
        return $model->delete();
    }
}
