<?php

namespace App\Services\Admin;

use App\Helpers\Api\AuthenticatedUser;
use Illuminate\Support\Facades\Auth;

class AuthenticationService
{
    private $token = null;

    use AuthenticatedUser;

    public function login($data)
    {
        if (Auth::guard('admin')->attempt($data)) {
            $user = $this->loggedUser('admin');
            $this->token = $user->createToken('admin')->plainTextToken;
            return true;
        }
        return false;
    }

    public function getToken()
    {
        return $this->token;
    }
}
