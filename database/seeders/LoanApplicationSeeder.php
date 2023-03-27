<?php

namespace Database\Seeders;

use App\Models\Attributes\LoanApplication\Status;
use App\Services\LoanApplication\CreateApplicationService;
use Illuminate\Database\Seeder;

class LoanApplicationSeeder extends Seeder
{
    protected CreateApplicationService $createApplicationService;

    public function __construct(
        CreateApplicationService $createApplicationService,
    ) {
        $this->createApplicationService = $createApplicationService;
    }

    /**
     * Insert dummy data for loan_applications table
     *
     * @return void
     *
     * @throws \App\Exceptions\LoanApplication\InvalidApplicationAmount
     * @throws \App\Exceptions\LoanApplication\InvalidApplicationTerm
     */
    public function run()
    {
        $loanDummyData = [
            [
                'user_id' => 1,
                'status' => Status::PENDING->name,
                'purpose' => 'Personal',
                'term' => 3,
                'amount' => 10000,
                'remaining_amount' => 10000,
            ],
            [
                'user_id' => 1,
                'status' => Status::APPROVED->name,
                'purpose' => 'Personal',
                'term' => 5,
                'amount' => 8000,
                'remaining_amount' => 8000,
            ],
            [
                'user_id' => 2,
                'status' => Status::PENDING->name,
                'purpose' => 'Personal',
                'term' => 3,
                'amount' => 5000,
                'remaining_amount' => 5000,
            ],
        ];

        foreach ($loanDummyData as $data) {
            $this->createApplicationService->execute($data);
        }
    }
}
