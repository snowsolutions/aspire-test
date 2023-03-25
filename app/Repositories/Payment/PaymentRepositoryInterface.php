<?php

namespace App\Repositories\Payment;

use App\Models\Payment;
use Illuminate\Support\Collection;

interface PaymentRepositoryInterface
{
    /**
     * Retrieve all records of the resource
     * @return Collection
     */
    public function findAll(): Collection;

    /**
     * Retrieve a record of the resource with specific ID
     * @param $id
     * @return Payment|null
     */
    public function findById($id): ?Payment;

    /**
     * Update current resource with specific data
     * @param Payment $model
     * @return bool|null
     */
    public function update(Payment $model, $data): bool|null;

    /**
     * Save current resource
     * @param Payment $model
     * @return bool|null
     */
    public function save(Payment $model): bool|null;

    /**
     * Delete current resource
     * @param Payment $model
     * @return bool|null
     */
    public function delete(Payment $model): bool|null;
}
