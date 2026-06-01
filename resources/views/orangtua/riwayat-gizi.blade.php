@extends('layouts.app')

@section('title', 'Riwayat Cek Gizi - GiziAnak')

@section('custom_css')
<style>
    /* --- HEADER --- */
    .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .page-header h2 { margin: 0 0 8px 0; font-size: 28px; font-weight: 900; color: #0f1c2e; font-family: Georgia, serif; }
    .page-header p { margin: 0; color: #64748b; font-size: 15px; }
    
    .header-actions { display: flex; gap: 12px; }
    .btn { padding: 10px 18px; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s; border: none; text-decoration: none; }
    .btn-danger-outline { background-color: #fef2f2; color: #ef4444; border: 1px solid #fecaca; }
    .btn-danger-outline:hover { background-color: #fee2e2; }
    .btn-primary { background-color: #3b82f6; color: white; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3); }
    .btn-primary:hover { background-color: #2563eb; transform: translateY(-1px); }

    /* --- SUMMARY CARDS --- */
    .summary-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px; }
    .summary-card { background: white; padding: 20px; border-radius: 16px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); border: 1px solid #f1f5f9; display: flex; flex-direction: column; justify-content: space-between; position: relative; }
    .card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; }
    .icon-wrapper { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
    .icon-blue { background-color: #eff6ff; }
    .icon-green { background-color: #f0fdf4; }
    .card-value { font-size: 32px; font-weight: 900; color: #1e3a8a; font-family: Georgia, serif; line-height: 1; margin: 0 0 4px 0; }
    .card-label { font-size: 13px; color: #94a3b8; font-weight: 600; margin: 0; }

    /* --- CHART SECTION --- */
    .chart-container { background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); border: 1px solid #f1f5f9; margin-bottom: 24px; }
    .chart-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; }
    .chart-title { font-size: 15px; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 8px; margin: 0; }
    .child-select { padding: 9px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; background: #f8fafc; color: #1e293b; font-size: 13px; font-weight: 700; outline: none; cursor: pointer; transition: all 0.2s; }
    .child-select:hover, .child-select:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }

    /* Legend */
    .chart-legend { display: flex; flex-wrap: wrap; gap: 14px; margin-bottom: 16px; }
    .legend-item { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #64748b; font-weight: 600; }
    .legend-dot { width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0; }
    .legend-line { width: 24px; height: 2.5px; border-radius: 2px; background: #3b82f6; flex-shrink: 0; }

    /* Stat cards di bawah grafik */
    .chart-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 20px; }
    .chart-stat-card { background: #f8fafc; border-radius: 10px; padding: 12px 16px; border: 1px solid #f1f5f9; }
    .chart-stat-val { font-size: 22px; font-weight: 900; font-family: Georgia, serif; line-height: 1; }
    .chart-stat-lbl { font-size: 12px; color: #94a3b8; font-weight: 600; margin-top: 4px; }

    /* Canvas wrapper */
    .canvas-wrapper { position: relative; width: 100%; height: 300px; }
    #chartOverlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.92); display: flex; flex-direction: column; align-items: center; justify-content: center; font-weight: 700; color: #64748b; border-radius: 8px; gap: 8px; font-size: 14px; }

    /* --- FILTER SECTION --- */
    .filter-section { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; flex-wrap: wrap; }
    .search-wrapper { flex: 0 0 280px; position: relative; }
    .search-input { width: 100%; padding: 10px 16px 10px 40px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; color: #334155; outline: none; transition: 0.2s; box-sizing: border-box; }
    .search-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
    .search-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 14px; color: #94a3b8; }
    .filter-group { display: flex; gap: 8px; flex-wrap: wrap; flex: 1; }
    .btn-filter { padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; border: 1px solid #e2e8f0; background: white; color: #64748b; transition: all 0.2s; }
    .btn-filter.active { background: #3b82f6; color: white; border-color: #3b82f6; }
    .btn-filter:hover:not(.active) { background: #f8fafc; }
    .btn-export { background: white; border: 1px solid #e2e8f0; color: #475569; margin-left: auto; }
    .btn-export:hover { background: #f8fafc; }

    /* --- TABLE SECTION --- */
    .table-container { background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); border: 1px solid #f1f5f9; overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; text-align: left; }
    th { padding: 16px 24px; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #f1f5f9; background: #f8fafc; }
    td { padding: 16px 24px; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #1e293b; font-weight: 600; vertical-align: middle; }
    tbody tr:hover { background-color: #f8fafc; }
    .text-imt { font-family: Georgia, serif; font-size: 16px; font-weight: 900; color: #1e3a8a; }

    /* Badges */
    .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; border: 1px solid transparent; display: inline-block; text-transform: capitalize; }
    .badge-kurang-gizi { background: #fef3c7; color: #b45309; border-color: #fde68a; }
    .badge-normal { background: #dcfce7; color: #16a34a; border-color: #bbf7d0; }
    .badge-obesitas { background: #fee2e2; color: #dc2626; border-color: #fecaca; }
    .badge-stunting { background: #fce7f3; color: #be185d; border-color: #fbcfe8; }

    /* Action Buttons */
    .action-group { display: flex; gap: 8px; }
    .btn-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; border: 1px solid #e2e8f0; background: #f8fafc; color: #64748b; cursor: pointer; transition: all 0.2s; padding: 0; outline: none; }
    .btn-icon:hover { background: #e2e8f0; }
    .btn-icon.delete:hover { background: #fee2e2; color: #ef4444; border-color: #fecaca; }

    /* --- POPUP MODAL --- */
    .popup-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15,28,46,0.6); z-index: 9999; display: flex; align-items: center; justify-content: center; animation: fadeInOverlay 0.3s ease; }
    .popup-content { background: white; padding: 32px; border-radius: 20px; max-width: 400px; width: 90%; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); animation: popUp 0.4s cubic-bezier(0.175,0.885,0.32,1.275); position: relative; }
    .popup-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; }
    .popup-title { font-size: 18px; font-weight: 800; color: #1e293b; margin: 0; }
    .close-btn { background: none; border: none; font-size: 20px; color: #94a3b8; cursor: pointer; transition: 0.2s; }
    .close-btn:hover { color: #ef4444; }
    .detail-row { display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px dashed #f1f5f9; }
    .detail-label { font-size: 13px; color: #64748b; font-weight: 700; }
    .detail-val { font-size: 14px; color: #1e293b; font-weight: 800; }
    .popup-icon-success { width: 64px; height: 64px; background: #dcfce7; color: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 16px; }

    @keyframes fadeInOverlay { from { opacity: 0; } to { opacity: 1; } }
    @keyframes popUp { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }

    @media (max-width: 1024px) { .summary-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) {
        .summary-grid { grid-template-columns: 1fr; }
        .page-header { flex-direction: column; }
        .header-actions { width: 100%; }
        .header-actions .btn { flex: 1; justify-content: center; }
        .filter-section { flex-direction: column; align-items: stretch; }
        .search-wrapper { flex: 1; }
        .btn-export { margin-left: 0; }
        .chart-stats { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h2>Riwayat Cek Gizi</h2>
        <p>Pantau perkembangan gizi anak dari waktu ke waktu</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('orangtua.cekgizi.index') }}" class="btn btn-primary">📊 Cek Gizi Baru</a>
    </div>
</div>

{{-- SUMMARY CARDS --}}
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-top"><div class="icon-wrapper icon-blue">📋</div></div>
        <div>
            <h3 class="card-value">{{ $totalPemeriksaan }}</h3>
            <p class="card-label">Total Pemeriksaan</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-top"><div class="icon-wrapper icon-blue">👶</div></div>
        <div>
            <h3 class="card-value">{{ $anakTercatat }}</h3>
            <p class="card-label">Anak Tercatat</p>
        </div>
    </div>
</div>

{{-- CHART SECTION --}}
<div class="chart-container">
    <div class="chart-header">
        <h3 class="chart-title">📈 Grafik IMT per Waktu</h3>
        <select class="child-select" id="chartChildSelect">
            <option value="" disabled selected>-- Pilih Anak --</option>
            @foreach($anaks as $anak)
                @if(isset($chartData[$anak->id]))
                    <option value="{{ $anak->id }}">{{ $anak->nama }}</option>
                @endif
            @endforeach
        </select>
    </div>

    {{-- Legend --}}
    <div class="chart-legend">
        <div class="legend-item"><div class="legend-dot" style="background:#ef4444;"></div> Kurang gizi (&lt;18.5)</div>
        <div class="legend-item"><div class="legend-dot" style="background:#22c55e;"></div> Normal (18.5–24.9)</div>
        <div class="legend-item"><div class="legend-dot" style="background:#f59e0b;"></div> Overweight (25–29.9)</div>
        <div class="legend-item"><div class="legend-dot" style="background:#f97316;"></div> Obesitas (≥30)</div>
        <div class="legend-item" style="margin-left:auto;"><div class="legend-line"></div> IMT anak</div>
    </div>

    {{-- Canvas --}}
    <div class="canvas-wrapper">
        <canvas id="imtChart"></canvas>
        <div id="chartOverlay">
            <span style="font-size:28px;">👆</span>
            Silakan pilih nama anak di menu dropdown atas
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="chart-stats">
        <div class="chart-stat-card">
            <div class="chart-stat-val" id="statMin">–</div>
            <div class="chart-stat-lbl">IMT Terendah</div>
        </div>
        <div class="chart-stat-card">
            <div class="chart-stat-val" id="statAvg">–</div>
            <div class="chart-stat-lbl">Rata-rata IMT</div>
        </div>
        <div class="chart-stat-card">
            <div class="chart-stat-val" id="statMax">–</div>
            <div class="chart-stat-lbl">IMT Tertinggi</div>
        </div>
    </div>
</div>

{{-- FILTER --}}
<div class="filter-section">
    <div class="search-wrapper">
        <span class="search-icon">🔍</span>
        <input type="text" id="searchInput" class="search-input" placeholder="Cari nama anak...">
    </div>
    <div class="filter-group" id="filterButtons">
        <button class="btn-filter active" data-filter="semua">Semua</button>
        <button class="btn-filter" data-filter="normal">Normal</button>
        <button class="btn-filter" data-filter="kurang_gizi">Kurang Gizi</button>
        <button class="btn-filter" data-filter="obesitas">Obesitas</button>
    </div>
</div>

{{-- TABLE --}}
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>TANGGAL</th>
                <th>NAMA ANAK</th>
                <th>UMUR</th>
                <th>BERAT</th>
                <th>TINGGI</th>
                <th>IMT</th>
                <th>STATUS</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            {{-- PERUBAHAN UTAMA: Menambahkan sortByDesc('created_at') di sini --}}
            @forelse($pengukurans->sortByDesc('created_at') as $p)
                @php
                    $imt = $p->berat_badan / pow($p->tinggi_badan / 100, 2);
                    $badgeClass = 'badge-normal';
                    if($p->status_gizi == 'kurang_gizi') $badgeClass = 'badge-kurang-gizi';
                    if($p->status_gizi == 'obesitas')    $badgeClass = 'badge-obesitas';
                    if($p->status_gizi == 'stunting')    $badgeClass = 'badge-stunting';
                    $tanggal    = \Carbon\Carbon::parse($p->tanggal_ukur)->translatedFormat('d M Y');
                    $statusName = str_replace('_', ' ', strtoupper($p->status_gizi));
                @endphp
                <tr class="data-row" data-nama="{{ strtolower($p->anak->nama) }}" data-status="{{ $p->status_gizi }}">
                    <td style="color:#64748b;">{{ $tanggal }}</td>
                    <td>{{ $p->anak->nama }}</td>
                    <td>{{ $p->umur_bulan }} bln</td>
                    <td>{{ $p->berat_badan }} kg</td>
                    <td>{{ $p->tinggi_badan }} cm</td>
                    <td class="text-imt">{{ number_format($imt, 1) }}</td>
                    <td><span class="status-badge {{ $badgeClass }}">{{ str_replace('_', ' ', $p->status_gizi) }}</span></td>
                    <td>
                        <div class="action-group">
                            <button class="btn-icon" title="Lihat Detail"
                                onclick="openDetailModal('{{ $tanggal }}','{{ $p->anak->nama }}','{{ $p->umur_bulan }}','{{ $p->berat_badan }}','{{ $p->tinggi_badan }}','{{ number_format($imt,1) }}','{{ $statusName }}','{{ $badgeClass }}')">
                                👁️
                            </button>
                            <form action="{{ route('orangtua.cekgizi.destroy', $p->id) }}" method="POST" style="display:inline;"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini secara permanen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon delete" title="Hapus Data">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr id="emptyRow">
                    <td colspan="8" style="text-align:center; padding:48px; color:#64748b;">
                        <div style="font-size:32px; margin-bottom:12px;">📭</div>
                        Belum ada riwayat pengukuran gizi.
                    </td>
                </tr>
            @endforelse
            <tr id="noSearchResult" style="display:none;">
                <td colspan="8" style="text-align:center; padding:48px; color:#64748b;">
                    <div style="font-size:32px; margin-bottom:12px;">🔍</div>
                    Tidak ada data yang cocok dengan pencarian/filter.
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{-- DETAIL MODAL --}}
<div class="popup-overlay" id="detailModal" style="display:none;">
    <div class="popup-content">
        <div class="popup-header">
            <h3 class="popup-title">Detail Pengukuran</h3>
            <button class="close-btn" onclick="closeDetailModal()">✖</button>
        </div>
        <div class="detail-row">
            <span class="detail-label">Tanggal</span>
            <span class="detail-val" id="mdl-tgl">...</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Nama Anak</span>
            <span class="detail-val" id="mdl-nama">...</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Umur</span>
            <span class="detail-val"><span id="mdl-umur">...</span> Bulan</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Berat Badan</span>
            <span class="detail-val"><span id="mdl-berat">...</span> Kg</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Tinggi Badan</span>
            <span class="detail-val"><span id="mdl-tinggi">...</span> Cm</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Skor IMT</span>
            <span class="detail-val" id="mdl-imt" style="font-family:Georgia,serif; color:#1e3a8a;">...</span>
        </div>
        <div class="detail-row" style="border:none; margin-bottom:0;">
            <span class="detail-label" style="align-self:center;">Status Gizi</span>
            <span class="status-badge" id="mdl-status">...</span>
        </div>
    </div>
</div>

{{-- SUCCESS POPUP --}}
@if(session('success'))
<div class="popup-overlay" id="successPopup">
    <div class="popup-content" style="text-align:center;">
        <div class="popup-icon-success">✅</div>
        <h3 style="font-size:20px; font-weight:900; color:#1e293b; margin:0 0 8px 0;">Berhasil!</h3>
        <p style="font-size:14px; color:#64748b; margin:0 0 24px 0; line-height:1.5;">{{ session('success') }}</p>
        <button class="btn btn-primary" style="width:100%; justify-content:center;" onclick="closeSuccessPopup()">Oke, Mengerti</button>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ==========================================
// MODAL FUNCTIONS
// ==========================================
function openDetailModal(tgl, nama, umur, berat, tinggi, imt, statusText, statusClass) {
    document.getElementById('mdl-tgl').innerText   = tgl;
    document.getElementById('mdl-nama').innerText  = nama;
    document.getElementById('mdl-umur').innerText  = umur;
    document.getElementById('mdl-berat').innerText = berat;
    document.getElementById('mdl-tinggi').innerText= tinggi;
    document.getElementById('mdl-imt').innerText   = imt;
    const badge = document.getElementById('mdl-status');
    badge.innerText   = statusText;
    badge.className   = 'status-badge ' + statusClass;
    document.getElementById('detailModal').style.display = 'flex';
}
function closeDetailModal() {
    document.getElementById('detailModal').style.display = 'none';
}
function closeSuccessPopup() {
    const popup = document.getElementById('successPopup');
    popup.style.opacity = '0';
    setTimeout(() => popup.style.display = 'none', 300);
}

document.addEventListener('DOMContentLoaded', function () {

    // ==========================================
    // FILTER & SEARCH
    // ==========================================
    const searchInput    = document.getElementById('searchInput');
    const filterButtons  = document.querySelectorAll('.btn-filter');
    const dataRows       = document.querySelectorAll('.data-row');
    const noSearchResult = document.getElementById('noSearchResult');
    let currentFilter = 'semua';
    let searchQuery   = '';

    function filterTable() {
        let visible = 0;
        dataRows.forEach(row => {
            const nama   = row.getAttribute('data-nama');
            const status = row.getAttribute('data-status');
            const ok = nama.includes(searchQuery) &&
                       (currentFilter === 'semua' || status === currentFilter);
            row.style.display = ok ? '' : 'none';
            if (ok) visible++;
        });
        if (noSearchResult)
            noSearchResult.style.display = (visible === 0 && dataRows.length > 0) ? '' : 'none';
    }

    searchInput?.addEventListener('input', e => {
        searchQuery = e.target.value.toLowerCase();
        filterTable();
    });

    filterButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.getAttribute('data-filter');
            filterTable();
        });
    });

    // ==========================================
    // HELPER: warna berdasarkan nilai IMT
    // ==========================================
    function getStatusColor(v) {
        if (v < 18.5) return '#ef4444'; // kurang gizi – merah
        if (v < 25)   return '#22c55e'; // normal      – hijau
        if (v < 30)   return '#f59e0b'; // overweight  – kuning
        return '#f97316';               // obesitas    – oranye
    }
    function getStatusBg(v) {
        if (v < 18.5) return 'rgba(239,68,68,0.10)';
        if (v < 25)   return 'rgba(34,197,94,0.10)';
        if (v < 30)   return 'rgba(245,158,11,0.10)';
        return 'rgba(249,115,22,0.10)';
    }

    // ==========================================
    // STAT CARDS (min / avg / max)
    // ==========================================
    function updateStats(data) {
        const min = Math.min(...data);
        const max = Math.max(...data);
        const avg = data.reduce((a, b) => a + b, 0) / data.length;
        const el  = (id, val) => {
            const el = document.getElementById(id);
            el.textContent = val.toFixed(1);
            el.style.color = getStatusColor(val);
        };
        el('statMin', min);
        el('statAvg', avg);
        el('statMax', max);
    }

    // ==========================================
    // PLUGIN: zona status gizi (garis batas)
    // ==========================================
    const zoneBandPlugin = {
        id: 'zoneBand',
        beforeDraw(chart) {
            const { ctx, chartArea, scales } = chart;
            if (!chartArea) return;
            ctx.save();

            const y185 = scales.y.getPixelForValue(18.5);
            const y249 = scales.y.getPixelForValue(24.9);
            ctx.fillStyle = 'rgba(34,197,94,0.06)';
            ctx.fillRect(chartArea.left, y249, chartArea.width, y185 - y249);

            const lines = [
                { val: 18.5, color: 'rgba(239,68,68,0.55)',  label: '18.5' },
                { val: 25,   color: 'rgba(245,158,11,0.55)', label: '25'   },
                { val: 30,   color: 'rgba(249,115,22,0.55)', label: '30'   },
            ];
            lines.forEach(({ val, color, label }) => {
                const y = scales.y.getPixelForValue(val);
                ctx.setLineDash([6, 4]);
                ctx.lineWidth    = 1.2;
                ctx.strokeStyle  = color;
                ctx.beginPath();
                ctx.moveTo(chartArea.left, y);
                ctx.lineTo(chartArea.right, y);
                ctx.stroke();

                ctx.setLineDash([]);
                ctx.font      = '10px sans-serif';
                ctx.fillStyle = color;
                ctx.textAlign = 'right';
                ctx.fillText('IMT ' + label, chartArea.right - 4, y - 4);
            });

            ctx.restore();
        }
    };

    // ==========================================
    // INISIALISASI CHART
    // ==========================================
    const rawChartData = @json($chartData);
    const ctx          = document.getElementById('imtChart').getContext('2d');

    const myChart = new Chart(ctx, {
        type: 'line',
        plugins: [zoneBandPlugin],
        data: {
            labels: [],
            datasets: [{
                label: 'Nilai IMT',
                data: [],
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.07)',
                borderWidth: 2.5,
                pointBackgroundColor: [], 
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2.5,
                pointRadius: 7,
                pointHoverRadius: 10,
                pointHoverBorderWidth: 3,
                tension: 0.4,
                fill: true,
                segment: {
                    borderColor: ctx2 => getStatusColor(ctx2.p1.parsed.y),
                    backgroundColor: ctx2 => getStatusBg(ctx2.p1.parsed.y),
                }
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15,28,46,0.92)',
                    padding: 14,
                    cornerRadius: 10,
                    titleFont: { size: 12 },
                    bodyFont:  { size: 15, weight: 'bold' },
                    displayColors: false,
                    callbacks: {
                        title: items => items[0].label,
                        label: item => {
                            const v = item.parsed.y;
                            const s = v < 18.5 ? 'Kurang Gizi'
                                    : v < 25   ? 'Normal'
                                    : v < 30   ? 'Overweight'
                                    : 'Obesitas';
                            return ['IMT : ' + v.toFixed(1), 'Status : ' + s];
                        }
                    }
                }
            },
            scales: {
                y: {
                    min: 12, max: 36,
                    ticks: { stepSize: 5, color: '#94a3b8', font: { size: 11 } },
                    grid:  { color: 'rgba(148,163,184,0.12)', borderDash: [4, 4] },
                    border:{ display: false }
                },
                x: {
                    ticks: { color: '#94a3b8', font: { size: 11, weight: 'bold' } },
                    grid:  { display: false },
                    border:{ display: false }
                }
            },
            animation: { duration: 700, easing: 'easeInOutQuart' }
        }
    });

    // ==========================================
    // LOAD DATA KE CHART
    // ==========================================
    function loadChildChart(childId) {
        const d = rawChartData[childId];
        if (!d) return;

        myChart.data.labels                            = d.labels;
        myChart.data.datasets[0].data                  = d.data;
        myChart.data.datasets[0].pointBackgroundColor  = d.data.map(getStatusColor);
        myChart.update();
        updateStats(d.data);
    }

    const selectChild = document.getElementById('chartChildSelect');
    const overlay     = document.getElementById('chartOverlay');

    selectChild?.addEventListener('change', function () {
        overlay.style.display = 'none';
        loadChildChart(this.value);
    });

    if (selectChild && selectChild.options.length > 1) {
        selectChild.selectedIndex = 1;
        overlay.style.display     = 'none';
        loadChildChart(selectChild.value);
    }
});
</script>
@endpush