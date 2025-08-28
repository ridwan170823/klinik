<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $sessions = [
            ['mulai' => '08:00', 'selesai' => '09:00', 'available' => true],
            ['mulai' => '09:00', 'selesai' => '10:00', 'available' => true],
            ['mulai' => '10:00', 'selesai' => '11:00', 'available' => true],
            ['mulai' => '11:00', 'selesai' => '12:00', 'available' => true],
            ['mulai' => '12:00', 'selesai' => '13:00', 'available' => false], // break
            ['mulai' => '13:00', 'selesai' => '14:00', 'available' => true],
        ];

        foreach ($days as $day) {
            foreach ($sessions as $slot) {
                Jadwal::updateOrCreate([
                    'hari' => $day,
                    'waktu_mulai' => $slot['mulai'],
                    'waktu_selesai' => $slot['selesai'],
                ], [
                    'kapasitas' => $slot['available'] ? 10 : 0,
                    'is_available' => $slot['available'],
                ]);
            }
        }
    }
}