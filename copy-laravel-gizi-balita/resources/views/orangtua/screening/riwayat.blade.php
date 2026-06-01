@extends('layouts.app')

@section('title', 'Riwayat Skrining Stunting - GiziAnak')

@section('custom_css')
<style>
    .page-header { margin-bottom: 24px; }
    .page-header h2 { font-size: 26px; font-weight: 900; color: #0f1c2e; margin: 0 0 6px 0; font-family: Georgia, serif; }
    .page-header p { font-size: 14px; color: #64748b; margin: 0; }

    .card { background: white; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 16px; }
    .card-header { padding: 20px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
    .card-header h3 { font-size: 16px; font-weight: 800; color: #0f1c2e; margin: 0; }
    .card-header p { font-size: 13px; color: #64748b; margin: 4px 0 0 0; }
    .card-body { padding: 20px 24px; }

    /* FILTER BAR */
    .filter-bar { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; margin-bottom: 20px; }
    .filter-select { padding: 9px 14px; border: 1.5px solid #e2e8f0; border-radius: 9px; font-size: 13px; color: #334155; background: #f8fafc; outline: none; font-family: inherit; cursor: pointer; }
    .filter-select:focus { border-color: #3b82f6; }
    .filter-count { font-size: 13px; color: #94a3b8; margin-left: auto; }

    /* RESULT CARD */
    .result-card { border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; margin-bottom: 12px; background: #fafafa; transition: all 0.2s; }
    .result-card:hover { border-color: #bfdbfe; background: white; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .result-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px; }
    .result-anak { font-size: 15px; font-weight: 800; color: #1e293b; margin: 0 0 3px; }
    .result-date { font-size: 11px; color: #94a3b8; }

    .stats-row { display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 12px; }
    .stat-chip span:first-child { font-size: 10px; color: #94a3b8; font-weight: 600; text-transform: uppercase; display: block; }
    .stat-chip span:last-child { font-size: 13px; font-weight: 700; color: #334155; }

    /* BADGE */
    .badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 11px; border-radius: 20px; font-size: 11px; font-weight: 700; }
    .badge-normal   { background: #dcfce7; color: #15803d; }
    .badge-risiko   { background: #fef9c3; color: #a16207; }
    .badge-stunting { background: #ffedd5; color: #c2410c; }
    .badge-severe   { background: #fee2e2; color: #b91c1c; }

    /* Z-SCORE BAR */
    .zscore-wrap { margin-bottom: 12px; }
    .zscore-labels { display: flex; justify-content: space-between; font-size: 10px; color: #94a3b8; font-weight: 600; margin-bottom: 4px; }
    .zscore-bar { height: 7px; background: linear-gradient(to right, #ef4444, #f97316, #fbbf24, #10b981); border-radius: 99px; position: relative; }
    .zscore-dot { width: 13px; height: 13px; border-radius: 50%; border: 2px solid white; box-shadow: 0 1px 4px rgba(0,0,0,0.25); position: absolute; top: -3px; transform: translateX(-50%); }

    .rekomendasi { font-size: 12px; color: #475569; line-height: 1.6; padding: 11px 13px; background: #f8fafc; border-left: 3px solid #3b82f6; border-radius: 0 8px 8px 0; }
    .rekomendasi.merah  { border-left-color: #ef4444; }
    .rekomendasi.kuning { border-left-color: #f59e0b; }
    .rekomendasi.hijau  { border-left-color: #10b981; }

    .btn-pdf { padding: 7px 14px; background: #1e3a8a; color: white; border-radius: 8px; font-size: 12px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: 0.2s; }
    .btn-pdf:hover { background: #1e40af; }

    /* STATS SUMMARY */
    .summary-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 24px; }
    .summary-box { background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; text-align: center; }
    .summary-box .val { font-size: 28px; font-weight: 900; font-family: Georgia, serif; line-height: 1; margin-bottom: 6px; }
    .summary-box .lbl { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.4px; }

    .empty-state { text-align: center; padding: 60px 20px; }
    .empty-state .icon { font-size: 40px; margin-bottom: 12px; opacity: 0.35; }
    .empty-state p { color: #94a3b8; font-size: 14px; margin: 0 0 16px; }
    .btn-primary { padding: 11px 22px; background: #1e3a8a; color: white; border-radius: 10px; font-size: 14px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }

    @media (max-width: 768px) { .summary-grid { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 480px) { .summary-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')

<div class="page-header">
    <h2>📋 Riwayat Skrining Stunting</h2>
    <p>Rekap seluruh hasil skrining pertumbuhan anak Anda</p>
</div>

@if($riwayat->count() > 0)

    {{-- SUMMARY --}}
    <div class="summary-grid">
        <div class="summary-box">
            <div class="val" style="color: #1e3a8a;">{{ $riwayat->count() }}</div>
            <div class="lbl">Total Skrining</div>
        </div>
        <div class="summary-box">
            <div class="val" style="color: #15803d;">{{ $riwayat->where('status','Normal')->count() }}</div>
            <div class="lbl">Normal</div>
        </div>
        <div class="summary-box">
            <div class="val" style="color: #c2410c;">{{ $riwayat->whereIn('status',['Stunting','Severely Stunting'])->count() }}</div>
            <div class="lbl">Stunting</div>
        </div>
        <div class="summary-box">
            <div class="val" style="color: #a16207;">{{ $riwayat->where('status','Berisiko Stunting')->count() }}</div>
            <div class="lbl">Berisiko</div>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="filter-bar">
        <select class="filter-select" id="filterAnak" onchange="filterData()">
            <option value="">Semua Anak</option>
            @foreach($riwayat->pluck('anak.nama')->unique()->filter() as $nama)
                <option value="{{ $nama }}">{{ ucfirst($nama) }}</option>
            @endforeach
        </select>
        <select class="filter-select" id="filterStatus" onchange="filterData()">
            <option value="">Semua Status</option>
            <option value="Normal">Normal</option>
            <option value="Berisiko Stunting">Berisiko Stunting</option>
            <option value="Stunting">Stunting</option>
            <option value="Severely Stunting">Severely Stunting</option>
        </select>
        <span class="filter-count" id="filterCount">{{ $riwayat->count() }} data</span>
    </div>

    {{-- LIST RIWAYAT --}}
    <div id="riwayatList">
        @foreach($riwayat as $item)
            @php
                $badgeClass = match($item->status) {
                    'Normal'            => 'badge-normal',
                    'Berisiko Stunting' => 'badge-risiko',
                    'Stunting'          => 'badge-stunting',
                    'Severely Stunting' => 'badge-severe',
                    default             => 'badge-risiko',
                };
                $rekColor = match($item->status) {
                    'Normal'            => 'hijau',
                    'Berisiko Stunting' => 'kuning',
                    'Stunting'          => 'kuning',
                    'Severely Stunting' => 'merah',
                    default             => '',
                };
                $dotColor = match($item->status) {
                    'Normal'            => '#10b981',
                    'Berisiko Stunting' => '#f59e0b',
                    'Stunting'          => '#f97316',
                    'Severely Stunting' => '#ef4444',
                    default             => '#3b82f6',
                };
                $zMin = -4; $zMax = 2;
                $zPct = max(2, min(98, (($item->zscore - $zMin) / ($zMax - $zMin)) * 100));
            @endphp

            <div class="result-card"
                data-anak="{{ strtolower($item->anak->nama ?? '') }}"
                data-status="{{ $item->status }}">

                <div class="result-header">
                    <div>
                        <p class="result-anak">{{ ucfirst($item->anak->nama ?? '-') }}</p>
                        <p class="result-date">
                            📅 {{ \Carbon\Carbon::parse($item->tanggal_pengukuran ?? $item->created_at)->translatedFormat('d F Y') }}
                            &bull; Usia {{ $item->usia_bulan }} bulan
                        </p>
                    </div>
                    <div style="display:flex; flex-direction:column; align-items:flex-end; gap:6px;">
                        <span class="badge {{ $badgeClass }}">{{ $item->status }}</span>
                        <a href="{{ route('orangtua.screening.pdf', $item->id) }}" class="btn-pdf" target="_blank">⬇ PDF</a>
                    </div>
                </div>

                <div class="stats-row">
                    <div class="stat-chip">
                        <span>Berat Badan</span>
                        <span>{{ $item->berat_badan }} kg</span>
                    </div>
                    <div class="stat-chip">
                        <span>Tinggi/PB</span>
                        <span>{{ $item->tinggi_badan }} cm</span>
                    </div>
                    @if($item->lingkar_kepala)
                    <div class="stat-chip">
                        <span>Lingkar Kepala</span>
                        <span>{{ $item->lingkar_kepala }} cm</span>
                    </div>
                    @endif
                    @if($item->lila)
                    <div class="stat-chip">
                        <span>LILA</span>
                        <span>{{ $item->lila }} cm</span>
                    </div>
                    @endif
                    <div class="stat-chip">
                        <span>Z-Score TB/U</span>
                        <span style="color:{{ $dotColor }}; font-weight:800;">{{ $item->zscore > 0 ? '+' : '' }}{{ $item->zscore }} SD</span>
                    </div>
                </div>

                <div class="zscore-wrap">
                    <div class="zscore-labels">
                        <span>-4 SD</span><span>-3 SD</span><span>-2 SD</span><span>-1 SD</span><span>0</span><span>+2 SD</span>
                    </div>
                    <div class="zscore-bar">
                        <div class="zscore-dot" style="left:{{ $zPct }}%; background:{{ $dotColor }};"></div>
                    </div>
                </div>

                @if($item->rekomendasi)
                    <div class="rekomendasi {{ $rekColor }}">{{ $item->rekomendasi }}</div>
                @endif

                @if($item->jadwal_kontrol_berikutnya)
                    <div style="margin-top:10px; font-size:11px; color:#64748b;">
                        📅 Kontrol berikutnya: <strong>{{ \Carbon\Carbon::parse($item->jadwal_kontrol_berikutnya)->translatedFormat('d F Y') }}</strong>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

@else
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <div class="icon">📋</div>
                <p>Belum ada riwayat skrining stunting.</p>
                <a href="{{ route('orangtua.screening.index') }}" class="btn-primary">🧒 Mulai Skrining Sekarang</a>
            </div>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script>
function filterData() {
    const filterAnak   = document.getElementById('filterAnak').value.toLowerCase();
    const filterStatus = document.getElementById('filterStatus').value;
    const cards        = document.querySelectorAll('.result-card');
    let count = 0;

    cards.forEach(card => {
        const anak   = card.dataset.anak;
        const status = card.dataset.status;
        const matchAnak   = !filterAnak   || anak.includes(filterAnak);
        const matchStatus = !filterStatus || status === filterStatus;

        if (matchAnak && matchStatus) {
            card.style.display = 'block';
            count++;
        } else {
            card.style.display = 'none';
        }
    });

    document.getElementById('filterCount').textContent = count + ' data';
}
</script>
@endpush