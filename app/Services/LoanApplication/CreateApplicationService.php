<?php

namespace App\Services\LoanApplication;

use App\Models\Attributes\LoanApplication\Status;
use App\Models\LoanApplication;
use App\Repositories\LoanApplication\LoanApplicationRepository;
use App\Services\Payment\CreatePaymentService;

class CreateApplicationService
{
    protected LoanApplicationRepository $loanApplicationRepository;
    protected CreatePaymentService $createPaymentService;

    public function __construct(
        LoanApplicationRepository $loanApplicationRepository,
        CreatePaymentService      $createPaymentService
    )
    {
        $this->loanApplicationRepository = $loanApplicationRepository;
        $this->createPaymentService = $createPaymentService;
    }

    public function execute($data)
    {
        $loanApplication = new LoanApplication();
        if (!array_key_exists('status', $data)) {
            $data['status'] = Status::PENDING->name;
        }
        $loanApplication->fill($data);
        $this->loanApplicationRepository->save($loanApplication);
        $this->createPaymentService->execute($loanApplication);
        return $loanApplication;
    }
}
