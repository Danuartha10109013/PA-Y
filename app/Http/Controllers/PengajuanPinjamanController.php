<?php

namespace App\Http\Controllers;

use App\Models\Tenor;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\DokuService;
use App\Models\VirtualAccount;
use App\Models\PinjamanAngunan;
use App\Models\PengajuanPinjaman;
use App\Models\PinjamanEmergency;
use App\Models\SalaryStatus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PengajuanPinjamanController extends Controller
{
    public function index()
    {
        return view('pages.anggota.pinjaman.index', [
            'pinjamans' => PengajuanPinjaman::all(),
            'title' => 'Anggota | Data Pengajuan Pinjaman'
        ]);
    }

    public function createEmergency()
    {
        $data = [
            'title' => 'Anggota | Form Tambah Pinjaman Emergency',
            'tenors' => Tenor::get()->all(),
            'virtualAccounts' => VirtualAccount::get()->all(),

        ];
        return view('pages.anggota.pinjaman.emergency.add', $data);
    }

    public function createAngunan()
    {
        $data = [
            'title' => 'Anggota | Form Tambah Pinjaman Emergency',
            'virtualAccounts' => VirtualAccount::get()->all(),

        ];
        return view('pages.anggota.pinjaman.angunan.add', $data);
    }

    public function createNonAngunan()
    {
        $data = [
            'title' => 'Anggota | Form Tambah Pinjaman Emergency',
            'virtualAccounts' => VirtualAccount::get()->all(),

        ];
        return view('pages.anggota.pinjaman.nonangunan.add', $data);
    }

    public function store(Request $request, DokuService $dokuService)
    {
        $userId = Auth::id();
        $invoiceNumber = $this->generateNomorPinjaman();
        $amount = (float) str_replace([',', 'Rp'], '', $request->input('amount'));
        $jangkaWaktu = (int) $request->input('jangka_waktu');
        $totalPinjaman = $amount * (1 + (0.1 * $jangkaWaktu));
        $nominalAngsuran = ceil($totalPinjaman / $jangkaWaktu);

        $request->validate([
            'amount' => ['required', 'numeric'],
            'nominal_angsuran' => 'required|numeric',
            'jangka_waktu' => 'required|integer|min:1|max:36',
            'keterangan' => 'required',
            'virtual_account' => 'required',
            'jenis_angunan' => 'nullable',
            'jenis_pinjaman' => 'required|string',
            'path' => 'required_if:virtual_account,0',
        ]);

        if ($request->virtual_account == '0') {
            $virtualAccountResponse = $dokuService->generateVirtualAccount($amount, $invoiceNumber, $request->path, substr(Str::random(12), 0, 12));

            if (isset($virtualAccountResponse['error'])) {
                return redirect()->back()->with('error', $virtualAccountResponse['error']);
            }

            $virtualAccountId = VirtualAccount::create([
                'user_id' => $userId,
                'virtual_account_number' => $virtualAccountResponse['virtual_account_info']['virtual_account_number'],
            ])->id;
        } else {
            $virtualAccountId = $request->virtual_account;
        }

        $pengajuanPinjaman = PengajuanPinjaman::create([
            'user_id' => $userId,
            'uuid' => uuid_create(),
            'amount' => $amount,
            'sisa_pinjaman' => $amount,
            'sisa_jangka_waktu' => $jangkaWaktu,
            'nomor_pinjaman' => $invoiceNumber,
            'nominal_angsuran' => $nominalAngsuran,
            'jangka_waktu' => $jangkaWaktu,
            'jenis_pinjaman' => $request->jenis_pinjaman,
            'keterangan' => $request->keterangan,
            'virtual_account_id' => $virtualAccountId,
            'jenis_angunan' => $request->jenis_angunan,
        ]);

        // Hitung dan simpan hasil SPK
        // $this->applySPK($pengajuanPinjaman);

        return redirect()->back()->with('success', 'Pinjaman berhasil ditambahkan!');
    }


    public function show(PengajuanPinjaman $pengajuanPinjaman)
    {
        $pinjamans = PengajuanPinjaman::with(['user', 'virtualAccount'])->get();
        $title = 'Data Pengajuan Pinjaman';
        return view('pages.show.pinjaman', compact('pinjamans', 'title'));
    }

    private function generateNomorPinjaman()
    {
        // Format nomor pinjaman: PIN-YYYYMMDD-RANDOM
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 5));
        return "PIN-{$date}-{$random}";
    }

    private function calculateNominalAngsuran($amount, $jangkaWaktu)
    {
        $bungaPerTahun = 0.1;
        $bungaPerBulan = $bungaPerTahun / 12;
        $totalBunga = $amount * $bungaPerBulan * $jangkaWaktu;
        $totalPinjamanDenganBunga = $amount + $totalBunga;
        $angsuranPerBulan = $totalPinjamanDenganBunga / $jangkaWaktu;
        $angsuranPerBulan = ceil($angsuranPerBulan);
        return $angsuranPerBulan;
    }

    // private function applySPK($pengajuanPinjaman)
    // {
    //     // Step 1: Define criteria, weights, and type
    //     $weights = [
    //         'gaji' => 0.5,
    //         'amount' => 0.3,
    //         'jangka_waktu' => 0.2,
    //     ];

    //     $criteriaType = [
    //         'gaji' => 'benefit',
    //         'amount' => 'cost',
    //         'jangka_waktu' => 'cost',
    //     ];

    //     // Step 2: Define rating scale
    //     $rating = function ($value, $type) {
    //         if ($type === 'gaji') {
    //             return $value > 10000000 ? 5 : ($value > 5000000 ? 4 : ($value > 3000000 ? 3 : ($value > 2000000 ? 2 : 1)));
    //         } elseif ($type === 'amount') {
    //             return $value <= 10000000 ? 5 : ($value <= 25000000 ? 4 : ($value <= 50000000 ? 3 : ($value <= 75000000 ? 2 : 1)));
    //         } elseif ($type === 'jangka_waktu') {
    //             return $value <= 6 ? 5 : ($value <= 12 ? 4 : ($value <= 24 ? 3 : ($value <= 36 ? 2 : 1)));
    //         }
    //     };

    //     // Step 3: Build decision matrix
    //     $decisionMatrix = [
    //         'gaji' => $rating($pengajuanPinjaman->gaji, 'gaji'),
    //         'amount' => $rating($pengajuanPinjaman->amount, 'amount'),
    //         'jangka_waktu' => $rating($pengajuanPinjaman->jangka_waktu, 'jangka_waktu'),
    //     ];

    //     // Step 4: Normalize matrix
    //     $normalizedMatrix = [];
    //     foreach (['gaji', 'amount', 'jangka_waktu'] as $key) {
    //         $max = $decisionMatrix[$key];
    //         $min = $decisionMatrix[$key];

    //         if ($criteriaType[$key] === 'benefit') {
    //             $normalizedMatrix[$key] = $decisionMatrix[$key] / max($max, 1);
    //         } else {
    //             $normalizedMatrix[$key] = min($min, 1) / $decisionMatrix[$key];
    //         }
    //     }

    //     // Step 5: Calculate score
    //     $score = ($weights['gaji'] * $normalizedMatrix['gaji']) +
    //         ($weights['amount'] * $normalizedMatrix['amount']) +
    //         ($weights['jangka_waktu'] * $normalizedMatrix['jangka_waktu']);

    //     // Step 6: Determine level
    //     $level = $score >= 0.8 ? 'Level 1 (Bagus)' : ($score >= 0.5 ? 'Level 2 (Cukup Bagus)' : 'Level 3 (Buruk)');

    //     // Step 7: Update pengajuan pinjaman
    //     $pengajuanPinjaman->update([
    //         'score' => $score,
    //         'level' => $level,
    //     ]);
    // }
}
