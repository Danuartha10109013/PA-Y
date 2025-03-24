<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DokuService;
use App\Models\PinjamanAngunan;
use App\Models\PengajuanPinjaman;
use App\Models\PinjamanEmergency;
use App\Models\HistoryTransaction;
use App\Models\PinjamanNonAngunan;
use Illuminate\Support\Facades\Auth;

class HistoryTransactionController extends Controller
{

    public function index()
    {
        $data = [
            'title' => 'History Transaction',
            'transactions' => HistoryTransaction::all()
        ];
        return view('pages.bendahara.history-transaction', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'History Transaction',
        ];
        return view('pages.bendahara.add-history', $data);
    }

    // public function store(Request $request, DokuService $dokuService)
    // {
    //     // Validasi input
    //     $invoiceNumber = $request->input('nomor_pinjaman');

    //     // Temukan pengajuan pinjaman berdasarkan invoice number
    //     $pengajuanPinjaman = PengajuanPinjaman::where('nomor_pinjaman', $invoiceNumber)->first();

    //     if (!$pengajuanPinjaman) {
    //         return response()->json(['error' => 'Pengajuan pinjaman tidak ditemukan'], 404);
    //     }

    //     // Ambil pengguna yang sedang login
    //     $users = Auth::user();

    //     // Periksa status pembayaran menggunakan DokuService
    //     $status = $dokuService->checkStatus($invoiceNumber);
    //     // dd($status);

    //     // Jika ada error dari Doku, kembalikan pesan error
    //     if (isset($status['error'])) {
    //         return response()->json(['error' => $status['error'], 'message' => $status['message']], 500);
    //     }

    //     // Extract data dari respons Doku
    //     $orderInvoiceNumber = $status['order']['invoice_number'];
    //     $orderAmount = $status['order']['amount'];
    //     $transactionStatus = $status['transaction']['status'];
    //     $acquirerName = $status['acquirer']['name'];

    //     // Simpan riwayat transaksi
    //     HistoryTransaction::create([
    //         'user_id' => $users->id,
    //         'jenis_pinjaman' => $pengajuanPinjaman->jenis_pinjaman,
    //         'nomor_pinjaman' => $invoiceNumber,
    //         'amount' => $pengajuanPinjaman->amount,
    //         // 'status' => $transactionStatus,
    //         'bank' => $acquirerName,
    //     ]);

    //     if ($transactionStatus === 'SUCCESS') {
    //         switch ($pengajuanPinjaman->jenis_pinjaman) {
    //             case 'pinjaman_emergency':
    //                 PinjamanEmergency::create([
    //                     'user_id' => $pengajuanPinjaman->user_id,
    //                     'amount' => $orderAmount,
    //                     'status' => strtolower($transactionStatus),
    //                 ]);
    //                 break;
    //             case 'pinjaman_angunan':
    //                 PinjamanAngunan::create([
    //                     'user_id' => $pengajuanPinjaman->user_id,
    //                     'amount' => $orderAmount,
    //                     'status' => strtolower($transactionStatus),
    //                 ]);
    //                 break;
    //             case 'pinjaman_non_angunan':
    //                 PinjamanNonAngunan::create([
    //                     'user_id' => $pengajuanPinjaman->user_id,
    //                     'amount' => $orderAmount,
    //                     'status' => strtolower($transactionStatus),
    //                 ]);
    //                 break;
    //         }
    //     }


    //     // Redirect dengan pesan sukses
    //     return redirect()->route('status.index')->with('success', 'Data berhasil ditambahkan.');
    // }
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nomor_pinjaman' => 'required|string',
            'payment_proof' => 'required|image|mimes:jpeg,jpg,png|max:2048', // Asumsi payment_proof adalah string (misalnya URL gambar)
        ]);

        // Cari pengajuan pinjaman berdasarkan nomor_pinjaman
        $pengajuanPinjaman = PengajuanPinjaman::where('nomor_pinjaman', $request->nomor_pinjaman)->first();
        $imagePath = null;

        // Jika pengajuan pinjaman tidak ditemukan, kembalikan response error
        if (!$pengajuanPinjaman) {
            return response()->json(['error' => 'Pengajuan pinjaman tidak ditemukan'], 404);
        }

        if ($request->hasFile('payment_proof')) {
            $imagePath = $request->file('payment_proof')->store('bukti-pembayaran');
        }

        // Ambil user yang sedang login
        $user = Auth::user();

        // Simpan riwayat transaksi
        $historyTransaction = HistoryTransaction::create([
            'user_id' => $user->id,
            'nomor_pinjaman' => $pengajuanPinjaman->nomor_pinjaman,
            'jenis_pinjaman' => $pengajuanPinjaman->jenis_pinjaman,
            'amount' => $pengajuanPinjaman->amount,
            'payment_proof' => $imagePath ? "storage/$imagePath" : null,
            'status' => 'success', // Default status
        ]);

        // Proses pemilihan jenis pinjaman dan simpan ke tabel yang sesuai
        switch ($pengajuanPinjaman->jenis_pinjaman) {
            case 'pinjaman_emergency':
                PinjamanEmergency::create([
                    'user_id' => $pengajuanPinjaman->user_id,
                    'amount' => $pengajuanPinjaman->amount,
                    'status' => 'success', // Sesuaikan dengan status yang diinginkan
                ]);
                break;
            case 'pinjaman_angunan':
                PinjamanAngunan::create([
                    'user_id' => $pengajuanPinjaman->user_id,
                    'amount' => $pengajuanPinjaman->amount,
                    'status' => 'success', // Sesuaikan dengan status yang diinginkan
                ]);
                break;
            case 'pinjaman_non_angunan':
                PinjamanNonAngunan::create([
                    'user_id' => $pengajuanPinjaman->user_id,
                    'amount' => $pengajuanPinjaman->amount,
                    'status' => 'success', // Sesuaikan dengan status yang diinginkan
                ]);
                break;
            default:
                // Jika jenis pinjaman tidak sesuai, kembalikan response error
                return response()->json(['error' => 'Jenis pinjaman tidak valid'], 400);
        }

        // Redirect atau kembalikan response sukses
        return redirect()->route('status.index')->with('success', 'Data berhasil ditambahkan.');
    }
}
