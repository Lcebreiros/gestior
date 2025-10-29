<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    // Constantes de jerarquía (para compatibilidad con la BD compartida)
    public const HIERARCHY_MASTER  = -1;
    public const HIERARCHY_COMPANY = 0;
    public const HIERARCHY_ADMIN   = 1;
    public const HIERARCHY_USER    = 2;

    protected $fillable = [
        'name',
        'email',
        'password',
        'parent_id',
        'hierarchy_level',
        'hierarchy_path',
        'is_active',
        'subscription_level',
        'organization_context',
        'branch_limit',
        'user_limit',
        'representable_id',
        'representable_type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'hierarchy_level' => 'integer',
            'user_limit' => 'integer',
            'branch_limit' => 'integer',
        ];
    }

    // Métodos básicos de jerarquía (para compatibilidad)
    public function isMaster(): bool 
    { 
        return $this->hierarchy_level === self::HIERARCHY_MASTER; 
    }

    public function isCompany(): bool 
    { 
        return $this->hierarchy_level === self::HIERARCHY_COMPANY; 
    }

    public function isAdmin(): bool 
    { 
        return $this->hierarchy_level === self::HIERARCHY_ADMIN; 
    }

    public function isRegularUser(): bool 
    { 
        return $this->hierarchy_level === self::HIERARCHY_USER; 
    }
}