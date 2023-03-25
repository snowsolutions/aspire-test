<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'loan_application_id',
        'user_id'
    ];

    public function application() : BelongsTo
    {
        return $this->belongsTo(LoanApplication::class, 'loan_application_id', 'id');
    }

}
