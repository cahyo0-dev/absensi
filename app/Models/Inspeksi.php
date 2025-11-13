<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}