<?php

namespace App\Services\Admin;

use App\Helpers\Api\AuthenticatedUser;
use Illuminate\Support\Facades\Auth;

class AuthenticationService
{
    private $token = null;

    /**
     * Attempt to login in with credentials
     * @param $data
     * @return bool
     */
    public function login($data)
    {
        if (Auth::guard('admin')->attempt($data)) {
            $user = Auth::guard('admin')->user();
            $this->token = $user->createToken('admin')->plainTextToken;
            return true;
        }
        return false;
    }

    /**
     * Retrieve the token
     * @return null
     */
    public function getToken()
    {
        return $this->token;
    }
}
