<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\KonsultasiAhli;

class PermintaanController extends Controller
{
    /**
     * Menampilkan daftar permintaan konsultasi yang masih berstatus 'menunggu'.
     */
    public function index()
    {
        // Mengambil data konsultasi milik dokter yang sedang login
        // difilter hanya yang statusnya 'menunggu'
        $permintaans = KonsultasiAhli::with(['orangtua', 'anak']) 
            ->where('ahli_id', Auth::id())
            ->where('status', 'menunggu')
            // Diurutkan berdasarkan tanggal jadwal terdekat agar dokter tahu mana yang harus didahulukan
            ->orderBy('tanggal_jadwal', 'asc') 
            ->orderBy('jam_jadwal', 'asc')
            ->get();

        return view('dokter.permintaan', compact('permintaans'));
    }

    /**
     * Memperbarui status konsultasi (Terima, Tolak, atau Selesai).
     */
    public function updateStatus(Request $request, $id)
    {
        // Validasi input status
        $request->validate([
            'status' => 'required|in:menunggu,disetujui,selesai,ditolak'
        ]);

        $booking = KonsultasiAhli::findOrFail($id);
        
        // Proteksi Keamanan: Memastikan dokter hanya bisa mengedit miliknya sendiri
        if ($booking->ahli_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke data ini.');
        }

        // Update status sesuai kiriman (disetujui/selesai/ditolak)
        $booking->status = $request->status; 
        $booking->save();

        // Pesan notifikasi yang lebih informatif
        if ($request->status == 'selesai') {
            $message = 'Konsultasi selesai dan data telah dipindahkan ke Daftar Pasien.';
        } elseif ($request->status == 'ditolak') {
            $message = 'Permintaan jadwal telah ditolak.';
        } else {
            $message = 'Status permintaan berhasil diperbarui.';
        }

        return redirect()->route('dokter.permintaan.index')->with('success', $message);
    }
}