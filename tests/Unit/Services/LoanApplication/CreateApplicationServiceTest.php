<?php

namespace Tests\Unit\Services\LoanApplication;

use App\Exceptions\LoanApplication\InvalidApplicationAmount;
use App\Exceptions\LoanApplication\InvalidApplicationTerm;
use App\Models\Attributes\LoanApplication\Status;
use App\Repositories\LoanApplication\LoanApplicationRepository;
use App\Services\LoanApplication\CreateApplicationService;
use App\Services\Payment\CreatePaymentService;
use Tests\TestCase;

class CreateApplicationServiceTest extends TestCase
{
    /** @var LoanApplicationRepository|LoanApplicationRepository&\PHPUnit\Framework\MockObject\MockObject|\PHPUnit\Framework\MockObject\MockObject */
    protected $loanApplicationRepositoryMock;

    /** @var CreatePaymentService|CreatePaymentService&\PHPUnit\Framework\MockObject\MockObject|\PHPUnit\Framework\MockObject\MockObject */
    protected $createPaymentServiceMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loanApplicationRepositoryMock = $this->createMock(LoanApplicationRepository::class);
        $this->createPaymentServiceMock = $this->createMock(CreatePaymentService::class);
    }

    public function testExecute()
    {
        $data = [
            'user_id' => 1,
            'amount' => 1000,
            'term' => 4,
            'purpose' => 'testing',
        ];

        $service = new CreateApplicationService(
            $this->loanApplicationRepositoryMock,
            $this->createPaymentServiceMock
        );

        $this->loanApplicationRepositoryMock->expects($this->once())->method('save');
        $this->createPaymentServiceMock->expects($this->once())->method('execute');
        $result = $service->execute($data);
        $this->assertEquals(Status::PENDING->name, $result->status);
        $this->assertEquals($data['amount'], $result->amount);
        $this->assertEquals($data['amount'], $result->remaining_amount);
    }

    public function testHandleInvalidAmount()
    {
        $this->expectException(InvalidApplicationAmount::class);
        $data = [
            'user_id' => 1,
            'amount' => 0,
            'term' => 4,
            'purpose' => 'testing',
        ];

        $service = new CreateApplicationService(
            $this->loanApplicationRepositoryMock,
            $this->createPaymentServiceMock
        );

        $service->execute($data);
    }

    public function testHandleInvalidTerm()
    {
        $this->expectException(InvalidApplicationTerm::class);
        $data = [
            'user_id' => 1,
            'amount' => 1000,
            'term' => 0,
            'purpose' => 'testing',
        ];

        $service = new CreateApplicationService(
            $this->loanApplicationRepositoryMock,
            $this->createPaymentServiceMock
        );

        $service->execute($data);
    }
}
