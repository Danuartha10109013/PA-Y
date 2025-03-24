<?php

namespace App\Exports;

use App\Models\PengajuanPinjaman;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PinjamanExport implements FromView
{
    protected $jenisPinjaman;
    protected $filters;

    /**
     * Constructor untuk menerima jenis pinjaman dan filter
     */
    public function __construct(string $jenisPinjaman, array $filters = [])
    {
        $this->jenisPinjaman = $jenisPinjaman;
        $this->filters = $filters;
    }

    /**
     * Menghasilkan view untuk export Excel
     */
    public function view(): View
    {
        $query = PengajuanPinjaman::where('jenis_pinjaman', $this->jenisPinjaman);

        // Terapkan filter tanggal jika ada
        if (!empty($this->filters['start_date']) && !empty($this->filters['end_date'])) {
            $query->whereBetween('created_at', [$this->filters['start_date'], $this->filters['end_date']]);
        } elseif (!empty($this->filters['start_date'])) {
            $query->where('created_at', '>=', $this->filters['start_date']);
        } elseif (!empty($this->filters['end_date'])) {
            $query->where('created_at', '<=', $this->filters['end_date']);
        }

        return view('pages.admin.excel.pinjaman', [
            'pinjaman' => $query->get()
        ]);
    }
}
