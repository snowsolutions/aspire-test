<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    /**
     * User can only view their Payment
     */
    public function view(User $user, Payment $payment): bool
    {
        return $user->id == $payment->user_id;
    }

    /**
     * User can only update their Payment
     */
    public function update(User $user, Payment $payment): bool
    {
        return $user->id == $payment->user_id;
    }
}
