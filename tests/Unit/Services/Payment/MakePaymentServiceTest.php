<?php

namespace Tests\Unit\Services\Payment;

use App\Exceptions\LoanApplication\InvalidApplicationStatus;
use App\Exceptions\Payment\InvalidPaymentAmount;
use App\Exceptions\Payment\InvalidPaymentStatus;
use App\Models\Attributes\LoanApplication\Status;
use App\Models\LoanApplication;
use App\Models\Payment;
use App\Repositories\LoanApplication\LoanApplicationRepository;
use App\Repositories\Payment\PaymentRepository;
use App\Services\Payment\MakePaymentService;
use App\Services\Payment\RecalculateRemainingTotalService;
use Tests\TestCase;

class MakePaymentServiceTest extends TestCase
{
    /** @var PaymentRepository|PaymentRepository&\PHPUnit\Framework\MockObject\MockObject|\PHPUnit\Framework\MockObject\MockObject */
    protected $paymentRepositoryMock;

    /** @var LoanApplicationRepository|LoanApplicationRepository&\PHPUnit\Framework\MockObject\MockObject|\PHPUnit\Framework\MockObject\MockObject */
    protected $loanApplicationRepositoryMock;

    /** @var RecalculateRemainingTotalService|RecalculateRemainingTotalService&\PHPUnit\Framework\MockObject\MockObject|\PHPUnit\Framework\MockObject\MockObject */
    protected $recalculateRemainingTotalServiceMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentRepositoryMock = $this->createMock(PaymentRepository::class);
        $this->loanApplicationRepositoryMock = $this->createMock(LoanApplicationRepository::class);
        $this->recalculateRemainingTotalServiceMock = $this->createMock(RecalculateRemainingTotalService::class);
    }

    public function testExecute()
    {
        $application = new LoanApplication();
        $application->id = 1;
        $application->status = Status::APPROVED->name;
        $application->amount = 10000;
        $application->remaining_amount = 10000;
        $application->term = 3;

        $payment = new Payment();
        $payment->id = 1;
        $payment->status = \App\Models\Attributes\Payment\Status::PENDING->name;
        $payment->amount = 3333.33;
        $payment->loan_application_id = 1;

        $this->paymentRepositoryMock->expects($this->once())->method('save');
        $this->loanApplicationRepositoryMock->expects($this->once())->method('findById')->willReturn($application);
        $this->loanApplicationRepositoryMock->expects($this->once())->method('save');
        $this->recalculateRemainingTotalServiceMock->expects($this->once())->method('execute');

        $service = new MakePaymentService(
            $this->paymentRepositoryMock,
            $this->loanApplicationRepositoryMock,
            $this->recalculateRemainingTotalServiceMock
        );
        $paidAmount = 4000;
        $result = $service->execute($payment, $paidAmount);
        $this->assertEquals($paidAmount, $result->amount);
        $this->assertEquals(\App\Models\Attributes\Payment\Status::PAID->name, $result->status);
    }

    public function testHandleInvalidSmallPaymentAmount()
    {
        $payment = new Payment();
        $payment->id = 1;
        $payment->status = \App\Models\Attributes\Payment\Status::PENDING->name;
        $payment->amount = 3333.33;
        $payment->loan_application_id = 1;

        $this->paymentRepositoryMock->expects($this->never())->method('save');
        $this->loanApplicationRepositoryMock->expects($this->never())->method('findById');
        $this->loanApplicationRepositoryMock->expects($this->never())->method('save');
        $this->recalculateRemainingTotalServiceMock->expects($this->never())->method('execute');
        $this->expectException(InvalidPaymentAmount::class);

        $service = new MakePaymentService(
            $this->paymentRepositoryMock,
            $this->loanApplicationRepositoryMock,
            $this->recalculateRemainingTotalServiceMock
        );
        $paidAmount = 3000;
        $service->execute($payment, $paidAmount);
    }

    public function testHandleInvalidBigPaymentAmount()
    {
        $application = new LoanApplication();
        $application->id = 1;
        $application->status = Status::APPROVED->name;
        $application->amount = 10000;
        $application->remaining_amount = 10000;
        $application->term = 3;

        $payment = new Payment();
        $payment->id = 1;
        $payment->status = \App\Models\Attributes\Payment\Status::PENDING->name;
        $payment->amount = 3333.33;
        $payment->loan_application_id = 1;

        $this->paymentRepositoryMock->expects($this->never())->method('save');
        $this->loanApplicationRepositoryMock->expects($this->once())->method('findById')->willReturn($application);
        $this->loanApplicationRepositoryMock->expects($this->never())->method('save');
        $this->recalculateRemainingTotalServiceMock->expects($this->never())->method('execute');
        $this->expectException(InvalidPaymentAmount::class);

        $service = new MakePaymentService(
            $this->paymentRepositoryMock,
            $this->loanApplicationRepositoryMock,
            $this->recalculateRemainingTotalServiceMock
        );
        $paidAmount = 12000;
        $service->execute($payment, $paidAmount);
    }

    public function testHandleInvalidPaymentStatus()
    {
        $payment = new Payment();
        $payment->id = 1;
        $payment->status = \App\Models\Attributes\Payment\Status::PAID->name;
        $payment->amount = 3333.33;
        $payment->loan_application_id = 1;

        $this->paymentRepositoryMock->expects($this->never())->method('save');
        $this->loanApplicationRepositoryMock->expects($this->never())->method('findById');
        $this->loanApplicationRepositoryMock->expects($this->never())->method('save');
        $this->recalculateRemainingTotalServiceMock->expects($this->never())->method('execute');
        $this->expectException(InvalidPaymentStatus::class);

        $service = new MakePaymentService(
            $this->paymentRepositoryMock,
            $this->loanApplicationRepositoryMock,
            $this->recalculateRemainingTotalServiceMock
        );
        $paidAmount = 4000;
        $service->execute($payment, $paidAmount);
    }

    public function testHandleInvalidApplicationStatus()
    {
        $application = new LoanApplication();
        $application->id = 1;
        $application->status = Status::PENDING->name;
        $application->amount = 10000;
        $application->remaining_amount = 10000;
        $application->term = 3;

        $payment = new Payment();
        $payment->id = 1;
        $payment->status = \App\Models\Attributes\Payment\Status::PENDING->name;
        $payment->amount = 3333.33;
        $payment->loan_application_id = 1;

        $this->paymentRepositoryMock->expects($this->never())->method('save');
        $this->loanApplicationRepositoryMock->expects($this->once())->method('findById')->willReturn($application);
        $this->loanApplicationRepositoryMock->expects($this->never())->method('save');
        $this->recalculateRemainingTotalServiceMock->expects($this->never())->method('execute');
        $this->expectException(InvalidApplicationStatus::class);

        $service = new MakePaymentService(
            $this->paymentRepositoryMock,
            $this->loanApplicationRepositoryMock,
            $this->recalculateRemainingTotalServiceMock
        );
        $paidAmount = 4000;
        $service->execute($payment, $paidAmount);
    }
}
