@extends('layouts.app')

@section('title', 'Dashboard Ahli Gizi - GiziAnak')

@section('custom_css')
<style>
    /* --- HERO BANNER --- */
    .hero-banner { background: linear-gradient(135deg, #0f1c2e 0%, #1e3a5f 100%); border-radius: 20px; padding: 48px; color: white; margin-bottom: 32px; position: relative; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(15, 28, 46, 0.2); }
    .hero-badge { display: inline-flex; align-items: center; gap: 6px; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); padding: 6px 16px; border-radius: 20px; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 24px; backdrop-filter: blur(4px); }
    .hero-title { font-size: 38px; font-weight: 900; line-height: 1.2; margin: 0 0 16px 0; font-family: Georgia, serif; }
    .hero-title-highlight { color: #fbbf24; font-style: italic; }
    .hero-subtitle { font-size: 15px; color: #94a3b8; max-width: 500px; line-height: 1.6; margin-bottom: 0; }

    /* --- SECTION LABELS & CARDS --- */
    .section-label { display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; }
    .card-white { background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; }

    /* --- STATS GRID --- */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px; }
    .stat-item { display: flex; align-items: center; gap: 16px; }
    .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0; }
    .bg-blue-light { background: #eff6ff; }
    .bg-orange-light { background: #fff7ed; }
    .bg-purple-light { background: #f3e8ff; }
    .bg-green-light { background: #f0fdf4; }
    .stat-val { font-size: 20px; font-weight: 800; color: #1e293b; margin: 0 0 4px 0; line-height: 1;}
    .stat-text { font-size: 13px; color: #64748b; font-weight: 500; margin: 0;}

    /* --- QUICK MENU GRID (4 Kolom untuk Ahli Gizi) --- */
    .quick-menu-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 40px; align-items: stretch; }
    .quick-card { display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s; padding: 20px; text-decoration: none; }
    .quick-card:hover { transform: translateY(-4px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); }
    .quick-icon { width: 40px; height: 40px; background: #eff6ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; margin-bottom: 16px; }
    .quick-title { font-size: 14px; font-weight: 800; color: #1e293b; margin: 0 0 6px 0; }
    .quick-desc { font-size: 12px; color: #64748b; line-height: 1.4; margin: 0; }

    /* --- BOTTOM GRID --- */
    .bottom-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: flex-start; }
    
    .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; }
    .card-header h3 { font-size: 16px; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 8px; }
    .btn-text-blue { color: #3b82f6; font-size: 13px; font-weight: 700; text-decoration: none; cursor: pointer; border: none; background: none; padding: 0; }
    .btn-text-blue:hover { color: #2563eb; text-decoration: underline; }

    /* List Jadwal Hari Ini */
    .schedule-list { display: flex; flex-direction: column; gap: 12px; }
    .schedule-item { display: flex; align-items: center; gap: 16px; padding: 16px; border: 1px solid #f1f5f9; border-radius: 12px; transition: background 0.2s; }
    .schedule-item:hover { background: #f8fafc; }
    .schedule-time { background: #eff6ff; color: #2563eb; font-weight: 800; font-size: 13px; padding: 8px 12px; border-radius: 8px; text-align: center; min-width: 70px; }
    .schedule-info { flex-grow: 1; }
    .patient-name { font-size: 14px; font-weight: 700; color: #1e293b; margin: 0 0 4px 0; }
    .patient-detail { font-size: 12px; color: #64748b; margin: 0; }
    .btn-mulai { background: #10b981; color: white; border: none; padding: 8px 16px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; transition: background 0.2s; display: inline-block; text-decoration: none; text-align: center; }
    .btn-mulai:hover { background: #059669; }

    /* List Permintaan Baru */
    .request-list { display: flex; flex-direction: column; gap: 12px; }
    .request-item { background: #fff7ed; border: 1px solid #ffedd5; padding: 16px; border-radius: 12px; }
    .request-header { display: flex; justify-content: space-between; margin-bottom: 12px; }
    .req-name { font-size: 13px; font-weight: 700; color: #1e293b; margin: 0; }
    .req-date { font-size: 11px; font-weight: 700; color: #ea580c; background: white; padding: 4px 8px; border-radius: 6px; }
    .req-note { font-size: 12px; color: #64748b; margin: 0 0 12px 0; font-style: italic; }
    .req-actions { display: flex; gap: 8px; }
    .btn-terima { flex: 1; background: #3b82f6; color: white; border: none; padding: 8px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; text-decoration: none; text-align: center; }
    .btn-tolak { flex: 1; background: white; color: #dc2626; border: 1px solid #fecaca; padding: 8px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; text-decoration: none; text-align: center;}

    /* --- RESPONSIVE --- */
    @media (max-width: 1200px) { .quick-menu-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 992px) { .bottom-grid { grid-template-columns: 1fr; } .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) { .hero-banner { padding: 32px 24px; } .hero-title { font-size: 32px; } }
    @media (max-width: 480px) { .stats-grid { grid-template-columns: 1fr; } .quick-menu-grid { grid-template-columns: 1fr; } .schedule-item { flex-direction: column; align-items: flex-start; } .btn-mulai { width: 100%; } }
</style>
@endsection

@section('content')

    <div class="hero-banner">
        <div class="hero-badge">👋 <span>SELAMAT BEKERJA, {{ strtoupper(Auth::user()->name) }}!</span></div>
        <h1 class="hero-title">
            Bantu Anak Mencapai <br> <span class="hero-title-highlight">Gizi Optimal Mereka</span>
        </h1>
        <p class="hero-subtitle">
            Kelola jadwal konsultasi Anda hari ini dan pantau perkembangan gizi pasien dengan mudah.
        </p>
    </div>

    <div class="stats-grid">
    
        <div class="card-white stat-item">
            <div class="stat-icon bg-orange-light">📥</div>
            <div>
                <p class="stat-val">{{ $totalPermintaanBaru }}</p>
                <p class="stat-text">Permintaan Baru</p>
            </div>
        </div>
        <div class="card-white stat-item">
            <div class="stat-icon bg-green-light">👶</div>
            <div>
                <p class="stat-val">{{ $totalPasien }}</p>
                <p class="stat-text">Total Pasien</p>
            </div>
        </div>
    </div>

    <div class="section-label"><span style="color: #fbbf24; font-size: 16px;">⚡</span> MENU CEPAT</div>
    <div class="quick-menu-grid">
        <a href="{{ route('ahligizi.permintaan.index') }}" class="card-white quick-card">
            <div class="quick-icon">📥</div>
            <h4 class="quick-title">Permintaan Masuk</h4>
            <p class="quick-desc">Cek dan konfirmasi jadwal baru</p>
        </a>
        <a href="{{ route('ahligizi.profile.index') }}" class="card-white quick-card">
            <div class="quick-icon">👤</div>
            <h4 class="quick-title">Akun saya</h4>
            <p class="quick-desc">Kelola Akun</p>
        </a>
        <a href="{{ route('ahligizi.jam.index') }}" class="card-white quick-card">
            <div class="quick-icon">⏰</div>
            <h4 class="quick-title">Jam Praktik</h4>
            <p class="quick-desc">Atur waktu ketersediaan Anda</p>
        </a>
        <a href="{{ route('ahligizi.pasien.index') }}" class="card-white quick-card">
            <div class="quick-icon">👶</div>
            <h4 class="quick-title">Daftar Pasien</h4>
            <p class="quick-desc">Lihat data seluruh pasien Anda</p>
        </a>
    </div>

    <div class="bottom-grid">
        
        <div>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                <div class="section-label" style="margin:0;"><span style="color: #3b82f6; font-size: 14px;">📅</span> Daftar Pasien Terbaru</div>
                <a href="{{ route('ahligizi.pasien.index') }}" class="btn-text-blue">Lihat Semua Pasien →</a>
            </div>
            
            <div class="card-white">
                <div class="card-header">
                    <h3>Daftar Pasien ({{ $pasienTerbaru->count() }} Pasien)</h3>
                </div>
                
                <div class="schedule-list">
                    @forelse($pasienTerbaru as $pasien)
                        @php
                            $umur = '-';
                            if(isset($pasien->anak->tanggal_lahir)) {
                                $umur = \Carbon\Carbon::parse($pasien->anak->tanggal_lahir)->diffInYears(now()) . ' thn';
                                if($umur == '0 thn') {
                                    $umur = \Carbon\Carbon::parse($pasien->anak->tanggal_lahir)->diffInMonths(now()) . ' bln';
                                }
                            }
                        @endphp
                        <div class="schedule-item">
                            <div class="schedule-time">
                                {{ \Carbon\Carbon::parse($pasien->tanggal_jadwal)->format('d M') }}<br>
                                <span style="font-size: 10px; font-weight: 600; color: #94a3b8;">{{ \Carbon\Carbon::parse($pasien->tanggal_jadwal)->format('Y') }}</span>
                            </div>
                            <div class="schedule-info">
                                <p class="patient-name">{{ $pasien->nama_anak ?? $pasien->anak->nama ?? 'Nama Anak' }} ({{ $umur }})</p>
                                <p class="patient-detail">Wali: {{ $pasien->nama_ortu ?? 'Wali' }} • Kasus: {{ Str::limit($pasien->keluhan ?? 'Konsultasi rutinan', 35) }}</p>
                            </div>
                            <a href="{{ route('ahligizi.pasien.index') }}" class="btn-mulai">Buka Pasien</a>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 20px; color: #94a3b8; font-size: 13px;">
                            Belum ada pasien yang ditangani.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                <div class="section-label" style="margin:0;"><span style="color: #fbbf24; font-size: 14px;">🔔</span> PERMINTAAN MASUK</div>
                <a href="{{ route('ahligizi.permintaan.index') }}" class="btn-text-blue">Lihat Semua →</a>
            </div>

            <div class="card-white">
                <h3 style="font-size: 15px; font-weight: 700; color: #1e293b; margin: 0 0 16px 0;">Menunggu Konfirmasi ({{ $permintaanMasuk->count() }})</h3>
                
                <div class="request-list">
                    @forelse($permintaanMasuk->take(3) as $req)
                        <div class="request-item">
                            <div class="request-header">
                                <p class="req-name">👤 Pasien: {{ $req->nama_anak ?? $req->anak->nama ?? 'Anak' }}</p>
                                <span class="req-date">{{ \Carbon\Carbon::parse($req->tanggal_jadwal)->translatedFormat('d M') }}</span>
                            </div>
                            <p class="req-note">"{{ Str::limit($req->keluhan ?? 'Konsultasi gizi', 50) }}"</p>
                            <div class="req-actions">
                                <a href="{{ route('ahligizi.permintaan.index') }}" class="btn-terima" style="display:block; width:100%;">Tinjau Permintaan</a>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 20px; color: #94a3b8; font-size: 13px;">
                            Tidak ada permintaan masuk.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

@endsection