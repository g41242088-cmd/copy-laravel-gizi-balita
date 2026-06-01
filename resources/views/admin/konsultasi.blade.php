@extends('layouts.app')

@section('title', 'Riwayat Konsultasi - GiziAnak')

@section('custom_css')
<style>
    /* --- HEADER HALAMAN --- */
    .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .header-title-group h2 { margin: 0 0 8px 0; font-size: 28px; font-weight: 900; color: #0f1c2e; font-family: Georgia, serif; }
    .header-title-group p { margin: 0; color: #64748b; font-size: 15px; }

    .btn-export { background: white; color: #1e293b; padding: 12px 20px; border-radius: 12px; font-size: 14px; font-weight: 700; cursor: pointer; border: 1px solid #e2e8f0; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); text-decoration: none; }
    .btn-export:hover { background: #f8fafc; border-color: #cbd5e1; }

    /* --- FILTER & SEARCH ROW --- */
    .filter-card { background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; padding: 20px; margin-bottom: 24px; display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center; }
    
    .search-wrapper { position: relative; flex-grow: 1; max-width: 400px; }
    .search-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); font-size: 16px; color: #94a3b8; }
    .search-input { width: 100%; padding: 12px 16px 12px 42px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 14px; outline: none; transition: border-color 0.2s; background: #f8fafc; color: #1e293b; font-family: inherit; }
    .search-input:focus { border-color: #3b82f6; background: white; }

    .filter-group { display: flex; gap: 12px; }
    .select-filter { padding: 12px 36px 12px 16px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 14px; outline: none; background-color: #f8fafc; color: #1e293b; font-weight: 700; cursor: pointer; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%2364748b" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/></svg>'); background-repeat: no-repeat; background-position: right 12px center; transition: border-color 0.2s; }
    .select-filter:focus { border-color: #3b82f6; background-color: white; }

    /* --- TABLE CONTAINER --- */
    .table-container { background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; text-align: left; min-width: 1000px; }
    th { padding: 16px 24px; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #f1f5f9; background-color: #f8fafc; }
    td { padding: 16px 24px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover { background-color: #f8fafc; }

    /* Elemen Kolom Waktu */
    .time-cell { display: flex; flex-direction: column; gap: 4px; }
    .t-date { font-size: 14px; font-weight: 800; color: #1e293b; }
    .t-time { font-size: 12px; color: #64748b; font-weight: 600; display: flex; align-items: center; gap: 4px; }

    /* Elemen Profil Kolom */
    .profile-cell { display: flex; align-items: center; gap: 12px; }
    .p-avatar { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 800; color: white; flex-shrink: 0; }
    
    .ava-anak { background: #f1f5f9; border: 1px solid #e2e8f0; font-size: 20px; }
    .ava-dokter { background: linear-gradient(135deg, #10b981, #059669); }
    .ava-gizi { background: linear-gradient(135deg, #f59e0b, #d97706); }
    
    .p-name { font-size: 14px; font-weight: 800; color: #1e293b; margin: 0 0 2px 0; }
    .p-detail { font-size: 12px; color: #64748b; margin: 0; }

    /* Status Badges */
    .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 800; display: inline-flex; align-items: center; gap: 6px; }
    .status-badge::before { content: ''; display: inline-block; width: 6px; height: 6px; border-radius: 50%; }
    .st-selesai { background: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; }
    .st-selesai::before { background: #16a34a; }
    .st-menunggu { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
    .st-menunggu::before { background: #2563eb; }
    .st-batal { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .st-batal::before { background: #dc2626; }

    /* Actions */
    .btn-detail { background: white; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; color: #3b82f6; transition: all 0.2s; padding: 6px 16px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
    .btn-detail:hover { background: #eff6ff; border-color: #bfdbfe; }

    /* =======================================
       CSS MODAL (POP-UP) DETAIL
       ======================================= */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 28, 46, 0.6); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 9999; opacity: 0; transition: opacity 0.3s ease; }
    .modal-overlay.active { display: flex; opacity: 1; }
    .modal-content { background: white; width: 90%; max-width: 600px; border-radius: 16px; padding: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); transform: translateY(20px); transition: transform 0.3s ease; max-height: 85vh; display: flex; flex-direction: column; }
    .modal-overlay.active .modal-content { transform: translateY(0); }
    
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; }
    .modal-title { font-size: 18px; font-weight: 800; color: #1e293b; margin: 0; }
    .btn-close { background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 50%; font-size: 14px; font-weight: bold; color: #64748b; cursor: pointer; transition: 0.2s; }
    .btn-close:hover { background: #e2e8f0; color: #dc2626; }
    
    .modal-body { overflow-y: auto; flex-grow: 1; padding-right: 8px; }
    .modal-body::-webkit-scrollbar { width: 6px; }
    .modal-body::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

    /* Layout Dalam Modal */
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; background: #f8fafc; padding: 16px; border-radius: 12px; border: 1px solid #e2e8f0; }
    .info-item { display: flex; flex-direction: column; }
    .info-label { font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; }
    .info-value { font-size: 14px; font-weight: 800; color: #1e293b; }

    .soap-card { background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 12px; }
    .soap-title { font-size: 13px; font-weight: 800; color: #3b82f6; margin: 0 0 8px 0; border-bottom: 1px dashed #e2e8f0; padding-bottom: 8px; display: flex; align-items: center; gap: 6px; }
    .soap-text { font-size: 13px; color: #334155; line-height: 1.5; margin: 0; white-space: pre-wrap; }

    @media (max-width: 768px) {
        .filter-card { flex-direction: column; align-items: stretch; }
        .search-wrapper { max-width: 100%; }
        .filter-group { flex-direction: column; }
        .info-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div class="header-title-group">
        <h2>Riwayat Konsultasi</h2>
        <p>Log aktivitas jadwal dan pertemuan antara Pasien dan Tenaga Medis di platform.</p>
    </div>
</div>

<form class="filter-card" method="GET" action="{{ url()->current() }}">
    <div class="search-wrapper">
        <span class="search-icon">🔍</span>
        <input type="text" name="search" class="search-input" value="{{ request('search') }}" placeholder="Cari nama pasien atau tenaga medis (Enter)...">
    </div>
    
    <div class="filter-group">
        <select name="layanan" class="select-filter" onchange="this.form.submit()">
            <option value="semua" {{ request('layanan') == 'semua' ? 'selected' : '' }}>Semua Layanan</option>
            <option value="dokter" {{ request('layanan') == 'dokter' ? 'selected' : '' }}>👨‍⚕️ Layanan Dokter</option>
            <option value="ahligizi" {{ request('layanan') == 'ahligizi' ? 'selected' : '' }}>🥗 Layanan Ahli Gizi</option>
        </select>
        
        <select name="status" class="select-filter" onchange="this.form.submit()">
            <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>✅ Selesai</option>
            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>⏳ Menunggu</option>
            <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>❌ Dibatalkan</option>
        </select>
        
        <select name="waktu" class="select-filter" onchange="this.form.submit()">
            <option value="semua" {{ request('waktu') == 'semua' ? 'selected' : '' }}>Semua Waktu</option>
            <option value="bulan_ini" {{ request('waktu') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
            <option value="bulan_lalu" {{ request('waktu') == 'bulan_lalu' ? 'selected' : '' }}>Bulan Lalu</option>
        </select>

        @if(request('search') || request('layanan') || request('status') || request('waktu'))
            <a href="{{ url()->current() }}" class="btn-detail" style="padding: 12px 16px; margin: 0; background: #fee2e2; color: #dc2626; border-color: #fecaca; text-decoration: none;">✕ Reset</a>
        @endif
    </div>
</form>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>TANGGAL & WAKTU</th>
                <th>PASIEN (ANAK)</th>
                <th>TENAGA MEDIS</th>
                <th>STATUS</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($semuaKonsultasi as $konsul)
                @php
                    // 1. Logika Waktu
                    $tanggal = '-'; $jamMulai = '-'; $jamSelesai = '-';
                    if($konsul->tanggal_jadwal) {
                        $tanggal = \Carbon\Carbon::parse($konsul->tanggal_jadwal)->translatedFormat('d M Y');
                    }
                    if($konsul->jam_jadwal) {
                        $jamMulai = \Carbon\Carbon::parse($konsul->jam_jadwal)->format('H:i');
                        $jamSelesai = \Carbon\Carbon::parse($konsul->jam_jadwal)->addMinutes(30)->format('H:i');
                    }

                    // 2. Logika Profil Anak
                    $jkAnak = strtolower($konsul->jenis_kelamin_anak ?? $konsul->anak->jenis_kelamin ?? '');
                    $avatarAnak = ($jkAnak == 'p' || $jkAnak == 'perempuan') ? '👧' : '👦';
                    $namaAnak = $konsul->nama_anak ?? $konsul->anak->nama ?? 'Nama Tidak Diketahui';
                    $namaWali = $konsul->nama_ortu ?? $konsul->orangtua->name ?? 'Wali Tidak Diketahui';

                    // 3. Logika Tenaga Medis
                    $namaMedis = $konsul->ahli->name ?? 'Tenaga Medis';
                    if (strtolower($konsul->kategori_ahli) == 'dokter') {
                        $avaMedis = 'ava-dokter'; $iconMedis = 'dr';
                        $profesiMedis = 'Dokter Spesialis'; $warnaProfesi = '#10b981';
                    } else {
                        $avaMedis = 'ava-gizi'; $iconMedis = 'AG';
                        $profesiMedis = 'Ahli Gizi'; $warnaProfesi = '#d97706';
                    }

                    // 4. Logika Status
                    $statusRaw = strtolower($konsul->status ?? 'menunggu');
                    if (str_contains($statusRaw, 'selesai')) { $classStatus = 'st-selesai'; $teksStatus = 'Selesai'; } 
                    elseif (str_contains($statusRaw, 'menunggu')) { $classStatus = 'st-menunggu'; $teksStatus = 'Menunggu'; } 
                    elseif (str_contains($statusRaw, 'tolak') || str_contains($statusRaw, 'batal')) { $classStatus = 'st-batal'; $teksStatus = 'Dibatalkan'; } 
                    else { $classStatus = 'st-menunggu'; $teksStatus = ucwords($statusRaw); }

                    // 5. KUMPULKAN DATA UNTUK MODAL DETAIL
                    $detailData = [
                        'tanggal' => $tanggal,
                        'jam' => $jamMulai . ' - ' . $jamSelesai . ' WIB',
                        'nama_anak' => $namaAnak,
                        'umur_anak' => $konsul->umur_anak ?? '-',
                        'nama_wali' => $namaWali,
                        'no_wa' => $konsul->no_wa ?? '-',
                        'tenaga_medis' => $namaMedis,
                        'profesi' => $profesiMedis,
                        'status' => $teksStatus,
                        'keluhan' => $konsul->catatan ?? '-',
                        'suhu' => $konsul->suhu ?? '-',
                        'subjective' => $konsul->subjective ?? '-',
                        'objective' => $konsul->objective ?? '-',
                        'assessment' => $konsul->assessment ?? '-',
                        'plan' => $konsul->plan ?? '-',
                        'vaksin' => $konsul->vaksin ?? '-'
                    ];
                @endphp

                <tr>
                    <td>
                        <div class="time-cell">
                            <span class="t-date">{{ $tanggal }}</span>
                            <span class="t-time">🕒 {{ $jamMulai }} - {{ $jamSelesai }} WIB</span>
                        </div>
                    </td>
                    <td>
                        <div class="profile-cell">
                            <div class="p-avatar ava-anak">{{ $avatarAnak }}</div>
                            <div>
                                <h4 class="p-name">{{ $namaAnak }}</h4>
                                <p class="p-detail">Wali: {{ $namaWali }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="profile-cell">
                            <div class="p-avatar {{ $avaMedis }}">{{ $iconMedis }}</div>
                            <div>
                                <h4 class="p-name">{{ $namaMedis }}</h4>
                                <p class="p-detail" style="color: {{ $warnaProfesi }}; font-weight: 600;">{{ $profesiMedis }}</p>
                            </div>
                        </div>
                    </td>
                    <td><span class="status-badge {{ $classStatus }}">{{ $teksStatus }}</span></td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <button type="button" class="btn-detail" data-detail="{{ json_encode($detailData) }}" onclick="bukaModalDetail(this)">
                                Lihat Detail
                            </button>

                            <form action="{{ route('admin.konsultasi.destroy', $konsul->id) ?? '#' }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat konsultasi ini? Data tidak dapat dikembalikan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-detail" style="color: #dc2626; border-color: #fecaca; background: #fef2f2; padding: 6px 12px;" title="Hapus Data">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px; color: #64748b;">
                        <span style="font-size: 40px; display: block; margin-bottom: 10px;">📭</span>
                        Belum ada riwayat konsultasi yang cocok dengan pencarian Anda.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="modal-overlay" id="modalDetail">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Detail Konsultasi Medis</h3>
            <button class="btn-close" onclick="tutupModalDetail()">✕</button>
        </div>
        <div class="modal-body">
            
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Tenaga Medis</span>
                    <span class="info-value" id="det_medis">-</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Waktu Konsultasi</span>
                    <span class="info-value" id="det_waktu">-</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Pasien (Anak)</span>
                    <span class="info-value" id="det_anak">-</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Wali Pasien</span>
                    <span class="info-value" id="det_wali">-</span>
                </div>
            </div>

            <div style="margin-bottom: 8px;">
                <span class="info-label" style="font-size: 13px;">📝 Rekam Medis / Hasil Pemeriksaan</span>
            </div>

            <div class="soap-card" id="card_keluhan">
                <h4 class="soap-title">💬 Keluhan Awal (Catatan Pendaftaran)</h4>
                <p class="soap-text" id="det_keluhan">-</p>
            </div>

            <div class="soap-card">
                <h4 class="soap-title">🩺 S & O (Subjective & Objective)</h4>
                <div style="margin-bottom: 8px;">
                    <span style="font-size: 11px; color: #64748b; font-weight: 700;">SUHU TUBUH:</span> 
                    <span id="det_suhu" style="font-weight: 800; color: #dc2626;">-</span>
                </div>
                <p class="soap-text"><strong>Subjektif:</strong> <span id="det_subjective">-</span></p>
                <p class="soap-text" style="margin-top: 6px;"><strong>Objektif:</strong> <span id="det_objective">-</span></p>
            </div>

            <div class="soap-card">
                <h4 class="soap-title">📌 Assessment (Diagnosis)</h4>
                <p class="soap-text" id="det_assessment">-</p>
            </div>

            <div class="soap-card">
                <h4 class="soap-title">📋 Plan (Rencana Tindakan & Rekomendasi)</h4>
                <p class="soap-text" id="det_plan">-</p>
            </div>

            <div class="soap-card" id="card_vaksin">
                <h4 class="soap-title">💉 Vaksin / Imunisasi Diberikan</h4>
                <p class="soap-text" id="det_vaksin">-</p>
            </div>

        </div>
    </div>
</div>

@if(session('success'))
<div class="modal-overlay active" id="modalSukses" style="z-index: 10000;">
    <div class="modal-content" style="max-width: 320px; text-align: center; padding: 40px 24px;">
        <div style="font-size: 60px; margin-bottom: 16px;">✅</div>
        <h3 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 800; color: #1e293b;">Berhasil!</h3>
        <p style="color: #64748b; font-size: 14px; margin: 0 0 24px 0;">{{ session('success') }}</p>
        <button onclick="document.getElementById('modalSukses').remove()" class="btn-export" style="background: #10b981; color: white; border:none; width:100%; justify-content:center;">Tutup</button>
    </div>
</div>
<script>
    setTimeout(() => { document.getElementById('modalSukses')?.remove(); }, 3500);
</script>
@endif

<script>
    function bukaModalDetail(element) {
        // Parse data JSON dari tombol
        const data = JSON.parse(element.getAttribute('data-detail'));

        // Isi Grid Atas
        document.getElementById('det_medis').innerText = data.tenaga_medis + ' (' + data.profesi + ')';
        document.getElementById('det_waktu').innerText = data.tanggal + ' | ' + data.jam;
        document.getElementById('det_anak').innerText = data.nama_anak + ' (Umur: ' + data.umur_anak + ')';
        document.getElementById('det_wali').innerText = data.nama_wali + ' - ' + data.no_wa;

        // Isi Kartu Medis
        document.getElementById('det_keluhan').innerText = data.keluhan;
        document.getElementById('det_suhu').innerText = (data.suhu !== '-' && data.suhu !== null) ? data.suhu + ' °C' : '-';
        document.getElementById('det_subjective').innerText = data.subjective || '-';
        document.getElementById('det_objective').innerText = data.objective || '-';
        document.getElementById('det_assessment').innerText = data.assessment || '-';
        document.getElementById('det_plan').innerText = data.plan || '-';
        document.getElementById('det_vaksin').innerText = data.vaksin || 'Tidak ada tindakan vaksin.';

        // Tampilkan Modal
        document.getElementById('modalDetail').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function tutupModalDetail() {
        document.getElementById('modalDetail').classList.remove('active');
        document.body.style.overflow = 'auto';
    }
</script>

@endsection