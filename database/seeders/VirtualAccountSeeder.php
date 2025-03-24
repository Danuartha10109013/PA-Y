<?php

namespace Database\Seeders;

use App\Models\Tenor;
use App\Models\VirtualAccount;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VirtualAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            [
                'user_id' => 5,
                'nama_bank' => 'BNI',
                'virtual_account_number' => '123456789',
            ],
            [
                'user_id' => 6,
                'nama_bank' => 'BRI',
                'virtual_account_number' => '123456789',
            ]

        ];

        foreach ($accounts as $account) {
            VirtualAccount::create([
                'user_id' => $account['user_id'],
                'nama_bank' => $account['nama_bank'],
                'virtual_account_number' => $account['virtual_account_number'],
            ]);
        }
    }
}
