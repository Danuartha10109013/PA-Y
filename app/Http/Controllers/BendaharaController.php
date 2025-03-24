<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tenor;
use App\Models\Anggota;
use Illuminate\Http\Request;
use App\Models\VirtualAccount;
use App\Mail\RejectNotification;
use App\Models\SimpananSukarela;
use App\Models\PenarikanSukarela;
use App\Models\PengajuanPinjaman;
use App\Models\SimpananBerjangka;
use App\Models\PenarikanBerjangka;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\LoanAndStatusService;
use App\Models\RekeningSimpananSukarela;
use App\Models\RekeningSimpananBerjangka;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BendaharaController extends Controller
{
    protected $loanService;

    public function __construct(LoanAndStatusService $loanService)
    {
        $this->loanService = $loanService;
    }
    /**
     * Display a listing of the resource.
     */
    public function indexanggota()
    {
        // Ambil data anggota dengan filter tertentu
        $anggota = Anggota::whereIn('status_manager', ['Diterima', 'Ditolak'])
            ->get();

        // Catat data anggota yang difilter ke dalam log
        Log::info('Data Anggota:', ['data' => $anggota]);

        // Kirimkan data ke view
        return view('pages.bendahara.approve_regis_bendahara', [
            'title' => 'Data Approve Registrasi',
            'anggota' => $anggota,
        ]);
    }


    public function dataAnggota()
    {
        $anggota = Anggota::orderBy('created_at', 'desc')->get();

        return view('pages.bendahara.data_anggota_bendahara', [
            'title' => 'Data Anggota',
            'anggota' => $anggota

        ]);
    }


    public function updateStatus($id, $status)
    {
        try {
            // Temukan anggota berdasarkan ID
            $anggota = Anggota::findOrFail($id);

            // Update status anggota berdasarkan parameter $status
            $anggota->status_bendahara = $status;

            if ($status === 'Ditolak') {
                // Jika status ditolak oleh manager, maka status bendahara dan ketua juga menjadi Ditolak
                $anggota->status_bendahara = 'Ditolak';
                $anggota->status_ketua = 'Ditolak';
            }

            $anggota->save();

            return response()->json(['message' => 'Status updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update status!', 'error' => $e->getMessage()], 500);
        }
    }

    public function index()
    {

        $dataAnggotas = Anggota::whereIn('status_ketua', ['Pengajuan', 'Diterima', 'Ditolak'])
            ->select('status_ketua')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('status_ketua')
            ->pluck('total', 'status_ketua');

        // Pastikan nilai default untuk menghindari undefined key
        $anggotas = [
            'pengajuan' => $dataAnggotas['Pengajuan'] ?? 0,
            'diterima' => $dataAnggotas['Diterima'] ?? 0,
            'ditolak' => $dataAnggotas['Ditolak'] ?? 0,
        ];

        // // Ambil data jumlah pinjaman berdasarkan status bendahara
        // $dataCounts = PengajuanPinjaman::whereIn('status_bendahara', ['Pengajuan', 'Diterima', 'Ditolak'])
        //     ->selectRaw('status_bendahara, COUNT(*) as total')
        //     ->groupBy('status_bendahara')
        //     ->pluck('total', 'status_bendahara');

        // // Format data jumlah diterima dan ditolak dengan nilai default jika tidak ada
        // $counts = [
        //     'pengajuan' => $dataCounts->get('Pengajuan', 0),
        //     'diterima' => $dataCounts->get('Diterima', 0),
        //     'ditolak' => $dataCounts->get('Ditolak', 0),
        // ];
        $emergencyLoans = $this->loanService->getEmergencyLoans();
        $angunanLoans = $this->loanService->getAngunanLoans();
        $nonangunanLoans = $this->loanService->getNonAngunanLoans();
        $totalLoans = $this->loanService->getTotalPinjaman();
        $totalSimpanans = $this->loanService->getTotalSimpanan();
        $totalOutcomes = $this->loanService->getTotalOutcome();

        // Siapkan data untuk komponen
        $emergencies = [
            [
                'label' => 'Pinjaman Darurat',
                'value' => number_format($emergencyLoans, 0, ',', '.'),
                'color' => 'text-danger',
                'suffix' => 'Rp',
            ],
        ];
        $angunans = [
            [
                'label' => 'Pinjaman Angunan',
                'value' => number_format($angunanLoans, 0, ',', '.'),
                'color' => 'text-info',
                'suffix' => 'Rp',
            ],
        ];
        $nonangunans = [
            [
                'label' => 'Pinjaman Angunan',
                'value' => number_format($nonangunanLoans, 0, ',', '.'),
                'color' => 'text-info',
                'suffix' => 'Rp',
            ],
        ];
        $totalLoans = [
            [
                'label' => 'Total Pinjaman',
                'value' => number_format($totalLoans, 0, ',', '.'),
                'color' => 'text-success',
                'suffix' => 'Rp',
            ],
        ];

        $simpanan = [
            [
                'label' => 'Total Simpanan',
                'value' => number_format($totalSimpanans, 0, ',', '.'),
                'color' => 'text-success',
                'suffix' => 'Rp',
            ]
        ];

        $income = [
            [
                'label' => 'Saldo Masuk',
                'value' => number_format($totalSimpanans, 0, ',', '.'),
                'color' => 'text-success',
                'suffix' => 'Rp',
            ]
        ];

        $outcome = [
            [
                'label' => 'Saldo Keluar',
                'value' => number_format($totalOutcomes, 0, ',', '.'),
                'color' => 'text-success',
                'suffix' => 'Rp',
            ]
        ];
        // Kirim data ke view
        return view('pages.bendahara.index', [
            'title' => 'Bendahara | Bendahara Page',
            // 'dataCounts' => $counts,
            'emergency' => $emergencies,
            'angunan' => $angunans,
            'nonangunan' => $nonangunans,
            'totalLoans' => $totalLoans,
            'totalSimpanans' => $simpanan,
            'anggotas' => $anggotas,
            'income' => $income,
            'outcome' => $outcome,
            'currentDateTime' => now()->format('d M Y, H:i:s'), // Tanggal dan waktu saat ini
        ]);
    }


    public function indexsimpanansukarela()
    {
        $simpananSukarelas = SimpananSukarela::whereHas('rekeningSimpananSukarela', function ($query) {
            $query->whereIn('approval_manager', ['approved', 'rejected']);
        })->with(['rekeningSimpananSukarela', 'user'])->get();
        Log::info('Data Simpanan Sukarela:', ['data' => $simpananSukarelas]);
        return view('pages.bendahara.index3', [
            'title' => 'Data Pengajuan Simpanan Sukarela',
            'simpananSukarelas' => $simpananSukarelas,
        ]);
    }


    public function indexsimpananberjangka()
    {
        $simpananBerjangkas = SimpananBerjangka::whereHas('rekeningSimpananBerjangka', function ($query) {
            $query->whereIn('approval_manager', ['approved', 'rejected']);
        })->with(['rekeningSimpananBerjangka', 'user'])->get();
        Log::info('Data Simpanan Berjangka:', ['data' => $simpananBerjangkas]);
        return view('pages.bendahara.index2', [
            'title' => 'Data Pengajuan Simpanan Berjangka',
            'simpananBerjangkas' => $simpananBerjangkas,
        ]);
    }




    public function indexPinjamanBendahara()
    {
        $data = [
            'title' => 'Bendahara | Data Pinjaman Emergency',
            'tenors' => Tenor::get()->all(),
            'virtualAccounts' => VirtualAccount::get()->all(),
            'pinjamans' => PengajuanPinjaman::all()
        ];

        return view('pages.bendahara.approve-pinjaman', $data);
    }

    public function bendaharaEmergency()
    {
        return view('pages.bendahara.index-emergency', [
            'title' => 'Bendahara | Data Pinjaman Emergency',
            'emergencies' => PengajuanPinjaman::where('jenis_pinjaman', 'pinjaman_emergency')->get(),
        ]);
    }

    public function bendaharaAngunan()
    {
        return view('pages.bendahara.index-angunan', [
            'title' => 'Bendahara | Data Pinjaman Angunan',
            'angunans' => PengajuanPinjaman::where('jenis_pinjaman', 'pinjaman_angunan')->get(),
        ]);
    }

    public function bendaharaNonAngunan()
    {
        return view('pages.bendahara.index-non-angunan', [
            'title' => 'Bendahara | Data Pinjaman Non Angunan',
            'nonAngunans' => PengajuanPinjaman::where('jenis_pinjaman', 'pinjaman_non_angunan')->get(),
        ]);
    }


    public function filter(Request $request)
    {
        // Ambil nilai status dari permintaan
        $status = $request->input('status');

        // Jika status adalah "all", ambil semua data berdasarkan status_bendahara yang tidak null
        if ($status === 'all') {
            $anggota = Anggota::whereNotNull('status_bendahara') // Filter data dengan status_bendahara tidak null
                ->orderBy('created_at', 'desc') // Mengurutkan berdasarkan kolom created_at secara descending
                ->get();
        } else {
            // Filter berdasarkan status_bendahara
            $anggota = Anggota::where('status_bendahara', $status)
                ->orderBy('created_at', 'desc') // Mengurutkan berdasarkan kolom created_at secara descending
                ->get();
        }

        // Kembalikan view partial untuk memperbarui tabel
        return view('pages.bendahara.table_registrasi', compact('anggota'))->render();
    }



    public function delete($id)
    {
        try {
            // Cari data berdasarkan ID
            $anggota = Anggota::findOrFail($id);

            // Hapus data di tabel users yang berelasi dengan anggota
            $usersDeleted = User::where('anggota_id', $id)->delete();

            // Hapus data anggota
            $anggota->delete();

            return response()->json([
                'message' => 'Data berhasil dihapus',
                'users_deleted' => $usersDeleted
            ], 200);
        } catch (ModelNotFoundException $e) {
            Log::error("Data dengan ID {$id} tidak ditemukan: " . $e->getMessage()); // Log jika data tidak ditemukan
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        } catch (\Exception $e) {
            Log::error("Error saat menghapus data dengan ID {$id}: " . $e->getMessage()); // Log jika ada error lain
            return response()->json(['message' => 'Terjadi kesalahan'], 500);
        }
    }




    public function search(Request $request)
    {
        $query = $request->input('query');

        // Cari berdasarkan nama, NIK, atau status_manager
        $anggota = Anggota::where('nama', 'LIKE', "%{$query}%")
            ->orWhere('nik', 'LIKE', "%{$query}%")
            ->orWhere('status_bendahara', 'LIKE', "%{$query}%")
            ->get();

        // Kembalikan partial view dengan hasil pencarian
        return view('pages.bendahara.table_registrasi', compact('anggota'))->render();
    }

    public function getDetail($id)
    {
        $anggota = Anggota::findOrFail($id); // Cari anggota berdasarkan ID
        return response()->json($anggota); // Kembalikan data sebagai JSON
    }

    public function update(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);

        // Validasi data
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat_domisili' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'nik' => 'required|string|max:255',
            'email_kantor' => 'required|email|max:255',
            'no_handphone' => 'required|string|max:15',
        ]);

        // Perbarui data anggota
        $anggota->update($request->all());

        return response()->json(['message' => 'Data berhasil diperbarui'], 200);
    }


    public function reject(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);

        // Mengubah status anggota menjadi 'Ditolak'
        $anggota->status_manager = 'Ditolak';
        $anggota->status_bendahara = 'Ditolak';
        $anggota->status = 'Ditolak';
        $anggota->status_ketua = 'Ditolak';
        $anggota->alasan_ditolak = $request->input('alasan_ditolak'); // Tetap bisa menyimpan alasan jika dikirim
        $anggota->save();

        // Mengubah status_pembayaran di tabel simpanan_pokok menjadi 'Gagal'
        $anggota->simpananPokok()->update(['status_pembayaran' => 'Gagal']);

        // Mengubah status_pembayaran di tabel simpanan_wajib menjadi 'Gagal'
        $anggota->simpananWajib()->update(['status_pembayaran' => 'Gagal']);

        // Kirim email penolakan
        Mail::to($anggota->email_kantor)->send(new RejectNotification($anggota));

        return response()->json(['message' => 'Anggota berhasil ditolak, status pembayaran diperbarui menjadi Gagal, dan email pemberitahuan telah dikirim.']);
    }


    public function updateApprovalManagerSimpananSukarela($id, $status)
    {
        try {
            // Validasi status
            if (!in_array($status, ['approved', 'rejected', 'pending'])) {
                return response()->json(['message' => 'Invalid status provided!'], 400);
            }

            // Temukan data berdasarkan ID
            $rekening = RekeningSimpananSukarela::findOrFail($id);

            // Update status approval manager
            $rekening->approval_bendahara = $status;

            if ($status === 'Ditolak') {
                // Jika status ditolak oleh manager, maka status bendahara dan ketua juga menjadi Ditolak
                $rekening->approval_manager = 'Ditolak';
                $rekening->approval_ketua = 'Ditolak';
            }


            $rekening->save();

            return response()->json(['message' => 'Approval Manager status updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update Approval Manager status!', 'error' => $e->getMessage()], 500);
        }
    }



    public function updateApprovalManagerSimpananBerjangka($id, $status)
    {
        try {
            // Validasi status
            if (!in_array($status, ['approved', 'rejected', 'pending'])) {
                return response()->json(['message' => 'Invalid status provided!'], 400);
            }

            // Temukan data berdasarkan ID
            $rekening = RekeningSimpananBerjangka::findOrFail($id);

            // Update status approval manager
            $rekening->approval_bendahara = $status;

            if ($status === 'rejected') {
                // Jika status rejected oleh manager, maka status bendahara dan ketua juga menjadi rejected
                $rekening->approval_manager = 'rejected';
                $rekening->approval_ketua = 'rejected';
            }


            $rekening->save();

            return response()->json(['message' => 'Approval Manager status updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update Approval Manager status!', 'error' => $e->getMessage()], 500);
        }
    }


    public function updateStatusPinjamanBendahara($id, $status)
    {
        try {
            // Temukan anggota berdasarkan ID
            $anggota = PengajuanPinjaman::findOrFail($id);

            // Update status anggota berdasarkan parameter $status
            $anggota->status_bendahara = $status;
            $anggota->save();

            return response()->json(['message' => 'Status updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update status!', 'error' => $e->getMessage()], 500);
        }
    }

    public function countDataRekeningSimpananSukarela($status)
    {
        if ($status == 'all') {
            // Total semua data
            $count = RekeningSimpananSukarela::count();
        } elseif ($status == 'diterima') {
            // Data yang diterima oleh ketua, manager, atau bendahara
            $count = RekeningSimpananSukarela::where(function ($query) {
                $query->where('approval_ketua', 'approved')
                    ->orWhere('approval_manager', 'approved')
                    ->orWhere('approval_bendahara', 'approved');
            })->count();
        } elseif ($status == 'pengajuan') {
            // Data yang masih dalam proses (belum diterima/ditolak oleh ketua, manager, atau bendahara)
            $count = RekeningSimpananSukarela::where('approval_ketua', 'pending')
                ->orWhere('approval_manager', 'pending')
                ->orWhere('approval_bendahara', 'pending')
                ->count();
        } elseif ($status == 'ditolak') {
            // Data yang ditolak oleh ketua, manager, atau bendahara
            $count = RekeningSimpananSukarela::where(function ($query) {
                $query->where('approval_ketua', 'rejected')
                    ->orWhere('approval_manager', 'rejected')
                    ->orWhere('approval_bendahara', 'rejected');
            })->count();
        }

        // Kembalikan hasil dalam format JSON
        return response()->json(['count' => $count]);
    }


    public function countDataRekeningSimpananBerjangka($status)
    {
        if ($status == 'all') {
            // Total semua data
            $count = RekeningSimpananBerjangka::count();
        } elseif ($status == 'diterima') {
            // Data yang diterima oleh ketua, manager, atau bendahara
            $count = RekeningSimpananBerjangka::where(function ($query) {
                $query->where('approval_ketua', 'approved')
                    ->orWhere('approval_manager', 'approved')
                    ->orWhere('approval_bendahara', 'approved');
            })->count();
        } elseif ($status == 'pengajuan') {
            // Data yang masih dalam proses (belum diterima/ditolak oleh ketua, manager, atau bendahara)
            $count = RekeningSimpananBerjangka::where('approval_ketua', 'pending')
                ->orWhere('approval_manager', 'pending')
                ->orWhere('approval_bendahara', 'pending')
                ->count();
        } elseif ($status == 'ditolak') {
            // Data yang ditolak oleh ketua, manager, atau bendahara
            $count = RekeningSimpananBerjangka::where(function ($query) {
                $query->where('approval_ketua', 'rejected')
                    ->orWhere('approval_manager', 'rejected')
                    ->orWhere('approval_bendahara', 'rejected');
            })->count();
        }

        // Kembalikan hasil dalam format JSON
        return response()->json(['count' => $count]);
    }

    public function countDataRekeningPenarikanSukarela($status)
    {
        if ($status == 'all') {
            // Total semua data
            $count = PenarikanSukarela::count();
        } elseif ($status == 'diterima') {
            // Data yang diterima oleh ketua, manager, atau bendahara
            $count = PenarikanSukarela::where(function ($query) {
                $query->where('status_ketua', 'diterima')
                    ->orWhere('status_manager', 'diterima')
                    ->orWhere('status_bendahara', 'diterima');
            })->count();
        } elseif ($status == 'pengajuan') {
            // Data yang masih dalam proses (belum diterima/ditolak oleh ketua, manager, atau bendahara)
            $count = PenarikanSukarela::where('status_ketua', 'pending')
                ->orWhere('status_manager', 'pending')
                ->orWhere('status_bendahara', 'pending')
                ->count();
        } elseif ($status == 'ditolak') {
            // Data yang ditolak oleh ketua, manager, atau bendahara
            $count = PenarikanSukarela::where(function ($query) {
                $query->where('status_ketua', 'ditolak')
                    ->orWhere('status_manager', 'ditolak')
                    ->orWhere('status_bendahara', 'ditolak');
            })->count();
        }

        // Kembalikan hasil dalam format JSON
        return response()->json(['count' => $count]);
    }


    public function countDataRekeningPenarikanBerjangka($status)
    {
        if ($status == 'all') {
            // Total semua data
            $count = PenarikanBerjangka::count();
        } elseif ($status == 'diterima') {
            // Data yang diterima oleh ketua, manager, atau bendahara
            $count = PenarikanBerjangka::where(function ($query) {
                $query->where('status_ketua', 'diterima')
                    ->orWhere('status_manager', 'diterima')
                    ->orWhere('status_bendahara', 'diterima');
            })->count();
        } elseif ($status == 'pengajuan') {
            // Data yang masih dalam proses (belum diterima/ditolak oleh ketua, manager, atau bendahara)
            $count = PenarikanBerjangka::where('status_ketua', 'pending')
                ->orWhere('status_manager', 'pending')
                ->orWhere('status_bendahara', 'pending')
                ->count();
        } elseif ($status == 'ditolak') {
            // Data yang ditolak oleh ketua, manager, atau bendahara
            $count = PenarikanBerjangka::where(function ($query) {
                $query->where('status_ketua', 'ditolak')
                    ->orWhere('status_manager', 'ditolak')
                    ->orWhere('status_bendahara', 'ditolak');
            })->count();
        }

        // Kembalikan hasil dalam format JSON
        return response()->json(['count' => $count]);
    }


    public function penarikanSukarelaApproval()
    {
        // Ambil data dengan kondisi kolom 'bank' tidak sama dengan 'Menunggu OTP'
        // dan 'status_manager' bernilai 'diterima' atau 'ditolak'
        $data = PenarikanSukarela::where('bank', '!=', 'Menunggu OTP')
            ->whereIn('status_manager', ['diterima', 'ditolak'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Log data yang akan ditampilkan
        Log::info('Data Approval Penarikan Sukarela:', ['data' => $data]);

        // Return ke view dengan data yang sudah difilter
        return view('pages.bendahara.penarikan.penarikan_sukarela_approval', [
            'title' => 'Data Approval Penarikan Sukarela',
            'data' => $data,
        ]);
    }



    public function penarikanBerjangkaApproval()
    {
        $data = PenarikanBerjangka::where('bank', '!=', 'Menunggu OTP')
            ->whereIn('status_manager', ['diterima', 'ditolak'])
            ->orderBy('created_at', 'desc')
            ->get();
        Log::info('Data Approval Simpanan Berjangka:', ['data' => $data]);
        return view('pages.bendahara.penarikan.penarikan_berjangka_approval', [
            'title' => 'Data Approval Simpanan Berjangka',
            'data' => $data,
        ]);
    }

    public function updateApprovalManagerPenarikanSukarela($id, $status)
    {
        try {
            // Validasi status
            if (!in_array($status, ['diterima', 'ditolak', 'pending'])) {
                return response()->json(['message' => 'Invalid status provided!'], 400);
            }

            // Temukan data berdasarkan ID
            $rekening = PenarikanSukarela::findOrFail($id);

            // Update status approval manager
            $rekening->status_bendahara = $status;

            if ($status === 'ditolak') {
                // Jika status ditolak oleh manager, maka status bendahara dan ketua juga menjadi Ditolak
                $rekening->status_manager = 'ditolak';
                $rekening->status_ketua = 'ditolak';
            }


            $rekening->save();

            return response()->json(['message' => 'Approval Manager status updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update Approval Manager status!', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateApprovalManagerPenarikanBerjangka($id, $status)
    {
        try {
            // Validasi status
            if (!in_array($status, ['diterima', 'ditolak', 'pending'])) {
                return response()->json(['message' => 'Invalid status provided!'], 400);
            }

            // Temukan data berdasarkan ID
            $rekening = PenarikanBerjangka::findOrFail($id);

            // Update status approval manager
            $rekening->status_bendahara = $status;

            if ($status === 'ditolak') {
                // Jika status ditolak oleh manager, maka status bendahara dan ketua juga menjadi Ditolak
                $rekening->status_manager = 'ditolak';
                $rekening->status_ketua = 'ditolak';
            }


            $rekening->save();

            return response()->json(['message' => 'Approval Manager status updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update Approval Manager status!', 'error' => $e->getMessage()], 500);
        }
    }
}
