<?php

namespace Tests\Unit\Services\LoanApplication;

use App\Exceptions\LoanApplication\InvalidApplicationStatus;
use App\Models\Attributes\LoanApplication\Status;
use App\Models\LoanApplication;
use App\Repositories\LoanApplication\LoanApplicationRepository;
use App\Services\LoanApplication\ApproveApplicationService;
use Tests\TestCase;

class ApproveApplicationServiceTest extends TestCase
{
    protected $loanApplicationRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loanApplicationRepositoryMock = $this->createMock(LoanApplicationRepository::class);
    }

    public function testExecute()
    {
        $application = new LoanApplication();
        $application->status = Status::PENDING->name;

        $this->loanApplicationRepositoryMock->expects($this->once())->method('save');

        $service = new ApproveApplicationService(
            $this->loanApplicationRepositoryMock
        );

        $application = $service->execute($application);
        $this->assertEquals(Status::APPROVED->name, $application->status);
    }

    public function testHandleInvalidApplicationStatus()
    {
        $application = new LoanApplication();
        $application->status = Status::APPROVED->name;

        $this->loanApplicationRepositoryMock->expects($this->never())->method('save');

        $service = new ApproveApplicationService(
            $this->loanApplicationRepositoryMock
        );
        $this->expectException(InvalidApplicationStatus::class);
        $service->execute($application);
    }
}
