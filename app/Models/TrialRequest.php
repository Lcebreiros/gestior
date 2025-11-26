<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrialRequest extends Model
{
    protected $fillable = [
        'name',
        'email',
        'plan',
        'status',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
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
