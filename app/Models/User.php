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
        'jabatan',       // = position
        'unit_kerja',    // = department  
        'provinsi',
        'role',
        'email',
        'password',
        'profile_photo', // baru
        'phone',         // baru
        'settings',      // baru
        'last_login_at'  // baru
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
            'last_login_at' => 'datetime', // baru
            'settings' => 'array',         // baru
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

    // Accessor untuk settings dengan default values
    public function getSettingsAttribute($value)
    {
        $defaultSettings = [
            'language' => 'id',
            'timezone' => 'Asia/Jakarta',
            'date_format' => 'd/m/Y',
            'records_per_page' => 25,
            'notifications' => [
                'email' => true,
                'sms' => false,
                'push' => true,
                'reports' => true,
                'security' => true,
            ],
            'security' => [
                'two_factor' => false,
                'session_timeout' => 60,
                'login_alerts' => true,
                'password_expiry' => 90,
            ],
            'theme' => [
                'color' => 'blue',
                'density' => 'comfortable',
                'dark_mode' => false,
            ]
        ];

        if ($value) {
            $decodedValue = json_decode($value, true);
            // Deep merge untuk nested arrays
            $mergedSettings = $this->arrayMergeRecursiveDistinct($defaultSettings, $decodedValue);
            return $mergedSettings;
        }

        return $defaultSettings;
    }

    // Method untuk update settings
    public function updateSettings(array $newSettings)
    {
        $currentSettings = $this->settings;
        $updatedSettings = $this->arrayMergeRecursiveDistinct($currentSettings, $newSettings);
        $this->settings = $updatedSettings;
        return $this->save();
    }

    /**
     * Deep merge arrays
     */
    private function arrayMergeRecursiveDistinct(array &$array1, array &$array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = $this->arrayMergeRecursiveDistinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
