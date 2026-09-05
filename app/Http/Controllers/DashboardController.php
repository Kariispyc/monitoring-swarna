<?php

namespace App\Http\Controllers;

use App\Models\SensorLog;
use App\Models\DeviceControl;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function index()
    {
        $latest = SensorLog::latest()->first();
        $control = DeviceControl::firstOrCreate(['id' => 1]);

        // Data Statis 12 Anggota Tim PPK Ormawa SWARNA
        $members = [
            ['nama' => 'Salsabila Anggraina Putri', 'jurusan' => 'Teknik Informatika', 'jabatan' => 'Ketua Tim', 'foto' => 'tim/tim1.png'],
            ['nama' => 'Thania Rizkita', 'jurusan' => 'Teknik Informatika', 'jabatan' => 'Sekretaris', 'foto' => 'tim/tim2.png'],
            ['nama' => 'Ismi Syatia Anggraeni', 'jurusan' => 'Teknik Sipil', 'jabatan' => 'Bendahara', 'foto' => 'tim/tim3.png'],
            ['nama' => 'Rakha Bagas Praditio', 'jurusan' => 'Teknik Sipil', 'jabatan' => 'Project & Pembangunan', 'foto' => 'tim/tim4.png'],
            ['nama' => 'Farhan Fadila', 'jurusan' => 'Teknik Informatika', 'jabatan' => 'Project & IoT', 'foto' => 'tim/tim5.png'],
            ['nama' => 'Trysuci Wulandari', 'jurusan' => 'Teknik Informatika', 'jabatan' => 'Acara & IoT', 'foto' => 'tim/tim6.png'],
            ['nama' => 'Setya Rahmawati', 'jurusan' => 'Teknik Informatika', 'jabatan' => 'Acara & Greenhouse Designer', 'foto' => 'tim/tim7.png'],
            ['nama' => 'Hikari Naufal', 'jurusan' => 'Teknik Informatika', 'jabatan' => 'Web Dev & Media Dokumentasi', 'foto' => 'tim/tim8.png'],
            ['nama' => 'Nadya Anisa Putry', 'jurusan' => 'Teknik Sipil', 'jabatan' => 'Humas & Greenhouse Designer', 'foto' => 'tim/tim9.png'],
            ['nama' => 'Diandra Dwi Nugroho', 'jurusan' => 'Teknik Sipil', 'jabatan' => 'Koordinator Lapangan & Greenhouse Designer', 'foto' => 'tim/tim10.png'],
            ['nama' => 'Dapit Saepudin', 'jurusan' => 'Teknik Sipil', 'jabatan' => 'Koordinator Lapangan & Pembangunan', 'foto' => 'tim/tim11.png'],
        ];

        return view('dashboard', compact('latest', 'control', 'members'));
    }

    public function getLiveData()
    {
        $latest = SensorLog::latest()->first();
        $control = DeviceControl::firstOrCreate(['id' => 1]);
        $history = SensorLog::latest()->take(15)->get()->reverse()->values();

        return response()->json([
            'latest'  => $latest,
            'control' => $control,
            'history' => $history
        ]);
    }

    // FUNGSI UNTUK MENGUBAH STATUS VALVE, POMPA, DAN MODE AUTO/MANUAL
    public function updateControl(Request $request)
    {
        $control = DeviceControl::firstOrCreate(['id' => 1]);
        $control->update($request->all());

        return response()->json([
            'status'  => 'success',
            'message' => 'Status kontrol berhasil diperbarui',
            'data'    => $control
        ]);
    }

    public function exportCsv(Request $request)
    {
        $days = $request->input('days', 7);
        $fileName = 'rekap_sensor_swarna_' . date('Y-m-d') . '.csv';
        $logs = SensorLog::where('created_at', '>=', now()->subDays($days))->latest()->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'Waktu',
            'Rata-rata Block A',
            'Rata-rata Block B',
            'Rata-rata Block C',
            'Rata-rata Block D',
            'Rata-rata Block E',
            'Rata-rata Block F',
            'Rata-rata Block G',
            'Suhu Air (C)',
            'Suhu Udara (C)',
            'Kelembapan Udara (%)'
        ];

        $callback = function () use ($logs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->avg_a,
                    $log->avg_b,
                    $log->avg_c,
                    $log->avg_d,
                    $log->avg_e,
                    $log->avg_f,
                    $log->avg_g,
                    $log->water_temp,
                    $log->air_temp,
                    $log->air_humidity
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
