<?php

namespace App\Repositories\Payment;

use App\Models\Payment;
use Illuminate\Support\Collection;

interface PaymentRepositoryInterface
{
    /**
     * Retrieve all records of the resource
     */
    public function findAll(): Collection;

    /**
     * Retrieve a record of the resource with specific ID
     */
    public function findById($id): ?Payment;

    /**
     * Update current resource with specific data
     */
    public function update(Payment $model, $data): bool|null;

    /**
     * Save current resource
     */
    public function save(Payment $model): bool|null;

    /**
     * Delete current resource
     */
    public function delete(Payment $model): bool|null;
}
