<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Dokter;
use App\Models\Layanan;

class DokterLayananSeeder extends Seeder
{
    public function run(): void
    {
        $dokter = Dokter::firstOrCreate(
            ['nama' => 'Dr. Sehat'],
            ['image' => null, 'spesialis' => 'Umum']
        );

        $layanan = Layanan::firstOrCreate([
            'nama' => 'Konsultasi',
        ]);

        DB::table('dokter_layanan')->insert([
            'dokter_id' => $dokter->id,
            'layanan_id' => $layanan->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}