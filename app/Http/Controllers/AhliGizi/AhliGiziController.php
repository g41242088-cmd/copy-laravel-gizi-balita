<?php

namespace App\Http\Controllers\AhliGizi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KonsultasiAhli;
use Illuminate\Support\Facades\Auth;

class AhliGiziController extends Controller
{
    public function dashboard()
    {
        $ahliId = Auth::id();

        $totalPermintaanBaru = KonsultasiAhli::where('ahli_id', $ahliId)->where('status', 'menunggu')->count();

        // Mengambil semua riwayat yang sudah disetujui/selesai
        $semuaKonsultasi = KonsultasiAhli::with(['anak', 'orangtua'])
            ->where('ahli_id', $ahliId)
            ->whereIn('status', ['disetujui', 'selesai'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Filter unik berdasarkan anak_id, lalu ambil 3 saja
        $pasienTerbaru = $semuaKonsultasi->unique('anak_id')->take(3)->values();
        $totalPasien = $semuaKonsultasi->unique('anak_id')->count();

        $permintaanMasuk = KonsultasiAhli::with(['anak', 'orangtua'])
            ->where('ahli_id', $ahliId)
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('ahligizi.dashboard', compact('totalPermintaanBaru', 'totalPasien', 'pasienTerbaru', 'permintaanMasuk'));
    }

    public function permintaanMasuk()
    {
        // Mengambil permintaan dengan status 'menunggu' untuk Ahli Gizi yang login
        $permintaans = KonsultasiAhli::with(['orangtua', 'anak'])
            ->where('ahli_id', Auth::id())
            ->where('status', 'menunggu')
            ->orderBy('tanggal_jadwal', 'asc')
            ->orderBy('jam_jadwal', 'asc')
            ->get();

        // Mengirimkan variabel $permintaans ke view
        return view('ahligizi.permintaan', compact('permintaans'));
    }

    public function jadwalSaya()
    {
        return view('ahligizi.jadwal-saya');
    }

    public function jamPraktik()
    {
        return view('ahligizi.jam-praktik');
    }

    public function daftarPasien()
    {
        // 1. Ambil semua riwayat konsultasi yang sudah "disetujui" atau "selesai" untuk Ahli Gizi yang login
        $semuaKonsultasi = KonsultasiAhli::with(['orangtua', 'anak'])
            ->where('ahli_id', Auth::id())
            ->whereIn('status', ['disetujui', 'selesai']) // Ditambah 'disetujui' agar pasien yang sedang berjalan juga masuk
            ->orderBy('tanggal_jadwal', 'desc')
            ->get();

        // 2. Filter agar nama anak tidak dobel di tabel (hanya ambil data konsultasi terbaru per anak)
        // WAJIB tambahkan ->values() agar index array ter-reset dengan benar untuk tabel dan JavaScript
        $pasiens = $semuaKonsultasi->unique('anak_id')->values();

        // Kirim $pasiens dan $semuaKonsultasi ke view
        return view('ahligizi.daftar-pasien', compact('pasiens', 'semuaKonsultasi'));
    }

    public function analisisGizi($id)
{
    // Tambahkan 'ahli' ke dalam with agar nama dokternya ikut terbawa
    $pasien = KonsultasiAhli::with(['anak', 'orangtua', 'ahli'])->findOrFail($id);
    
    $riwayatPengukuran = \App\Models\Pengukuran::where('anak_id', $pasien->anak_id)
        ->orderBy('umur_bulan', 'asc')
        ->get();

    return view('ahligizi.analisis', compact('pasien', 'riwayatPengukuran'));
}

    // 👇 FUNGSI BARU UNTUK MENYIMPAN HASIL ANALISIS 👇
    public function simpanAnalisis(Request $request, $id)
    {
        // Validasi input dari form (tanggal_kontrol opsional)
        $request->validate([
            'assessment' => 'required|string',
            'plan' => 'required|string',
            'tanggal_kontrol' => 'nullable|date', // Tambahkan validasi ini
        ]);

        $konsultasi = KonsultasiAhli::findOrFail($id);

        // TRIK CERDAS: Gabungkan tanggal kontrol ke dalam teks Plan (Rekomendasi)
        $rekomendasiAkhir = $request->plan;
        
        // Jika Ahli Gizi mengisi tanggal kontrol, tambahkan kalimat di bawah rekomendasi
        if ($request->filled('tanggal_kontrol')) {
            $tanggalIndo = \Carbon\Carbon::parse($request->tanggal_kontrol)->translatedFormat('d F Y');
            $rekomendasiAkhir .= "\n\n📌 Jadwal Kontrol Selanjutnya: " . $tanggalIndo;
        }

        // Pastikan hanya Ahli Gizi yang bersangkutan yang bisa menyimpan datanya
        if ($konsultasi->ahli_id == Auth::id()) {
            $konsultasi->update([
                'assessment' => $request->assessment, 
                'plan' => $rekomendasiAkhir, // Simpan teks yang sudah digabung        
                'status' => 'selesai' 
            ]);
        }

        return redirect()->route('ahligizi.pasien.index')->with('success', 'Analisis Gizi berhasil disimpan!');
    }

    // Fungsi khusus untuk Ahli Gizi menerima/menolak permintaan
    public function updateStatus(Request $request, $id)
    {
        $permintaan = KonsultasiAhli::findOrFail($id);

        // Pastikan hanya Ahli Gizi yang bersangkutan yang bisa mengubah statusnya
        if ($permintaan->ahli_id == Auth::id()) {
            $permintaan->update([
                'status' => $request->status // 'disetujui' atau 'ditolak'
            ]);
        }

        // Mengembalikan halaman ke tempat Ahli Gizi berada tanpa nyasar ke halaman Dokter
        return redirect()->back()->with('success', 'Status permintaan berhasil diperbarui!');
    }
}