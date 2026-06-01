<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\KonsultasiAhli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\JamPraktik;

class DokterController extends Controller
{
    public function dashboard()
    {
        $doctorId = Auth::id();
        $hariIni = \Carbon\Carbon::today()->format('Y-m-d');

        // 1. STATISTIK
        $countHariIni = KonsultasiAhli::where('ahli_id', $doctorId)
                            ->where('tanggal_jadwal', $hariIni)
                            ->where('status', 'disetujui')
                            ->count();

        $countTotalPasien = KonsultasiAhli::where('ahli_id', $doctorId)
                            ->where('status', 'selesai')
                            ->distinct('anak_id')
                            ->count('anak_id');

        $countMenunggu = KonsultasiAhli::where('ahli_id', $doctorId)
                            ->where('status', 'menunggu')
                            ->count();

        // 2. DATA LIST
        $jadwalHariIni = KonsultasiAhli::with(['orangtua', 'anak'])
                            ->where('ahli_id', $doctorId)
                            ->where('tanggal_jadwal', $hariIni)
                            ->where('status', 'disetujui')
                            ->orderBy('jam_jadwal', 'asc')
                            ->get();

        $permintaanMenunggu = KonsultasiAhli::with(['orangtua', 'anak'])
                            ->where('ahli_id', $doctorId)
                            ->where('status', 'menunggu')
                            ->orderBy('created_at', 'asc')
                            ->take(3)
                            ->get();

        // 3. 3 Pasien Terakhir
        $pasienTerbaru = KonsultasiAhli::with(['orangtua', 'anak'])
                            ->where('ahli_id', $doctorId)
                            ->where('status', 'selesai')
                            ->orderBy('tanggal_jadwal', 'desc')
                            ->take(3)
                            ->get();

        return view('dokter.dashboard', compact(
            'countHariIni', 'countTotalPasien', 'countMenunggu', 
            'jadwalHariIni', 'permintaanMenunggu', 'pasienTerbaru'
        ));
    }

    public function permintaanMasuk()
    {
        $permintaans = KonsultasiAhli::with(['orangtua', 'anak'])
            ->where('ahli_id', Auth::id())
            ->where('status', 'menunggu')
            ->orderBy('tanggal_jadwal', 'asc')
            ->orderBy('jam_jadwal', 'asc')
            ->get();

        return view('dokter.permintaan', compact('permintaans'));
    }

    public function jadwalPemeriksaan()
    {
        return view('dokter.jadwal-pemeriksaan');
    }

    public function jamPraktik()
    {
        $jadwal = JamPraktik::where('ahli_id', Auth::id())->get()->keyBy('hari');
        return view('dokter.jam-praktik', compact('jadwal'));
    }

    public function updateJamPraktik(Request $request)
    {
        $haris = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];

        foreach ($haris as $hari) {
            JamPraktik::updateOrCreate(
                ['ahli_id' => Auth::id(), 'hari' => $hari], 
                [
                    'is_active'   => $request->has("jadwal.{$hari}.is_active"), 
                    'jam_mulai'   => $request->input("jadwal.{$hari}.jam_mulai"),
                    'jam_selesai' => $request->input("jadwal.{$hari}.jam_selesai"),
                ]
            );
        }

        return redirect()->back()->with('success', 'Jadwal praktik berhasil diperbarui!');
    }

    // 👇 INI ADALAH FUNGSI YANG KITA UBAH 👇
    public function daftarPasien(Request $request)
    {
        // Panggil relasi sekalian agar tidak error di Blade saat menghitung umur
        $query = KonsultasiAhli::with(['anak', 'orangtua'])
                    ->where('ahli_id', Auth::id())
                    ->where('status', 'selesai');

        // 1. FUNGSI SEARCH
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_anak', 'like', "%{$search}%")
                  ->orWhere('nama_ortu', 'like', "%{$search}%");
            });
        }

        // 2. FUNGSI SORTING (Dropdown)
        if ($request->filled('sort') && $request->sort == 'nama_a_z') {
            $query->orderBy('nama_anak', 'asc');
        } else {
            // Default: Pemeriksaan Terbaru
            $query->orderBy('tanggal_jadwal', 'desc')->orderBy('jam_jadwal', 'desc');
        }

        $pasiens = $query->get();

        return view('dokter.daftar-pasien', compact('pasiens'));
    }
    // 👆 SAMPAI SINI 👆

    public function catatanMedis()
    {
        return redirect()->route('dokter.pasien.index')
                         ->with('info', 'Silakan klik tombol "Catatan Medis" pada salah satu pasien di bawah ini.');
    }

    public function catatanMedisDetail($id)
    {
        $pasien = KonsultasiAhli::with(['orangtua', 'anak'])->findOrFail($id);
        return view('dokter.catatan', compact('pasien'));
    }

    public function storeCatatan(Request $request, $id)
    {
        $request->validate([
            'subjective' => 'nullable|string',
            'suhu'       => 'nullable|string',
            'objective'  => 'nullable|string',
            'assessment' => 'nullable|string',
            'plan'       => 'nullable|string', 
            'vaksin'     => 'nullable|string',
        ]);

        $pasien = KonsultasiAhli::findOrFail($id);

        $pasien->update([
            'subjective' => $request->subjective,
            'suhu'       => $request->suhu,
            'objective'  => $request->objective,
            'assessment' => $request->assessment,
            'plan'       => $request->plan,
            'vaksin'     => $request->vaksin,
        ]);

        return redirect()->back()->with('success', 'Catatan medis SOAP berhasil disimpan!');
    }

    // ==========================================
    // FUNGSI PROFIL & KEAMANAN BARU
    // ==========================================

    public function profil()
    {
        $user = Auth::user();
        
        // Hitung total pasien untuk ditampilkan di kartu profil kiri
        $totalPasien = KonsultasiAhli::where('ahli_id', $user->id)
                            ->where('status', 'selesai')
                            ->distinct('anak_id')
                            ->count('anak_id');

        return view('dokter.profil', compact('user', 'totalPasien'));
    }

    public function updateProfil(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validasi disesuaikan dengan kolom terbaru
        $request->validate([
            'name'           => 'required|string|max:255',
            'spesialisasi'   => 'nullable|string|max:255',
            'no_sip'         => 'nullable|string|max:255',
            'no_telepon'     => 'nullable|string|max:20', 
            'alamat_praktik' => 'nullable|string|max:255',
            'bio'            => 'nullable|string',
        ]);

        // Simpan data ke database
        $user->update([
            'name'           => $request->name,
            'spesialisasi'   => $request->spesialisasi,
            'no_sip'         => $request->no_sip,
            'no_telepon'     => $request->no_telepon,
            'alamat_praktik' => $request->alamat_praktik,
            'bio'            => $request->bio,
        ]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Cek apakah password lama yang diketik sesuai dengan di database
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Password lama tidak sesuai!');
        }

        // Simpan password baru
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with('success', 'Password berhasil diperbarui!');
    }

    public function downloadPDF($id)
    {
        $pasien = KonsultasiAhli::with(['orangtua', 'anak.pengukurans'])->findOrFail($id);
        $cekGiziTerbaru = $pasien->anak->pengukurans()->latest()->first();

        $pdf = Pdf::loadView('dokter.catatan_pdf', compact('pasien', 'cekGiziTerbaru'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Catatan_Medis_'.$pasien->nama_anak.'.pdf');
    }
}