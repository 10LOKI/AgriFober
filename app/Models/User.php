<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\RoleEnum;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'role',
        'region',
        'experience_level',
        'surface_totale',
        'employee_code',
        'last_audit_at',
        'is_approved',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'surface_totale' => 'decimal:4',
            'last_audit_at' => 'datetime',
            'is_approved' => 'boolean',
            'role' => \App\Enums\RoleEnum::class,
            'experience_level' => \App\Enums\ExperienceLevelEnum::class,
        ];
    }

    public function parcels(): HasMany
    {
        return $this->hasMany(Parcel::class);
    }

    public function interactionIas(): HasMany
    {
        return $this->hasMany(InteractionIA::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === RoleEnum::ADMIN;
    }

    public function isAgriculteur(): bool
    {
        return $this->role === RoleEnum::AGRICULTEUR;
    }

    public function isTechnicien(): bool
    {
        return $this->role === RoleEnum::TECHNICIEN;
    }
}
