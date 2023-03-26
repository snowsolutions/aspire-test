<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\Api\AuthenticatedUser;
use App\Http\Controllers\Api\ValidatorHandler;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Resources\LoanApplicationCollection;
use App\Http\Response\ApiResponseHandler;
use App\Repositories\LoanApplication\LoanApplicationRepository;
use App\Services\LoanApplication\ApproveApplicationService;
use App\Services\LoanApplication\CreateApplicationService;
use Illuminate\Http\Request;

class LoanApplicationController extends Controller
{
    use ValidatorHandler;
    use AuthenticatedUser;

    protected LoanApplicationRepository $loanApplicationRepository;
    protected ApproveApplicationService $approveApplicationService;

    public function __construct(
        LoanApplicationRepository $loanApplicationRepository,
        ApproveApplicationService $approveApplicationService
    )
    {
        $this->loanApplicationRepository = $loanApplicationRepository;
        $this->approveApplicationService = $approveApplicationService;
    }

    /**
     * Retrieve all loan applications in the system
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $data = $this->loanApplicationRepository->findAll();
            return ApiResponseHandler::success(new LoanApplicationCollection($data));
        } catch (\Exception $exception) {
            return ApiResponseHandler::exception($exception->getMessage());
        }
    }

    /**
     * Approve a pending loan application
     * @param $applicationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve($applicationId)
    {
        try {
            $application = $this->loanApplicationRepository->findById($applicationId);
            $result = $this->approveApplicationService->execute($application);
            return ApiResponseHandler::success($result);
        } catch (\Exception $exception) {
            return ApiResponseHandler::exception($exception->getMessage());
        }
    }
}
