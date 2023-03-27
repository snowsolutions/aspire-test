<?php

namespace App\Services\User;

use App\Helpers\Api\AuthenticatedUser;
use Illuminate\Support\Facades\Auth;

class AuthenticationService
{
    private $token = null;

    use AuthenticatedUser;

    public function login($data)
    {
        if (Auth::guard('api')->attempt($data)) {
            $user = $this->loggedUser();
            $this->token = $user->createToken('api')->plainTextToken;

            return true;
        }

        return false;
    }

    public function getToken()
    {
        return $this->token;
    }
}
