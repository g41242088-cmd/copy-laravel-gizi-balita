<?php

namespace App\Http\Controllers\AhliGizi;

use App\Http\Controllers\Controller;
use App\Models\JamPraktik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JamPraktikController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // 1. Ambil jadwal yang sudah tersimpan di database (jika ada)
        $jadwal = JamPraktik::where('ahli_id', $userId)->get()->keyBy('hari');

        // 2. Buat daftar hari standar
        $hariStandar = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        // 3. Kirim kedua variabel tersebut ke Blade
        return view('ahligizi.jam-praktik', compact('jadwal', 'hariStandar'));
    }

    public function store(Request $request)
    {
        $userId = Auth::id();
        $hariInput = $request->hari; // Array dari form

        foreach ($hariInput as $hari => $data) {
            $isAktif = isset($data['is_aktif']) ? true : false;

            JamPraktik::updateOrCreate(
                ['ahli_id' => $userId, 'hari' => $hari], // Cari berdasarkan User ID & Hari
                [
                    'is_aktif' => $isAktif,
                    'jam_buka' => $isAktif ? $data['jam_buka'] : null,
                    'jam_tutup' => $isAktif ? $data['jam_tutup'] : null,
                ]
            );
        }

        return redirect()->back()->with('success', 'Jadwal praktik berhasil diperbarui!');
    }
}