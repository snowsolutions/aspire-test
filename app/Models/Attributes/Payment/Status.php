<?php

namespace App\Models\Attributes\Payment;

enum Status
{
    case PENDING;
    case PAID;
}
