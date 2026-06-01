<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // <-- Wajib untuk menangkap input filter
use App\Models\KonsultasiAhli;
use Barryvdh\DomPDF\Facade\Pdf; 
use Carbon\Carbon; // <-- Wajib untuk filter waktu (bulan ini/lalu)

class KonsultasiController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data dasar konsultasi beserta relasi yang dibutuhkan
        $query = KonsultasiAhli::with(['anak', 'orangtua', 'ahli']);

        // 2. FUNGSI SEARCH (Cari nama pasien anak, orang tua, atau nama tenaga medis)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Cari di tabel relasi ahli (tenaga medis)
                $q->whereHas('ahli', function($qMedis) use ($search) {
                    $qMedis->where('name', 'like', "%{$search}%");
                })
                // Cari di tabel relasi anak (pasien)
                ->orWhereHas('anak', function($qAnak) use ($search) {
                    $qAnak->where('nama', 'like', "%{$search}%");
                })
                // Cari di kolom langsung (sebagai cadangan jika data relasi kosong)
                ->orWhere('nama_anak', 'like', "%{$search}%")
                ->orWhere('nama_ortu', 'like', "%{$search}%");
            });
        }

        // 3. FUNGSI FILTER LAYANAN (Dokter / Ahli Gizi)
        if ($request->filled('layanan') && $request->layanan != 'semua') {
            $query->where(function($q) use ($request) {
                $q->where('kategori_ahli', $request->layanan)
                  ->orWhereHas('ahli', function($qAhli) use ($request) {
                      $qAhli->where('role', $request->layanan);
                  });
            });
        }

        // 4. FUNGSI FILTER STATUS (Selesai, Menunggu, Batal)
        if ($request->filled('status') && $request->status != 'semua') {
            if ($request->status == 'batal') {
                $query->whereIn('status', ['batal', 'ditolak', 'dibatalkan']);
            } else {
                $query->where('status', 'like', "%{$request->status}%");
            }
        }

        // 5. FUNGSI FILTER WAKTU (Bulan Ini / Bulan Lalu)
        if ($request->filled('waktu') && $request->waktu != 'semua') {
            $now = Carbon::now();
            
            if ($request->waktu == 'bulan_ini') {
                $query->whereMonth('tanggal_jadwal', $now->month)
                      ->whereYear('tanggal_jadwal', $now->year);
            } elseif ($request->waktu == 'bulan_lalu') {
                $lastMonth = $now->copy()->subMonth();
                $query->whereMonth('tanggal_jadwal', $lastMonth->month)
                      ->whereYear('tanggal_jadwal', $lastMonth->year);
            }
        }

        // Urutkan dari jadwal yang paling baru, lalu eksekusi
        $semuaKonsultasi = $query->orderBy('tanggal_jadwal', 'desc')
                                 ->orderBy('jam_jadwal', 'desc')
                                 ->get();

        return view('admin.konsultasi', compact('semuaKonsultasi'));
    }

    // 1. FUNGSI UNTUK MENGHAPUS DATA
    public function destroy($id)
    {
        $konsultasi = KonsultasiAhli::findOrFail($id);
        $konsultasi->delete();

        return redirect()->back()->with('success', 'Data riwayat konsultasi berhasil dihapus!');
    }

    // 2. FUNGSI UNTUK EXPORT PDF
    public function exportPdf()
    {
        $semuaKonsultasi = KonsultasiAhli::with(['anak', 'orangtua', 'ahli'])
                                ->orderBy('tanggal_jadwal', 'desc')
                                ->orderBy('jam_jadwal', 'desc')
                                ->get();

        // Menghitung ringkasan statistik untuk PDF
        $totalKonsultasi = $semuaKonsultasi->count();
        $totalDokter = $semuaKonsultasi->where('kategori_ahli', 'dokter')->count();
        $totalGizi = $semuaKonsultasi->where('kategori_ahli', '!=', 'dokter')->count();
        
        $totalSelesai = $semuaKonsultasi->filter(function($item) {
            return str_contains(strtolower($item->status), 'selesai');
        })->count();

        // Meload file view khusus PDF
        $pdf = Pdf::loadView('admin.konsultasi-pdf', compact(
            'semuaKonsultasi', 'totalKonsultasi', 'totalDokter', 'totalGizi', 'totalSelesai'
        ));

        // Mendownload filenya
        return $pdf->download('Laporan_Konsultasi_GiziAnak.pdf');
    }
}