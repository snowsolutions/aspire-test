<?php

namespace App\Services\Payment;

use App\Exceptions\LoanApplication\InvalidApplicationStatus;
use App\Exceptions\Payment\InvalidPaymentAmount;
use App\Exceptions\Payment\InvalidPaymentStatus;
use App\Models\Attributes\Payment\Status;
use App\Models\Payment;
use App\Repositories\LoanApplication\LoanApplicationRepository;
use App\Repositories\Payment\PaymentRepository;

class MakePaymentService
{
    protected PaymentRepository $paymentRepository;

    protected LoanApplicationRepository $loanApplicationRepository;

    protected RecalculateRemainingTotalService $recalculateRemainingTotalService;

    public function __construct(
        PaymentRepository $paymentRepository,
        LoanApplicationRepository $loanApplicationRepository,
        RecalculateRemainingTotalService $recalculateRemainingTotalService
    ) {
        $this->paymentRepository = $paymentRepository;
        $this->loanApplicationRepository = $loanApplicationRepository;
        $this->recalculateRemainingTotalService = $recalculateRemainingTotalService;
    }

    /**
     * Submit an amount to specific payment
     *
     * @return Payment
     *
     * @throws InvalidApplicationStatus
     * @throws InvalidPaymentAmount
     * @throws InvalidPaymentStatus
     */
    public function execute(Payment $payment, $paidAmount)
    {
        if ($paidAmount < $payment->amount) {
            throw new InvalidPaymentAmount('Submitted amount must greater or equal to the payment amount');
        }

        if ($payment->status != Status::PENDING->name) {
            throw new InvalidPaymentStatus('Only a pending payment can be submitted a make payment action');
        }

        $application = $this->loanApplicationRepository->findById($payment->loan_application_id);

        if ($application->status != \App\Models\Attributes\LoanApplication\Status::APPROVED->name) {
            throw new InvalidApplicationStatus('You can only make payment for approved application');
        }

        if ($paidAmount > $application->remaining_amount) {
            throw new InvalidPaymentAmount("You can only submitted an amount less than or equal to $application->remaining_amount");
        }

        $application->remaining_amount = $application->remaining_amount - $paidAmount;
        /**
         * Handle once off case & finish to the last payment
         */
        if ($application->remaining_amount < 0.001) {
            $application->status = \App\Models\Attributes\LoanApplication\Status::PAID->name;
        }
        $payment->amount = $paidAmount;
        $payment->status = Status::PAID->name;
        $this->paymentRepository->save($payment);
        $this->loanApplicationRepository->save($application);
        /**
         * Recalculate the remaining total and update other pending payments with new amount
         */
        $this->recalculateRemainingTotalService->execute($application);

        return $payment;
    }
}
