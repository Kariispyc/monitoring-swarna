<?php

namespace App\Http\Controllers;

use App\Models\SensorLog;
use App\Models\DeviceControl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    // EKSPOR CSV HARIAN (1 BARIS PER HARI)
    public function exportCsv(Request $request)
    {
        $fileName = 'rekap_harian_swarna_' . date('Y-m-d') . '.csv';

        // Query Agregasi Rata-rata Harian dari Model SensorLog
        $logs = SensorLog::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('ROUND(AVG(avg_a), 1) as block_a'),
            DB::raw('ROUND(AVG(avg_b), 1) as block_b'),
            DB::raw('ROUND(AVG(avg_c), 1) as block_c'),
            DB::raw('ROUND(AVG(avg_d), 1) as block_d'),
            DB::raw('ROUND(AVG(avg_e), 1) as block_e'),
            DB::raw('ROUND(AVG(avg_f), 1) as block_f'),
            DB::raw('ROUND(AVG(avg_g), 1) as block_g'),
            DB::raw('ROUND(AVG(water_temp), 1) as suhu_air'),
            DB::raw('ROUND(AVG(air_temp), 1) as suhu_udara'),
            DB::raw('ROUND(AVG(air_humidity), 1) as kelembapan_udara')
        )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tanggal', 'DESC')
            ->get();

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'Tanggal',
            'Rata-rata Block A (%)',
            'Rata-rata Block B (%)',
            'Rata-rata Block C (%)',
            'Rata-rata Block D (%)',
            'Rata-rata Block E (%)',
            'Rata-rata Block F (%)',
            'Rata-rata Block G (%)',
            'Suhu Air (°C)',
            'Suhu Udara (°C)',
            'Kelembapan Udara (%)'
        ];

        $callback = function () use ($logs, $columns) {
            $file = fopen('php://output', 'w');

            // Tambahkan BOM UTF-8 agar Excel otomatis membaca format tulisan dan pemisah kolom
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Gunakan ';' sebagai delimiter agar otomatis terpisah per kolom di Excel Indonesia
            fputcsv($file, $columns, ';');

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->tanggal,
                    $log->block_a,
                    $log->block_b,
                    $log->block_c,
                    $log->block_d,
                    $log->block_e,
                    $log->block_f,
                    $log->block_g,
                    $log->suhu_air,
                    $log->suhu_udara,
                    $log->kelembapan_udara
                ], ';');
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
