<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sensor_logs', function (Blueprint $table) {
            $table->id();

            // 14 Sensor Kelembapan Tanah Individu (%)
            $table->float('soil_a1')->default(0);
            $table->float('soil_a2')->default(0);
            $table->float('soil_b1')->default(0);
            $table->float('soil_b2')->default(0);
            $table->float('soil_c1')->default(0);
            $table->float('soil_c2')->default(0);
            $table->float('soil_d1')->default(0);
            $table->float('soil_d2')->default(0);
            $table->float('soil_e1')->default(0);
            $table->float('soil_e2')->default(0);
            $table->float('soil_f1')->default(0);
            $table->float('soil_f2')->default(0);
            $table->float('soil_g1')->default(0);
            $table->float('soil_g2')->default(0);

            // Rata-rata Kelembapan Tanah per Block A-G (%)
            $table->float('avg_a')->default(0);
            $table->float('avg_b')->default(0);
            $table->float('avg_c')->default(0);
            $table->float('avg_d')->default(0);
            $table->float('avg_e')->default(0);
            $table->float('avg_f')->default(0);
            $table->float('avg_g')->default(0);

            // Parameter Lingkungan & Air
            $table->float('water_temp')->default(0); // Suhu Air (°C)
            $table->float('air_temp')->default(0);   // Suhu Udara DHT22 (°C)
            $table->float('air_humidity')->default(0); // Kelembapan Udara DHT22 (%)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_logs');
    }
};
