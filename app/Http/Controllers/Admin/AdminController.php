<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Anak;
use App\Models\KonsultasiAhli;
use App\Models\Pengukuran;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalPengguna = User::count();
        $anakTerdaftar = Anak::count();
        $tenagaMedis = User::whereIn('role', ['dokter', 'ahligizi'])->count();
        $totalKonsultasi = KonsultasiAhli::count();

        $daftarMedis = User::whereIn('role', ['dokter', 'ahligizi'])
                           ->orderBy('created_at', 'desc')
                           ->take(3)
                           ->get();

        $roleData = [
            User::where('role', 'orangtua')->count(),
            User::where('role', 'dokter')->count(),
            User::where('role', 'ahligizi')->count(),
            User::where('role', 'admin')->count()
        ];

        $giziData = [
            Pengukuran::where('status_gizi', 'normal')->count(),
            Pengukuran::where('status_gizi', 'kurang_gizi')->count(),
            Pengukuran::where('status_gizi', 'obesitas')->count(),
            Pengukuran::where('status_gizi', 'stunting')->count()
        ];

        $bulanLabels = [];
        $konsultasiData = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $bulanLabels[] = $bulan->translatedFormat('M');
            $jumlah = KonsultasiAhli::whereMonth('created_at', $bulan->month)
                                    ->whereYear('created_at', $bulan->year)
                                    ->count();
            $konsultasiData[] = $jumlah;
        }

        return view('admin.dashboard', compact(
            'totalPengguna', 'anakTerdaftar', 'tenagaMedis', 'totalKonsultasi',
            'daftarMedis', 'roleData', 'giziData', 'bulanLabels', 'konsultasiData'
        ));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('admin.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return back()->with('status', 'profile-updated');
    }
}