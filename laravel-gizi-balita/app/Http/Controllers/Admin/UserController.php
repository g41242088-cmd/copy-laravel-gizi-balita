<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;                  // Wajib ditambahkan untuk memanggil tabel users
use Illuminate\Support\Facades\Hash;  // Wajib ditambahkan untuk mengacak (enkripsi) password
use Illuminate\Support\Facades\Auth;  // Wajib ditambahkan untuk mendapatkan data user yang sedang login

class UserController extends Controller
{
    // 1. MENAMPILKAN HALAMAN MANAJEMEN AKUN
    public function index()
    {
        // Mengambil semua data dari tabel users, diurutkan dari yang terbaru
        $users = User::orderBy('created_at', 'desc')->get();
        
        // Mengirimkan variabel $users ke file blade admin.users
        return view('admin.users', compact('users'));
    }

    // 2. MENYIMPAN AKUN BARU
    public function store(Request $request)
    {
        // Validasi data yang diisi di form
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,dokter,ahligizi,orangtua',
            'spesialisasi' => 'nullable|string|max:255',
        ]);

        // Spesialisasi hanya disimpan jika role-nya dokter atau ahli gizi
        $spesialisasi = in_array($request->role, ['dokter', 'ahligizi']) ? $request->spesialisasi : null;

        // Simpan ke database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Password dienkripsi
            'role' => $request->role,
            'spesialisasi' => $spesialisasi,
        ]);

        return redirect()->back()->with('success', 'Akun ' . $request->name . ' berhasil ditambahkan!');
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            // Pengecualian email agar bisa memakai email yang sama saat di-edit
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6', // Password opsional saat edit
            'role' => 'required|in:admin,dokter,ahligizi,orangtua',
            'spesialisasi' => 'nullable|string|max:255',
        ]);

        $spesialisasi = in_array($request->role, ['dokter', 'ahligizi']) ? $request->spesialisasi : null;

        // Siapkan data yang akan diupdate
        $dataToUpdate = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'spesialisasi' => $spesialisasi,
        ];

        // Jika form password diisi, update passwordnya. Jika kosong, biarkan password lama.
        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        $user->update($dataToUpdate);

        return redirect()->back()->with('success', 'Data akun ' . $user->name . ' berhasil diperbarui!');
    }

    // 4. MENGHAPUS AKUN
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Mencegah admin menghapus akunnya sendiri yang sedang login
        if ($user->id == Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $namaUser = $user->name;
        $user->delete();

        return redirect()->back()->with('success', 'Akun ' . $namaUser . ' berhasil dihapus secara permanen.');
    }

}