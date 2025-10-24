<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Inspeksi extends Model
{
    use HasFactory;

    protected $table = 'inspeksis';

    protected $fillable = [
        'pengawas_id',
        'kategori_id',
        'tanda_tangan',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function pengawas()
    {
        return $this->belongsTo(User::class, 'pengawas_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriInspeksi::class, 'kategori_id');
    }

    public function jawaban()
    {
        return $this->hasMany(Jawaban::class, 'inspeksi_id');
    }

    /**
     * Cek apakah sudah ada inspeksi hari ini (GLOBAL)
     */
    public static function sudahInspeksiHariIni()
    {
        return self::whereDate('created_at', Carbon::today())->exists();
    }

    /**
     * Ambil data inspeksi hari ini
     */
    public static function getInspeksiHariIni()
    {
        return self::with('pengawas')
            ->whereDate('created_at', Carbon::today())
            ->first();
    }

    /**
     * Ambil semua inspeksi dengan pagination
     */
    public static function getAllInspeksiPaginated($perPage = 10)
    {
        return self::with(['kategori', 'jawaban'])
            ->orderBy('tanggal', 'desc')
            ->paginate($perPage);
    }

    /**
     * Scope untuk inspeksi bulan ini
     */
    public function scopeBulanIni($query)
    {
        return $query->whereMonth('created_at', now()->month);
    }
}