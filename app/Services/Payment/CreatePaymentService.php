<?php

namespace App\Services\Payment;

use App\Models\Attributes\LoanApplication\Status;
use App\Models\LoanApplication;
use App\Models\Payment;
use App\Repositories\Payment\PaymentRepository;

class CreatePaymentService
{
    protected PaymentRepository $paymentRepository;

    public function __construct(
        PaymentRepository $paymentRepository
    )
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function execute(LoanApplication $application)
    {
        $totalAmount = $application->amount;
        $remainAmount = $totalAmount;
        $term = $application->term;
        $averageAmount = round($totalAmount / $term, 2);
        for ($i = 1; $i <= $term; $i++) {
            if ($i == $term) {
                $paymentAmount = $remainAmount;
            } else {
                $paymentAmount = $averageAmount;
                $remainAmount -= $averageAmount;
            }
            $payment = new Payment();
            $payment->fill([
                'amount' => $paymentAmount,
                'loan_application_id' => $application->id,
                'user_id' => $application->user_id
            ]);
            $this->paymentRepository->save($payment);
        }

    }
}
