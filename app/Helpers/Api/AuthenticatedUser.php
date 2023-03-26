<?php

namespace App\Helpers\Api;

use Illuminate\Support\Facades\Auth;

trait AuthenticatedUser
{
    /**
     * Retrieve logged user from specific guard
     * @param $guard
     * @return \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function loggedUser($guard = null)
    {
        if (!is_null($guard)) {
            return Auth::guard($guard)->user();
        }
        return Auth::user();
    }
}
