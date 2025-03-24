<?php

namespace App\Http\Controllers;

use App\Models\PengajuanPinjaman;

class PDFController extends Controller
{
    public function exportPDF($jenisPinjaman)
    {
        // Ambil data berdasarkan jenis pinjaman dari database
        $data = PengajuanPinjaman::where('jenis_pinjaman', $jenisPinjaman)->get();

        // Data yang dikirimkan ke view PDF
        $viewData = [
            'title' => 'Laporan ' . ucwords(str_replace('_', ' ', $jenisPinjaman)),
            'date' => now()->format('d-m-Y'),
            'content' => $data
        ];

        // Render view menjadi HTML
        $html = view('pages.admin.pdf.pinjaman', $viewData)->render();

        // Inisialisasi mPDF
        $mpdf = new \Mpdf\Mpdf();

        // Tambahkan HTML ke dalam mPDF
        $mpdf->WriteHTML($html);

        // Output PDF ke browser (Download)
        return $mpdf->Output('laporan_' . $jenisPinjaman . '.pdf', 'D'); // 'D' untuk download
    }

}
