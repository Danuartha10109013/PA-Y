<?php

namespace App\Http\Controllers;

use App\Models\Simpanan;
use App\Models\Penarikan;
use Illuminate\Http\Request;

class PenarikanController extends Controller
{
    public function viewPenarikan()
    {
        $user = auth()->user();
        $simpanan = $user->simpanans;

        // Ambil saldo simpanan pertama atau default ke 0 jika tidak ada
        $saldoSimpanan = $simpanan ? $simpanan->saldo : 0;

        return view('pages.anggota.simpanan.penarikan_simpanan', compact('saldoSimpanan'));
    }

    public function ajukanPenarikan(Request $request)
    {
        $user = auth()->user();
        $simpanan = $user->simpanans;

        // Validasi jumlah dan keberadaan simpanan
        $request->validate([
            'jumlah' => 'required|numeric|min:10000',
        ], [
            'jumlah.required' => 'Jumlah penarikan harus diisi.',
            'jumlah.numeric' => 'Jumlah penarikan harus berupa angka.',
            'jumlah.min' => 'Jumlah penarikan minimal adalah Rp 10,000.',
        ]);

        if (!$simpanan) {
            return redirect()->back()->withErrors('Data simpanan tidak ditemukan.');
        }

        $saldoSimpanan = $simpanan->saldo;

        // Validasi saldo
        if ($request->jumlah > $saldoSimpanan) {
            return redirect()->back()->withErrors('Saldo tidak mencukupi untuk penarikan.');
        }

        // Proses penarikan dan pengurangan saldo
        $simpanan->update(['saldo' => $saldoSimpanan - $request->jumlah]);

        Penarikan::create([
            'user_id' => $user->id,
            'jumlah' => $request->jumlah,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Permintaan penarikan berhasil diajukan.');
    }

    public function verifikasi(Request $request)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:10000',
        ]);

        // Simpan jumlah untuk verifikasi ke session
        session(['jumlahPenarikan' => $request->jumlah]);

        return view('pages.anggota.simpanan.verifikasi', ['jumlahPenarikan' => $request->jumlah]);
    }
}
