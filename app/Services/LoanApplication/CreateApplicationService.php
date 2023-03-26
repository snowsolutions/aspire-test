<?php

namespace App\Services\LoanApplication;

use App\Exceptions\LoanApplication\InvalidApplicationAmount;
use App\Exceptions\LoanApplication\InvalidApplicationTerm;
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

    /**
     * Create a new loan application
     * @param $data
     * @return LoanApplication
     * @throws InvalidApplicationAmount
     * @throws InvalidApplicationTerm
     */
    public function execute($data)
    {
        $loanApplication = new LoanApplication();
        if ($data['amount'] <= 0) {
            throw new InvalidApplicationAmount('The amount must greater than 0');
        }
        if ($data['term'] <= 0) {
            throw new InvalidApplicationTerm('The term must greater than 0');
        }
        if (!array_key_exists('status', $data)) {
            $data['status'] = Status::PENDING->name;
        }
        $data['remaining_amount'] = $data['amount'];
        $loanApplication->fill($data);
        $this->loanApplicationRepository->save($loanApplication);
        /**
         * Create schedule payment according to term & amount
         */
        $this->createPaymentService->execute($loanApplication);
        return $loanApplication;
    }
}
