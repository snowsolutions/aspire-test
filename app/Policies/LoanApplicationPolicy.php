<?php

namespace App\Policies;

use App\Models\LoanApplication;
use App\Models\User;

class LoanApplicationPolicy
{
    /**
     * User can only view their Loan Application
     */
    public function view(User $user, LoanApplication $loanApplication): bool
    {
        return $user->id == $loanApplication->user_id;
    }
}
