<?php

namespace App\Models\Attributes\LoanApplication;
enum Status
{
    case PENDING;
    case APPROVED;
    case PAID;
}
