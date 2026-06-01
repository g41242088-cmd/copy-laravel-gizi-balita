<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengukuran; // Pastikan Model Pengukuran Anda benar namanya
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // 1. Tangkap inputan dari form filter (Bulan & Tahun)
        // Jika baru pertama kali buka halaman, otomatis pakai bulan & tahun SAAT INI
        $filterBulan = $request->input('bulan', date('m'));
        $filterTahun = $request->input('tahun', date('Y'));

        // 2. Ambil data dari database HANYA untuk Bulan dan Tahun yang dipilih
        $semuaPengukuran = Pengukuran::with('anak')
            ->whereMonth('created_at', $filterBulan)
            ->whereYear('created_at', $filterTahun)
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Hindari duplikasi (Jika 1 anak diukur 2x sebulan, ambil data terbarunya saja)
        $uniqueData = $semuaPengukuran->unique('anak_id');

        // 4. Hitung Statistik
        $total = $uniqueData->count();
        
        $normal = $uniqueData->filter(function($q) {
            $status = strtolower($q->status_gizi ?? '');
            return str_contains($status, 'normal') || str_contains($status, 'baik');
        })->count();

        $stunting = $uniqueData->filter(function($q) {
            $status = strtolower($q->status_gizi ?? '');
            return str_contains($status, 'stunting') || str_contains($status, 'buruk') || str_contains($status, 'kurang');
        })->count();

        // Data untuk Kotak Widget Atas
        $stats = [
            'total' => $total,
            'normal' => $normal,
            'stunting' => $stunting
        ];

        // Hitung Persentase
        $persenNormal = $total > 0 ? round(($normal / $total) * 100) : 0;
        $persenStunting = $total > 0 ? round(($stunting / $total) * 100) : 0;

        // 5. Siapkan Data untuk Tabel Bawah
        $periode = Carbon::createFromDate($filterTahun, $filterBulan, 1)->translatedFormat('F Y');
        $laporanTable = [];
        
        // Tabel hanya akan menampilkan baris jika ada data di bulan tersebut
        if ($total > 0) {
            $laporanTable[] = [
                'periode' => $periode,
                'bulan_angka' => str_pad($filterBulan, 2, '0', STR_PAD_LEFT),
                'tahun_angka' => $filterTahun,
                'total' => $total,
                'normal' => $normal,
                'stunting' => $stunting
            ];
        }

        // Kirim semua variabel ke view laporan.blade.php
        return view('admin.laporan', compact(
            'laporanTable', 'stats', 'filterBulan', 'filterTahun', 'persenNormal', 'persenStunting'
        ));
    }

    // FUNGSI UNTUK EXPORT PDF (Yang dipanggil oleh tombol di tabel)
    public function exportPdf($bulan, $tahun)
    {
        $dataPengukuran = Pengukuran::with('anak')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('anak_id');

        $periode = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y');
        $total = $dataPengukuran->count();

        // Memanggil file laporan-pdf.blade.php yang sudah kita buat sebelumnya
        $pdf = Pdf::loadView('admin.laporan-pdf', compact('dataPengukuran', 'periode', 'total'));
        
        return $pdf->download('Laporan_Gizi_'.$periode.'.pdf');
    }
}