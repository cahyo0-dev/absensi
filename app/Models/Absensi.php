<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip',
        'nama',
        'jabatan',
        'unit_kerja',
        'provinsi',
        'waktu_masuk',
        'waktu_pulang',
        'tanda_tangan_masuk', // PASTIKAN INI ADA
        'tanda_tangan_pulang', // PASTIKAN INI ADA
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'waktu_masuk' => 'datetime',
        'waktu_pulang' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship dengan User jika perlu
    public function user()
    {
        return $this->belongsTo(User::class, 'nip', 'nip');
    }

    // Scope untuk absensi hari ini
    public function scopeHariIni($query, $nip)
    {
        return $query->where('nip', $nip)
            ->whereDate('created_at', today());
    }

    // Accessor untuk status
    public function getStatusAttribute()
    {
        if ($this->waktu_masuk && $this->waktu_pulang) {
            return 'pulang';
        } elseif ($this->waktu_masuk) {
            return 'masuk';
        }
        return 'belum_absen';
    }
}
