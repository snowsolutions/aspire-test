<?php

namespace App\Repositories\LoanApplication;

use App\Models\LoanApplication;
use Illuminate\Support\Collection;

class LoanApplicationRepository implements LoanApplicationRepositoryInterface
{

    /**
     * Retrieve all records of the resource
     * @return Collection
     */
    public function findAll(): Collection
    {
        return LoanApplication::all();
    }

    /**
     * Retrieve a record of the resource with specific ID
     * @param $id
     * @return LoanApplication|null
     */
    public function findById($id): ?LoanApplication
    {
        return LoanApplication::find($id);
    }

    /**
     * Retrieve a record of the resource with specific user_id
     * @param $userId
     * @return Collection
     */
    public function findAllByUserId($userId): Collection
    {
        return LoanApplication::all()->where('user_id', $userId);
    }

    /**
     * Update current resource with specific data
     * @param LoanApplication $model
     * @return bool|null
     */
    public function update(LoanApplication $model, $data): bool|null
    {
        $model->fill($data);
        return $this->save($model);
    }

    /**
     * Save current resource
     * @param LoanApplication $model
     * @return bool|null
     */
    public function save(LoanApplication $model): bool|null
    {
        return $model->save();
    }

    /**
     * Delete current resource
     * @param LoanApplication $model
     * @return bool|null
     */
    public function delete(LoanApplication $model): bool|null
    {
        return $model->delete();
    }
}
