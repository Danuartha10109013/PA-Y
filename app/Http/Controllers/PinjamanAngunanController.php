<?php

namespace App\Http\Controllers;

use App\Models\Tenor;
use Illuminate\Http\Request;
use App\Models\VirtualAccount;
use App\Models\PinjamanAngunan;
use Illuminate\Support\Facades\Auth;

class PinjamanAngunanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Data Pinjaman Angunan',
            'pinjamans' => auth()->user()->pinjamanAngunan,
            'tenors' => Tenor::get()->all(),
            'virtualAccounts' => VirtualAccount::get()->all(),
        ];

        return view('pages.anggota.pinjaman.angunan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Form Tambah Pinjaman Dengan Angunan',
            'tenors' => Tenor::get()->all(),
            'angunans' => PinjamanAngunan::ANGUNAN_OPTIONS,
            'new_nmr' => $this->generateNomorPinjaman(),
            'virtualAccounts' => auth()->user()->virtualAccounts,

        ];
        return view('pages.anggota.pinjaman.angunan.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //  dd($request->all());
        $request->validate([
            'nomor_pinjaman' => 'required',
            'nominal' => 'required|numeric',
            'nominal_angsuran' => 'required|numeric',
            'keterangan' => 'required',
            'virtual_account' => 'required',
            'nama_bank' => 'required_if:virtual_account,0',
            'no_rekening' => 'required_if:virtual_account,0',
            'jenis_angungan' => 'required',
            'image' => ['required', 'file', 'image:png,jpg,jpeg', 'max:2048'],

        ]);

        $users = Auth::user();

        $imagePath = null;

        // Periksa apakah ada gambar yang diunggah
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('bukti-upload');
        }

        if ($request->virtual_account == '0') {
            // Buat rekening baru
            $virtualAccount = VirtualAccount::create([
                'user_id' => $users->id,
                'nama_bank' => $request->nama_bank,
                'no_rekening' => $request->no_rekening,
            ]);

            $virtualAccountId = $virtualAccount->id; // Simpan ID virtual account
        } else {
            // Gunakan rekening yang sudah ada, cari berdasarkan uuid
            $virtualAccount = VirtualAccount::where('id', $request->virtual_account)->first();

            if ($virtualAccount) {
                $virtualAccountId = $virtualAccount->id; // Ambil ID dari virtual account
            } else {
                // Handle jika virtual account tidak ditemukan
                return redirect()->back()->with('error', 'Rekening tidak ditemukan.');
            }
        }

        // Buat pinjaman dengan virtual_account_id
        PinjamanAngunan::create([
            'user_id' => $users->id,
            'uuid' => uuid_create(),
            'virtual_account_id' => $virtualAccountId, // Isi kolom virtual_account_id
            'nomor_pinjaman' => $request->nomor_pinjaman,
            'nominal' => $request->nominal,
            'nominal_angsuran' => $request->nominal_angsuran,
            'keterangan' => $request->keterangan,
            'jenis_angungan' => $request->jenis_angungan,
            'image' => $imagePath ? "storage/$imagePath" : null,
        ]);

        return redirect()->route('pinjaman-angunan.index')->with('success', 'Pinjaman Dengan Angunan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PinjamanAngunan $pinjamanAngunan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PinjamanAngunan $pinjamanAngunan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PinjamanAngunan $pinjamanAngunan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PinjamanAngunan $pinjamanAngunan)
    {
        //
    }

    private function generateNomorPinjaman()
    {
        // Format nomor pinjaman: PIN-YYYYMMDD-RANDOM
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 5));
        return "PIN-{$date}-{$random}";
    }
}
