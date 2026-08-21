<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SensorLog;
use App\Models\DeviceControl;
use Illuminate\Http\Request;

class IotController extends Controller
{
    // 1. Endpoint untuk menerima data dari ESP32 Master (POST)
    public function storeData(Request $request)
    {
        // Hitung rata-rata kelembapan tiap block
        $avg_a = ($request->input('soil_a1', 0) + $request->input('soil_a2', 0)) / 2;
        $avg_b = ($request->input('soil_b1', 0) + $request->input('soil_b2', 0)) / 2;
        $avg_c = ($request->input('soil_c1', 0) + $request->input('soil_c2', 0)) / 2;
        $avg_d = ($request->input('soil_d1', 0) + $request->input('soil_d2', 0)) / 2;
        $avg_e = ($request->input('soil_e1', 0) + $request->input('soil_e2', 0)) / 2;
        $avg_f = ($request->input('soil_f1', 0) + $request->input('soil_f2', 0)) / 2;
        $avg_g = ($request->input('soil_g1', 0) + $request->input('soil_g2', 0)) / 2;

        // Simpan log ke database
        $log = SensorLog::create([
            'soil_a1' => $request->input('soil_a1', 0),
            'soil_a2' => $request->input('soil_a2', 0),
            'soil_b1' => $request->input('soil_b1', 0),
            'soil_b2' => $request->input('soil_b2', 0),
            'soil_c1' => $request->input('soil_c1', 0),
            'soil_c2' => $request->input('soil_c2', 0),
            'soil_d1' => $request->input('soil_d1', 0),
            'soil_d2' => $request->input('soil_d2', 0),
            'soil_e1' => $request->input('soil_e1', 0),
            'soil_e2' => $request->input('soil_e2', 0),
            'soil_f1' => $request->input('soil_f1', 0),
            'soil_f2' => $request->input('soil_f2', 0),
            'soil_g1' => $request->input('soil_g1', 0),
            'soil_g2' => $request->input('soil_g2', 0),
            'avg_a'   => $avg_a,
            'avg_b'   => $avg_b,
            'avg_c'   => $avg_c,
            'avg_d'   => $avg_d,
            'avg_e'   => $avg_e,
            'avg_f'   => $avg_f,
            'avg_g'   => $avg_g,
            'water_temp'    => $request->input('water_temp', 0),
            'air_temp'      => $request->input('air_temp', 0),
            'air_humidity'  => $request->input('air_humidity', 0),
        ]);

        // Cek mode operasional
        $control = DeviceControl::firstOrCreate(['id' => 1]);

        // Jika Mode Auto, update status valve dan pompa berdasarkan threshold kelembapan
        if ($control->mode === 'auto') {
            $valves = ['a' => $avg_a, 'b' => $avg_b, 'c' => $avg_c, 'd' => $avg_d, 'e' => $avg_e, 'f' => $avg_f, 'g' => $avg_g];
            $anyValveOn = false;

            foreach ($valves as $block => $avg) {
                $field = "valve_" . $block;
                // Logika: <= 35% ON (Buka), >= 50% OFF (Tutup)
                if ($avg <= 35) {
                    $control->$field = true;
                } elseif ($avg >= 50) {
                    $control->$field = false;
                }
                if ($control->$field) {
                    $anyValveOn = true;
                }
            }

            // Pompa air otomatis ON jika minimal ada 1 valve terbuka
            $control->pump_water = $anyValveOn;
            $control->save();
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Data telemetry berhasil disimpan',
            'control' => $control
        ], 201);
    }

    // 2. Endpoint untuk ESP32 membaca status kontrol (GET)
    public function getControlStatus()
    {
        $control = DeviceControl::firstOrCreate(['id' => 1]);
        return response()->json($control, 200);
    }

    // 3. Endpoint untuk update kontrol manual dari Web Dashboard (POST)
    public function updateControl(Request $request)
    {
        $control = DeviceControl::firstOrCreate(['id' => 1]);

        if ($request->has('mode')) {
            $control->mode = $request->mode;
        }

        // Field valve dan pump
        $fields = ['valve_a', 'valve_b', 'valve_c', 'valve_d', 'valve_e', 'valve_f', 'valve_g', 'pump_water', 'pump_fertilizer'];
        foreach ($fields as $field) {
            if ($request->has($field)) {
                $control->$field = filter_var($request->$field, FILTER_VALIDATE_BOOLEAN);
            }
        }

        $control->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Status kontrol diperbarui',
            'data'    => $control
        ]);
    }
}
