<?php

namespace App\Services\Payment;

use App\Exceptions\LoanApplication\InvalidApplicationTerm;
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

    /**
     * Create payment records base on given loan application
     * @param LoanApplication $application
     * @return array
     */
    public function execute(LoanApplication $application)
    {
        $totalAmount = $application->amount;
        $remainAmount = $totalAmount;
        $term = $application->term;
        $averageAmount = round($totalAmount / $term, 2);
        $paymentDate = new \DateTime();
        $payments = [];
        for ($i = 1; $i <= $term; $i++) {
            if ($i > 1) {
                /**
                 * The frequency is 7 days
                 */
                $paymentDate = $paymentDate->modify('+7 days');
            }
            if ($i == $term) {
                $paymentAmount = $remainAmount;
            } else {
                $paymentAmount = $averageAmount;
                $remainAmount -= $averageAmount;
            }
            $payment = new Payment();
            $payment->fill([
                'amount' => $paymentAmount,
                'status' => \App\Models\Attributes\Payment\Status::PENDING->name,
                'loan_application_id' => $application->id,
                'user_id' => $application->user_id,
                'payment_date' => $paymentDate,
            ]);
            $this->paymentRepository->save($payment);
            $payments[] = $payment;
        }
        return $payments;
    }
}
