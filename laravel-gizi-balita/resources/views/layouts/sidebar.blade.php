<!-- File: resources/views/layouts/sidebar.blade.php -->

<!-- MOBILE HEADER -->
<div class="mobile-header">
    <div style="display: flex; align-items: center; gap: 12px; font-weight: bold; font-size: 18px;">
        <div class="logo-icon" style="width: 32px; height: 32px; font-size: 16px;">🌱</div>
        <span>Gizi<span style="color: #fbbf24;">Anak</span></span>
    </div>
    <button class="hamburger-btn" onclick="toggleSidebar()">☰</button>
</div>

<!-- OVERLAY UNTUK MOBILE -->
<div class="sidebar-overlay" onclick="toggleSidebar()"></div>

<!-- SIDEBAR -->
<div class="sidebar">
    <!-- LOGO TETAP DI ATAS -->
    <div class="logo-section">
        <div class="logo-icon">🌱</div>
        <span>Gizi<span style="color: #fbbf24;">Anak</span></span>
    </div>

    @auth
        @php
            $userRole = auth()->user()->role;
        @endphp

        <div class="sidebar-scrollable">

            <div class="badge-admin">
                @if ($userRole === 'admin')
                    🛡 PANEL ADMIN
                @elseif($userRole === 'dokter')
                    👨‍⚕️ PANEL DOKTER
                @elseif($userRole === 'ahligizi')
                    🥗 PANEL AHLI GIZI
                @else
                    👨‍👩‍👧 PANEL ORANG TUA
                @endif
            </div>

            @if ($userRole === 'admin')
                <div class="section-title">DASHBOARD</div>
                <a href="{{ url('/admin') }}" class="menu-item {{ request()->is('admin') ? 'active' : '' }}">
                    <span>🏠</span> Beranda
                </a>

                <div class="section-title">MANAJEMEN SISTEM</div>
                <a href="{{ route('admin.users.index') }}"
                    class="menu-item {{ request()->is('admin/users*') ? 'active' : '' }}">
                    <span>👥</span> Manajemen Akun
                </a>

                <div class="section-title">PEMANTAUAN DATA KLINIS</div>
                <a href="{{ route('admin.anak.index') }}"
                    class="menu-item {{ request()->is('admin/anak*') ? 'active' : '' }}">
                    <span>👶</span> Master Data Anak
                </a>
                <a href="{{ route('admin.konsultasi.index') }}"
                    class="menu-item {{ request()->is('admin/konsultasi*') ? 'active' : '' }}">
                    <span>📅</span> Riwayat
                </a>
                <a href="{{ route('admin.screening.index') }}"
                    class="menu-item {{ request()->is('admin/screening*') ? 'active' : '' }}">
                    <span>🧒</span> Data Skrining Stunting
                </a>
                <a href="{{ route('admin.laporan.index') ?? '#' }}"
                    class="menu-item {{ request()->is('admin/laporan*') ? 'active' : '' }}">
                    <span>📊</span> Laporan Sistem
                </a>

                <div class="section-title">KONTEN & ULASAN</div>
                <a href="{{ route('admin.artikel.index') ?? '#' }}"
                    class="menu-item {{ request()->is('admin/artikel*') ? 'active' : '' }}">
                    <span>📄</span> Artikel & Tips
                </a>

            @elseif ($userRole === 'dokter')
                <div class="section-title">DASHBOARD</div>
                <a href="{{ route('dokter.dashboard') }}"
                    class="menu-item {{ request()->is('dokter/dashboard*') ? 'active' : '' }}">
                    <span>🏠</span> Beranda
                </a>

                <div class="section-title">MANAJEMEN PEMERIKSAAN</div>
                <a href="{{ route('dokter.permintaan.index') }}"
                    class="menu-item {{ request()->is('dokter/permintaan-masuk*') ? 'active' : '' }}">
                    <span>📥</span> Permintaan Masuk
                </a>
                <a href="{{ route('dokter.jam.index') }}"
                    class="menu-item {{ request()->is('dokter/jam-praktik*') ? 'active' : '' }}">
                    <span>⏰</span> Jam Praktik
                </a>

                <div class="section-title">REKAM MEDIS PASIEN</div>
                <a href="{{ route('dokter.pasien.index') }}"
                    class="menu-item {{ request()->is('dokter/daftar-pasien*') ? 'active' : '' }}">
                    <span>👶</span> Daftar Pasien
                </a>
                <a href="{{ route('dokter.catatan.index') }}"
                    class="menu-item {{ request()->routeIs('dokter.catatan.*') ? 'active' : '' }}">
                    <span>🩺</span> Catatan Medis
                </a>

                <div class="section-title">PROFIL</div>
                <a href="{{ route('dokter.profil.index') }}"
                    class="menu-item {{ request()->is('dokter/profil*') ? 'active' : '' }}">
                    <span>👨‍⚕️</span> Profil Dokter
                </a>

            @elseif ($userRole === 'ahligizi')
                <div class="section-title">DASHBOARD</div>
                <a href="{{ route('ahligizi.dashboard') }}"
                    class="menu-item {{ request()->is('ahligizi') ? 'active' : '' }}">
                    <span>🏠</span> Beranda
                </a>

                <div class="section-title">MANAJEMEN KONSULTASI</div>
                <a href="{{ route('ahligizi.permintaan.index') }}"
                    class="menu-item {{ request()->is('ahligizi/permintaan-masuk*') ? 'active' : '' }}">
                    <span>📥</span> Permintaan Masuk
                </a>
                <a href="{{ route('ahligizi.jam.index') }}"
                    class="menu-item {{ request()->is('ahligizi/jam-praktik*') ? 'active' : '' }}">
                    <span>⏰</span> Jam Praktik
                </a>

                <div class="section-title">REKAM GIZI PASIEN</div>
                <a href="{{ route('ahligizi.pasien.index') }}"
                    class="menu-item {{ request()->is('ahligizi/daftar-pasien*') ? 'active' : '' }}">
                    <span>👶</span> Daftar Pasien
                </a>
                <a href="{{ route('ahligizi.pasien.index') }}"
                    class="menu-item {{ request()->is('ahligizi/analisis*') ? 'active' : '' }}">
                    <span>📈</span> Analisis Gizi
                </a>

                <div class="section-title">PENGATURAN</div>
                <a href="{{ route('ahligizi.profile.index') }}"
                    class="menu-item {{ request()->is('ahligizi/akun-saya*') ? 'active' : '' }}">
                    <span>👤</span> Akun Saya
                </a>

            @else
                {{-- ORANG TUA --}}
                <div class="section-title">MENU UTAMA</div>

                <a href="{{ url('/orangtua') }}" class="menu-item {{ request()->is('orangtua') ? 'active' : '' }}">
                    <span>🏠</span> Beranda
                </a>

                <a href="{{ route('orangtua.anak.create') }}"
                    class="menu-item {{ request()->is('orangtua/tambah-anak*') ? 'active' : '' }}">
                    <span>👶</span> Daftarkan Anak
                </a>

                <a href="{{ route('orangtua.cekgizi.index') }}"
                    class="menu-item {{ request()->is('orangtua/cek-gizi*') ? 'active' : '' }}">
                    <span>📊</span> Cek Gizi
                </a>

                <a href="{{ route('orangtua.riwayat.index') }}"
                    class="menu-item {{ request()->is('orangtua/riwayat-gizi*') ? 'active' : '' }}">
                    <span>📈</span> Riwayat Gizi
                </a>

                {{-- SKRINING STUNTING (setelah Riwayat Gizi) --}}
                <a href="{{ route('orangtua.screening.index') }}"
                    class="menu-item {{ request()->is('orangtua/screening-stunting') ? 'active' : '' }}">
                    <span>🛡️</span> Skrining Stunting
                </a>

                <a href="{{ route('orangtua.riwayat-screening.index') }}"
                    class="menu-item {{ request()->is('orangtua/riwayat-screening*') ? 'active' : '' }}">
                    <span>📋</span> Riwayat Skrining
                </a>

                <a href="{{ route('orangtua.booking.index') }}"
                    class="menu-item {{ request()->is('orangtua/booking*') ? 'active' : '' }}">
                    <span>📅</span> Booking Dokter
                </a>

                <a href="{{ route('orangtua.riwayat-jadwal.index') }}"
                    class="menu-item {{ request()->is('orangtua/riwayat-jadwal*') ? 'active' : '' }}">
                    <span>📋</span> Riwayat Booking Dokter
                </a>

                <a href="{{ route('orangtua.konsultasi.index') }}"
                    class="menu-item {{ request()->is('orangtua/konsultasi*') ? 'active' : '' }}">
                    <span>👨‍⚕️</span> Konsultasi Ahli
                </a>

                <a href="{{ route('orangtua.riwayat-konsultasi.index') ?? '#' }}"
                    class="menu-item {{ request()->is('orangtua/riwayat-konsultasi*') ? 'active' : '' }}">
                    <span>💬</span> Riwayat Konsultasi Ahli
                </a>

                <div class="section-title">LAINNYA</div>

                <a href="{{ route('orangtua.tips.index') }}"
                    class="menu-item {{ request()->is('orangtua/tips*') ? 'active' : '' }}">
                    <span>💡</span> Tips Gizi
                </a>

                <a href="{{ route('orangtua.akun.index') }}"
                    class="menu-item {{ request()->is('orangtua/akun*') ? 'active' : '' }}">
                    <span>👤</span> Akun Saya
                </a>
            @endif

        </div>

        <div style="border-top: 1px solid rgba(255,255,255,0.05); padding: 12px 0;">
            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                @csrf
            </form>
            <div class="menu-item" style="color: #ef4444; cursor: pointer; margin-bottom: 8px;"
                onclick="document.getElementById('logout-form').submit();">
                <span>🚪</span> Keluar
            </div>
        </div>

    @endauth
</div>