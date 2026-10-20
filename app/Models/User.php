<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'nip',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
        ];
    }

    /**
     * Cek apakah user adalah Super Admin (dibuat fleksibel terhadap variasi string database).
     */
    public function isSuperAdmin(): bool
    {
        // Normalisasi string role menjadi huruf kecil dan hilangkan spasi
        $role = strtolower(trim($this->role ?? ''));
        
        return in_array($role, ['super_admin', 'superadmin', 'super-admin']);
    }

    /**
     * Cek apakah user adalah Admin biasa.
     */
    public function isAdmin(): bool
    {
        $role = strtolower(trim($this->role ?? ''));
        
        return in_array($role, ['admin', 'administrator']);
    }
}