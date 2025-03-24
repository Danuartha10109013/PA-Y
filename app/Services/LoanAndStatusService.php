<?php

namespace App\Services;

use App\Models\PinjamanAngunan;
use App\Models\PinjamanEmergency;
use App\Models\PinjamanNonAngunan;
use App\Models\HistoryTransaction;
use App\Models\PengajuanPinjaman;
use App\Models\SimpananBerjangka;
use App\Models\SimpananPokok;
use App\Models\SimpananSukarela;
use App\Models\SimpananWajib;

class LoanAndStatusService
{
    public function getEmergencyLoans()
    {
        return PinjamanEmergency::where('status', 'success')->sum('amount');
    }

    /**
     * Hitung total pinjaman reguler (regular loans).
     *
     * @return float
     */
    public function getAngunanLoans()
    {
        return PinjamanAngunan::where('status', 'success')->sum('amount');
    }

    /**
     * Hitung total semua pinjaman.
     *
     * @return float
     */
    public function getNonAngunanLoans()
    {
        return PinjamanNonAngunan::where('status', 'success')->sum('amount');
    }

    /**
     * Hitung total semua pinjaman.
     *
     * @return float
     */
    public function getTotalPinjaman()
    {
        return $this->getEmergencyLoans() + $this->getAngunanLoans() + $this->getNonAngunanLoans();
    }

    public function getLoanCounts()
    {
        $dataCounts = PengajuanPinjaman::whereIn('status_manager', ['Pengajuan', 'Diterima', 'Ditolak'])
            ->select('status_manager')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('status_manager')
            ->pluck('total', 'status_manager');

        return [
            'pengajuan' => $dataCounts['Pengajuan'] ?? 0,
            'diterima' => $dataCounts['Diterima'] ?? 0,
            'ditolak' => $dataCounts['Ditolak'] ?? 0,
        ];
    }

    public function getTotalSimpanan()
    {
        return SimpananPokok::sum('nominal') + SimpananWajib::sum('nominal') + SimpananSukarela::sum('nominal') + SimpananBerjangka::sum('nominal');
    }

    public function getTotalOutcome()
    {
        return HistoryTransaction::sum('amount');
    }
}
