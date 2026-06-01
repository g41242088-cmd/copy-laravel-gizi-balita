@extends('layouts.app')

@section('title', 'Beranda Orang Tua - GiziAnak')

@section('custom_css')
<style>
    /* --- HERO BANNER --- */
    .hero-banner {
        background: linear-gradient(135deg, #0f1c2e 0%, #1e3a5f 100%);
        border-radius: 20px;
        padding: 48px;
        color: white;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(15, 28, 46, 0.2);
    }
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 24px;
        backdrop-filter: blur(4px);
    }
    .hero-title { 
        font-size: 42px; 
        font-weight: 900; 
        line-height: 1.2; 
        margin: 0 0 16px 0; 
        font-family: Georgia, serif;
    }
    .hero-title-highlight { 
        color: #fbbf24; 
        font-style: italic; 
    }
    .hero-subtitle { 
        font-size: 15px; 
        color: #94a3b8; 
        max-width: 500px; 
        line-height: 1.6; 
        margin-bottom: 32px; 
    }
    .hero-buttons { 
        display: flex; 
        gap: 16px; 
    }
    .btn-hero { 
        padding: 14px 28px; 
        border-radius: 12px; 
        font-weight: 700; 
        font-size: 14px; 
        cursor: pointer; 
        display: flex; 
        align-items: center; 
        gap: 8px; 
        border: none; 
        transition: all 0.3s ease; 
        text-decoration: none;
    }
    .btn-yellow { 
        background-color: #fbbf24; 
        color: #78350f; 
    }
    .btn-yellow:hover { 
        background-color: #f59e0b; 
        transform: translateY(-2px);
    }
    .btn-outline { 
        background-color: rgba(255, 255, 255, 0.1); 
        color: white; 
        border: 1px solid rgba(255, 255, 255, 0.3); 
        backdrop-filter: blur(4px);
    }
    .btn-outline:hover { 
        background-color: rgba(255, 255, 255, 0.2); 
        transform: translateY(-2px);
    }

    /* --- SECTION LABELS & CARDS --- */
    .section-label { 
        display: flex; 
        align-items: center; 
        gap: 8px; 
        font-size: 12px; 
        font-weight: 800; 
        color: #64748b; 
        text-transform: uppercase; 
        letter-spacing: 0.5px; 
        margin-bottom: 16px; 
    }
    .card-white { 
        background: white; 
        border-radius: 16px; 
        padding: 24px; 
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02); 
        border: 1px solid #f1f5f9; 
    }

    /* --- STATS GRID --- */
    .stats-grid { 
        display: grid; 
        grid-template-columns: repeat(4, 1fr); 
        gap: 20px; 
        margin-bottom: 40px; 
    }
    .stat-item { 
        display: flex; 
        align-items: center; 
        gap: 16px; 
    }
    .stat-icon { 
        width: 48px; 
        height: 48px; 
        border-radius: 12px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 24px; 
        flex-shrink: 0;
    }
    .bg-blue-light { background: #eff6ff; }
    .bg-green-light { background: #f0fdf4; }
    .stat-val { font-size: 20px; font-weight: 800; color: #1e293b; margin: 0 0 4px 0; line-height: 1;}
    .stat-text { font-size: 13px; color: #64748b; font-weight: 500; margin: 0;}

    /* --- QUICK MENU GRID --- */
    .quick-menu-grid { 
        display: grid; 
        grid-template-columns: repeat(6, 1fr); /* PERBAIKAN: Diubah menjadi 6 kolom agar jadi 1 baris */
        gap: 16px; /* Jarak antar kotak sedikit dikurangi agar muat */
        margin-bottom: 56px; 
        align-items: stretch;
    }
    .quick-card { 
        display: flex; 
        flex-direction: column; 
        transition: transform 0.2s, box-shadow 0.2s; 
        padding: 16px; /* PERBAIKAN: Padding dikurangi dari 24px jadi 16px agar ruang teks lebih lega */
    }
    .quick-card:hover { 
        transform: translateY(-4px); 
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); 
    }
    .quick-icon { 
        width: 36px; /* PERBAIKAN: Ikon sedikit diperkecil */
        height: 36px; 
        background: #eff6ff; 
        border-radius: 10px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 16px; 
        margin-bottom: 12px; 
    }
    .quick-title { font-size: 14px; font-weight: 800; color: #1e293b; margin: 0 0 6px 0; }
    .quick-desc { font-size: 11px; color: #64748b; line-height: 1.4; margin: 0 0 12px 0; flex-grow: 1; }
    .quick-link { font-size: 12px; font-weight: 700; color: #3b82f6; text-decoration: none; display: flex; align-items: center; gap: 4px; margin-top: auto; }
    .quick-link:hover { color: #2563eb; }

    /* --- BOTTOM GRID --- */
    .bottom-grid { 
        display: grid; 
        grid-template-columns: 2fr 1fr; 
        gap: 24px; 
        align-items: flex-start;
    }
    
    /* Riwayat Section */
    .history-header { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 20px; 
        border-bottom: 1px solid #f1f5f9; 
        padding-bottom: 16px;
    }
    .history-header h3 { 
        font-size: 16px; 
        font-weight: 700; 
        color: #1e293b; 
        margin: 0; 
        display: flex; 
        align-items: center; 
        gap: 8px;
    }
    .btn-text-blue { 
        color: #3b82f6; 
        font-size: 13px; 
        font-weight: 700; 
        text-decoration: none; 
        cursor: pointer; 
        border: none; 
        background: none; 
        padding: 0;
    }
    .btn-text-blue:hover { color: #2563eb; text-decoration: underline; }
    
    .empty-state { 
        text-align: center; 
        padding: 60px 20px; 
    }
    .empty-state-icon { 
        font-size: 48px; 
        margin-bottom: 16px; 
        opacity: 0.5; 
        display: inline-block;
    }
    .empty-state p { 
        color: #64748b; 
        font-size: 14px; 
        margin: 0 0 8px 0; 
        font-weight: 500;
    }
    .empty-state .link { 
        color: #3b82f6; 
        text-decoration: none; 
        font-weight: 600;
        font-size: 14px;
    }
    .empty-state .link:hover { text-decoration: underline; }

    /* Tips Section */
    .tips-list { 
        display: flex; 
        flex-direction: column; 
        gap: 12px; 
    }
    .tip-item { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        background: #f8fafc; 
        padding: 16px; 
        border-radius: 12px; 
        text-decoration: none; 
        transition: background 0.2s;
        border: 1px solid transparent;
    }
    .tip-item:hover { 
        background: #f1f5f9; 
        border-color: #e2e8f0;
    }
    .tip-content { 
        display: flex; 
        align-items: center; 
        gap: 14px; 
    }
    .tip-icon { 
        width: 40px; 
        height: 40px; 
        border-radius: 10px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 20px; 
        flex-shrink: 0;
    }
    .tip-bg-blue { background: #eff6ff; }
    .tip-bg-green { background: #f0fdf4; }
    .tip-bg-orange { background: #fff7ed; }
    .tip-title { font-size: 14px; font-weight: 700; color: #1e293b; margin: 0 0 4px 0; }
    .tip-desc { font-size: 12px; color: #64748b; margin: 0; line-height: 1.4; }
    .tip-arrow { color: #94a3b8; font-size: 14px; font-weight: bold; }

    /* --- RESPONSIVE ADJUSTMENTS --- */
    @media (max-width: 1400px) {
        .quick-menu-grid { grid-template-columns: repeat(3, 1fr); } /* Jika layar laptop kecil, jadikan 3 baris */
    }
    @media (max-width: 1200px) {
        .quick-menu-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 992px) {
        .bottom-grid { grid-template-columns: 1fr; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .hero-banner { padding: 32px 24px; }
        .hero-title { font-size: 32px; }
        .hero-buttons { flex-direction: column; }
        .btn-hero { justify-content: center; }
        .quick-menu-grid { grid-template-columns: repeat(2, 1fr); } /* Di HP jadikan 2 baris */
    }
    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr; }
        .quick-menu-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

    <!-- HERO BANNER -->
    <div class="hero-banner">
        <div class="hero-badge">👋 <span>SELAMAT PAGI!</span></div>
        <h1 class="hero-title">
            Pantau Tumbuh Kembang <br> Anak Anda <span class="hero-title-highlight">dengan Mudah</span>
        </h1>
        <p class="hero-subtitle">
            GiziAnak membantu Anda memantau status gizi anak dan menjadwalkan konsultasi bersama dokter gizi terpercaya.
        </p>
        <div class="hero-buttons">
             <a href="{{ url('orangtua/cek-gizi') }}" class="btn-hero btn-yellow">📊 Cek Gizi Sekarang</a>
            <a href="{{ url('orangtua/konsultasi') }}" class="btn-hero btn-outline">📅 Buat Jadwal</a>
        </div>
    </div>

    <!-- STATS GRID -->
    <div class="stats-grid">
        <div class="card-white stat-item">
            <div class="stat-icon bg-blue-light">📊</div>
            <div>
                <p class="stat-val">{{ $totalCekGizi }}</p>
                <p class="stat-text">Total Cek Gizi</p>
            </div>
        </div>
        <div class="card-white stat-item">
            <div class="stat-icon bg-green-light">✅</div>
            <div>
                <p class="stat-val">{{ $totalKonsultasi }}</p>
                <p class="stat-text">Total Konsultasi dan Booking</p>
            </div>
        </div>
        <div class="card-white stat-item">
            <div class="stat-icon bg-blue-light">📈</div>
            <div>
                <p class="stat-val">{{ $rataImt }}</p>
                <p class="stat-text">Rata-rata IMT</p>
            </div>
        </div>
    </div>

    <!-- QUICK MENU (Tautan sudah disesuaikan) -->
    <div class="section-label"><span style="color: #fbbf24; font-size: 16px;">⚡</span> MENU CEPAT</div>
    <div class="quick-menu-grid">
        <div class="card-white quick-card">
            <div class="quick-icon">📊</div>
            <h4 class="quick-title">Cek Gizi</h4>
            <p class="quick-desc">Hitung IMT dan status gizi anak berdasarkan usia</p>
            <a href="{{ url('orangtua/cek-gizi') }}" class="quick-link">Mulai →</a>
        </div>
        <div class="card-white quick-card">
            <div class="quick-icon">📈</div>
            <h4 class="quick-title">Riwayat Gizi</h4>
            <p class="quick-desc">Lihat grafik dan tabel riwayat hasil cek gizi anak</p>
            <a href="{{ url('orangtua/riwayat-gizi') }}" class="quick-link">Lihat →</a>
        </div>
        <div class="card-white quick-card">
            <div class="quick-icon">📅</div>
            <h4 class="quick-title">Booking Jadwal</h4>
            <p class="quick-desc">Buat janji konsultasi dengan dokter gizi pilihan</p>
            <a href="{{ url('orangtua/konsultasi') }}" class="quick-link">Booking →</a>
        </div>
        <div class="card-white quick-card">
            <div class="quick-icon">📋</div>
            <h4 class="quick-title">Riwayat Jadwal</h4>
            <p class="quick-desc">Lihat semua jadwal konsultasi yang pernah dibuat</p>
            <a href="{{ url('orangtua/riwayat-konsultasi') }}" class="quick-link">Lihat →</a>
        </div>
        <div class="card-white quick-card">
            <div class="quick-icon">💡</div>
            <h4 class="quick-title">Tips Gizi</h4>
            <p class="quick-desc">Artikel dan tips gizi sesuai usia anak dari ahlinya</p>
            <a href="{{ url('orangtua/tips') }}" class="quick-link">Baca →</a>
        </div>
        <div class="card-white quick-card">
            <div class="quick-icon">👤</div>
            <h4 class="quick-title">Profil Akun</h4>
            <p class="quick-desc">Kelola informasi dan pengaturan akun Anda</p>
            <a href="{{ url('orangtua/akun') }}" class="quick-link">Edit →</a>
        </div>
    </div>

    <!-- BOTTOM GRID -->
    <div class="bottom-grid">
        
        <!-- Kiri: Riwayat Cek Gizi -->
        <div>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                <div class="section-label" style="margin:0;"><span style="color: #64748b; font-size: 14px;">🕒</span> CEK GIZI TERAKHIR</div>
                <a href="{{ url('orangtua/riwayat-gizi') }}" class="btn-text-blue">Lihat Semua →</a>
            </div>
            
            <div class="card-white">
                <div class="history-header">
                    <h3>📊 Riwayat Cek Gizi Terbaru</h3>
                    <a href="{{ url('orangtua/cek-gizi') }}" class="btn-text-blue" style="text-decoration: none;">+ Cek Baru</a>
                </div>
                
                @if($riwayatTerbaru->isEmpty())
                <!-- Jika Kosong -->
                <div class="empty-state">
                    <div class="empty-state-icon">📊</div>
                    <p>Belum ada riwayat cek gizi.</p>
                    <a href="{{ url('orangtua/cek-gizi') }}" class="link">Mulai cek gizi anak Anda sekarang!</a>
                </div>
                @else
                <!-- Jika Ada Data -->
                <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 16px;">
                    @foreach($riwayatTerbaru as $riwayat)
                        @php
                            $imtVal = $riwayat->berat_badan / pow($riwayat->tinggi_badan / 100, 2);
                            $badgeColor = $riwayat->status_gizi == 'normal' ? '#10b981' : ($riwayat->status_gizi == 'kurang_gizi' ? '#f59e0b' : '#ef4444');
                            $badgeBg = $riwayat->status_gizi == 'normal' ? '#dcfce7' : ($riwayat->status_gizi == 'kurang_gizi' ? '#fef3c7' : '#fee2e2');
                        @endphp
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="font-size: 24px; background: white; padding: 8px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                                {{ $riwayat->anak->jenis_kelamin == 'L' ? '👦' : '👧' }}
                            </div>
                            <div>
                                <h4 style="margin: 0 0 4px 0; font-size: 14px; font-weight: 800; color: #1e293b; text-transform: capitalize;">{{ $riwayat->anak->nama }}</h4>
                                <p style="margin: 0; font-size: 12px; color: #64748b;">{{ \Carbon\Carbon::parse($riwayat->tanggal_ukur)->translatedFormat('d M Y') }} • IMT: {{ number_format($imtVal, 1) }}</p>
                            </div>
                        </div>
                        <div style="font-size: 11px; font-weight: 800; padding: 6px 12px; border-radius: 20px; color: {{ $badgeColor }}; background: {{ $badgeBg }}; border: 1px solid {{ $badgeColor }}40;">
                            {{ strtoupper(str_replace('_', ' ', $riwayat->status_gizi)) }}
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Kanan: Tips Terbaru -->
        <div>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                <div class="section-label" style="margin:0;"><span style="color: #fbbf24; font-size: 14px;">💡</span> TIPS TERBARU</div>
                <a href="#" class="btn-text-blue">Lihat Semua →</a>
            </div>

            <div class="card-white">
                <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin: 0 0 20px 0; display: flex; align-items: center; gap: 8px;">
                    💡 Tips Gizi & Tumbuh Kembang
                </h3>
                
                <div class="tips-list">
                    <a href="#" class="tip-item">
                        <div class="tip-content">
                            <div class="tip-icon tip-bg-blue">🍼</div>
                            <div>
                                <p class="tip-title">Usia 0–1 Tahun</p>
                                <p class="tip-desc">Fokus pada ASI eksklusif dan MPASI bergizi</p>
                            </div>
                        </div>
                        <span class="tip-arrow">❯</span>
                    </a>
                    
                    <a href="#" class="tip-item">
                        <div class="tip-content">
                            <div class="tip-icon tip-bg-green">🥦</div>
                            <div>
                                <p class="tip-title">Usia 1–2 Tahun</p>
                                <p class="tip-desc">Pola makan seimbang dan variasi makanan setiap hari</p>
                            </div>
                        </div>
                        <span class="tip-arrow">❯</span>
                    </a>
                    
                    <a href="#" class="tip-item">
                        <div class="tip-content">
                            <div class="tip-icon tip-bg-orange">⚡</div>
                            <div>
                                <p class="tip-title">Usia 2–3 Tahun</p>
                                <p class="tip-desc">Energi cukup untuk aktivitas bermain dan belajar</p>
                            </div>
                        </div>
                        <span class="tip-arrow">❯</span>
                    </a>
                    
                    <a href="#" class="tip-item">
                        <div class="tip-content">
                            <div class="tip-icon tip-bg-blue">📘</div>
                            <div>
                                <p class="tip-title">Usia 3–4 Tahun</p>
                                <p class="tip-desc">Dukung pertumbuhan tulang dan fokus belajar optimal</p>
                            </div>
                        </div>
                        <span class="tip-arrow">❯</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

@endsection