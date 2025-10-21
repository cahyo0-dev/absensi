<?php
// app/Models/JawabanInspeksi.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{
    use HasFactory;

    protected $table = 'jawabans';

    protected $fillable = [
        'inspeksi_id',
        'pertanyaan_id',
        'jawaban',
    ];

    public function inspeksi()
    {
        return $this->belongsTo(Inspeksi::class, 'inspeksi_id');
    }

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id');
    }
}