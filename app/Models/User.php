<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
            'role' => \App\Enums\RoleEnum::class,
            'experience_level' => \App\Enums\ExperienceLevelEnum::class,
        ];
    }

    /**
     * Get the parcels for the user.
     */
    public function parcels(): HasMany
    {
        return $this->hasMany(Parcel::class);
    }

    /**
     * Get the interaction IAs for the user.
     */
    public function interactionIas(): HasMany
    {
        return $this->hasMany(InteractionIA::class);
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === RoleEnum::ADMIN->value;
    }

    /**
     * Check if user is an agriculteur.
     */
    public function isAgriculteur(): bool
    {
        return $this->role === RoleEnum::AGRICULTEUR->value;
    }

    /**
     * Check if user is a technicien.
     */
    public function isTechnicien(): bool
    {
        return $this->role === RoleEnum::TECHNICIEN->value;
    }
}
