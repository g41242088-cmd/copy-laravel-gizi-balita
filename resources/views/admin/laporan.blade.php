@extends('layouts.app')

@section('title', 'Laporan Status Gizi Bulanan - GiziAnak')

@section('custom_css')
<style>
    /* --- CSS SAMA SEPERTI SEBELUMNYA --- */
    .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .header-title-group h2 { margin: 0 0 8px 0; font-size: 28px; font-weight: 900; color: #0f1c2e; font-family: Georgia, serif; }
    .header-title-group p { margin: 0; color: #64748b; font-size: 15px; }
    .btn-generate { background: #3b82f6; color: white; padding: 12px 20px; border-radius: 12px; font-size: 14px; font-weight: 700; cursor: pointer; border: none; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2); text-decoration: none;}
    .btn-generate:hover { background: #2563eb; transform: translateY(-2px); }
    .filter-panel { background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; padding: 24px; margin-bottom: 24px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; align-items: end; }
    .form-group { display: flex; flex-direction: column; gap: 8px; }
    .form-label { font-size: 12px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
    .form-input { padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 14px; outline: none; background: #f8fafc; color: #1e293b; font-family: inherit; transition: border-color 0.2s; }
    .form-input:focus { border-color: #3b82f6; background: white; }
    select.form-input { cursor: pointer; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%2364748b" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/></svg>'); background-repeat: no-repeat; background-position: right 12px center; }
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px; }
    .stat-box { background: white; border-radius: 16px; padding: 20px; border: 1px solid #f1f5f9; border-left: 4px solid #3b82f6; display: flex; flex-direction: column; gap: 8px; }
    .stat-box.green { border-left-color: #10b981; }
    .stat-box.red { border-left-color: #ef4444; }
    .stat-title { font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; }
    .stat-value { font-size: 32px; font-weight: 900; color: #1e293b; line-height: 1; display: flex; align-items: baseline; gap: 8px; }
    .stat-value span { font-size: 14px; font-weight: 600; color: #64748b; }
    .table-container { background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; text-align: left; min-width: 900px; }
    th { padding: 16px 24px; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #f1f5f9; background-color: #f8fafc; }
    td { padding: 16px 24px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover { background-color: #f8fafc; }
    .period-title { font-size: 15px; font-weight: 800; color: #1e293b; margin: 0; }
    .badge-count { padding: 6px 12px; border-radius: 8px; font-size: 13px; font-weight: 800; display: inline-flex; align-items: center; gap: 6px; }
    .bc-total { background: #eff6ff; color: #2563eb; }
    .bc-normal { background: #f0fdf4; color: #16a34a; }
    .bc-stunting { background: #fef2f2; color: #dc2626; }
    .btn-download { background: white; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; color: #334155; transition: all 0.2s; padding: 8px 16px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
    .btn-download:hover { background: #f8fafc; border-color: #cbd5e1; }
    .btn-download.excel:hover { color: #16a34a; border-color: #bbf7d0; background: #f0fdf4; }
    .btn-download.pdf:hover { color: #dc2626; border-color: #fecaca; background: #fef2f2; }
    @media (max-width: 992px) { .stats-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')

<div class="page-header">
    <div class="header-title-group">
        <h2>Laporan Status Gizi Bulanan</h2>
        <p>Rekapitulasi data seluruh anak berdasarkan pengelompokan status gizi Normal dan Stunting.</p>
    </div>
</div>

<form action="{{ route('admin.laporan.index') }}" method="GET" class="filter-panel">
    
    <div class="form-group">
        <label class="form-label">Pilih Bulan</label>
        <select class="form-input" name="bulan">
            @for($i = 1; $i <= 12; $i++)
                @php $valBulan = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                <option value="{{ $valBulan }}" {{ $filterBulan == $valBulan ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                </option>
            @endfor
        </select>
    </div>
    
    <div class="form-group">
        <label class="form-label">Pilih Tahun</label>
        <select class="form-input" name="tahun">
            @php $tahunSekarang = date('Y'); @endphp
            @for($i = $tahunSekarang; $i >= ($tahunSekarang - 3); $i--)
                <option value="{{ $i }}" {{ $filterTahun == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>
    </div>
    
    <button type="submit" class="btn-generate" style="height: 42px; justify-content: center; background: #1e293b; box-shadow: none;">
        Tampilkan Data
    </button>
</form>

<div class="stats-grid">
    <div class="stat-box">
        <span class="stat-title">Total Anak Dipantau (Bulan Dipilih)</span>
        <div class="stat-value">{{ $stats['total'] }} <span>Anak</span></div>
    </div>
    <div class="stat-box green">
        <span class="stat-title">🟢 Status Gizi Normal</span>
        <div class="stat-value">{{ $stats['normal'] }} <span>Anak ({{ $persenNormal }}%)</span></div>
    </div>
    <div class="stat-box red">
        <span class="stat-title">🔴 Indikasi Stunting / Kurang</span>
        <div class="stat-value">{{ $stats['stunting'] }} <span>Anak ({{ $persenStunting }}%)</span></div>
    </div>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>PERIODE LAPORAN</th>
                <th>TOTAL DATA ANAK</th>
                <th>GIZI NORMAL 🟢</th>
                <th>STUNTING 🔴</th>
                <th>AKSI UNDUH DETAIL</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporanTable as $laporan)
                <tr>
                    <td><h4 class="period-title">{{ $laporan['periode'] }}</h4></td>
                    <td><span class="badge-count bc-total">{{ $laporan['total'] }} Anak</span></td>
                    <td><span class="badge-count bc-normal">{{ $laporan['normal'] }} Anak</span></td>
                    <td><span class="badge-count bc-stunting">{{ $laporan['stunting'] }} Anak</span></td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('admin.laporan.exportPdf', ['bulan' => $laporan['bulan_angka'], 'tahun' => $laporan['tahun_angka']]) }}" class="btn-download pdf">📄 PDF</a>
                            
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px; color: #64748b;">
                        Belum ada data pengukuran bulanan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection