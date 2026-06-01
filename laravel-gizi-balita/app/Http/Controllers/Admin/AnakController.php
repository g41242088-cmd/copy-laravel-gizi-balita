<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anak;
use Carbon\Carbon; // Jangan lupa tambahkan ini untuk memanipulasi tanggal/umur

class AnakController extends Controller
{
    /**
     * Menampilkan daftar anak dengan fitur Search dan Filter
     */
    public function index(Request $request)
    {
        // 1. Ambil data anak beserta relasi orang tua dan pengukuran
        $query = Anak::with(['orangtua', 'pengukurans']);

        // 2. FUNGSI SEARCH (Cari berdasarkan Nama Anak atau Nama Orang Tua)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhereHas('orangtua', function($qOrtu) use ($search) {
                      $qOrtu->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 3. FUNGSI FILTER UMUR
        if ($request->filled('umur') && $request->umur != 'semua') {
            $now = Carbon::now();
            if ($request->umur == 'bayi') {
                // Bayi: Umur 0 sampai kurang dari 2 tahun (tanggal lahir >= 2 tahun lalu)
                $query->where('tanggal_lahir', '>=', $now->copy()->subYears(2));
            } elseif ($request->umur == 'balita') {
                // Balita: Umur 2 tahun sampai 5 tahun
                $query->where('tanggal_lahir', '<', $now->copy()->subYears(2))
                      ->where('tanggal_lahir', '>=', $now->copy()->subYears(5));
            }
        }

        // Eksekusi query ke database
        $anaks = $query->orderBy('created_at', 'desc')->get();

        // 4. FUNGSI FILTER STATUS GIZI (Filter berdasarkan riwayat terakhir)
        if ($request->filled('status') && $request->status != 'semua') {
            $anaks = $anaks->filter(function($anak) use ($request) {
                // Ambil data pengukuran yang paling baru
                $pengukuranTerakhir = $anak->pengukurans->sortByDesc('created_at')->first();
                
                // Jika anak belum pernah diukur, sembunyikan dari filter status gizi
                if (!$pengukuranTerakhir) return false;
                
                $statusRaw = strtolower($pengukuranTerakhir->status_gizi);
                $reqStatus = strtolower($request->status);
                
                if ($reqStatus == 'normal' && str_contains($statusRaw, 'normal')) return true;
                if ($reqStatus == 'kurang' && str_contains($statusRaw, 'kurang')) return true;
                if ($reqStatus == 'stunting' && (str_contains($statusRaw, 'stunting') || str_contains($statusRaw, 'buruk'))) return true;
                if ($reqStatus == 'obesitas' && str_contains($statusRaw, 'obesitas')) return true;
                
                return false;
            });
        }

        // Kirim ke view (Sesuai dengan nama view Anda: 'admin.anak')
        return view('admin.anak', compact('anaks'));
    }

    /**
     * Menangani pembaruan data profil anak
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan,L,P',
            'tanggal_lahir' => 'required|date',
            'berat_lahir' => 'nullable|numeric',
            'panjang_lahir' => 'nullable|numeric',
        ]);

        $anak = Anak::findOrFail($id);
        $anak->update($request->all());

        return redirect()->back()->with('success', 'Profil ' . $anak->nama . ' berhasil diperbarui!');
    }
}