<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\Api\AuthenticatedUser;
use App\Http\Controllers\Api\ValidatorHandler;
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
            $data = $this->loanApplicationRepository->findAll();
            return ApiResponseHandler::success(new LoanApplicationCollection($data));
        } catch (\Exception $exception) {
            return ApiResponseHandler::exception($exception->getMessage());
        }
    }

    public function approve(Request $request)
    {

    }
}
