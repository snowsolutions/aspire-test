<?php

namespace App\Helpers\Api;

use Illuminate\Support\Facades\Auth;

trait AuthenticatedUser
{
    public function loggedUser($guard = null)
    {
        if (!is_null($guard)) {
            return Auth::guard($guard)->user();
        }
        return Auth::user();
    }
}
