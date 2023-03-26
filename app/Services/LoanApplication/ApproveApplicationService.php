<?php

namespace App\Services\LoanApplication;

use App\Exceptions\LoanApplication\InvalidApplicationStatus;
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

    /**
     * Approve a pending application
     * @param LoanApplication $application
     * @return LoanApplication
     * @throws InvalidApplicationStatus
     */
    public function execute(LoanApplication $application)
    {
        if ($application->status != Status::PENDING->name) {
            throw new InvalidApplicationStatus('Only pending applications can be approved');
        }
        $application->status = Status::APPROVED->name;
        $this->loanApplicationRepository->save($application);
        return $application;
    }
}
