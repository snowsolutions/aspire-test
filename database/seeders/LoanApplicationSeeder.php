<?php

namespace Database\Seeders;

use App\Models\Attributes\LoanApplication\Status;
use App\Services\LoanApplication\CreateApplicationService;
use App\Services\Payment\CreatePaymentService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoanApplicationSeeder extends Seeder
{
    protected CreateApplicationService $createApplicationService;

    public function __construct(
        CreateApplicationService $createApplicationService,
    )
    {
        $this->createApplicationService = $createApplicationService;
    }

    public function run()
    {
        $loanDummyData = [
            [
                'user_id' => 1,
                'status' => Status::PENDING->name,
                'purpose' => 'Personal',
                'term' => 3,
                'amount' => 10000,
            ],
            [
                'user_id' => 2,
                'status' => Status::PENDING->name,
                'purpose' => 'Personal',
                'term' => 3,
                'amount' => 5000,
            ]
        ];

        foreach ($loanDummyData as $data) {
            $this->createApplicationService->execute($data);
        }
    }
}
