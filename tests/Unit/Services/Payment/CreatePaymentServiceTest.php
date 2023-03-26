<?php

namespace Tests\Unit\Services\Payment;

use App\Models\Attributes\LoanApplication\Status;
use App\Models\LoanApplication;
use App\Repositories\Payment\PaymentRepository;
use App\Services\Payment\CreatePaymentService;
use Tests\TestCase;

class CreatePaymentServiceTest extends TestCase
{
    /** @var PaymentRepository|PaymentRepository&\PHPUnit\Framework\MockObject\MockObject|\PHPUnit\Framework\MockObject\MockObject  */
    protected $paymentRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentRepositoryMock = $this->createMock(PaymentRepository::class);
    }

    public function testExecute()
    {
        $application = new LoanApplication();
        $application->user_id = 1;
        $application->status = Status::APPROVED->name;
        $application->amount = 10000;
        $application->remaining_amount = 10000;
        $application->term = 3;
        $this->paymentRepositoryMock->expects($this->exactly($application->term))->method('save');
        $service = new CreatePaymentService(
            $this->paymentRepositoryMock
        );

        $payments = $service->execute($application);
        $this->assertEquals($application->term, count($payments));
        $this->assertEquals(3333.33, $payments[0]->amount);
        $this->assertEquals(\App\Models\Attributes\Payment\Status::PENDING->name, $payments[0]->status);
        $this->assertEquals(3333.33, $payments[1]->amount);
        $this->assertEquals(\App\Models\Attributes\Payment\Status::PENDING->name, $payments[1]->status);
        $this->assertEquals(3333.34, $payments[2]->amount);
        $this->assertEquals(\App\Models\Attributes\Payment\Status::PENDING->name, $payments[2]->status);
    }
}
