<?php

namespace App\Repositories\LoanApplication;

use App\Models\LoanApplication;
use Illuminate\Support\Collection;

interface LoanApplicationRepositoryInterface
{
    /**
     * Retrieve all records of the resource
     * @return Collection
     */
    public function findAll(): Collection;

    /**
     * Retrieve a record of the resource with specific ID
     * @param $id
     * @return LoanApplication|null
     */
    public function findById($id): ?LoanApplication;

    /**
     * Update current resource with specific data
     * @param LoanApplication $model
     * @return bool|null
     */
    public function update(LoanApplication $model, $data): bool|null;

    /**
     * Save current resource
     * @param LoanApplication $model
     * @return bool|null
     */
    public function save(LoanApplication $model): bool|null;

    /**
     * Delete current resource
     * @param LoanApplication $model
     * @return bool|null
     */
    public function delete(LoanApplication $model): bool|null;
}
