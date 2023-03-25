<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * The base repository interface
 */
interface BaseRepository
{
    /**
     * Retrieve all records of the resource
     * @return Collection
     */
    public function findAll();

    /**
     * Retrieve a record of the resource with specific ID
     * @param $id
     * @return Model|null
     */
    public function findById($id);

    /**
     * Update current resource with specific data
     * @param Model $model
     * @return bool
     */
    public function update($model, $data): bool;

    /**
     * Save current resource
     * @param Model $model
     * @return bool
     */
    public function save($model): bool;

    /**
     * Delete current resource
     * @param Model $model
     * @return void
     */
    public function delete($model): void;
}
