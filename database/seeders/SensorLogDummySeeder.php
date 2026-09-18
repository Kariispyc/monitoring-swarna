<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SensorLog;
use Carbon\Carbon;

class SensorLogDummySeeder extends Seeder
{
    public function run()
    {
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);

            for ($j = 0; $j < 10; $j++) {
                SensorLog::create([
                    'soil_a1' => rand(40, 60), 'soil_a2' => rand(40, 60), 'avg_a' => rand(40, 60),
                    'soil_b1' => rand(35, 55), 'soil_b2' => rand(35, 55), 'avg_b' => rand(35, 55),
                    'soil_c1' => rand(45, 65), 'soil_c2' => rand(45, 65), 'avg_c' => rand(45, 65),
                    'soil_d1' => rand(50, 70), 'soil_d2' => rand(50, 70), 'avg_d' => rand(50, 70),
                    'soil_e1' => rand(40, 60), 'soil_e2' => rand(40, 60), 'avg_e' => rand(40, 60),
                    'soil_f1' => rand(30, 50), 'soil_f2' => rand(30, 50), 'avg_f' => rand(30, 50),
                    'soil_g1' => rand(42, 62), 'soil_g2' => rand(42, 62), 'avg_g' => rand(42, 62),
                    'water_temp' => rand(24, 28) + (rand(0, 9) / 10),
                    'air_temp' => rand(27, 33) + (rand(0, 9) / 10),
                    'air_humidity' => rand(60, 85),
                    'created_at' => $date->copy()->addHours(rand(1, 23)),
                    'updated_at' => $date->copy()->addHours(rand(1, 23)),
                ]);
            }
        }
    }
}