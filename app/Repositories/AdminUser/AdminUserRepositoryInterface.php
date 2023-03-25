<?php

namespace App\Repositories\AdminUser;

use App\Models\AdminUser;
use Illuminate\Support\Collection;

interface AdminUserRepositoryInterface
{
    /**
     * Retrieve all records of the resource
     * @return Collection
     */
    public function findAll(): Collection;

    /**
     * Retrieve a record of the resource with specific ID
     * @param $id
     * @return AdminUser|null
     */
    public function findById($id): ?AdminUser;

    /**
     * Update current resource with specific data
     * @param AdminUser $model
     * @return bool|null
     */
    public function update(AdminUser $model, $data): bool|null;

    /**
     * Save current resource
     * @param AdminUser $model
     * @return bool|null
     */
    public function save(AdminUser $model): bool|null;

    /**
     * Delete current resource
     * @param AdminUser $model
     * @return bool|null
     */
    public function delete(AdminUser $model): bool|null;
}
