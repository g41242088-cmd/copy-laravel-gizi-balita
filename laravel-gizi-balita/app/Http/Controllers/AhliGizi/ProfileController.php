<?php

namespace App\Http\Controllers\AhliGizi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        // Ambil data user yang sedang login
        $user = Auth::user();
        return view('ahligizi.profile', compact('user'));
    }

    public function update(Request $request)
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    // 1. Validasi input
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'no_telepon' => 'nullable|string',
        'alamat_praktik' => 'nullable|string',
        'bio' => 'nullable|string',
        'spesialisasi' => 'nullable|string',
        'no_sip' => 'nullable|string',
        'jenis_kelamin' => 'nullable|in:L,P',
        'password' => 'nullable|string|min:8|confirmed',
    ]);

    // 2. Ambil semua data dari request KECUALI password
    $data = $request->except(['password']);

    // 3. Logika Password: Hanya tambahkan ke array data JIKA diisi
    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    // 4. Update data ke database
    $user->update($data);

    return redirect()->route('ahligizi.profile.index')->with('success', 'Profil Anda berhasil diperbarui!');
}
}
