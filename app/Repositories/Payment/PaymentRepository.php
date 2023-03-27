<?php

namespace App\Repositories\Payment;

use App\Models\Attributes\Payment\Status;
use App\Models\Payment;
use Illuminate\Support\Collection;

class PaymentRepository implements PaymentRepositoryInterface
{
    /**
     * Retrieve all records of the resource
     */
    public function findAll(): Collection
    {
        return Payment::all();
    }

    /**
     * Retrieve a record of the resource with specific ID
     */
    public function findById($id): ?Payment
    {
        return Payment::find($id);
    }

    /**
     * Retrieve a record of the resource with specific user_id
     */
    public function findAllByUserId($userId): Collection
    {
        return Payment::all()->where('user_id', $userId);
    }

    /**
     * Retrieve a record of the resource with specific loan_application_id
     *
     * @return Collection
     */
    public function findAllPendingByApplication($applicationId)
    {
        return Payment::all()->where('loan_application_id', $applicationId)->where('status', Status::PENDING->name);
    }

    /**
     * Update current resource with specific data
     */
    public function update(Payment $model, $data): bool|null
    {
        $model->fill($data);

        return $this->save($model);
    }

    /**
     * Save current resource
     */
    public function save(Payment $model): bool|null
    {
        return $model->save();
    }

    /**
     * Delete current resource
     */
    public function delete(Payment $model): bool|null
    {
        return $model->delete();
    }
}
