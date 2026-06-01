<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\orangtua\GiziAnakController;
use App\Http\Controllers\AhliGizi\AhliGiziController;
use App\Http\Controllers\Dokter\DokterController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AnakController;
use App\Http\Controllers\Admin\KonsultasiController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\ArtikelController;
use App\Http\Controllers\Admin\UlasanController;
use App\Http\Controllers\Dokter\PermintaanController;
use App\Http\Controllers\OrangTua\BookingController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman awal
Route::get('/', function () {
    return view('welcome');
});

// =====================
// DASHBOARD BERDASARKAN ROLE
// =====================

// ADMIN
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/anak', [AnakController::class, 'index'])->name('admin.anak.index');
    Route::get('/konsultasi', [KonsultasiController::class, 'index'])->name('admin.konsultasi.index');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');
    Route::get('/artikel', [ArtikelController::class, 'index'])->name('admin.artikel.index');
    Route::get('/ulasan', [UlasanController::class, 'index'])->name('admin.ulasan.index');

    // Rute Manajemen Akun
    Route::get('/manajemen-akun', [App\Http\Controllers\Admin\AkunController::class, 'index'])->name('admin.akun.index');
    Route::post('/manajemen-akun', [App\Http\Controllers\Admin\AkunController::class, 'store'])->name('admin.akun.store');

    // Rute untuk menampilkan halaman
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');

    // Rute untuk memproses form tambah akun
    Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.akun.store');
    Route::put('/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.akun.update');
    Route::delete('/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.akun.destroy');

    Route::put('/anak/update/{id}', [App\Http\Controllers\Admin\AnakController::class, 'update'])->name('admin.anak.update');

    // Rute untuk Export PDF
    Route::get('/konsultasi/export-pdf', [App\Http\Controllers\Admin\KonsultasiController::class, 'exportPdf'])->name('admin.konsultasi.exportPdf');

    // Rute untuk Hapus Data
    Route::delete('/konsultasi/{id}', [App\Http\Controllers\Admin\KonsultasiController::class, 'destroy'])->name('admin.konsultasi.destroy');

    Route::get('/laporan', [App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('admin.laporan.index');
    Route::get('/laporan/export-pdf/{bulan}/{tahun}', [App\Http\Controllers\Admin\LaporanController::class, 'exportPdf'])->name('admin.laporan.exportPdf');

    Route::post('/artikel', [App\Http\Controllers\Admin\ArtikelController::class, 'store'])->name('admin.artikel.store');
    Route::put('/artikel/{id}', [App\Http\Controllers\Admin\ArtikelController::class, 'update'])->name('admin.artikel.update');
    Route::delete('/artikel/{id}', [App\Http\Controllers\Admin\ArtikelController::class, 'destroy'])->name('admin.artikel.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/anak', [AnakController::class, 'index'])->name('admin.anak.index');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');
});



// ROLE: ORANG TUA
// ==========================================
Route::middleware(['auth', 'role:orangtua'])->prefix('orangtua')->group(function () {

    // 1. DASHBOARD / BERANDA
    Route::get('/', [App\Http\Controllers\orangtua\GiziAnakController::class, 'dashboard'])->name('orangtua.dashboard');

    // 2. SISTEM CEK GIZI & GRAFIK
    Route::get('/cek-gizi', [App\Http\Controllers\orangtua\GiziAnakController::class, 'index'])->name('orangtua.cekgizi.index');
    Route::post('/cek-gizi', [App\Http\Controllers\orangtua\GiziAnakController::class, 'hitung'])->name('orangtua.cekgizi.hitung');
    Route::delete('/cek-gizi/{id}', [App\Http\Controllers\orangtua\GiziAnakController::class, 'destroy'])->name('orangtua.cekgizi.destroy');
    Route::get('/riwayat-gizi', [App\Http\Controllers\orangtua\GiziAnakController::class, 'riwayat'])->name('orangtua.riwayat.index');

    // 3. SISTEM DATA ANAK
    Route::get('/tambah-anak', [App\Http\Controllers\OrangTua\AnakController::class, 'create'])->name('orangtua.anak.create');
    Route::post('/tambah-anak', [App\Http\Controllers\OrangTua\AnakController::class, 'store'])->name('orangtua.anak.store');
    Route::put('/tambah-anak/{id}', [App\Http\Controllers\OrangTua\AnakController::class, 'update'])->name('orangtua.anak.update');
    Route::delete('/tambah-anak/{id}', [App\Http\Controllers\OrangTua\AnakController::class, 'destroy'])->name('orangtua.anak.destroy');

    // 4. SISTEM BOOKING DOKTER 
    Route::get('/booking', [App\Http\Controllers\OrangTua\BookingController::class, 'indexDokter'])->name('orangtua.booking.index');
    Route::post('/booking', [App\Http\Controllers\OrangTua\BookingController::class, 'store'])->name('orangtua.booking.store');

    // 5. SISTEM KONSULTASI AHLI GIZI 
    Route::get('/konsultasi', [App\Http\Controllers\OrangTua\BookingController::class, 'indexAhliGizi'])->name('orangtua.konsultasi.index');
    Route::post('/konsultasi', [App\Http\Controllers\OrangTua\BookingController::class, 'store'])->name('orangtua.konsultasi.store');

    // 6. AJAX CEK JADWAL (Digunakan oleh Dokter & Ahli Gizi)
    Route::get('/cek-jam-tersedia', [App\Http\Controllers\OrangTua\BookingController::class, 'cekJamTersedia'])->name('cek.jam.tersedia');

    // 7. RIWAYAT KONSULTASI / JADWAL
    Route::get('/riwayat-jadwal', [App\Http\Controllers\OrangTua\RiwayatJadwalController::class, 'index'])->name('orangtua.riwayat-jadwal.index');
    Route::get('/riwayat-konsultasi', [App\Http\Controllers\orangtua\GiziAnakController::class, 'riwayatKonsultasi'])->name('orangtua.riwayat-konsultasi.index');

    // 8. FITUR LAINNYA
    Route::get('/tips', [App\Http\Controllers\orangtua\GiziAnakController::class, 'tips'])->name('orangtua.tips.index');
    Route::get('/akun', [App\Http\Controllers\orangtua\GiziAnakController::class, 'akun'])->name('orangtua.akun.index');
    Route::put('/akun', [App\Http\Controllers\orangtua\GiziAnakController::class, 'updateAkun'])->name('orangtua.akun.update');
    Route::put('/akun/password', [App\Http\Controllers\orangtua\GiziAnakController::class, 'updatePassword'])->name('orangtua.akun.password');

     // 9. SCREENING STUNTING 
    Route::get('/screening-stunting', [App\Http\Controllers\OrangTua\ScreeningStuntingController::class, 'index'])->name('orangtua.screening.index');
    Route::post('/screening-stunting', [App\Http\Controllers\OrangTua\ScreeningStuntingController::class, 'store'])->name('orangtua.screening.store');
    Route::get('/screening-stunting/{id}/pdf', [App\Http\Controllers\OrangTua\ScreeningStuntingController::class, 'exportPdf'])->name('orangtua.screening.pdf');
    Route::get('/riwayat-screening', [App\Http\Controllers\OrangTua\ScreeningStuntingController::class, 'riwayat'])->name('orangtua.riwayat-screening.index');
});

// DOKTER
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function () {

    // Dashboard Dokter
    Route::get('/dashboard', [DokterController::class, 'dashboard'])->name('dokter.dashboard');

    // Permintaan Masuk (Diubah menggunakan PermintaanController yang baru kita buat)
    Route::get('/permintaan-masuk', [PermintaanController::class, 'index'])->name('dokter.permintaan.index');

    // Rute lainnya (Tetap menggunakan DokterController)
    Route::get('/jadwal-pemeriksaan', [DokterController::class, 'jadwalPemeriksaan'])->name('dokter.jadwal.index');
    Route::get('/jam-praktik', [DokterController::class, 'jamPraktik'])->name('dokter.jam.index');
    Route::get('/daftar-pasien', [DokterController::class, 'daftarPasien'])->name('dokter.pasien.index');
    // Rute untuk Sidebar (Daftar semua catatan)
    // Rute untuk Sidebar (Menampilkan semua daftar pasien yang sudah selesai)
    Route::get('/catatan-medis', [DokterController::class, 'catatanMedis'])->name('dokter.catatan.index');

    // Rute Baru untuk Detail (Menampilkan form catatan medis spesifik)
    Route::get('/catatan-medis/detail/{id}', [DokterController::class, 'catatanMedisDetail'])->name('dokter.catatan.detail');
    Route::get('/profil', [DokterController::class, 'profil'])->name('dokter.profil.index');
    Route::put('/permintaan/{id}/update-status', [App\Http\Controllers\Dokter\PermintaanController::class, 'updateStatus'])->name('dokter.permintaan.update');
    Route::put('/catatan-medis/store/{id}', [DokterController::class, 'storeCatatan'])->name('dokter.catatan.store');
    Route::get('/catatan-medis/pdf/{id}', [App\Http\Controllers\Dokter\DokterController::class, 'downloadPDF'])->name('dokter.catatan.pdf');

    Route::get('/jam-praktik', [DokterController::class, 'jamPraktik'])->name('dokter.jam.index');
    Route::post('/jam-praktik', [DokterController::class, 'updateJamPraktik'])->name('dokter.jam.update');

    Route::put('/profil/update', [DokterController::class, 'updateProfil'])->name('dokter.profil.update');

    // Memproses update password (TAMBAHKAN INI)
    Route::put('/profil/password', [DokterController::class, 'updatePassword'])->name('dokter.profil.password');
});
// AHLI GIZI
Route::middleware(['auth', 'role:ahligizi'])->prefix('ahligizi')->group(function () {

    // Dashboard Ahli Gizi
    Route::get('/', [App\Http\Controllers\AhliGizi\AhliGiziController::class, 'dashboard'])->name('ahligizi.dashboard');

    // Permintaan Masuk & Update Status
    Route::get('/permintaan-masuk', [App\Http\Controllers\AhliGizi\AhliGiziController::class, 'permintaanMasuk'])->name('ahligizi.permintaan.index');
    Route::put('/permintaan/{id}/update-status', [App\Http\Controllers\AhliGizi\AhliGiziController::class, 'updateStatus'])->name('ahligizi.permintaan.update');

    // Jadwal & Jam Praktik
    Route::get('/jadwal-saya', [App\Http\Controllers\AhliGizi\AhliGiziController::class, 'jadwalSaya'])->name('ahligizi.jadwal.index');
    Route::get('/jam-praktik', [App\Http\Controllers\AhliGizi\JamPraktikController::class, 'index'])->name('ahligizi.jam.index');
    Route::post('/jam-praktik', [App\Http\Controllers\AhliGizi\JamPraktikController::class, 'store'])->name('ahligizi.jampraktik.store');

    // Daftar Pasien
    Route::get('/daftar-pasien', [App\Http\Controllers\AhliGizi\AhliGiziController::class, 'daftarPasien'])->name('ahligizi.pasien.index');

    // Analisis Gizi (Hanya bisa diakses dengan ID)
    Route::get('/analisis/{id}', [App\Http\Controllers\AhliGizi\AhliGiziController::class, 'analisisGizi'])->name('ahligizi.analisis.detail');
    Route::post('/analisis/{id}', [App\Http\Controllers\AhliGizi\AhliGiziController::class, 'simpanAnalisis'])->name('ahligizi.analisis.store');

    Route::get('/akun-saya', [App\Http\Controllers\AhliGizi\ProfileController::class, 'index'])->name('ahligizi.profile.index');
    Route::put('/akun-saya', [App\Http\Controllers\AhliGizi\ProfileController::class, 'update'])->name('ahligizi.profile.update');
});

// =====================
// PROFILE (BREEZE)
// =====================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/anak', function () {
    return "Ini halaman Master Data Anak (Sedang dibuat...)";
})->name('admin.anak.index');

// 4. Riwayat Konsultasi
Route::get('/konsultasi', function () {
    return "Ini halaman Riwayat Konsultasi (Sedang dibuat...)";
})->name('admin.konsultasi.index');

Route::get('/dashboard', function () {

    $user = Auth::user();

    if ($user->role == 'admin') {
        return redirect('/admin');
    }

    if ($user->role == 'dokter') {
        return redirect('/dokter');
    }

    if ($user->role == 'orangtua') {
        return redirect('/orangtua');
    }

    if ($user->role == 'ahligizi') {
        return redirect('/ahligizi');
    }

    return redirect('/');
})->middleware('auth')->name('dashboard');

// =====================
// AUTH ROUTES
// =====================
require __DIR__ . '/auth.php';
