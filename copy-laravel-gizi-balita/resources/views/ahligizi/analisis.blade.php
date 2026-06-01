@extends('layouts.app')

@section('title', 'Analisis Gizi Pasien - GiziAnak')

@section('custom_css')
<style>
    /* --- HEADER HALAMAN --- */
    .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .header-title-group h2 { margin: 0 0 8px 0; font-size: 28px; font-weight: 900; color: #0f1c2e; font-family: Georgia, serif; }
    .header-title-group p { margin: 0; color: #64748b; font-size: 15px; }

    .btn-back { padding: 8px 16px; border-radius: 10px; font-size: 13px; font-weight: 700; color: #64748b; background: white; border: 1px solid #e2e8f0; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; }
    .btn-back:hover { background: #f8fafc; color: #1e293b; }

    /* --- LAYOUT UTAMA --- */
    .analysis-grid { display: grid; grid-template-columns: 1fr 400px; gap: 24px; align-items: start; }

    /* --- PATIENT INFO CARD --- */
    .patient-card { background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; padding: 24px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; }
    
    .p-profile { display: flex; align-items: center; gap: 16px; }
    .p-avatar { width: 60px; height: 60px; border-radius: 16px; background: #eff6ff; display: flex; align-items: center; justify-content: center; font-size: 32px; }
    .p-name { font-size: 20px; font-weight: 900; color: #1e293b; margin: 0 0 4px 0; text-transform: capitalize;}
    .p-detail { font-size: 13px; color: #64748b; margin: 0; }

    .p-stats { display: flex; gap: 24px; }
    .stat-box { text-align: right; }
    .stat-label { font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px; display: block; }
    .stat-value { font-size: 18px; font-weight: 900; color: #1e293b; font-family: Georgia, serif; }
    .status-badge { display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: capitalize;}
    
    .status-normal { background: #dcfce7; color: #16a34a; }
    .status-obesitas { background: #ffedd5; color: #ea580c; }
    .status-kurang_gizi { background: #fef08a; color: #ca8a04; }

    /* --- CHART CARD --- */
    .chart-card { background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; padding: 24px; }
    .analisis-section-title { font-size: 14px; font-weight: 800; color: #1e293b; margin: 0 0 20px 0; display: flex; justify-content: space-between; align-items: center; }
    
    .chart-tabs { display: flex; gap: 12px; margin-bottom: 20px; }
    .chart-tab { padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 700; cursor: pointer; border: 1px solid #e2e8f0; background: white; color: #64748b; transition: 0.2s;}
    .chart-tab.active { background: #1e293b; color: white; border-color: #1e293b; }
    
    .chart-area { height: 350px; width: 100%; position: relative; }

    /* --- FORM KANAN --- */
    .form-card { background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; padding: 24px; position: sticky; top: 24px; }
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; font-size: 12px; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 8px; }
    .form-input { width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 14px; color: #334155; outline: none; transition: border-color 0.2s; background: #f8fafc; font-family: inherit; box-sizing: border-box;}
    .form-input:focus { border-color: #3b82f6; background: white; }
    textarea.form-input { resize: vertical; min-height: 100px; }

    .btn-submit { width: 100%; padding: 14px; border-radius: 10px; font-size: 14px; font-weight: 700; cursor: pointer; border: none; background: #3b82f6; color: white; transition: all 0.2s; box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2); }
    .btn-submit:hover { background: #2563eb; transform: translateY(-2px); }

    /* TANDA TANGAN (Sembunyikan di layar) */
    .signature-wrapper { display: none; }

    /* --- CSS KHUSUS CETAK PDF (1 HALAMAN) --- */
    /* --- CSS KHUSUS CETAK PDF (1 HALAMAN PASTI MUAT) --- */
    @media print {
        /* 1. Margin kertas diperkecil agar lebih luas */
        @page { size: A4 portrait; margin: 0.5cm; }

        nav, aside, header, .sidebar, .btn-back, .btn-submit, .btn-print, .chart-tabs { display: none !important; }

        body, html { background-color: white !important; margin: 0 !important; padding: 0 !important; font-size: 11px !important; }

        main, .content, #app, .content-wrapper { margin: 0 !important; padding: 0 !important; width: 100% !important; }

        .analysis-grid { display: block !important; }

        /* 2. Perkecil ukuran judul saat print */
        .page-header { margin-bottom: 10px !important; }
        .header-title-group h2 { font-size: 18px !important; margin-bottom: 4px !important;}
        .header-title-group p { font-size: 11px !important; margin: 0 !important;}
        
        .patient-card, .chart-card, .form-card {
            box-shadow: none !important;
            border: 1px solid #cbd5e1 !important;
            padding: 10px 14px !important; /* Padding dikurangi */
            margin-bottom: 8px !important; /* Jarak antar kotak dirapatkan */
            page-break-inside: avoid;
        }

        .form-card { position: static !important; margin-top: 0 !important; }

        /* 3. Tinggi grafik dikurangi sedikit lagi */
        .chart-area {
            height: 180px !important; 
            position: relative !important;
            overflow: hidden !important; 
            margin-bottom: 0 !important;
        }
        .chart-area canvas {
            height: 100% !important;
            max-height: 180px !important;
            width: 100% !important;
        }

        .analisis-section-title { margin-bottom: 8px !important; font-size: 12px !important; }

        /* 4. Form input dibuat lebih pipih */
        .form-input { border: 1px solid #e2e8f0 !important; background: transparent !important; padding: 6px !important; font-size: 11px !important; }
        textarea.form-input { resize: none !important; min-height: 50px !important; overflow: hidden !important; }
        .form-group { margin-bottom: 8px !important; }
        .form-label { margin-bottom: 2px !important; font-size: 10px !important; }

        /* 5. Tanda tangan lebih rapat */
        .signature-wrapper {
            display: flex !important;
            justify-content: flex-end;
            margin-top: 10px !important;
            width: 100%;
            page-break-inside: avoid;
        }
        .signature-box { text-align: center; width: 200px; font-size: 11px !important; }
        .signature-space { height: 50px !important; }
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')

@php
    $jenisKelamin = strtolower($pasien->jenis_kelamin_anak ?? $pasien->anak->jenis_kelamin ?? '');
    $avatar = ($jenisKelamin == 'p' || $jenisKelamin == 'perempuan') ? '👧' : '👦';
    $genderText = ($jenisKelamin == 'p' || $jenisKelamin == 'perempuan') ? 'Perempuan' : 'Laki-laki';
    
    $namaAnak = $pasien->nama_anak ?? $pasien->anak->nama ?? 'Nama Anak';
    $namaOrtu = $pasien->nama_ortu ?? 'Wali Pasien';
    
    if(isset($pasien->anak->tanggal_lahir)) {
        $umurBulan = round(\Carbon\Carbon::parse($pasien->anak->tanggal_lahir)->diffInMonths(now()));
    } else {
        $umurBulan = round($pasien->umur_anak ?? 0);
    }

    $statusGiziTerakhir = 'Belum Ada Data';
    $classStatus = 'status-normal';
    if($riwayatPengukuran->count() > 0) {
        $pengukuranTerakhir = $riwayatPengukuran->last();
        $statusGiziTerakhir = str_replace('_', ' ', $pengukuranTerakhir->status_gizi);
        $classStatus = 'status-' . strtolower($pengukuranTerakhir->status_gizi);
    }
@endphp

<div class="page-header">
    <div class="header-title-group">
        <h2>Analisis & Rekam Gizi</h2>
        <p>Evaluasi grafik tumbuh kembang dan berikan rekomendasi gizi.</p>
    </div>
    <a href="{{ route('ahligizi.pasien.index') ?? '#' }}" class="btn-back">◀ Kembali ke Daftar</a>
</div>

<div class="analysis-grid">
    <div>
        <div class="patient-card">
            <div class="p-profile">
                <div class="p-avatar">{{ $avatar }}</div>
                <div>
                    <h3 class="p-name">{{ $namaAnak }} ({{ $umurBulan }} Bln)</h3>
                    <p class="p-detail">{{ $genderText }} • Wali: {{ $namaOrtu }}</p>
                </div>
            </div>
            <div class="p-stats">
                <div class="stat-box">
                    <span class="stat-label">Tanggal Janji</span>
                    <span class="stat-value" style="font-size: 15px;">{{ \Carbon\Carbon::parse($pasien->tanggal_jadwal)->translatedFormat('d M Y') }}</span>
                </div>
                <div class="stat-box">
                    <span class="stat-label">Status Gizi (Akhir)</span>
                    <span class="status-badge {{ $classStatus }}">{{ $statusGiziTerakhir }}</span>
                </div>
            </div>
        </div>

        <div class="chart-card">
            <h3 class="analisis-section-title">
                Grafik Pertumbuhan (Historis)
                <button type="button" class="btn-print" style="border:none; background:none; color:#3b82f6; font-size:13px; font-weight:700; cursor:pointer;" onclick="window.print()">Cetak PDF 📥</button>
            </h3>
            
            <div class="chart-tabs">
                <button class="chart-tab active" data-type="imt">Indeks Massa Tubuh (IMT)</button>
                <button class="chart-tab" data-type="bb">Berat Badan (BB/U)</button>
                <button class="chart-tab" data-type="tb">Tinggi Badan (TB/U)</button>
            </div>

            <div class="chart-area">
                <canvas id="growthChart"></canvas>
            </div>
        </div>
    </div>

    <div class="form-card">
        <h3 class="analisis-section-title" style="margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #f1f5f9;">📝 Catatan Ahli Gizi</h3>
        
        <form action="{{ route('ahligizi.analisis.store', $pasien->id) }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Diagnosis / Hasil Analisis</label>
                <textarea class="form-input" name="assessment" required placeholder="Tuliskan analisis Anda terhadap grafik di samping...">{{ $pasien->assessment }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Rekomendasi Diet / Intervensi</label>
                <textarea class="form-input" name="plan" required placeholder="Berikan saran asupan kalori atau jenis makanan...">{{ $pasien->plan }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Jadwal Kontrol Selanjutnya</label>
                <input type="date" name="tanggal_kontrol" class="form-input">
            </div>

            <button type="submit" class="btn-submit">💾 Simpan Hasil Analisis</button>
        </form>

        <div class="signature-wrapper">
            <div class="signature-box">
                <p>Jember, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p>Ahli Gizi Pemeriksa,</p>
                <div class="signature-space"></div>
                <p><strong>({{ $pasien->ahli->name ?? 'Tenaga Medis' }})</strong></p>
                <p style="font-size: 11px;">NIP/STR: {{ $pasien->ahli->nip ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const riwayatData = @json($riwayatPengukuran);
    
    if(riwayatData.length === 0) {
        document.querySelector('.chart-area').innerHTML = '<div style="height:100%; display:flex; align-items:center; justify-content:center; color:#94a3b8;">Belum ada riwayat pengukuran.</div>';
        return;
    }

    const labelsBulan = [];
    const dataBB = [];
    const dataTB = [];
    const dataIMT = [];

    riwayatData.forEach(item => {
        labelsBulan.push(item.umur_bulan + ' Bln');
        dataBB.push(item.berat_badan);
        dataTB.push(item.tinggi_badan);
        let tbMeter = item.tinggi_badan / 100;
        dataIMT.push((item.berat_badan / (tbMeter * tbMeter)).toFixed(1));
    });

    const ctx = document.getElementById('growthChart').getContext('2d');
    let growthChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labelsBulan,
            datasets: [{
                label: 'IMT Pasien',
                data: dataIMT,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    const tabs = document.querySelectorAll('.chart-tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            const type = this.getAttribute('data-type');
            
            if (type === 'imt') {
                growthChart.data.datasets[0].label = 'IMT Pasien';
                growthChart.data.datasets[0].data = dataIMT;
                growthChart.data.datasets[0].borderColor = '#3b82f6';
            } else if (type === 'bb') {
                growthChart.data.datasets[0].label = 'Berat Badan (Kg)';
                growthChart.data.datasets[0].data = dataBB;
                growthChart.data.datasets[0].borderColor = '#10b981';
            } else if (type === 'tb') {
                growthChart.data.datasets[0].label = 'Tinggi Badan (Cm)';
                growthChart.data.datasets[0].data = dataTB;
                growthChart.data.datasets[0].borderColor = '#8b5cf6';
            }
            growthChart.update();
        });
    });
});
</script>
@endpush