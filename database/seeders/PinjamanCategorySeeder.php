<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PinjamanCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PinjamanCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $loans = [
            [
                'name' => 'Pinjaman Regular',
            ],
            [
                'name' => 'Pinjaman Emergency',
            ],
            [
                'name' => 'Pinjaman Tanpa Anggunan',
            ],
            [
                'name' => 'Pinjaman dengan Anggunan',
            ],
        ];
        foreach ($loans as $loan) {
            PinjamanCategory::insert([
                'name' => $loan['name'],
            ]);
        }
    }
}
