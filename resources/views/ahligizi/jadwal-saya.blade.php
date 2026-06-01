@extends('layouts.app')

@section('title', 'Jadwal Saya - GiziAnak')

@section('custom_css')
<style>
    /* --- HEADER HALAMAN --- */
    .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; flex-wrap: wrap; gap: 16px; }
    .header-title-group h2 { margin: 0 0 8px 0; font-size: 28px; font-weight: 900; color: #0f1c2e; font-family: Georgia, serif; }
    .header-title-group p { margin: 0; color: #64748b; font-size: 15px; }

    /* --- FILTER BAR & TANGGAL --- */
    .top-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    
    .date-picker-wrapper { display: flex; align-items: center; gap: 12px; background: white; border: 1px solid #e2e8f0; padding: 6px 16px; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.01); }
    .btn-nav-date { background: none; border: none; font-size: 16px; color: #64748b; cursor: pointer; padding: 4px 8px; transition: color 0.2s; }
    .btn-nav-date:hover { color: #3b82f6; }
    .current-date { font-size: 14px; font-weight: 800; color: #1e293b; min-width: 140px; text-align: center; }

    .status-filter { display: flex; gap: 8px; }
    .filter-btn { padding: 8px 16px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer; border: 1px solid transparent; transition: all 0.2s; }
    .filter-btn.active { background: #1e293b; color: white; }
    .filter-btn:not(.active) { background: white; border-color: #e2e8f0; color: #64748b; }
    .filter-btn:not(.active):hover { background: #f8fafc; }

    /* --- LIST JADWAL --- */
    .schedule-list-container { background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; overflow: hidden; }
    
    .schedule-row { display: flex; align-items: center; padding: 20px 24px; border-bottom: 1px solid #f1f5f9; transition: background 0.2s; gap: 24px; }
    .schedule-row:last-child { border-bottom: none; }
    .schedule-row:hover { background: #f8fafc; }

    /* Elemen di dalam Baris Jadwal */
    .time-block { text-align: center; min-width: 80px; border-right: 2px dashed #e2e8f0; padding-right: 24px; }
    .time-val { font-size: 18px; font-weight: 900; color: #3b82f6; font-family: Georgia, serif; line-height: 1; margin: 0 0 4px 0; }
    .time-am-pm { font-size: 11px; font-weight: 700; color: #94a3b8; }

    .patient-block { flex-grow: 1; }
    .patient-header { display: flex; align-items: center; gap: 12px; margin-bottom: 6px; }
    .p-name { font-size: 16px; font-weight: 800; color: #1e293b; margin: 0; }
    .p-age { font-size: 12px; font-weight: 600; color: #64748b; background: #f1f5f9; padding: 2px 8px; border-radius: 6px; }
    .parent-name { font-size: 13px; color: #64748b; margin: 0; display: flex; align-items: center; gap: 6px; }

    .status-block { min-width: 140px; }
    .badge { padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; }
    .badge-menunggu { background: #fff7ed; color: #ea580c; border: 1px solid #ffedd5; }
    .badge-selesai { background: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; }

    .action-block { min-width: 140px; text-align: right; }
    .btn-action { padding: 10px 16px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer; border: none; transition: all 0.2s; }
    .btn-start { background: #3b82f6; color: white; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2); }
    .btn-start:hover { background: #2563eb; transform: translateY(-1px); }
    .btn-view { background: #f1f5f9; color: #475569; }
    .btn-view:hover { background: #e2e8f0; }

    /* --- RESPONSIVE --- */
    @media (max-width: 768px) {
        .top-controls { flex-direction: column; align-items: stretch; }
        .date-picker-wrapper { justify-content: space-between; }
        .schedule-row { flex-direction: column; align-items: flex-start; gap: 16px; position: relative; }
        .time-block { border-right: none; border-bottom: 2px dashed #e2e8f0; width: 100%; padding-right: 0; padding-bottom: 12px; text-align: left; display: flex; align-items: baseline; gap: 8px; }
        .action-block { width: 100%; text-align: left; }
        .btn-action { width: 100%; }
        .status-block { position: absolute; top: 20px; right: 24px; }
    }
</style>
@endsection

@section('content')

<!-- HEADER -->
<div class="page-header">
    <div class="header-title-group">
        <h2>Jadwal Saya</h2>
        <p>Daftar pasien yang akan melakukan konsultasi gizi dengan Anda</p>
    </div>
</div>

<!-- TOP CONTROLS (TANGGAL & FILTER) -->
<div class="top-controls">
    <div class="date-picker-wrapper">
        <button class="btn-nav-date">◀</button>
        <span class="current-date">Hari Ini, 9 Mei 2026</span>
        <button class="btn-nav-date">▶</button>
    </div>
    <div class="status-filter">
        <button class="filter-btn active">Semua Jadwal</button>
        <button class="filter-btn">Akan Datang</button>
        <button class="filter-btn">Selesai</button>
    </div>
</div>

<!-- LIST JADWAL -->
<div class="schedule-list-container">
    
    <!-- Baris 1 (Akan Datang) -->
    <div class="schedule-row">
        <div class="time-block">
            <h3 class="time-val">09:00</h3>
            <span class="time-am-pm">WIB</span>
        </div>
        <div class="patient-block">
            <div class="patient-header">
                <h3 class="p-name">Raka</h3>
                <span class="p-age">5 Tahun</span>
            </div>
            <p class="parent-name"><span>👤</span> Nama Orang Tua: Budi Santoso</p>
        </div>
        <div class="status-block">
            <span class="badge badge-menunggu">⏳ Menunggu Sesi</span>
        </div>
        <div class="action-block">
            <button class="btn-action btn-start">Mulai Konsultasi</button>
        </div>
    </div>

    <!-- Baris 2 (Akan Datang) -->
    <div class="schedule-row">
        <div class="time-block">
            <h3 class="time-val">11:30</h3>
            <span class="time-am-pm">WIB</span>
        </div>
        <div class="patient-block">
            <div class="patient-header">
                <h3 class="p-name">Salsa</h3>
                <span class="p-age">2 Tahun</span>
            </div>
            <p class="parent-name"><span>👤</span> Nama Orang Tua: Rina Lestari</p>
        </div>
        <div class="status-block">
            <span class="badge badge-menunggu">⏳ Menunggu Sesi</span>
        </div>
        <div class="action-block">
            <button class="btn-action btn-start">Mulai Konsultasi</button>
        </div>
    </div>

    <!-- Baris 3 (Selesai) -->
    <div class="schedule-row">
        <div class="time-block">
            <h3 class="time-val">14:00</h3>
            <span class="time-am-pm">WIB</span>
        </div>
        <div class="patient-block">
            <div class="patient-header">
                <h3 class="p-name">Bagas</h3>
                <span class="p-age">8 Bulan</span>
            </div>
            <p class="parent-name"><span>👤</span> Nama Orang Tua: Sari Utami</p>
        </div>
        <div class="status-block">
            <span class="badge badge-selesai">✅ Selesai</span>
        </div>
        <div class="action-block">
            <button class="btn-action btn-view">Lihat Catatan</button>
        </div>
    </div>

</div>

@endsection