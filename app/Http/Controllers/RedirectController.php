<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectController extends Controller
{
    public function redirect()
    {
        // Ambil role user yang sedang login
        $role = Auth::user()->roles;

        // Redirect berdasarkan role
        switch ($role) {
            case 'bendahara':
                return redirect()->route('bendahara.index');
            case 'anggota':
                return redirect()->route('home-anggota');
            case 'manager':
                return redirect()->route('home.manager');
            case 'ketua':
                return redirect()->route('home-ketua');
            case 'admin':
                return redirect()->route('home-admin');
            default:
                // Jika tidak ada role yang sesuai, redirect ke halaman utama
                return redirect()->route('home');
        }
    }
}
