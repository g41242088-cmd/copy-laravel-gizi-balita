<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    // Menampilkan halaman manajemen akun beserta data user
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.manajemen-akun', compact('users'));
    }

    // Menyimpan akun baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,dokter,ahligizi,orangtua',
            'spesialisasi' => 'nullable|string|max:255',
        ]);

        // Pastikan spesialisasi hanya tersimpan jika rolenya dokter/ahli gizi
        $spesialisasi = in_array($request->role, ['dokter', 'ahligizi']) ? $request->spesialisasi : null;

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'spesialisasi' => $spesialisasi,
        ]);

        return redirect()->back()->with('success', 'Akun ' . $request->name . ' berhasil ditambahkan!');
    }
}