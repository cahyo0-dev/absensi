<?php
// app/Models/Absensi.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensis';

    protected $fillable = [
        'nip',
        'nama',
        'jabatan',
        'unit_kerja',
        'provinsi',
        'tanda_tangan',
    ];
}