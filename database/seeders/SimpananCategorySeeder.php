<?php

namespace Database\Seeders;

use App\Models\SimpananCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SimpananCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $simpanans = [
            [
                'name' => 'Simpanan Pokok',
            ],
            [
                'name' => 'Simpanan Wajib',
            ],
            [
                'name' => 'Simpanan Sukarela',
            ],
            [
                'name' => 'Simpanan Berjangka',
            ],
        ];
        foreach ($simpanans as $simpanan) {
            SimpananCategory::insert([
                'name' => $simpanan['name'],
            ]);
        }
    }
}
