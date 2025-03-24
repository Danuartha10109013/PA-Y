<?php

namespace App\Http\Controllers;

use App\Models\Tenor;
use App\Models\Anggota;
use App\Models\Simpanan;
use Illuminate\Http\Request;
use App\Models\VirtualAccount;
use App\Exports\PinjamanExport;
use App\Models\SimpananSukarela;
use App\Models\PengajuanPinjaman;
use App\Models\PinjamanEmergency;
use App\Models\SimpananBerjangka;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\LoanAndStatusService;
use Maatwebsite\Excel\Excel as ExcelExcel;


class DashboardPagesControlller extends Controller
{
    protected $loanService;

    public function __construct(LoanAndStatusService $loanService)
    {
        $this->loanService = $loanService;
    }

    public function homeadmin()
    {

        return view('pages.admin.home_admin', [
            'title' => 'Admin | Dashboard Admin',


        ]);
    }

    public function laporanregisadmin()
    {
        return view('pages.admin.laporan_regis_admin', [
            'title' => 'Admin | Data Approve Registrasi',
            'anggota' => Anggota::all()

        ]);
    }

    public function emergencyAnggota()
    {
        $userId = auth()->id(); // Ambil ID pengguna yang sedang login

        return view('pages.anggota.pinjaman.emergency.index', [
            'pinjamans' => PengajuanPinjaman::where('jenis_pinjaman', 'pinjaman_emergency')
                ->where('user_id', $userId) // Batasi hanya untuk pengguna yang sedang login
                ->get(),
            'title' => 'Anggota | Pinjaman Emergency'
        ]);
    }


    public function angunanyAnggota()
    {
        $userId = auth()->id(); // Ambil ID pengguna yang sedang login

        return view('pages.anggota.pinjaman.angunan.index', [
            'pinjamans' => PengajuanPinjaman::where('jenis_pinjaman', 'pinjaman_angunan')
                ->where('user_id', $userId) // Batasi hanya untuk pengguna yang sedang login
                ->get(),
            'title' => 'Anggota | Pinjaman Angunan'
        ]);
    }


    public function nonangunanAnggota()
    {
        $userId = auth()->id(); // Ambil ID pengguna yang sedang login

        return view('pages.anggota.pinjaman.nonangunan.index', [
            'pinjamans' => PengajuanPinjaman::where('jenis_pinjaman', 'pinjaman_non_angunan')
                ->where('user_id', $userId) // Batasi hanya untuk pengguna yang sedang login
                ->get(),
            'title' => 'Anggota | Pinjaman Non Angunan'
        ]);
    }


    //Manager
    public function indexManager()
    {
        // Dapatkan nilai pinjaman secara terpisah
        $emergencyLoans = $this->loanService->getEmergencyLoans();
        $angunans = $this->loanService->getAngunanLoans();
        $nonangunans = $this->loanService->getNonAngunanLoans();
        $totalLoans = $this->loanService->getTotalPinjaman();
        $totalSimpanans = $this->loanService->getTotalSimpanan();

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
                'label' => 'Pinjaman Reguler',
                'value' => number_format($angunans, 0, ',', '.'),
                'color' => 'text-info',
                'suffix' => 'Rp',
            ],
        ];
        $nonangunans = [
            [
                'label' => 'Pinjaman Reguler',
                'value' => number_format($nonangunans, 0, ',', '.'),
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

        return view('pages.manager.home_manager', [
            'title' => 'Manager | Dashboard Manager',
            // 'dataCounts' => $dataCounts,
            'emergencyLoans' => $emergencies,
            'angunan' => $angunans,
            'nonangunan' => $nonangunans,
            'totalLoans' => $totalLoans,
            'totalSimpanans' => $simpanan,
            'currentDateTime' => now()->format('d M Y, H:i:s'), // Tanggal dan waktu saat ini
        ]);
    }


    public function managerEmergency()
    {
        $data = [
            'title' => 'Manager | Data Pinjaman Emergency',
            'tenors' => Tenor::all(),
            'virtualAccounts' => VirtualAccount::all(),
            'pinjamanEmergency' => PengajuanPinjaman::where('jenis_pinjaman', 'pinjaman_emergency')->get(),
        ];

        return view('pages.manager.pinjaman.emergency', $data);
    }

    public function managerAngunan()
    {
        $data = [
            'title' => 'Manager | Data Pinjaman Angunan',
            'tenors' => Tenor::all(),
            'virtualAccounts' => VirtualAccount::all(),
            'pinjamanAngunan' => PengajuanPinjaman::where('jenis_pinjaman', 'pinjaman_angunan')->get(),
        ];

        return view('pages.manager.pinjaman.angunan', $data);
    }

    public function managerNonAngunan()
    {
        $data = [
            'title' => 'Manager | Data Pinjaman Non Angunan',
            'tenors' => Tenor::all(),
            'virtualAccounts' => VirtualAccount::all(),
            'pinjamanNonAngunan' => PengajuanPinjaman::where('jenis_pinjaman', 'pinjaman_non_angunan')->get(),
        ];

        return view('pages.manager.pinjaman.non-angunan', $data);
    }



    public function ManagerApproveRegister()
    {
        // Mengambil semua data anggota dari database

        // Mengirimkan data anggota ke view
        $anggota = Anggota::orderBy('created_at', 'desc')->get();


        // Mengirimkan data anggota ke view
        return view('pages.manager.approve_regis_manager', [
            'title' => 'Data Approve Registrasi',
            'anggota' => $anggota,
        ]);
    }

    //Ketua
    public function indexKetua()
    {
        $dataCounts = Anggota::whereIn('status_ketua', ['Pengajuan', 'Diterima', 'Ditolak'])
            ->select('status_ketua')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('status_ketua')
            ->pluck('total', 'status_ketua');

        // Pastikan nilai default untuk menghindari undefined key
        $counts = [
            'pengajuan' => $dataCounts['Pengajuan'] ?? 0,
            'diterima' => $dataCounts['Diterima'] ?? 0,
            'ditolak' => $dataCounts['Ditolak'] ?? 0,
        ];

        $emergencyLoans = $this->loanService->getEmergencyLoans();
        $angunans = $this->loanService->getAngunanLoans();
        $nonangunans = $this->loanService->getNonAngunanLoans();
        $totalLoans = $this->loanService->getTotalPinjaman();
        $totalSimpanans = $this->loanService->getTotalSimpanan();

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
                'label' => 'Pinjaman Reguler',
                'value' => number_format($angunans, 0, ',', '.'),
                'color' => 'text-info',
                'suffix' => 'Rp',
            ],
        ];
        $nonangunans = [
            [
                'label' => 'Pinjaman Reguler',
                'value' => number_format($nonangunans, 0, ',', '.'),
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

        return view('pages.ketua.home_ketua', [
            'title' => 'Ketua | Dashboard Ketua',
            'dataCounts' => $counts,
            'emergencyLoans' => $emergencies,
            'angunan' => $angunans,
            'nonangunan' => $nonangunans,
            'totalLoans' => $totalLoans,
            'totalSimpanans' => $simpanan,
            'currentDateTime' => now()->format('d M Y, H:i:s'), // Tanggal dan waktu saat ini
        ]);
    }

    public function dataRegister()
    {
        $anggota = Anggota::whereIn('status_bendahara', ['Diterima', 'Ditolak'])
            ->get();

        // Catat data anggota yang difilter ke dalam log
        Log::info('Data Anggota:', ['data' => $anggota]);

        // Kirimkan data ke view
        return view('pages.ketua.approve_regis_ketua', [
            'title' => 'Data Approve Registrasi',
            'anggota' => $anggota,
        ]);
    }


    public function KetuaApproveRegister()
    {
        return view('pages.admin.detail_laporanregis', [
            'title' => 'Admin | Data Anggota Registrasi',
            'anggota' => Anggota::all()
        ]);
    }


    public function ketuaEmergency()
    {
        return view('pages.ketua.pinjaman.emergency', [
            'title' => 'Ketua | Data Pinjaman Emergency',
            'pinjamanEmergency' => PengajuanPinjaman::where('jenis_pinjaman', 'pinjaman_emergency')->get(),

        ]);
    }

    public function ketuaAngunan()
    {
        return view('pages.ketua.pinjaman.angunan', [
            'title' => 'Ketua | Data Pinjaman Angunan',
            'pinjamanAngunan' => PengajuanPinjaman::where('jenis_pinjaman', 'pinjaman_angunan')->get(),

        ]);
    }

    public function ketuaNonAngunan()
    {
        return view('pages.ketua.pinjaman.nonangunan', [
            'title' => 'Ketua | Data Pinjaman Non Angunan',
            'pinjamanNonAngunan' => PengajuanPinjaman::where('jenis_pinjaman', 'pinjaman_non_angunan')->get(),

        ]);
    }

    public function indexSimpananBerjangka()
    {
        return view('pages.ketua.simpanan.berjangka', [
            'title' => 'Ketua | Data Simpanan Berjangka',
            'simpananBerjangka' => SimpananBerjangka::all(),
        ]);
    }

    public function indexSimpananSukarela()
    {
        return view('pages.ketua.simpanan.sukarela', [
            'title' => 'Ketua | Data Simpanan Sukarela',
            'simpananSukarela' => SimpananSukarela::all(),
        ]);
    }
    public function indexSimpananWajibPokok(Request $request)
    {
        $jenis = $request->query('jenis_simpanan', 'wajib'); // Default ke 'wajib' jika tidak ada query 'jenis'

        // Filter data berdasarkan jenis simpanan
        $simpanan = Simpanan::where('jenis_simpanan', $jenis)->get();

        return view('pages.ketua.simpanan.wajib-pokok', [
            'title' => 'Ketua | Data Simpanan ' . ucfirst($jenis), // Dinamis berdasarkan jenis
            'simpanan' => $simpanan,
            'jenis' => $jenis,
        ]);
    }

    //Admin Pages


    public function exportExcelByJenis(Request $request, $jenisPinjaman)
    {
        // Validasi jenis pinjaman jika diperlukan
        $allowedJenis = ['pinjaman_emergency', 'pinjaman_angunan', 'pinjaman_non_angunan'];
        if (!in_array($jenisPinjaman, $allowedJenis)) {
            abort(404, 'Jenis pinjaman tidak valid');
        }

        $filters = $request->only(['start_date', 'end_date']);

        // Nama file export
        $fileName = ucwords(str_replace('_', ' ', $jenisPinjaman)) . '.xlsx';

        return Excel::download(new PinjamanExport($jenisPinjaman, $filters), $fileName);
    }

    //Mutasi Pinjaman
    public function adminEmergency(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date']);

        // Menggunakan scope filter untuk memfilter data berdasarkan tanggal
        $pinjamanEmergency = PengajuanPinjaman::where('jenis_pinjaman', 'pinjaman_emergency')
            ->filter($filters) // Menggunakan scope filter
            ->get();

        $saldoEmergency = PinjamanEmergency::where('status', 'success')->sum('amount');

        return view('pages.admin.pinjaman.mutasi.emergency', [
            'title' => 'Admin | Data Pinjaman Emergency',
            'pinjamanEmergency' => $pinjamanEmergency,
            'saldoEmergency' => $saldoEmergency
        ]);
    }

    public function adminAngunan(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date']);

        // Menggunakan scope filter untuk memfilter data berdasarkan tanggal
        $pinjamanAngunan = PengajuanPinjaman::where('jenis_pinjaman', 'pinjaman_angunan')
            ->filter($filters) // Menggunakan scope filter
            ->get();

        $saldoAngunan = PengajuanPinjaman::where('status', 'success')->sum('amount');

        return view('pages.admin.pinjaman.mutasi.angunan', [
            'title' => 'Admin | Data Pinjaman Angunan',
            'pinjamanAngunan' => $pinjamanAngunan,
            'saldoAngunan' => $saldoAngunan

        ]);
    }

    public function adminNonAngunan(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date']);

        // Menggunakan scope filter untuk memfilter data berdasarkan tanggal
        $pinjamanNonAngunan = PengajuanPinjaman::where('jenis_pinjaman', 'pinjaman_non_angunan')
            ->filter($filters) // Menggunakan scope filter
            ->get();
        $saldoNonAngunan = PengajuanPinjaman::where('status', 'success')->sum('amount');

        return view('pages.admin.pinjaman.mutasi.nonangunan', [
            'title' => 'Admin | Data Pinjaman Non Angunan',
            'pinjamanNonAngunan' => $pinjamanNonAngunan,
            'saldoNonAngunan' => $saldoNonAngunan

        ]);
    }

    public function adminSimpanan(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date']);

        // Menggunakan scope filter untuk memfilter data berdasarkan tanggal
    }

    //Mutasi Pinjaman
    public function adminSukarela(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date']);

        // Menggunakan scope filter untuk memfilter data berdasarkan tanggal
        $SimpananSukarela = SimpananSukarela::where('jenis_pinjaman', 'pinjaman_emergency')
            ->filter($filters) // Menggunakan scope filter
            ->get();

        $simpanan_sukarela = SimpananSukarela::with('user')->filter($filters)->get();

        // Menghitung saldo emergency dari pinjaman yang berhasil
        // $saldoEmergency = PinjamanEmergency::where('status', 'success')->sum('amount');

        return view('pages.admin.simpanan.mutasi.sukarela', [
            'title' => 'Admin | Data Simpanan Sukarela',
            'simpanan_sukarela' => $simpanan_sukarela,
        ]);
    }
}
