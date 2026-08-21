<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SensorLog;
use App\Models\DeviceControl;

class DummySensorSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 15 data riwayat berkala mundur tiap 5 menit
        for ($i = 15; $i >= 0; $i--) {
            $a1 = rand(25, 60);
            $a2 = rand(25, 60);
            $b1 = rand(20, 55);
            $b2 = rand(20, 55);

            SensorLog::create([
                'soil_a1' => $a1,
                'soil_a2' => $a2,
                'soil_b1' => $b1,
                'soil_b2' => $b2,
                'soil_c1' => rand(30, 70),
                'soil_c2' => rand(30, 70),
                'soil_d1' => rand(30, 70),
                'soil_d2' => rand(30, 70),
                'soil_e1' => rand(20, 45),
                'soil_e2' => rand(20, 45),
                'soil_f1' => rand(40, 80),
                'soil_f2' => rand(40, 80),
                'soil_g1' => rand(30, 60),
                'soil_g2' => rand(30, 60),
                'avg_a'   => ($a1 + $a2) / 2,
                'avg_b'   => ($b1 + $b2) / 2,
                'avg_c'   => rand(30, 70),
                'avg_d'   => rand(30, 70),
                'avg_e'   => rand(20, 45),
                'avg_f'   => rand(40, 80),
                'avg_g'   => rand(30, 60),
                'water_temp'    => rand(24, 28) + (rand(0, 9) / 10),
                'air_temp'      => rand(27, 33) + (rand(0, 9) / 10),
                'air_humidity'  => rand(65, 85),
                'created_at'    => now()->subMinutes($i * 5),
                'updated_at'    => now()->subMinutes($i * 5),
            ]);
        }
    }
}
