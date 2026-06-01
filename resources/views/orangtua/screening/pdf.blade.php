<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Skrining Stunting — {{ ucfirst($screening->anak->nama) }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; color: #1e293b; background: white; }

        /* HEADER */
        .header { background: #0A2540; color: white; padding: 20px 28px; display: flex; justify-content: space-between; align-items: center; }
        .header-left h1 { font-size: 18px; font-weight: 700; margin-bottom: 2px; }
        .header-left p { font-size: 10px; opacity: 0.65; }
        .header-right { text-align: right; font-size: 10px; opacity: 0.7; }

        /* HERO STATUS */
        .status-hero { padding: 20px 28px; display: flex; align-items: center; gap: 20px; border-bottom: 2px solid #f1f5f9; }
        .status-icon { width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 26px; flex-shrink: 0; }
        .status-text h2 { font-size: 20px; font-weight: 700; }
        .status-text p { font-size: 11px; color: #64748b; margin-top: 3px; }
        .zscore-big { font-size: 28px; font-weight: 700; margin-left: auto; }

        /* STATUS COLORS */
        .status-normal     { background: #dcfce7; color: #15803d; }
        .status-risiko     { background: #fef9c3; color: #a16207; }
        .status-stunting   { background: #ffedd5; color: #c2410c; }
        .status-severe     { background: #fee2e2; color: #b91c1c; }

        /* SECTIONS */
        .section { padding: 18px 28px; border-bottom: 1px solid #f1f5f9; }
        .section-title { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: #94a3b8; margin-bottom: 14px; }

        /* GRID DATA */
        .data-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px 20px; }
        .data-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px 20px; }
        .data-item label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; color: #94a3b8; display: block; margin-bottom: 3px; }
        .data-item span { font-size: 13px; font-weight: 600; color: #1e293b; }

        /* Z-SCORE BAR */
        .zscore-section { padding: 16px 28px; background: #f8fafc; border-bottom: 1px solid #f1f5f9; }
        .zscore-bar-wrap { margin-top: 8px; }
        .zscore-bar-labels { display: flex; justify-content: space-between; font-size: 9px; color: #94a3b8; margin-bottom: 4px; }
        .zscore-bar-bg { height: 10px; background: linear-gradient(to right, #ef4444, #f97316, #fbbf24, #10b981); border-radius: 99px; position: relative; }
        .zscore-marker { width: 14px; height: 14px; border-radius: 50%; border: 2px solid white; position: absolute; top: -2px; transform: translateX(-50%); }

        /* REKOMENDASI */
        .rekomendasi-box { padding: 14px 16px; border-radius: 8px; border-left: 4px solid #3b82f6; background: #f0f9ff; font-size: 12px; line-height: 1.7; color: #334155; }
        .rekomendasi-box.merah  { border-left-color: #ef4444; background: #fff1f2; }
        .rekomendasi-box.kuning { border-left-color: #f59e0b; background: #fffbeb; }
        .rekomendasi-box.hijau  { border-left-color: #10b981; background: #f0fdf4; }

        /* BADGES */
        .badge { display: inline-block; padding: 2px 9px; border-radius: 20px; font-size: 10px; font-weight: 700; }
        .badge-ya    { background: #dcfce7; color: #15803d; }
        .badge-tidak { background: #fee2e2; color: #b91c1c; }
        .badge-na    { background: #f1f5f9; color: #64748b; }

        /* FAKTOR RISIKO */
        .risiko-list { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
        .risiko-chip { padding: 3px 10px; border-radius: 20px; font-size: 10px; font-weight: 600; background: #ffedd5; color: #c2410c; }

        /* FOOTER */
        .footer { padding: 16px 28px; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #f1f5f9; }
        .footer strong { color: #475569; }

        /* JADWAL KONTROL */
        .kontrol-box { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; gap: 12px; }
        .kontrol-box .icon { font-size: 22px; }
        .kontrol-box .text label { font-size: 9px; font-weight: 700; text-transform: uppercase; color: #3b82f6; letter-spacing: 0.4px; }
        .kontrol-box .text span { display: block; font-size: 14px; font-weight: 700; color: #1e3a8a; }
    </style>
</head>
<body>

@php
    $anak      = $screening->anak;
    $status    = $screening->status;
    $zscore    = $screening->zscore;

    $statusClass = match($status) {
        'Normal'            => 'status-normal',
        'Berisiko Stunting' => 'status-risiko',
        'Stunting'          => 'status-stunting',
        'Severely Stunting' => 'status-severe',
        default             => 'status-risiko',
    };
    $rekColor = match($status) {
        'Normal'            => 'hijau',
        'Berisiko Stunting' => 'kuning',
        'Stunting'          => 'kuning',
        'Severely Stunting' => 'merah',
        default             => '',
    };
    $dotColor = match($status) {
        'Normal'            => '#10b981',
        'Berisiko Stunting' => '#f59e0b',
        'Stunting'          => '#f97316',
        'Severely Stunting' => '#ef4444',
        default             => '#3b82f6',
    };
    $statusIcon = match($status) {
        'Normal'            => '✅',
        'Berisiko Stunting' => '⚠',
        'Stunting'          => '🟠',
        'Severely Stunting' => '🔴',
        default             => '📋',
    };

    $zMin = -4; $zMax = 2;
    $zPct = max(2, min(98, (($zscore - $zMin) / ($zMax - $zMin)) * 100));

    $yaBadge    = '<span class="badge badge-ya">Ya</span>';
    $tidakBadge = '<span class="badge badge-tidak">Tidak</span>';
    $naBadge    = '<span class="badge badge-na">—</span>';

    $b = fn($val) => $val === null ? $naBadge : ($val == 1 ? $yaBadge : $tidakBadge);

    $tglUkur = \Carbon\Carbon::parse($screening->tanggal_pengukuran ?? $screening->created_at)->translatedFormat('d F Y');
    $tglCetak = \Carbon\Carbon::now()->translatedFormat('d F Y, H:i');
    $tglLahir = \Carbon\Carbon::parse($anak->tanggal_lahir)->translatedFormat('d F Y');
@endphp

{{-- HEADER --}}
<div class="header">
    <div class="header-left">
        <h1>GiziAnak — Laporan Skrining Stunting</h1>
        <p>Sistem Pemantauan Pertumbuhan Anak | Berbasis Standar WHO & Kemenkes RI</p>
    </div>
    <div class="header-right">
        Dicetak: {{ $tglCetak }}<br>
        No. Rekam: #{{ str_pad($screening->id, 5, '0', STR_PAD_LEFT) }}
    </div>
</div>

{{-- STATUS HERO --}}
<div class="status-hero">
    <div class="status-icon {{ $statusClass }}">{{ $statusIcon }}</div>
    <div class="status-text">
        <h2>{{ $status }}</h2>
        <p>Berdasarkan Z-Score TB/U (Tinggi Badan menurut Umur) — WHO Child Growth Standards 2006</p>
    </div>
    <div class="zscore-big" style="color: {{ $dotColor }};">
        {{ $zscore > 0 ? '+' : '' }}{{ $zscore }} SD
    </div>
</div>

{{-- Z-SCORE BAR --}}
<div class="zscore-section">
    <div class="section-title">Posisi Z-Score pada Kurva Pertumbuhan WHO</div>
    <div class="zscore-bar-wrap">
        <div class="zscore-bar-labels">
            <span>-4 SD (Sangat Pendek)</span>
            <span>-3 SD</span>
            <span>-2 SD (Stunting)</span>
            <span>-1 SD</span>
            <span>0 (Median)</span>
            <span>+2 SD</span>
        </div>
        <div class="zscore-bar-bg">
            <div class="zscore-marker" style="left: {{ $zPct }}%; background: {{ $dotColor }};"></div>
        </div>
    </div>
</div>

{{-- DATA ANAK --}}
<div class="section">
    <div class="section-title">Identitas Anak</div>
    <div class="data-grid">
        <div class="data-item"><label>Nama</label><span>{{ ucfirst($anak->nama) }}</span></div>
        <div class="data-item"><label>Jenis Kelamin</label><span>{{ $anak->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span></div>
        <div class="data-item"><label>Tanggal Lahir</label><span>{{ $tglLahir }}</span></div>
        <div class="data-item"><label>Usia Saat Pengukuran</label><span>{{ $screening->usia_bulan }} bulan</span></div>
        <div class="data-item"><label>Tanggal Pengukuran</label><span>{{ $tglUkur }}</span></div>
        @if($screening->berat_lahir)
        <div class="data-item"><label>Berat Lahir</label><span>{{ $screening->berat_lahir }} kg {{ $screening->berat_lahir < 2.5 ? '⚠ BBLR' : '' }}</span></div>
        @endif
    </div>
</div>

{{-- DATA ANTROPOMETRI --}}
<div class="section">
    <div class="section-title">Data Antropometri</div>
    <div class="data-grid">
        <div class="data-item"><label>Berat Badan</label><span>{{ $screening->berat_badan }} kg</span></div>
        <div class="data-item"><label>Tinggi / Panjang Badan</label><span>{{ $screening->tinggi_badan }} cm</span></div>
        <div class="data-item"><label>Z-Score TB/U</label><span style="color:{{ $dotColor }}; font-weight:700;">{{ $zscore > 0 ? '+' : '' }}{{ $zscore }} SD</span></div>
        @if($screening->lingkar_kepala)
        <div class="data-item"><label>Lingkar Kepala</label><span>{{ $screening->lingkar_kepala }} cm</span></div>
        @endif
        @if($screening->lila)
        <div class="data-item"><label>LILA</label><span>{{ $screening->lila }} cm {{ $screening->lila < 12.5 ? '⚠ Kurang' : '' }}</span></div>
        @endif
    </div>
</div>

{{-- RIWAYAT KESEHATAN --}}
<div class="section">
    <div class="section-title">Riwayat Kesehatan</div>
    <div class="data-grid">
        <div class="data-item"><label>ASI Eksklusif</label><span>{!! $screening->asi_eksklusif === null ? $naBadge : ($screening->asi_eksklusif == 1 ? $yaBadge : ($screening->asi_eksklusif == 2 ? '<span class="badge badge-na">Sebagian</span>' : $tidakBadge)) !!}</span></div>
        <div class="data-item"><label>Lahir Prematur</label><span>{!! $b($screening->riwayat_prematur) !!}</span></div>
        <div class="data-item"><label>Imunisasi Dasar Lengkap</label><span>{!! $b($screening->imunisasi_lengkap) !!}</span></div>
        <div class="data-item"><label>Riwayat Diare Berulang</label><span>{!! $b($screening->riwayat_diare_berulang) !!}</span></div>
        <div class="data-item"><label>Pernah Rawat Inap</label><span>{!! $b($screening->riwayat_rawat_inap) !!}</span></div>
        @if($screening->riwayat_penyakit)
        <div class="data-item" style="grid-column:span 2;"><label>Riwayat Penyakit Infeksi</label><span>{{ $screening->riwayat_penyakit }}</span></div>
        @endif
    </div>
</div>

{{-- POLA MAKAN & SANITASI --}}
<div class="section">
    <div class="section-title">Pola Makan & Sanitasi</div>
    <div class="data-grid">
        <div class="data-item">
            <label>Frekuensi Makan / Hari</label>
            <span>{{ $screening->frekuensi_makan ? $screening->frekuensi_makan . 'x' : '—' }}</span>
        </div>
        <div class="data-item"><label>MPASI Sesuai Usia</label><span>{!! $b($screening->mpasi_sesuai_usia) !!}</span></div>
        <div class="data-item">
            <label>Protein Hewani</label>
            <span>{!! $screening->konsumsi_protein === null ? $naBadge : ($screening->konsumsi_protein == 1 ? '<span class="badge badge-ya">Rutin</span>' : ($screening->konsumsi_protein == 2 ? '<span class="badge badge-na">Kadang</span>' : '<span class="badge badge-tidak">Jarang</span>')) !!}</span>
        </div>
        <div class="data-item"><label>Suplemen / Vitamin</label><span>{!! $b($screening->konsumsi_suplemen) !!}</span></div>
        <div class="data-item"><label>Akses Air Bersih</label><span>{!! $b($screening->akses_air_bersih) !!}</span></div>
        <div class="data-item"><label>Kebiasaan Cuci Tangan</label><span>{!! $b($screening->kebiasaan_cuci_tangan) !!}</span></div>
        @if($screening->kondisi_sanitasi)
        <div class="data-item"><label>Kondisi Sanitasi</label><span style="text-transform:capitalize;">{{ $screening->kondisi_sanitasi }}</span></div>
        @endif
    </div>
</div>

{{-- REKOMENDASI --}}
<div class="section">
    <div class="section-title">Rekomendasi Tindak Lanjut</div>
    <div class="rekomendasi-box {{ $rekColor }}">
        {{ $screening->rekomendasi }}
    </div>
</div>

{{-- JADWAL KONTROL --}}
@if($screening->jadwal_kontrol_berikutnya)
<div class="section">
    <div class="kontrol-box">
        <div class="icon">📅</div>
        <div class="text">
            <label>Jadwal Kontrol Berikutnya</label>
            <span>{{ \Carbon\Carbon::parse($screening->jadwal_kontrol_berikutnya)->translatedFormat('d F Y') }}</span>
        </div>
    </div>
</div>
@endif

{{-- FOOTER --}}
<div class="footer">
    Dokumen ini diterbitkan oleh sistem <strong>GiziAnak</strong> berdasarkan data yang diinput oleh orang tua/wali.
    Hasil skrining ini <strong>bukan pengganti diagnosis medis</strong>. Konsultasikan kondisi anak ke tenaga kesehatan yang berkompeten.<br>
    Referensi: WHO Child Growth Standards (2006) &bull; Permenkes RI No. 2 Tahun 2020 tentang Standar Antropometri Anak
</div>

</body>
</html>