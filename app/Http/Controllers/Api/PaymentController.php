<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Api\AuthenticatedUser;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Resources\PaymentCollection;
use App\Http\Controllers\Resources\PaymentResource;
use App\Http\Response\ApiResponseHandler;
use App\Repositories\Payment\PaymentRepository;
use App\Services\Payment\MakePaymentService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected PaymentRepository $paymentRepository;
    protected MakePaymentService $makePaymentService;

    use AuthenticatedUser;
    use ValidatorHandler;

    public function __construct(
        PaymentRepository  $paymentRepository,
        MakePaymentService $makePaymentService
    )
    {
        $this->paymentRepository = $paymentRepository;
        $this->makePaymentService = $makePaymentService;
    }

    /**
     * Retrieve list of payments from current logged user
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $userId = $this->loggedUser()->getAuthIdentifier();
            $data = $this->paymentRepository->findAllByUserId($userId);
            return ApiResponseHandler::success(new PaymentCollection($data));
        } catch (\Exception $exception) {
            return ApiResponseHandler::exception($exception->getMessage());
        }
    }

    /**
     * Retrieve a specific payment with ID
     * @param $paymentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($paymentId)
    {
        try {
            $payment = $this->paymentRepository->findById($paymentId);
            $this->authorize('view', $payment);
            return ApiResponseHandler::success(new PaymentResource($payment));
        } catch (AuthorizationException) {
            return ApiResponseHandler::unauthorized();
        } catch (\Exception $exception) {
            return ApiResponseHandler::exception($exception->getMessage());
        }
    }

    /**
     * Make a payment for scheduled payments
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function makePayment(Request $request)
    {
        $data = $request->only(['id', 'amount']);

        list($isInvalid, $errorResponse) = $this->handleValidation($data, [
            'id' => 'required',
            'amount' => 'required',
        ]);

        try {
            if (!$isInvalid) {
                $payment = $this->paymentRepository->findById($data['id']);
                $this->authorize('update', $payment);
                $result = $this->makePaymentService->execute($payment, $data['amount']);
                return ApiResponseHandler::success($result);
            }
            return $errorResponse;
        } catch (AuthorizationException) {
            return ApiResponseHandler::unauthorized();
        } catch (\Exception $exception) {
            return ApiResponseHandler::exception($exception->getMessage());
        }
    }
}
