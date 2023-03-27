<?php

namespace Tests\Unit\Services\Payment;

use App\Models\LoanApplication;
use App\Models\Payment;
use App\Repositories\Payment\PaymentRepository;
use App\Services\Payment\RecalculateRemainingTotalService;
use Tests\TestCase;

class RecalculateRemainingTotalServiceTest extends TestCase
{
    /** @var PaymentRepository|PaymentRepository&\PHPUnit\Framework\MockObject\MockObject|\PHPUnit\Framework\MockObject\MockObject */
    protected $paymentRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentRepositoryMock = $this->createMock(PaymentRepository::class);
    }

    public function testExecute()
    {
        $application = new LoanApplication();
        $application->id = 1;
        $application->remaining_amount = 10000;
        $pendingPaymentCollection = collect([new Payment(), new Payment(), new Payment()]);
        $this->paymentRepositoryMock->expects($this->once())->method('findAllPendingByApplication')->willReturn(
            $pendingPaymentCollection
        );

        $this->paymentRepositoryMock->expects($this->exactly($pendingPaymentCollection->count()))->method('update');

        $service = new RecalculateRemainingTotalService(
            $this->paymentRepositoryMock
        );

        $result = $service->execute($application);

        $this->assertEquals($pendingPaymentCollection->count(), $result->count());
    }

    public function testRemainingTermZero()
    {
        $application = new LoanApplication();
        $application->id = 1;
        $application->remaining_amount = 10000;
        $pendingPaymentCollection = collect([]);
        $this->paymentRepositoryMock->expects($this->once())->method('findAllPendingByApplication')->willReturn(
            $pendingPaymentCollection
        );

        $this->paymentRepositoryMock->expects($this->never())->method('update');

        $service = new RecalculateRemainingTotalService(
            $this->paymentRepositoryMock
        );

        $service->execute($application);
    }
}
