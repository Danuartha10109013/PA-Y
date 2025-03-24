<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'yuniarsih',
                'email' => 'yuniarsih@gmail.com',
                'roles' => 'manager',
                'password' => Hash::make('12345'),
            ],
            [
                'name' => 'sima',
                'email' => 'sima@gmail.com',
                'roles' => 'ketua',
                'password' => Hash::make('12345'),
            ],
            [
                'name' => 'nanda',
                'email' => 'nanda@gmail.com',
                'roles' => 'admin',
                'password' => Hash::make('12345'),
            ],
            [
                'name' => 'bendahara',
                'email' => 'bendahara@gmail.com',
                'roles' => 'bendahara',
                'password' => Hash::make('12345'),
            ],
            [
                'name' => 'Dodi Sopandi',
                'email' => 'dodi@gmail.com',
                'roles' => 'anggota',
                'password' => Hash::make('12345'),
            ],
            [
                'name' => 'Yahya',
                'email' => 'yahya@gmail.com',
                'roles' => 'anggota',
                'password' => Hash::make('12345'),
            ]
        ];
        foreach ($users as $user) {
            User::insert([
                'name' => $user['name'],
                'email' => $user['email'],
                'roles' => $user['roles'],
                'password' => $user['password'],
            ]);
        }
    }
}
