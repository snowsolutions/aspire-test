<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Api\AuthenticatedUser;
use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponseHandler;
use App\Services\User\AuthenticationService;
use Illuminate\Http\Request;

class AuthenticateController extends Controller
{
    use ValidatorHandler;
    use AuthenticatedUser;

    protected AuthenticationService $authenticationService;

    public function __construct(
        AuthenticationService $authenticationService
    ) {
        $this->authenticationService = $authenticationService;
    }

    /**
     * Retrieve user info
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function info()
    {
        $userData = $this->loggedUser();

        return ApiResponseHandler::success($userData);
    }

    /**
     * Authorize the login credentials
     *
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function login(Request $request)
    {
        $loginData = $request->only(['email', 'password']);

        [$isInvalid, $errorResponse] = $this->handleValidation($loginData, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            if (! $isInvalid) {
                if ($this->authenticationService->login($loginData)) {
                    return ApiResponseHandler::success([
                        'token' => $this->authenticationService->getToken(),
                    ]);
                }

                return ApiResponseHandler::errors(422, __('Incorrect email or password'));
            }

            return $errorResponse;
        } catch (\Exception $exception) {
            return ApiResponseHandler::exception($exception->getMessage());
        }
    }
}
