<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    public function verifikasi()
    {
        return view('auth.verifikasi_regis');
    }
}
