<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artikel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // 👈 PENTING: Untuk mengelola file gambar

class ArtikelController extends Controller
{
    // 1. Tampilkan Halaman & Tangani Filter/Search
    public function index(Request $request)
    {
        $query = Artikel::query();

        // FUNGSI SEARCH (Cari Judul atau Penulis)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%");
            });
        }

        // FUNGSI FILTER KATEGORI (Nutrisi, Resep, Pola Asuh)
        if ($request->filled('kategori') && $request->kategori != 'semua') {
            $query->where('kategori', $request->kategori);
        }

        // FUNGSI FILTER STATUS (Terbit / Draft)
        if ($request->filled('status') && $request->status != 'semua') {
            $query->where('status', $request->status);
        }

        // Urutkan artikel dari yang paling baru
        $artikels = $query->orderBy('created_at', 'desc')->get();

        return view('admin.artikel', compact('artikels'));
    }

    // 2. Simpan Artikel Baru (Beserta Upload Gambar)
    public function store(Request $request)
    {
        // Validasi input dari user
        $request->validate([
            'judul' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
            'rentang_usia' => 'required|string',
            'kategori' => 'required|string',
            'tags' => 'nullable|string',
            'status' => 'required|in:terbit,draft',
            'konten' => 'required'
        ]);

        // Logika menangkap dan menyimpan file gambar
        $pathGambar = null;
        if ($request->hasFile('gambar')) {
            // Gambar disimpan ke folder storage/app/public/artikel_images
            $pathGambar = $request->file('gambar')->store('artikel_images', 'public');
        }

        // Masukkan ke database
       Artikel::create([
            'judul' => $request->judul,
            'gambar' => $pathGambar, 
            'rentang_usia' => $request->rentang_usia,
            'kategori' => $request->kategori,
            'tags' => $request->tags,
            'status' => $request->status,
            'konten' => $request->konten,
            'penulis' => Auth::user()->name ?? 'Admin GiziAnak', 
            'views' => 0
        ]);

        return redirect()->back()->with('success', 'Artikel berhasil ditambahkan!');
    }

    // 3. Edit Artikel (Beserta Update Gambar)
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'rentang_usia' => 'required|string',
            'kategori' => 'required|string',
            'tags' => 'nullable|string',
            'status' => 'required|in:terbit,draft',
            'konten' => 'required'
        ]);

        $artikel = Artikel::findOrFail($id);
        
        // Ambil semua data request kecuali token dan method
        $data = $request->except(['_token', '_method']);

        // Jika Admin mengupload gambar baru saat edit
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama dari folder agar tidak menumpuk sampah
            if ($artikel->gambar) {
                Storage::disk('public')->delete($artikel->gambar);
            }
            // Simpan gambar yang baru
            $data['gambar'] = $request->file('gambar')->store('artikel_images', 'public');
        }

        // Update database
        $artikel->update($data);

        return redirect()->back()->with('success', 'Perubahan artikel berhasil disimpan!');
    }

    // 4. Hapus Artikel
    public function destroy($id)
    {
        $artikel = Artikel::findOrFail($id);
        
        // Hapus file gambar fisik dari folder jika artikel dihapus
        if ($artikel->gambar) {
            Storage::disk('public')->delete($artikel->gambar);
        }
        
        $artikel->delete();
        
        return redirect()->back()->with('success', 'Artikel berhasil dihapus!');
    }
}