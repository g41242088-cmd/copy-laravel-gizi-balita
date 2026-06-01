<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Anak;
use App\Models\User;
use App\Models\KonsultasiAhli;
use App\Models\JamPraktik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Halaman khusus Booking Dokter (Hanya memunculkan role 'dokter')
     */
    public function indexDokter()
    {
        $anaks = Anak::where('orangtua_id', Auth::id())->get();
        // Tetap menggunakan nama variabel $dokters agar sesuai dengan file blade Anda
        $dokters = User::where('role', 'dokter')->get();

        return view('orangtua.booking', compact('anaks', 'dokters'));
    }

    /**
     * Halaman khusus Konsultasi Ahli Gizi (Hanya memunculkan role 'ahligizi')
     */
    public function indexAhliGizi()
    {
        $anaks = Anak::where('orangtua_id', Auth::id())->get();
        // Tetap menggunakan nama variabel $dokters agar sesuai dengan file blade Anda
        $dokters = User::where('role', 'ahligizi')->get();

        return view('orangtua.konsultasi', compact('anaks', 'dokters'));
    }

    /**
     * Memproses pengiriman form (Dipakai bersama oleh Dokter & Ahli Gizi)
     */
    public function store(Request $request)
    {
        $request->validate([
            'anak_id' => 'required|exists:anaks,id',
            'ahli_id' => 'required|exists:users,id',
            'tanggal_jadwal' => 'required|date|after_or_equal:today',
            'jam_jadwal' => 'required',
            'no_wa' => 'required|string|max:20',
            'catatan' => 'nullable|string',
        ]);

        try {
            $anak = Anak::findOrFail($request->anak_id);
            $tenagaMedis = User::findOrFail($request->ahli_id);
            $umur = \Carbon\Carbon::parse($anak->tanggal_lahir)->diffInMonths(now());

            KonsultasiAhli::create([
                'orangtua_id' => Auth::id(),
                'anak_id' => $request->anak_id,
                'ahli_id' => $request->ahli_id,

                // Mendeteksi otomatis apakah ini dokter atau ahli gizi dari database
                'kategori_ahli' => $tenagaMedis->role,

                'nama_ortu' => Auth::user()->name,
                'nama_anak' => $anak->nama,
                'umur_anak' => $umur,
                'jenis_kelamin_anak' => $anak->jenis_kelamin,
                'tanggal_jadwal' => $request->tanggal_jadwal,
                'jam_jadwal' => $request->jam_jadwal,
                'no_wa' => $request->no_wa,
                'catatan' => $request->catatan,
                'status' => 'menunggu',
            ]);

            // POLISI LALU LINTAS: Cek role-nya untuk menentukan arah redirect
            if ($tenagaMedis->role === 'ahligizi') {
                return redirect()->route('orangtua.riwayat-konsultasi.index')
                                 ->with('success', 'Jadwal Konsultasi Ahli Gizi berhasil diajukan!');
            } else {
                return redirect()->route('orangtua.riwayat-jadwal.index')
                                 ->with('success', 'Booking Jadwal Dokter berhasil diajukan!');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Mengecek ketersediaan jam praktik (AJAX)
     */
    public function cekJamTersedia(Request $request)
    {
        $tanggal = $request->tanggal;
        $ahli_id = $request->ahli_id;

        // 1. PERBAIKAN: Ubah format hari menjadi Huruf Kapital Awal (Senin, Selasa, dll)
        $namaHariInggris = date('l', strtotime($tanggal));
        $hariIndo = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $hari = $hariIndo[$namaHariInggris];

        // 2. Ambil jadwal dari database
        $jadwal = JamPraktik::where('ahli_id', $ahli_id)
            ->where('hari', $hari)
            ->first();

        // 3. PERBAIKAN KRUSIAL: Cek status buka/tutup menggunakan is_aktif ATAU is_active
        $statusPraktik = $jadwal ? ($jadwal->is_aktif ?? $jadwal->is_active ?? false) : false;

        if (!$jadwal || !$statusPraktik) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mohon maaf, tenaga medis tidak praktik pada hari ' . $hari . '. Silakan pilih tanggal lain.'
            ]);
        }

        // 4. KUNCI PENYELESAIAN MASALAH: Cek kolom jam_buka ATAU jam_mulai
        // Sistem akan cerdas mengambil mana saja yang tidak NULL
        $jamMulaiPraktik = $jadwal->jam_buka ?? $jadwal->jam_mulai;
        $jamSelesaiPraktik = $jadwal->jam_tutup ?? $jadwal->jam_selesai;

        // Jika kebetulan dua-duanya kosong di database
        if (!$jamMulaiPraktik || !$jamSelesaiPraktik) {
             return response()->json([
                'status' => 'error',
                'message' => 'Jam praktik belum diatur oleh Admin. Silakan pilih tanggal lain.'
            ]);
        }

        $start = strtotime($jamMulaiPraktik);
        $end = strtotime($jamSelesaiPraktik);
        $slots = [];

        while ($start < $end) {
            $timeString = date('H:i', $start);
            $timeStringDB = date('H:i:s', $start);

            $isBooked = KonsultasiAhli::where('ahli_id', $ahli_id)
                ->where('tanggal_jadwal', $tanggal)
                ->where(function ($query) use ($timeString, $timeStringDB) {
                    $query->where('jam_jadwal', $timeString)
                        ->orWhere('jam_jadwal', $timeStringDB);
                })
                ->whereIn('status', ['menunggu', 'disetujui'])
                ->exists();

            $slots[] = [
                'time' => $timeString,
                'is_booked' => $isBooked
            ];

            // Tambah 60 menit
            $start = strtotime('+60 minutes', $start);
        }

        return response()->json(['status' => 'success', 'slots' => $slots]);
    }
}