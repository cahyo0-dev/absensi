<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nip',
        'name',
        'jabatan',
        'unit_kerja',
        'provinsi',
        'role',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Helper methods untuk check role
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPengawas(): bool
    {
        return $this->role === 'pengawas';
    }

    // Scope untuk filter role
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopePengawas($query)
    {
        return $query->where('role', 'pengawas');
    }

    public static function totalPengawas()
    {
        return self::where('role', 'pengawas')->count();
    }
}