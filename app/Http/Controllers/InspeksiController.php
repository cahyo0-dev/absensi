<?php
// app/Http/Controllers/InspeksiController.php

namespace App\Http\Controllers;

use App\Models\KategoriInspeksi;
use Illuminate\Http\Request;

class InspeksiController extends Controller
{
    public function create()
    {
        $kategories = KategoriInspeksi::all();
        
        return view('pengawas.inspeksi', compact('kategories'));
    }
    
    // method lainnya...
}