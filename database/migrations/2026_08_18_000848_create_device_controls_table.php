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
        Schema::create('device_controls', function (Blueprint $table) {
            $table->id();

            // Mode Operasional: 'auto' atau 'manual'
            $table->enum('mode', ['auto', 'manual'])->default('auto');

            // Status Valve A-G (0 = OFF / Tutup, 1 = ON / Buka)
            $table->boolean('valve_a')->default(false);
            $table->boolean('valve_b')->default(false);
            $table->boolean('valve_c')->default(false);
            $table->boolean('valve_d')->default(false);
            $table->boolean('valve_e')->default(false);
            $table->boolean('valve_f')->default(false);
            $table->boolean('valve_g')->default(false);

            // Status Pompa
            $table->boolean('pump_water')->default(false); // Pompa Air
            $table->boolean('pump_fertilizer')->default(false); // Pompa Pupuk

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_controls');
    }
};
