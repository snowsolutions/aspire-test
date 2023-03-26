<?php

namespace App\Policies;

use App\Models\LoanApplication;
use App\Models\User;

class LoanApplicationPolicy
{
    /**
     * User can only view their Loan Application
     * @param User $user
     * @param LoanApplication $loanApplication
     * @return bool
     */
    public function view(User $user, LoanApplication $loanApplication): bool
    {
        return $user->id == $loanApplication->user_id;
    }
}
