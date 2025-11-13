<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SOPController extends Controller
{
    public function index()
    {
        return view('sop.index');
    }
}