<?php

namespace App\Http\Controllers;

use App\Imports\SalaryStatusImport;
use App\Models\PengajuanPinjaman;
use App\Models\SalaryStatus;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SalaryStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pinjamanAktif = PengajuanPinjaman::orderBy('created_at', 'desc')
            ->get();

        return view('pages.admin.gaji.index', [
            'title' => 'Data Potongan Gaji',
            'pinjamanAktif' => $pinjamanAktif,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($uuid)
    {
        $pinjaman = PengajuanPinjaman::where('uuid', $uuid)->firstOrFail();

        return view('pages.admin.gaji.import', [
            'title' => 'Import Data Potongan Gaji',
            'pinjamanAktif' => $pinjaman,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $uuid)
    {
        $pengajuanPinjaman = PengajuanPinjaman::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $currentMonth = now()->format('Y-m');
        $existingPayment = SalaryStatus::where('pengajuan_pinjamans_id', $pengajuanPinjaman->id)
            ->where('created_at', 'like', "$currentMonth%")
            ->first();

        if ($existingPayment) {
            return redirect()->back()->with('error', 'Pembayaran untuk bulan ini sudah dilakukan.');
        }

        $buktiPath = $request->file('bukti_pembayaran')->store('bukti-pembayaran');

        $pengajuanPinjaman->sisa_pinjaman -= $pengajuanPinjaman->nominal_angsuran;
        $pengajuanPinjaman->sisa_jangka_waktu -= 1;

        $pengajuanPinjaman->status_pembayaran = $pengajuanPinjaman->sisa_pinjaman <= 0
            ? 'Lunas'
            : 'Aktif';

        if ($pengajuanPinjaman->sisa_pinjaman <= 0) {
            $pengajuanPinjaman->sisa_pinjaman = 0;
        }

        $pengajuanPinjaman->save();

        SalaryStatus::create([
            'pengajuan_pinjamans_id' => $pengajuanPinjaman->id,
            'jumlah_pembayaran' => $pengajuanPinjaman->nominal_angsuran,
            'bukti_pembayaran' => "storage/$buktiPath",
            'status' => 'sukses',
        ]);

        return redirect()->route('data.potongan.gaji')->with('success', 'Bukti pembayaran berhasil diunggah dan data telah diperbarui.');
    }

    /**
     * Display the specified resource.
     */
    public function show($uuid)
    {
        // Ambil data pengajuan pinjaman berdasarkan UUID
        $pengajuanPinjaman = PengajuanPinjaman::where('uuid', $uuid)->firstOrFail();

        // Ambil riwayat pembayaran terkait pinjaman tersebut
        $salaryHistory = SalaryStatus::where('pengajuan_pinjamans_id', $pengajuanPinjaman->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Kirim data ke view
        return view('pages.admin.gaji.show', [
            'title' => 'Riwayat Potongan Gaji Anggota',
            'pengajuanPinjaman' => $pengajuanPinjaman,
            'salaryHistory' => $salaryHistory,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalaryStatus $salaryStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalaryStatus $salaryStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalaryStatus $salaryStatus)
    {
        //
    }
}
