<?php

namespace App\Http\Controllers\OrangTua; // Memastikan penulisan huruf besar/kecil sesuai standar Laravel

use App\Http\Controllers\Controller;
use App\Models\KonsultasiAhli; 
use Illuminate\Support\Facades\Auth;

class RiwayatJadwalController extends Controller
{
    public function index()
    {
        // Ambil riwayat booking milik orang tua yang sedang login KHUSUS DOKTER
        $jadwals = KonsultasiAhli::with(['ahli', 'anak']) 
            ->where('orangtua_id', Auth::id())
            ->where('kategori_ahli', 'dokter') // <-- INI KUNCI PEMISAHNYA
            ->orderBy('tanggal_jadwal', 'desc') 
            ->get();

        // Nama di dalam compact harus 'jadwals' agar terbaca di Blade Anda
        return view('orangtua.riwayat-jadwal', compact('jadwals'));
    }
}