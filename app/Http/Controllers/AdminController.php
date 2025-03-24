<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use App\Models\Anggota;
use Mpdf\Output\Destination;
use App\Models\SimpananSukarela;
use App\Models\SimpananBerjangka;
use Illuminate\Support\Facades\DB;
use App\Services\LoanAndStatusService;

class AdminController extends Controller
{
    protected $loanService;

    public function __construct(LoanAndStatusService $loanService)
    {
        $this->loanService = $loanService;
    }
    public function homeadmin()
    {
        $emergencyLoans = $this->loanService->getEmergencyLoans();
        $angunanLoans = $this->loanService->getAngunanLoans();
        $nonangunanLoans = $this->loanService->getNonAngunanLoans();
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

        return view('pages.admin.home_admin', [
            'title' => 'Dashboard Admin',
            'emergency' => $emergencies,
            'angunan' => $angunans,
            'nonangunan' => $nonangunans,
            'totalLoans' => $totalLoans,
            'simpanan' => $simpanan,
            'currentDateTime' => now()->format('d M Y, H:i:s'), // Tanggal dan waktu saat ini

        ]);
    }

    public function dataanggota()
    {
        return view('pages.admin.data_anggota', [
            'title' => 'Data Anggota',
            'anggota' => Anggota::all()
        ]);
    }
    public function getAllSimpanan($userId = null)
    {
        // Ambil data dari Simpanan Berjangka
        $berjangka = SimpananBerjangka::with('user') // Relasi user
            ->select(
                'id',
                'user_id',
                'no_simpanan',
                'nominal',
                'virtual_account',
                'jangka_waktu',
                'jumlah_jasa_perbulan',
                'status_payment',
                'expired_at',
                'tanggal_pengajuan as created_at',
                DB::raw("'berjangka' as jenis_simpanan")
            )
            ->when($userId, function ($query, $userId) {
                $query->where('user_id', $userId);
            })
            ->get();

        // Ambil data dari Simpanan Sukarela
        $sukarela = SimpananSukarela::with('user') // Relasi user
            ->select(
                'id',
                'user_id',
                'no_simpanan',
                'nominal',
                'virtual_account',
                'status_payment',
                'expired_at',
                'created_at',
                DB::raw("'sukarela' as jenis_simpanan")
            )
            ->when($userId, function ($query, $userId) {
                $query->where('user_id', $userId);
            })
            ->get();

        // Kirim data terpisah ke view
        $title = 'Mutasi Simpanan';
        return view('pages.admin.mutasi_simpanan', compact('berjangka', 'sukarela', 'title'));
    }

    public function exportPDF()
    {
        $sukarela = SimpananSukarela::get();
        $berjangka = SimpananBerjangka::get();
        // Load HTML View untuk PDF
        $html = view('pages.admin.pdf.simpanan', compact('sukarela', 'berjangka'))->render();

        // Inisialisasi mPDF
        $mpdf = new Mpdf([
            'format' => 'A4',
            'orientation' => 'P',
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
            'margin_right' => 10
        ]);
        $mpdf->WriteHTML($html);

        // Download file PDF
        return response()->streamDownload(
            fn() => print($mpdf->Output('', 'S')),
            'laporan-simpanan.pdf'
        );
    }

    public function exportPdfDashboard()
    {
        // Ambil data dari LoanAndStatusService
        $emergencyLoans = $this->loanService->getEmergencyLoans();
        $angunanLoans = $this->loanService->getAngunanLoans();
        $nonangunanLoans = $this->loanService->getNonAngunanLoans();
        $totalLoans = $this->loanService->getTotalPinjaman();
        $totalSimpanans = $this->loanService->getTotalSimpanan();

        // Format data agar sesuai dengan tampilan tabel di PDF
        $data = [
            'title' => 'Laporan Dashboard',
            'currentDateTime' => now()->format('d-m-Y H:i'),
            'emergency' => number_format($emergencyLoans, 0, ',', '.'),
            'angunan' => number_format($angunanLoans, 0, ',', '.'),
            'nonangunan' => number_format($nonangunanLoans, 0, ',', '.'),
            'totalLoans' => number_format($totalLoans, 0, ',', '.'),
            'simpanan' => number_format($totalSimpanans, 0, ',', '.'),
        ];

        // Render view PDF
        $html = view('pages.admin.pdf.dashboard-pdf', $data)->render();

        // Buat PDF dengan ukuran A4
        $mpdf = new Mpdf([
            'format' => 'A4',
            'orientation' => 'P' // Potrait
        ]);

        $mpdf->WriteHTML($html);

        // Tampilkan PDF di browser
        return response($mpdf->Output('dashboard.pdf', Destination::INLINE))
            ->header('Content-Type', 'application/pdf');
    }
};
