<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin Utama',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pasien Test',
                'email' => 'pasien@example.com',
                'password' => Hash::make('password123'),
                'role' => 'pasien',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dokter Klinik',
                'email' => 'dokter@example.com',
                'password' => Hash::make('password123'),
                'role' => 'dokter',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
