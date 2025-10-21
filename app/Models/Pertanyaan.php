<?php
// app/Models/Pertanyaan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    use HasFactory;

    protected $table = 'pertanyaan';

    protected $fillable = [
        'kategori_id',
        'pertanyaan',
        'tipe', // 'ya_tidak' untuk checkbox Ya/Tidak
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriInspeksi::class, 'kategori_id');
    }

    public function jawaban()
    {
        return $this->hasMany(Jawaban::class, 'pertanyaan_id');
    }
}