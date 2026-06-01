@extends('layouts.app')

@section('title', 'Cek Gizi Anak - GiziAnak')

@section('custom_css')
<style>
    /* --- HEADER HALAMAN --- */
    .page-header { margin-bottom: 32px; }
    .page-header h2 { font-size: 28px; font-weight: 900; color: #0f1c2e; margin: 0 0 8px 0; font-family: Georgia, serif; }
    .page-header p { font-size: 15px; color: #64748b; margin: 0; }

    /* --- LAYOUT GRID UTAMA --- */
    .gizi-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 24px; align-items: start; }

    /* --- CARD STYLING --- */
    .card-white { background: white; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; overflow: hidden; }
    .card-header { padding: 20px 24px; border-bottom: 1px solid #f1f5f9; font-size: 15px; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 10px; }
    .card-body { padding: 24px; }

    /* --- FORM STYLING --- */
    .form-group { margin-bottom: 20px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .form-label { display: block; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
    
    .input-wrapper { position: relative; display: flex; align-items: center; }
    .form-input { width: 100%; padding: 14px 16px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 14px; color: #334155; transition: all 0.2s; outline: none; }
    .form-input:focus { border-color: #3b82f6; background-color: white; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
    .input-suffix { position: absolute; right: 16px; font-size: 13px; font-weight: 600; color: #94a3b8; pointer-events: none; }
    .form-input.has-suffix { padding-right: 60px; }

    /* --- TOMBOL --- */
    .btn-primary { width: 100%; padding: 16px; background-color: #2563eb; color: white; border: none; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer; transition: all 0.2s; display: flex; justify-content: center; align-items: center; gap: 8px; }
    .btn-primary:hover { background-color: #1d4ed8; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); }

    /* --- EMPTY STATE & RESULTS --- */
    .empty-result { text-align: center; padding: 60px 20px; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; min-height: 280px; }
    .empty-icon { font-size: 48px; margin-bottom: 16px; opacity: 0.8; }
    .empty-text { font-size: 14px; color: #64748b; line-height: 1.5; font-weight: 500; }
    
    .status-box { padding: 20px; border-radius: 12px; text-align: center; margin-top: 20px; }
    .status-normal { background: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0; }
    .status-warning { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .status-danger { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }

    /* --- RESPONSIVE --- */
    @media (max-width: 992px) { .gizi-grid { grid-template-columns: 1fr; } }
    @media (max-width: 480px) { .form-row { grid-template-columns: 1fr; gap: 0; } }
</style>
@endsection

@section('content')
<div class="page-header">
    <h2>Cek Gizi Anak</h2>
    <p>Masukkan data anak untuk mengetahui status gizinya secara instan</p>
</div>

<div class="gizi-grid">
    <div class="card-white">
        <div class="card-header"><span>📋</span> Data Anak</div>
        <div class="card-body">
            <form action="{{ route('orangtua.cekgizi.hitung') }}" method="POST">
                @csrf
                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: baseline;">
                        <label class="form-label">Pilih Anak</label>
                        <a href="{{ route('orangtua.anak.create') }}" style="font-size: 11px; font-weight: 700; color: #3b82f6; text-decoration: none;">+ Anak Baru</a>
                    </div>
                    <select class="form-input" name="anak_id" required>
                        <option value="" selected disabled>-- Pilih Anak --</option>
                        @foreach($anaks as $anak)
                            <option value="{{ $anak->id }}">👶 {{ $anak->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Umur (Bulan)</label>
                        <div class="input-wrapper">
                            <input type="number" name="umur_bulan" class="form-input has-suffix" placeholder="Contoh: 12" required>
                            <span class="input-suffix">bulan</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Berat Badan</label>
                        <div class="input-wrapper">
                            <input type="number" step="0.1" name="berat_badan" class="form-input has-suffix" placeholder="0.0" required>
                            <span class="input-suffix">kg</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tinggi Badan</label>
                    <div class="input-wrapper">
                        <input type="number" step="0.1" name="tinggi_badan" class="form-input has-suffix" placeholder="0" required>
                        <span class="input-suffix">cm</span>
                    </div>
                </div>

                <button type="submit" class="btn-primary">⚡ Hitung Status Gizi</button>
            </form>
        </div>
    </div>

    <div class="card-white">
        <div class="card-header"><span>📈</span> Hasil Pemeriksaan</div>
        <div class="card-body">
            @if(session('hasil'))
                @php
                    $hasil = session('hasil');
                    $umur = is_array($hasil) ? $hasil['umur_bulan'] : $hasil->umur_bulan;
                    $berat = is_array($hasil) ? $hasil['berat_badan'] : $hasil->berat_badan;
                    $tinggi = is_array($hasil) ? $hasil['tinggi_badan'] : $hasil->tinggi_badan;
                    $status = is_array($hasil) ? $hasil['status_gizi'] : $hasil->status_gizi;
                    
                    $imt = $berat / pow($tinggi/100, 2);
                @endphp

                <div class="result-display">
                    <h4 style="margin:0; color:#64748b; font-size:13px;">HASIL UNTUK:</h4>
                    <h2 style="margin:5px 0 20px 0; color:#1e293b;">{{ session('nama_anak') }}</h2>
                    
                    <div class="form-row" style="margin-bottom:20px;">
                        <div style="text-align:center;">
                            <small class="form-label">UMUR</small>
                            <div style="font-weight:800; font-size: 18px; color: #334155;">{{ $umur }} Bln</div>
                        </div>
                        <div style="text-align:center;">
                            <small class="form-label">IMT</small>
                            <div style="font-weight:800; font-size: 18px; color: #334155;">{{ number_format($imt, 1) }}</div>
                        </div>
                    </div>

                    <div class="status-box {{ $status == 'normal' ? 'status-normal' : ($status == 'obesitas' ? 'status-danger' : 'status-warning') }}">
                        <div style="font-size:12px; font-weight:700; text-transform:uppercase;">Status Gizi:</div>
                        <div style="font-size:24px; font-weight:900;">{{ str_replace('_', ' ', strtoupper($status)) }}</div>
                    </div>
                </div>
            @else
                <div class="empty-result">
                    <div class="empty-icon">📊</div>
                    <p class="empty-text">Pilih anak dan isi data di sebelah kiri<br>untuk melihat hasil status gizi</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection