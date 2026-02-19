<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrialRequest extends Model
{
    protected $fillable = [
        'name',
        'email',
        'plan',
        'business_type',
        'password',
        'status',
        'user_id',
        'notes',
        'approved_by',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
    ];

    public function getPlanNameAttribute(): string
    {
        return match($this->plan) {
            'basic' => 'Básico',
            'premium' => 'Premium',
            'enterprise' => 'Enterprise',
            default => $this->plan,
        };
    }
}
