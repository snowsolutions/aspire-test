<?php

namespace App\Services\LoanApplication;

use App\Models\Attributes\LoanApplication\Status;
use App\Models\LoanApplication;
use App\Repositories\LoanApplication\LoanApplicationRepository;

class ApproveApplicationService
{
    protected LoanApplicationRepository $loanApplicationRepository;

    public function __construct(
        LoanApplicationRepository $loanApplicationRepository
    )
    {
        $this->loanApplicationRepository = $loanApplicationRepository;
    }

    public function execute(LoanApplication $application)
    {
        if ($application->status != Status::PENDING->name) {
            throw new \Exception('Only [PENDING] applications can be approved');
        }
        $application->status = Status::APPROVED->name;
        $this->loanApplicationRepository->save($application);
        return $application;
    }
}
