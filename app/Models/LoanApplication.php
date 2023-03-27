<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoanApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'purpose',
        'amount',
        'remaining_amount',
        'user_id',
        'term',
    ];

    protected $with = [
        'payment',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function payment(): HasMany
    {
        return $this->hasMany(Payment::class, 'loan_application_id', 'id');
    }
}
