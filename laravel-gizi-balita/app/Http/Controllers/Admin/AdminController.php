<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// PENTING: Panggil semua model yang dibutuhkan untuk menghitung statistik
use App\Models\User;
use App\Models\Anak;
use App\Models\KonsultasiAhli;
use App\Models\Pengukuran;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Menampilkan halaman utama (Dashboard) untuk Admin
     */
    public function dashboard()
    {
        // 1. Data Kartu Statistik Atas
        $totalPengguna = User::count();
        $anakTerdaftar = Anak::count();
        $tenagaMedis = User::whereIn('role', ['dokter', 'ahligizi'])->count();
        $totalKonsultasi = KonsultasiAhli::count();

        // 2. Daftar Tenaga Medis Aktif (Ambil 5 data terbaru)
        $daftarMedis = User::whereIn('role', ['dokter', 'ahligizi'])
                           ->orderBy('created_at', 'desc')
                           ->take(3)
                           ->get();

        // 3. Data Grafik Distribusi Role Pengguna (Doughnut Chart)
        $roleData = [
            User::where('role', 'orangtua')->count(),
            User::where('role', 'dokter')->count(),
            User::where('role', 'ahligizi')->count(),
            User::where('role', 'admin')->count()
        ];

        // 4. Data Grafik Status Gizi Nasional (Pie Chart)
        $giziData = [
            Pengukuran::where('status_gizi', 'normal')->count(),
            Pengukuran::where('status_gizi', 'kurang_gizi')->count(),
            Pengukuran::where('status_gizi', 'obesitas')->count(),
            Pengukuran::where('status_gizi', 'stunting')->count() 
        ];

        // 5. Data Grafik Tren Konsultasi (Line Chart - 6 Bulan Terakhir)
        $bulanLabels = [];
        $konsultasiData = [];
        
        // Looping mundur dari 5 bulan lalu sampai bulan ini (0)
        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $bulanLabels[] = $bulan->translatedFormat('M'); // Contoh: Jan, Feb, Mar
            
            // Hitung total konsultasi di bulan dan tahun tersebut
            $jumlah = KonsultasiAhli::whereMonth('created_at', $bulan->month)
                                    ->whereYear('created_at', $bulan->year)
                                    ->count();
            $konsultasiData[] = $jumlah;
        }

        // Kirim semua data variabel di atas ke view 'admin.dashboard'
        return view('admin.dashboard', compact(
            'totalPengguna', 'anakTerdaftar', 'tenagaMedis', 'totalKonsultasi',
            'daftarMedis', 'roleData', 'giziData', 'bulanLabels', 'konsultasiData'
        ));
    }
}