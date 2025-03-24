<?php

namespace App\Http\Controllers;

use App\Models\Simpanan;
use App\Models\SimpananSukarela;
use App\Models\Penarikan;
use App\Models\SimpananBerjangka;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SimpananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function simpananwajib()
    {
        return view('pages.anggota.simpanan.wajib.index', [
            'title' => 'Simpanan Wajib',
        ]);
    }



    public function simpanansukarela()
{
    $userId = Auth::id(); // Mendapatkan ID pengguna yang sedang login

    return view('pages.anggota.simpanan.sukarela.index', [
        'title' => 'Simpanan Sukarela',
        'simpanan_sukarela' => SimpananSukarela::where('user_id', $userId)
            ->orderBy('created_at', 'desc') // Mengurutkan berdasarkan created_at dari terbaru
            ->get(),
    ]);
}

    
    public function simpananberjangka()
{
    $userId = Auth::id(); // Mendapatkan ID pengguna yang sedang login

    return view('pages.anggota.simpanan.berjangka.index', [
        'title' => 'Simpanan Berjangka',
        'simpanan_berjangka' => SimpananBerjangka::where('user_id', $userId)
            ->orderBy('created_at', 'desc') // Mengurutkan berdasarkan created_at dari terbaru
            ->get(),
    ]);
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Simpanan $simpanan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Simpanan $simpanan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Simpanan $simpanan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Simpanan $simpanan)
    {
        //
    }

    public function approveSimpanan(Request $request, $id)
    {
        $simpanan = Simpanan::find($id);

        if (!$simpanan) {
            return redirect()->back()->with('error', 'Data simpanan tidak ditemukan.');
        }

        $simpanan->status = 'Diterima'; // Status setelah di-approve
        $simpanan->save();

        return redirect()->back()->with('success', 'Simpanan berhasil di-approve.');
    }

    public function rejectSimpanan(Request $request, $id)
    {
        $simpanan = Simpanan::find($id);

        if (!$simpanan) {
            return redirect()->back()->with('error', 'Data simpanan tidak ditemukan.');
        }

        $simpanan->status = 'Ditolak'; // Status setelah ditolak
        $simpanan->save();

        return redirect()->back()->with('success', 'Simpanan berhasil ditolak.');
    }

    public function viewPenarikan()
    {
        $user = auth()->user();

        // Ambil data simpanan pertama dari relasi
        $simpanan = $user->simpanans;

        if (!$simpanan) {
            // Jika tidak ada data simpanan, berikan default saldo
            $saldoSimpanan = 0;
        } else {
            // Jika ada, ambil saldo dari data simpanan
            $saldoSimpanan = $simpanan->saldo;
        }

        return view('pages.anggota.simpanan.penarikan', compact('saldoSimpanan'));
    }


    public function ajukanPenarikan(Request $request)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:10000', // Minimum penarikan Rp 10,000
        ]);

        $user = auth()->user();

        // Ambil data simpanan user, cek apakah data ada
        $simpanan = $user->simpanans;

        if (!$simpanan) {
            return redirect()->back()->withErrors('Data simpanan tidak ditemukan. Silakan periksa kembali.');
        }

        $saldoSimpanan = $simpanan->saldo;

        // Validasi jika saldo tidak mencukupi
        if ($request->jumlah > $saldoSimpanan) {
            return redirect()->back()->withErrors('Saldo tidak mencukupi untuk penarikan.');
        }

        // Simpan data penarikan ke database
        Penarikan::create([
            'user_id' => $user->id,
            'jumlah' => $request->jumlah,
            'status' => 'pending',
        ]);

        // Kurangi saldo simpanan
        $simpanan->update([
            'saldo' => $saldoSimpanan - $request->jumlah,
        ]);

        return redirect()->back()->with('success', 'Permintaan penarikan berhasil diajukan.');
    }

    public function verifikasi(Request $request)
    {
        // Validasi input
        $request->validate([
            'jumlah' => 'required|numeric|min:10000', // Minimum penarikan Rp 10,000
        ]);

        // Simpan data sementara ke session untuk diverifikasi
        $jumlahPenarikan = $request->jumlah;
        session(['jumlahPenarikan' => $jumlahPenarikan]);

        // Redirect ke halaman verifikasi
        return view('pages.anggota.simpanan.verifikasi', compact('jumlahPenarikan'));
    }
}
