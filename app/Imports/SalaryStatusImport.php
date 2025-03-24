<?php

namespace App\Imports;

use App\Models\SalaryStatus;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SalaryStatusImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
{
    foreach ($collection as $row) {
        // Hilangkan simbol "Rp", titik, dan spasi, lalu ubah menjadi integer
        $nominalPinjaman = (int) str_replace(['Rp', '.', ','], '', $row['nominal_pinjaman']);
        $nominalAngsuran = (int) str_replace(['Rp', '.', ','], '', $row['nominal_angsuran']);

        SalaryStatus::updateOrCreate(
            // Kriteria untuk menentukan data yang sama
            [
                'nomor_pinjaman' => $row['nomor_pinjaman']
            ],
            // Data yang akan diperbarui atau ditambahkan
            [
                'user_id' => $row['user_id'],
                'nama' => $row['nama'],
                'jenis_pinjaman' => strtolower(str_replace(' ', '_', $row['jenis_pinjaman'])),
                'nominal_pinjaman' => $nominalPinjaman,
                'jangka_waktu' => $row['jangka_waktu'],
                'nominal_angsuran' => $nominalAngsuran,
                'status' => $row['status'],
                'updated_at' => now(),
            ]
        );
    }
}

}
