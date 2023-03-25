<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    /**
     * Retrieve all records of the resource
     * @return Collection
     */
    public function findAll(): Collection;

    /**
     * Retrieve a record of the resource with specific ID
     * @param $id
     * @return User|null
     */
    public function findById($id): ?User;

    /**
     * Update current resource with specific data
     * @param User $model
     * @return bool|null
     */
    public function update(User $model, $data): bool|null;

    /**
     * Save current resource
     * @param User $model
     * @return bool|null
     */
    public function save(User $model): bool|null;

    /**
     * Delete current resource
     * @param User $model
     * @return bool|null
     */
    public function delete(User $model): bool|null;
}
