<?php
// app/Models/KategoriInspeksi.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriInspeksi extends Model
{
    use HasFactory;

    protected $table = 'kategori_inspeksis';

    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    public function pertanyaan()
    {
        return $this->hasMany(Pertanyaan::class, 'kategori_id');
    }

    public function inspeksis()
    {
        return $this->hasMany(Inspeksi::class, 'kategori_id');
    }
}