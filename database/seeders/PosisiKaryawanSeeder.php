<?php

namespace Database\Seeders;

use App\Models\PosisiKaryawan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PosisiKaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama_posisi' => 'Manajer', 'gaji_min' => 8000000, 'gaji_max' => 15000000],
            ['nama_posisi' => 'Asisten Manajer', 'gaji_min' => 6000000, 'gaji_max' => 10000000],
            ['nama_posisi' => 'Ketua Koperasi', 'gaji_min' => 7000000, 'gaji_max' => 14000000],
            ['nama_posisi' => 'Bendahara', 'gaji_min' => 5000000, 'gaji_max' => 9000000],
            ['nama_posisi' => 'Sekretaris', 'gaji_min' => 4000000, 'gaji_max' => 8000000],
            ['nama_posisi' => 'Staf Keuangan', 'gaji_min' => 3500000, 'gaji_max' => 7000000],
            ['nama_posisi' => 'Staf Administrasi', 'gaji_min' => 3000000, 'gaji_max' => 6000000],
            ['nama_posisi' => 'Marketing Koperasi', 'gaji_min' => 3500000, 'gaji_max' => 7500000],
            ['nama_posisi' => 'IT Support', 'gaji_min' => 4000000, 'gaji_max' => 9000000],
            ['nama_posisi' => 'Customer Service', 'gaji_min' => 3000000, 'gaji_max' => 6000000],
            ['nama_posisi' => 'Penagih Lapangan', 'gaji_min' => 2500000, 'gaji_max' => 5000000],
            ['nama_posisi' => 'Internship', 'gaji_min' => 2000000, 'gaji_max' => 3000000],
        ];

        foreach ($data as $item) {
            PosisiKaryawan::create($item);
        }
    }
}
