<?php

namespace Database\Seeders;

use App\Models\Tenor;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TenorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenors = [
            [
                'tenor' => '3 Bulan',
            ],
            [
                'tenor' => '6 Bulan',
            ],
            [
                'tenor' => '9 Bulan',
            ],
            [
                'tenor' => '12 Bulan',
            ],
            [
                'tenor' => '15 bulan',
            ],
            [
                'tenor' => '18 bulan',
            ],
            [
                'tenor' => '21 bulan',
            ],
            [
                'tenor' => '24 bulan',
            ]
        ];

        foreach ($tenors as $tenor) {
            Tenor::create([
                'tenor' => $tenor['tenor'],
                'uuid' => uuid_create()
            ]);
        }
    }
}
