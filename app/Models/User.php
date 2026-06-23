<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
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
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole($role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole($roles): bool
    {
        return in_array($this->role, (array) $roles);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Get price histories changed by this user
     */
    public function priceHistories()
    {
        return $this->hasMany(PriceHistory::class, 'changed_by');
    }

    /**
     * Get shifts opened by this user
     */
    public function shiftsOpened()
    {
        return $this->hasMany(Shift::class, 'opened_by');
    }

    /**
     * Get shifts closed by this user
     */
    public function shiftsClosed()
    {
        return $this->hasMany(Shift::class, 'closed_by');
    }

    /**
     * Get sales recorded by this attendant
     */
    public function sales()
    {
        return $this->hasMany(Sale::class, 'attendant_id');
    }

    /**
     * Get shift pump readings assigned to this attendant
     */
    public function shiftPumpReadings()
    {
        return $this->hasMany(ShiftPumpReading::class, 'attendant_id');
    }
}
