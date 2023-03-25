<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Api\AuthenticatedUser;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Resources\LoanApplicationCollection;
use App\Http\Response\ApiResponseHandler;
use App\Repositories\LoanApplication\LoanApplicationRepository;
use App\Services\LoanApplication\CreateApplicationService;
use Illuminate\Http\Request;

class LoanApplicationController extends Controller
{
    use ValidatorHandler;
    use AuthenticatedUser;

    protected LoanApplicationRepository $loanApplicationRepository;
    protected CreateApplicationService $createApplicationService;

    public function __construct(
        LoanApplicationRepository $loanApplicationRepository,
        CreateApplicationService  $createApplicationService
    )
    {
        $this->loanApplicationRepository = $loanApplicationRepository;
        $this->createApplicationService = $createApplicationService;
    }

    public function index()
    {
        try {
            $userId = $this->loggedUser()->getAuthIdentifier();
            $data = $this->loanApplicationRepository->findAllByUserId($userId);
            return ApiResponseHandler::success(new LoanApplicationCollection($data));
        } catch (\Exception $exception) {
            return ApiResponseHandler::exception($exception->getMessage());
        }
    }

    public function store(Request $request)
    {
        $data = $request->only(['purpose', 'amount', 'term']);
        $this->handleValidation($data, [
            'purpose' => 'required',
            'amount' => 'required',
            'term' => 'required',
        ]);

        try {
            $data['user_id'] = $this->loggedUser()->getAuthIdentifier();
            $application = $this->createApplicationService->execute($data);
            return ApiResponseHandler::success($application);
        } catch (\Exception $exception) {
            return ApiResponseHandler::exception($exception->getMessage());
        }

    }
}
