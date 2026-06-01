@extends('layouts.app')

@section('title', 'Master Data Anak - GiziAnak')

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

    .child-profile { display: flex; align-items: center; gap: 12px; }
    .avatar { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; background: #f1f5f9; border: 1px solid #e2e8f0; }
    .c-name { font-size: 15px; font-weight: 800; color: #1e293b; margin: 0 0 4px 0; }
    .c-detail { font-size: 12px; color: #64748b; margin: 0; font-weight: 600; }
    .parent-info { display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; color: #334155; }
    .measurement { display: flex; flex-direction: column; gap: 4px; }
    .m-value { font-size: 14px; font-weight: 800; color: #1e293b; }
    .m-date { font-size: 11px; color: #94a3b8; }

    /* Status Gizi Badges */
    .gizi-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 800; display: inline-flex; align-items: center; gap: 6px; }
    .gizi-badge::before { content: ''; display: inline-block; width: 6px; height: 6px; border-radius: 50%; }
    .gb-normal { background: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; }
    .gb-normal::before { background: #16a34a; }
    .gb-kurang { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .gb-kurang::before { background: #d97706; }
    .gb-stunting { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .gb-stunting::before { background: #dc2626; }
    .gb-obesitas { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
    .gb-obesitas::before { background: #2563eb; }
    .gb-belum { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }
    .gb-belum::before { background: #94a3b8; }

    .action-group { display: flex; gap: 8px; }
    .btn-action { background: white; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; color: #64748b; transition: all 0.2s; padding: 6px 12px; text-decoration: none; }
    .btn-action:hover { background: #f8fafc; color: #3b82f6; border-color: #cbd5e1; }

    /* =======================================
       CSS MODAL (POP-UP) 
       ======================================= */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 28, 46, 0.6); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 9999; opacity: 0; transition: opacity 0.3s ease; }
    .modal-overlay.active { display: flex; opacity: 1; }
    .modal-content { background: white; width: 90%; max-width: 450px; border-radius: 16px; padding: 20px 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); transform: translateY(20px); transition: transform 0.3s ease; max-height: 85vh; display: flex; flex-direction: column; }
    .modal-overlay.active .modal-content { transform: translateY(0); }
    
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; }
    .modal-title { font-size: 16px; font-weight: 800; color: #1e293b; margin: 0; }
    .btn-close { background: #f1f5f9; border: none; width: 28px; height: 28px; border-radius: 50%; font-size: 12px; font-weight: bold; color: #64748b; cursor: pointer; }

    .modal-body { overflow-y: auto; flex-grow: 1; padding-right: 6px; }
    .modal-body::-webkit-scrollbar { width: 4px; }
    .modal-body::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

    .modal-input { width: 100%; box-sizing: border-box; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; color: #1e293b; background: white; transition: border-color 0.2s; margin-top: 6px; }
    .modal-input:focus { border-color: #3b82f6; outline: none; }
    .btn-simpan-modal { width: 100%; background: #3b82f6; color: white; border: none; padding: 12px; border-radius: 8px; font-size: 14px; font-weight: 800; cursor: pointer; transition: 0.2s; margin-top: 10px; }
    .btn-simpan-modal:hover { background: #2563eb; }
    
    .timeline { position: relative; margin-left: 10px; padding-left: 16px; border-left: 2px solid #e2e8f0; display: flex; flex-direction: column; gap: 12px; }
    .timeline-item { position: relative; }
    .timeline-dot { position: absolute; left: -22px; top: 2px; width: 10px; height: 10px; border-radius: 50%; background: #3b82f6; border: 2px solid white; box-shadow: 0 0 0 2px #bfdbfe; }
    .timeline-date { font-size: 11px; font-weight: 800; color: #3b82f6; margin-bottom: 4px; }
    .timeline-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 12px; }
    .t-label { font-size: 9px; color: #64748b; text-transform: uppercase; font-weight: 800; margin-bottom: 2px; }
    .t-value { font-size: 13px; font-weight: 800; color: #1e293b; }

    @media (max-width: 768px) {
        .filter-card { flex-direction: column; align-items: stretch; }
        .search-wrapper { max-width: 100%; }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div class="header-title-group">
        <h2>Master Data Anak</h2>
        <p>Pantau seluruh data anak, riwayat pertumbuhan, dan status gizi secara terpusat.</p>
    </div>
</div>

<form class="filter-card" method="GET" action="{{ route('admin.anak.index') ?? '#' }}">
    <div class="search-wrapper">
        <span class="search-icon">🔍</span>
        <input type="text" name="search" class="search-input" value="{{ request('search') }}" placeholder="Cari nama anak atau nama orang tua (Tekan Enter)...">
    </div>
    <div class="filter-group">
        <select name="status" class="select-filter" onchange="this.form.submit()">
            <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status Gizi</option>
            <option value="normal" {{ request('status') == 'normal' ? 'selected' : '' }}>🟢 Gizi Normal</option>
            <option value="kurang" {{ request('status') == 'kurang' ? 'selected' : '' }}>🟡 Kurang Gizi</option>
            <option value="stunting" {{ request('status') == 'stunting' ? 'selected' : '' }}>🔴 Stunting</option>
            <option value="obesitas" {{ request('status') == 'obesitas' ? 'selected' : '' }}>🔵 Obesitas</option>
        </select>
        
        <select name="umur" class="select-filter" onchange="this.form.submit()">
            <option value="semua" {{ request('umur') == 'semua' ? 'selected' : '' }}>Semua Umur</option>
            <option value="bayi" {{ request('umur') == 'bayi' ? 'selected' : '' }}>Bayi (0 - 2 Tahun)</option>
            <option value="balita" {{ request('umur') == 'balita' ? 'selected' : '' }}>Balita (2 - 5 Tahun)</option>
        </select>
        
        @if(request('search') || request('status') || request('umur'))
            <a href="{{ route('admin.anak.index') ?? '#' }}" class="btn-action" style="padding: 12px 16px; margin: 0; background: #fee2e2; color: #dc2626; border-color: #fecaca;">✕ Reset</a>
        @endif
    </div>
</form>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>PROFIL ANAK</th>
                <th>WALI / ORANG TUA</th>
                <th>TB/BB</th>
                <th>STATUS GIZI</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($anaks as $anak)
                @php
                    $jk = strtolower($anak->jenis_kelamin);
                    $avatar = ($jk == 'p' || $jk == 'perempuan') ? '👧' : '👦';
                    $genderText = ($jk == 'p' || $jk == 'perempuan') ? 'Perempuan' : 'Laki-laki';

                    // Hitung Umur Dibulatkan
                    $umurText = '-';
                    if($anak->tanggal_lahir) {
                        $tglLahir = \Carbon\Carbon::parse($anak->tanggal_lahir);
                        $umurTahun = (int) $tglLahir->diffInYears(now());
                        $umurBulanSisa = (int) ($tglLahir->diffInMonths(now()) % 12);
                        
                        if($umurTahun > 0 && $umurBulanSisa > 0) { $umurText = "{$umurTahun} Thn {$umurBulanSisa} Bln"; }
                        elseif ($umurTahun > 0) { $umurText = "{$umurTahun} Thn"; }
                        else { $umurText = "{$umurBulanSisa} Bln"; }
                    }

                    $pengukuranTerakhir = $anak->pengukurans->sortByDesc('created_at')->first();
                    $tb_bb = "Belum diukur"; $tglDiperbarui = "-"; $statusGizi = "Belum Diukur"; $badgeClass = "gb-belum";

                    if($pengukuranTerakhir) {
                        $tb_bb = "{$pengukuranTerakhir->tinggi_badan} cm / {$pengukuranTerakhir->berat_badan} kg";
                        $tglDiperbarui = "Diedit: " . \Carbon\Carbon::parse($pengukuranTerakhir->created_at)->translatedFormat('d M Y');
                        $statusRaw = strtolower($pengukuranTerakhir->status_gizi);
                        if (str_contains($statusRaw, 'kurang')) { $badgeClass = 'gb-kurang'; $statusGizi = 'Kurang'; } 
                        elseif (str_contains($statusRaw, 'stunting') || str_contains($statusRaw, 'buruk')) { $badgeClass = 'gb-stunting'; $statusGizi = 'Stunting'; } 
                        elseif (str_contains($statusRaw, 'obesitas')) { $badgeClass = 'gb-obesitas'; $statusGizi = 'Obesitas'; } 
                        else { $badgeClass = 'gb-normal'; $statusGizi = 'Normal'; }
                    }
                @endphp

                <tr>
                    <td>
                        <div class="child-profile">
                            <div class="avatar">{{ $avatar }}</div>
                            <div>
                                <h4 class="c-name">{{ $anak->nama }}</h4>
                                <p class="c-detail">{{ $genderText }} • {{ $umurText }}</p>
                            </div>
                        </div>
                    </td>
                    <td><div class="parent-info"><span>👨‍👩‍👧 {{ $anak->orangtua->name ?? 'Wali' }}</span></div></td>
                    <td><div class="measurement"><span class="m-value">{{ $tb_bb }}</span><span class="m-date">{{ $tglDiperbarui }}</span></div></td>
                    <td><span class="gizi-badge {{ $badgeClass }}">{{ $statusGizi }}</span></td>
                    <td>
                        <div class="action-group">
                            <button type="button" class="btn-action" 
                                    data-nama="{{ $anak->nama }}"
                                    data-riwayat="{{ json_encode($anak->pengukurans->sortByDesc('created_at')->values()) }}"
                                    onclick="bukaModalRiwayat(this)"
                                    @if($badgeClass == 'gb-stunting' || $badgeClass == 'gb-kurang') style="color: #dc2626; border-color: #fecaca; background: #fef2f2;" @endif>
                                @if($badgeClass == 'gb-stunting' || $badgeClass == 'gb-kurang') ⚠️ Tinjau @else Riwayat @endif
                            </button>
                            <button type="button" class="btn-action" 
                                    data-id="{{ $anak->id }}" data-nama="{{ $anak->nama }}" data-jk="{{ $anak->jenis_kelamin }}" 
                                    data-tgl="{{ $anak->tanggal_lahir }}" data-berat="{{ $anak->berat_lahir }}" data-panjang="{{ $anak->panjang_lahir }}"
                                    onclick="bukaModalEdit(this)">✏️</button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align: center; padding: 40px; color: #64748b;">Data yang Anda cari tidak ditemukan atau belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="modal-overlay" id="modalRiwayat">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Riwayat Tumbuh Kembang<br><span id="modalNamaAnakRiwayat" style="color: #3b82f6; font-size: 13px;"></span></h3>
            <button class="btn-close" onclick="tutupModalRiwayat()">✕</button>
        </div>
        <div class="modal-body" id="modalRiwayatBody"></div>
    </div>
</div>

<div class="modal-overlay" id="modalEdit">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-header">
            <h3 class="modal-title">Edit Profil Anak</h3>
            <button class="btn-close" onclick="tutupModalEdit()">✕</button>
        </div>
        <div class="modal-body">
            <form id="formEditAnak" method="POST">
                @csrf 
                @method('PUT')
                
                <div style="margin-bottom: 14px;">
                    <label class="t-label">Nama Lengkap</label>
                    <input type="text" name="nama" id="edit_nama" class="modal-input" required>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                    <div>
                        <label class="t-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="edit_jk" class="modal-input">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="t-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="edit_tgl" class="modal-input" required>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                    <div>
                        <label class="t-label">Berat Lahir (kg)</label>
                        <input type="number" step="0.01" name="berat_lahir" id="edit_berat" class="modal-input">
                    </div>
                    <div>
                        <label class="t-label">Panjang Lahir (cm)</label>
                        <input type="number" step="0.1" name="panjang_lahir" id="edit_panjang" class="modal-input">
                    </div>
                </div>
                
                <button type="submit" class="btn-simpan-modal">💾 Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
<div class="modal-overlay active" id="modalSukses" style="z-index: 10000;">
    <div class="modal-content" style="max-width: 320px; text-align: center; padding: 40px 24px;">
        <div style="font-size: 60px; margin-bottom: 16px; animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;">✅</div>
        <h3 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 800; color: #1e293b;">Berhasil!</h3>
        <p style="color: #64748b; font-size: 14px; margin: 0 0 24px 0; line-height: 1.5;">{{ session('success') }}</p>
        <button onclick="document.getElementById('modalSukses').remove()" class="btn-simpan-modal" style="margin-top: 0; background: #10b981;">Siap, Mengerti</button>
    </div>
</div>

<style>
    @keyframes popIn {
        0% { transform: scale(0); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
</style>

<script>
    // Tutup pop-up sukses otomatis
    setTimeout(function() {
        let modalSukses = document.getElementById('modalSukses');
        if(modalSukses) {
            modalSukses.style.opacity = '0';
            setTimeout(() => modalSukses.remove(), 300);
        }
    }, 3500);
</script>
@endif

<script>
    function bukaModalRiwayat(el) {
        document.getElementById('modalNamaAnakRiwayat').innerText = el.getAttribute('data-nama');
        const riwayat = JSON.parse(el.getAttribute('data-riwayat'));
        const body = document.getElementById('modalRiwayatBody');
        if (riwayat.length === 0) {
            body.innerHTML = '<p style="text-align:center; padding:20px; color:#94a3b8;">Belum ada riwayat.</p>';
        } else {
            let html = '<div class="timeline">';
            riwayat.forEach(item => {
                const d = new Date(item.created_at).toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'});
                let color = '#16a34a', bg = '#dcfce7', txt = (item.status_gizi || 'NORMAL').toUpperCase();
                if(txt.includes('KURANG')) { color='#d97706'; bg='#fde68a'; }
                else if(txt.includes('STUNTING') || txt.includes('BURUK')) { color='#dc2626'; bg='#fecaca'; }
                else if(txt.includes('OBESITAS')) { color='#2563eb'; bg='#bfdbfe'; }
                
                html += `<div class="timeline-item"><div class="timeline-dot"></div><div class="timeline-date">${d}</div><div class="timeline-card">
                    <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 8px; align-items: end;">
                        <div style="display: flex; flex-direction: column;"><span class="t-label">Tinggi</span><span class="t-value">${item.tinggi_badan} cm</span></div>
                        <div style="display: flex; flex-direction: column;"><span class="t-label">Berat</span><span class="t-value">${item.berat_badan} kg</span></div>
                        <div style="display: flex; flex-direction: column; align-items: flex-end;"><span class="t-label">Status</span><span style="padding: 2px 6px; border-radius: 4px; font-size: 8px; font-weight: 800; background: ${bg}; color: ${color};">${txt}</span></div>
                    </div></div></div>`;
            });
            body.innerHTML = html + '</div>';
        }
        document.getElementById('modalRiwayat').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function tutupModalRiwayat() { document.getElementById('modalRiwayat').classList.remove('active'); document.body.style.overflow = 'auto'; }

    function bukaModalEdit(el) {
        document.getElementById('edit_nama').value = el.getAttribute('data-nama');
        document.getElementById('edit_jk').value = el.getAttribute('data-jk');
        document.getElementById('edit_tgl').value = el.getAttribute('data-tgl');
        document.getElementById('edit_berat').value = el.getAttribute('data-berat');
        document.getElementById('edit_panjang').value = el.getAttribute('data-panjang');
        document.getElementById('formEditAnak').action = `/admin/anak/update/${el.getAttribute('data-id')}`;
        document.getElementById('modalEdit').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function tutupModalEdit() { document.getElementById('modalEdit').classList.remove('active'); document.body.style.overflow = 'auto'; }
</script>

@endsection