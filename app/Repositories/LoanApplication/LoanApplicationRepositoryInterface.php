<?php

namespace App\Repositories\LoanApplication;

use App\Models\LoanApplication;
use Illuminate\Support\Collection;

interface LoanApplicationRepositoryInterface
{
    /**
     * Retrieve all records of the resource
     */
    public function findAll(): Collection;

    /**
     * Retrieve a record of the resource with specific ID
     */
    public function findById($id): ?LoanApplication;

    /**
     * Update current resource with specific data
     */
    public function update(LoanApplication $model, $data): bool|null;

    /**
     * Save current resource
     */
    public function save(LoanApplication $model): bool|null;

    /**
     * Delete current resource
     */
    public function delete(LoanApplication $model): bool|null;
}
