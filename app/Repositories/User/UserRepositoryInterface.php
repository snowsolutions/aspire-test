<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    /**
     * Retrieve all records of the resource
     */
    public function findAll(): Collection;

    /**
     * Retrieve a record of the resource with specific ID
     */
    public function findById($id): ?User;

    /**
     * Update current resource with specific data
     */
    public function update(User $model, $data): bool|null;

    /**
     * Save current resource
     */
    public function save(User $model): bool|null;

    /**
     * Delete current resource
     */
    public function delete(User $model): bool|null;
}
