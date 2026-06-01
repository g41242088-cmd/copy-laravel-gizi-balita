<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Anak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnakController extends Controller
{
    // 1. Menampilkan form tambah anak
    public function create()
    {
        return view('orangtua.tambah-anak');
    }

    // 2. Memproses data form dan menyimpan ke database
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            'berat_lahir' => 'nullable|numeric|min:0.5',
            'panjang_lahir' => 'nullable|numeric|min:20',
        ]);

        // Simpan ke database
        Anak::create([
            'orangtua_id' => Auth::id(), 
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'berat_lahir' => $request->berat_lahir,
            'panjang_lahir' => $request->panjang_lahir,
        ]);

        return redirect()->back()
                         ->with('success', 'Data anak bernama ' . $request->nama . ' berhasil didaftarkan!');
    }

    // 3. Memproses update data anak (Untuk Modal Edit)
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        $anak = Anak::findOrFail($id);
        
        // Proteksi keamanan: Pastikan anak ini milik orang tua yang login
        if ($anak->orangtua_id == Auth::id()) {
            $anak->update([
                'nama' => $request->nama,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);
            
            return redirect()->back()->with('success', 'Data anak ' . $request->nama . ' berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengubah data ini.');
    }

    // 4. Memproses hapus data anak
    public function destroy($id)
    {
        $anak = Anak::findOrFail($id);

        // Proteksi keamanan: Pastikan anak ini milik orang tua yang login
        if ($anak->orangtua_id == Auth::id()) {
            $namaAnak = $anak->nama;
            $anak->delete();
            
            return redirect()->back()->with('success', 'Data anak bernama ' . $namaAnak . ' berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Gagal menghapus. Anda tidak memiliki akses.');
    }
}