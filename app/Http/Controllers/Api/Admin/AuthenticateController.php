<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\Api\AuthenticatedUser;
use App\Http\Controllers\Api\ValidatorHandler;
use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponseHandler;
use App\Services\Admin\AuthenticationService;
use Illuminate\Http\Request;

class AuthenticateController extends Controller
{
    use ValidatorHandler;
    use AuthenticatedUser;

    protected AuthenticationService $authenticationService;

    public function __construct(
        AuthenticationService $authenticationService
    )
    {
        $this->authenticationService = $authenticationService;
    }

    /**
     * Retrieve admin user info
     * @return \Illuminate\Http\JsonResponse
     */
    public function info()
    {
        $userData = $this->loggedUser();
        return ApiResponseHandler::success($userData);
    }

    /**
     * Authorize the login credentials
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $loginData = $request->only(['email', 'password']);


        $this->handleValidation($loginData, [
            'email' => 'required|email',
            'password' => 'required',
        ]);


        try {
            if ($this->authenticationService->login($loginData)) {
                return ApiResponseHandler::success([
                    'token' => $this->authenticationService->getToken()
                ]);
            }
            return ApiResponseHandler::errors(422, __('Incorrect email or password for admin account'));
        } catch (\Exception $exception) {
            return ApiResponseHandler::exception($exception->getMessage());
        }
    }
}
