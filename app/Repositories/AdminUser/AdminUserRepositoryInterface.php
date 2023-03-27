<?php

namespace App\Repositories\AdminUser;

use App\Models\AdminUser;
use Illuminate\Support\Collection;

interface AdminUserRepositoryInterface
{
    /**
     * Retrieve all records of the resource
     */
    public function findAll(): Collection;

    /**
     * Retrieve a record of the resource with specific ID
     */
    public function findById($id): ?AdminUser;

    /**
     * Update current resource with specific data
     */
    public function update(AdminUser $model, $data): bool|null;

    /**
     * Save current resource
     */
    public function save(AdminUser $model): bool|null;

    /**
     * Delete current resource
     */
    public function delete(AdminUser $model): bool|null;
}
