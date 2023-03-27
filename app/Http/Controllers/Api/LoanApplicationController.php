<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Api\AuthenticatedUser;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Resources\LoanApplicationCollection;
use App\Http\Controllers\Resources\LoanApplicationResource;
use App\Http\Response\ApiResponseHandler;
use App\Repositories\LoanApplication\LoanApplicationRepository;
use App\Services\LoanApplication\CreateApplicationService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class LoanApplicationController extends Controller
{
    use ValidatorHandler;
    use AuthenticatedUser;

    protected LoanApplicationRepository $loanApplicationRepository;

    protected CreateApplicationService $createApplicationService;

    public function __construct(
        LoanApplicationRepository $loanApplicationRepository,
        CreateApplicationService $createApplicationService
    ) {
        $this->loanApplicationRepository = $loanApplicationRepository;
        $this->createApplicationService = $createApplicationService;
    }

    /**
     * Retrieve list of loan application from current logged user
     *
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Retrieve specific loan application with ID
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($applicationId)
    {
        try {
            $application = $this->loanApplicationRepository->findById($applicationId);
            $this->authorize('view', $application);

            return ApiResponseHandler::success(new LoanApplicationResource($application));
        } catch (AuthorizationException) {
            return ApiResponseHandler::unauthorized();
        } catch (\Exception $exception) {
            return ApiResponseHandler::exception($exception->getMessage());
        }
    }

    /**
     * Store new loan application
     *
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store(Request $request)
    {
        $data = $request->only(['purpose', 'amount', 'term']);
        [$isInvalid, $errorResponse] = $this->handleValidation($data, [
            'purpose' => 'required',
            'amount' => 'required',
            'term' => 'required',
        ]);
        try {
            if (! $isInvalid) {
                $data['user_id'] = $this->loggedUser()->getAuthIdentifier();
                $application = $this->createApplicationService->execute($data);

                return ApiResponseHandler::success($application);
            }

            return $errorResponse;
        } catch (\Exception $exception) {
            return ApiResponseHandler::exception($exception->getMessage());
        }
    }
}
