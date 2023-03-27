<?php

namespace App\Services\Payment;

use App\Models\Attributes\Payment\Status;
use App\Models\LoanApplication;
use App\Repositories\Payment\PaymentRepository;

class RecalculateRemainingTotalService
{
    protected PaymentRepository $paymentRepository;

    public function __construct(
        PaymentRepository $paymentRepository
    ) {
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * Recalculate the remaining total and update other pending payments with new amount
     *
     * @return \Illuminate\Support\Collection|void
     */
    public function execute(LoanApplication $application)
    {
        $pendingPayments = $this->paymentRepository->findAllPendingByApplication($application->id);
        $remainingTerm = $pendingPayments->count();
        $remainingAmount = $application->remaining_amount;
        if ($remainingTerm == 0) {
            return;
        }
        $averageAmount = round($remainingAmount / $remainingTerm, 2);
        foreach ($pendingPayments as $pendingPayment) {
            $remainingTerm--;

            if ($remainingTerm == 0) {
                $paymentAmount = $remainingAmount;
            } else {
                $paymentAmount = $averageAmount;
                $remainingAmount -= $averageAmount;
            }
            $updateData['amount'] = $paymentAmount;
            /**
             * If the payment amount < 0.001, consider it is PAID
             */
            if ($paymentAmount < 0.001) {
                $updateData['status'] = Status::PAID->name;
            }
            $this->paymentRepository->update($pendingPayment, $updateData);
        }

        return $pendingPayments;
    }
}
