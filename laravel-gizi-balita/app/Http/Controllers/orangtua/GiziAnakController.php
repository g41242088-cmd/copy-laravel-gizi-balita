<?php

namespace App\Http\Controllers\orangtua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anak;
use App\Models\Pengukuran;
use App\Models\User;
use App\Models\KonsultasiAhli;
use App\Models\Artikel; // 👈 PENTING: Tambahkan model Artikel di sini
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class GiziAnakController extends Controller
{
    // =======================================================
    // FITUR DASHBOARD / BERANDA ORANG TUA
    // =======================================================
    
    public function dashboard()
    {
        $userId = Auth::id();

        // 1. Hitung Total Cek Gizi (Berdasarkan anak dari orang tua ini)
        $totalCekGizi = Pengukuran::whereHas('anak', function($query) use ($userId) {
            $query->where('orangtua_id', $userId);
        })->count();

        // 2. Hitung Total Konsultasi Ahli
        $totalKonsultasi = KonsultasiAhli::where('orangtua_id', $userId)->count();

        // 3. Hitung Rata-rata IMT
        $semuaPengukuran = Pengukuran::whereHas('anak', function($query) use ($userId) {
            $query->where('orangtua_id', $userId);
        })->get();
        
        $totalImt = 0;
        foreach ($semuaPengukuran as $p) {
            $imt = $p->berat_badan / pow($p->tinggi_badan / 100, 2);
            $totalImt += $imt;
        }
        $rataImt = $totalCekGizi > 0 ? number_format($totalImt / $totalCekGizi, 1) : 0;

        // 4. Ambil 3 Riwayat Cek Gizi Terbaru untuk ditampilkan di beranda
        $riwayatTerbaru = Pengukuran::with('anak')
            ->whereHas('anak', function($query) use ($userId) {
                $query->where('orangtua_id', $userId);
            })
            ->orderBy('tanggal_ukur', 'desc')
            ->take(3)
            ->get();

        // Pastikan nama view ini sesuai (orangtua.dashboard atau orangtua.beranda)
        return view('orangtua.dashboard', compact('totalCekGizi', 'totalKonsultasi', 'rataImt', 'riwayatTerbaru'));
    }

    // =======================================================
    // FITUR CEK GIZI & RIWAYAT GIZI
    // =======================================================

    // 1. Menampilkan Halaman Cek Gizi
    public function index()
    {
        $anaks = Anak::where('orangtua_id', Auth::id())->get();
        return view('orangtua.cekgizi', compact('anaks'));
    }

    // 2. Memproses Perhitungan Gizi
    public function hitung(Request $request)
    {
        $request->validate([
            'anak_id' => 'required',
            'umur_bulan' => 'required|numeric|min:0',
            'berat_badan' => 'required|numeric',
            'tinggi_badan' => 'required|numeric',
        ]);

        $anak = Anak::findOrFail($request->anak_id);
        $umur_bulan = $request->umur_bulan;

        // Logika Status Gizi Sederhana (IMT)
        $imt = $request->berat_badan / pow($request->tinggi_badan/100, 2);
        
        if ($imt < 14) { $status = 'kurang_gizi'; }
        elseif ($imt > 23) { $status = 'obesitas'; }
        else { $status = 'normal'; }

        $pengukuran = Pengukuran::create([
            'anak_id' => $anak->id,
            'tanggal_ukur' => now(),
            'umur_bulan' => $umur_bulan,
            'berat_badan' => $request->berat_badan,
            'tinggi_badan' => $request->tinggi_badan,
            'status_gizi' => $status,
        ]);

        return redirect()->back()->with([
            'hasil' => $pengukuran,
            'nama_anak' => $anak->nama
        ]);
    }

    // 3. Menampilkan Riwayat Gizi & Grafik
    public function riwayat()
    {
        $userId = Auth::id();

        // A. Ambil data untuk Tabel
        $pengukurans = Pengukuran::with('anak')
            ->whereHas('anak', function($query) use ($userId) {
                $query->where('orangtua_id', $userId);
            })
            ->orderBy('tanggal_ukur', 'desc')
            ->get();

        // B. Ambil data untuk Grafik
        $pengukuransAsc = Pengukuran::with('anak')
            ->whereHas('anak', function($query) use ($userId) {
                $query->where('orangtua_id', $userId);
            })
            ->orderBy('tanggal_ukur', 'asc')
            ->get();

        // Menghitung Statistik
        $totalPemeriksaan = $pengukurans->count();
        $anakTercatat = $pengukurans->unique('anak_id')->count();
        
        $totalImt = 0;
        $statusNormal = 0;

        foreach ($pengukurans as $p) {
            $imt_val = $p->berat_badan / pow($p->tinggi_badan / 100, 2);
            $totalImt += $imt_val;
            if ($p->status_gizi == 'normal') {
                $statusNormal++;
            }
        }

        $rataImt = $totalPemeriksaan > 0 ? number_format($totalImt / $totalPemeriksaan, 1) : 0;
        $persenNormal = $totalPemeriksaan > 0 ? round(($statusNormal / $totalPemeriksaan) * 100) : 0;

        // Menyiapkan Data Grafik (JSON)
        $chartData = [];
        foreach ($pengukuransAsc as $p) {
            $imt = round($p->berat_badan / pow($p->tinggi_badan / 100, 2), 1);
            $tanggal = Carbon::parse($p->tanggal_ukur)->translatedFormat('d M');

            if (!isset($chartData[$p->anak_id])) {
                $chartData[$p->anak_id] = ['labels' => [], 'data' => []];
            }
            $chartData[$p->anak_id]['labels'][] = $tanggal;
            $chartData[$p->anak_id]['data'][] = $imt;
        }

        $anaks = Anak::where('orangtua_id', $userId)->get();

        return view('orangtua.riwayat-gizi', compact(
            'pengukurans', 'totalPemeriksaan', 'anakTercatat', 'rataImt', 
            'statusNormal', 'persenNormal', 'anaks', 'chartData'
        ));
    }

    // 4. Menghapus Riwayat Gizi
    public function destroy($id)
    {
        $pengukuran = Pengukuran::findOrFail($id);
        
        if ($pengukuran->anak->orangtua_id == Auth::id()) {
            $pengukuran->delete();
            return redirect()->back()->with('success', 'Data riwayat pengukuran berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Gagal menghapus. Anda tidak memiliki akses ke data ini.');
    }


    // =======================================================
    // FITUR BOOKING & KONSULTASI AHLI
    // =======================================================

    // 5. Menampilkan Halaman Konsultasi & Daftar Ahli
    public function konsultasi()
    {
        // Ambil data user rolenya 'ahligizi' BESERTA jadwal praktiknya
        $ahliGizi = User::with('jamPraktiks')->where('role', 'ahligizi')->get();
        return view('orangtua.konsultasi', compact('ahliGizi'));
    }

    // 6. Menyimpan Form Booking Konsultasi
    public function storeKonsultasi(Request $request)
    {
        // Validasi
        $request->validate([
            'ahli_gizi_id' => 'required|exists:users,id',
            'nama_ortu' => 'required|string|max:255',
            'nama_anak' => 'required|string|max:255',
            'umur_anak' => 'required|string|max:50',
            'jenis_kelamin_anak' => 'required|in:L,P',
            'no_wa' => 'required|string|max:20',
            'catatan' => 'nullable|string',
        ]);

        KonsultasiAhli::create([
            'orangtua_id' => Auth::id(),
            'ahli_id' => $request->ahli_gizi_id,
            'nama_ortu' => $request->nama_ortu,
            'nama_anak' => $request->nama_anak,
            'umur_anak' => $request->umur_anak,
            'jenis_kelamin_anak' => $request->jenis_kelamin_anak,
            'no_wa' => $request->no_wa,
            'catatan' => $request->catatan,
            'status' => 'menunggu', 
        ]);

        return redirect()->back()->with('success', 'Permintaan booking berhasil dikirim! Silakan tunggu pesan WhatsApp dari tenaga ahli terkait.');
    }

    // 7. MENAMPILKAN RIWAYAT KONSULTASI
    public function riwayatKonsultasi()
    {
        // 1. Nama variabel diubah menjadi $riwayat (tanpa 's') agar dikenali oleh Blade
        $riwayat = \App\Models\KonsultasiAhli::with(['anak', 'ahli'])
            ->where('orangtua_id', \Illuminate\Support\Facades\Auth::id())
            ->where('kategori_ahli', 'ahligizi') // 2. Memastikan hanya data Ahli Gizi yang ditarik
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Mengirimkan compact('riwayat')
        return view('orangtua.riwayat-konsultasi', compact('riwayat'));
    }
    

    // =======================================================
    // FITUR LAINNYA
    // =======================================================

    public function booking()
    {
        // Khusus halaman Booking Dokter
        $dokters = User::with('jamPraktiks')->where('role', 'dokter')->get();
        return view('orangtua.booking', compact('dokters'));
    }

    public function riwayatJadwal()
    {
        return view('orangtua.riwayat-jadwal');
    }
    
    // 👇 FITUR TIPS GIZI YANG DIPERBARUI 👇
    public function tips()
    {
        // Hanya ambil artikel yang statusnya 'terbit', diurutkan dari yang terbaru
        $tipsGizi = Artikel::where('status', 'terbit')
                           ->orderBy('updated_at', 'desc')
                           ->get();
                           
        return view('orangtua.tips', compact('tipsGizi'));
    }
    // 👆 =================================== 👆
    
    // 8. MENAMPILKAN HALAMAN AKUN & STATISTIK PROFIL
    public function akun()
    {
        $user = Auth::user();

        // Ambil Data Anak untuk ditampilkan di Tab Data Anak
        $anaks = Anak::where('orangtua_id', $user->id)->get();

        // Menghitung statistik profil
        $totalAnak = $anaks->count();
        $totalCekGizi = Pengukuran::whereHas('anak', function($query) use ($user) {
            $query->where('orangtua_id', $user->id);
        })->count();
        $totalKonsultasi = KonsultasiAhli::where('orangtua_id', $user->id)->count();

        // Tambahkan '$anaks' ke dalam compact
        return view('orangtua.akun', compact('user', 'totalAnak', 'totalCekGizi', 'totalKonsultasi', 'anaks'));
    }

    // 9. MEMPROSES UPDATE DATA AKUN
    public function updateAkun(Request $request)
    {
        $user = User::find(Auth::id());

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'kota' => 'nullable|string|max:100',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_lahir' => 'nullable|date',
        ]);

        // Update semua data ke database
        $user->name = $request->name;
        $user->no_telepon = $request->no_telepon;
        $user->kota = $request->kota;
        $user->jenis_kelamin = $request->jenis_kelamin;
        $user->tanggal_lahir = $request->tanggal_lahir;
        
        $user->save();

        return redirect()->back()->with('success', 'Profil akun Anda berhasil diperbarui!');
    }

    // 10. MEMPROSES UPDATE PASSWORD
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed', 
        ]);

        $user = User::find(Auth::id());

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Password saat ini yang Anda masukkan salah!');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Kata sandi berhasil diperbarui! Silakan gunakan sandi baru pada login berikutnya.');
    }
}