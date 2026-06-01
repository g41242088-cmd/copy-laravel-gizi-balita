@extends('layouts.app')

@section('title', 'Daftar Pasien - GiziAnak')

@section('custom_css')
<style>
    /* --- HEADER HALAMAN --- */
    .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .header-title-group h2 { margin: 0 0 8px 0; font-size: 28px; font-weight: 900; color: #0f1c2e; font-family: Georgia, serif; }
    .header-title-group p { margin: 0; color: #64748b; font-size: 15px; }

    /* --- FILTER & SEARCH ROW --- */
    .filter-row { display: flex; justify-content: space-between; gap: 16px; margin-bottom: 24px; flex-wrap: wrap; }
    .search-input-wrapper { width: 350px; position: relative; }
    .search-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px;}
    .search-input { width: 100%; padding: 12px 16px 12px 42px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 14px; outline: none; transition: border-color 0.2s; color: #334155; }
    .search-input:focus { border-color: #3b82f6; }
    
    .sort-select { padding: 12px 36px 12px 16px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 14px; outline: none; background-color: white; color: #1e293b; font-weight: 600; cursor: pointer; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%2364748b" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/></svg>'); background-repeat: no-repeat; background-position: right 12px center; }

    /* --- TABLE CONTAINER --- */
    .table-card { background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; text-align: left; min-width: 800px; }
    th { padding: 16px 24px; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #f1f5f9; background-color: #f8fafc; }
    td { padding: 20px 24px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover { background-color: #f8fafc; }

    /* Elemen Dalam Tabel */
    .patient-cell { display: flex; align-items: center; gap: 12px; }
    .p-avatar { width: 40px; height: 40px; border-radius: 50%; background: #eff6ff; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
    .p-name { font-size: 14px; font-weight: 800; color: #1e293b; margin: 0 0 2px 0; }
    .p-age { font-size: 12px; color: #64748b; margin: 0; }
    
    .parent-name { font-size: 14px; font-weight: 600; color: #334155; display: flex; align-items: center; gap: 6px; }
    
    .date-text { font-size: 14px; font-weight: 700; color: #1e293b; display: block; }
    .date-sub { font-size: 12px; color: #94a3b8; }

    /* Status Badges */
    .status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; }
    .status-badge::before { content: ''; display: block; width: 6px; height: 6px; border-radius: 50%; }
    
    .st-normal { background: #dcfce7; color: #16a34a; }
    .st-normal::before { background: #16a34a; }

    /* Button Aksi */
    .btn-detail { background: white; border: 1px solid #e2e8f0; color: #3b82f6; font-size: 13px; font-weight: 700; padding: 8px 16px; border-radius: 8px; cursor: pointer; transition: all 0.2s; text-decoration: none; display: inline-block; }
    .btn-detail:hover { background: #eff6ff; border-color: #bfdbfe; }

    /* --- RESPONSIVE --- */
    @media (max-width: 768px) {
        .filter-row { flex-direction: column; }
        .search-input-wrapper { width: 100%; }
        .sort-select { width: 100%; }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div class="header-title-group">
        <h2>Daftar Pasien Saya</h2>
        <p>Pantau riwayat konsultasi dan data gizi seluruh pasien Anda.</p>
    </div>
</div>

<div class="filter-row">
    <div class="search-input-wrapper">
        <span class="search-icon">🔍</span>
        <!-- ID Search Input -->
        <input type="text" id="searchInput" class="search-input" placeholder="Cari nama pasien atau wali...">
    </div>
    <!-- ID Sort Select -->
    <select id="sortSelect" class="sort-select">
        <option value="terbaru">Urutkan: Konsultasi Terbaru</option>
        <option value="terlama">Urutkan: Konsultasi Terlama</option>
        <option value="nama_a_z">Nama Pasien (A-Z)</option>
    </select>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>NAMA PASIEN</th>
                <th>WALI / ORANG TUA</th>
                <th>KONSULTASI TERAKHIR</th>
                <th>STATUS GIZI (TERAKHIR)</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody id="patientTableBody">
            @forelse($pasiens as $pasien)
                @php
                    $totalKonsul = $semuaKonsultasi->where('anak_id', $pasien->anak_id)->count();
                    $jenisKelamin = strtolower($pasien->jenis_kelamin_anak ?? $pasien->anak->jenis_kelamin ?? '');
                    $avatar = ($jenisKelamin == 'p' || $jenisKelamin == 'perempuan') ? '👧' : '👦';
                    $genderText = ($jenisKelamin == 'p' || $jenisKelamin == 'perempuan') ? 'Perempuan' : 'Laki-laki';
                    
                    $namaAnak = $pasien->nama_anak ?? $pasien->anak->nama ?? 'Nama Anak';
                    $namaOrtu = $pasien->nama_ortu ?? 'Wali Pasien';
                    
                    // Hitung umur asli dengan Carbon agar tidak error/desimal panjang
                    $umurBulan = 0;
                    if(isset($pasien->anak->tanggal_lahir)) {
                        $umurBulan = round(\Carbon\Carbon::parse($pasien->anak->tanggal_lahir)->diffInMonths(now()));
                    } else {
                        $umurBulan = round($pasien->umur_anak ?? 0);
                    }
                @endphp
                <!-- Data Atrribut untuk JavaScript Searching & Sorting -->
                <tr class="patient-row" 
                    data-name="{{ strtolower($namaAnak) }}" 
                    data-parent="{{ strtolower($namaOrtu) }}"
                    data-date="{{ $pasien->tanggal_jadwal }}">
                    
                    <td>
                        <div class="patient-cell">
                            <div class="p-avatar">{{ $avatar }}</div>
                            <div>
                                <h4 class="p-name">{{ $namaAnak }}</h4>
                                <p class="p-age">{{ $genderText }} • {{ $umurBulan }} bln</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="parent-name">{{ $namaOrtu }}</span>
                    </td>
                    <td>
                        <span class="date-text">{{ \Carbon\Carbon::parse($pasien->tanggal_jadwal)->translatedFormat('d M Y') }}</span>
                        <span class="date-sub">{{ $totalKonsul }} Konsultasi</span>
                    </td>
                    <td>
                        <span class="status-badge st-normal">Gizi Normal</span>
                    </td>
                    <td>
                        <a href="{{ route('ahligizi.analisis.detail', $pasien->id) }}" class="btn-detail">Lihat Analisis Gizi</a>
                    </td>
                </tr>
            @empty
                <tr id="emptyRow">
                    <td colspan="5" style="text-align: center; padding: 48px; background: white;">
                        <div style="font-size: 40px; margin-bottom: 12px;">🏥</div>
                        <h3 style="color: #1e293b; margin: 0 0 8px 0;">Belum ada riwayat pasien</h3>
                        <p style="color: #64748b; margin: 0; font-size: 14px;">Anda belum memiliki pasien dengan status konsultasi yang sudah disetujui atau selesai.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(session('success'))
<style>
    .modal-overlay-success { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 28, 46, 0.6); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; z-index: 9999; }
    .modal-box-success { background: white; width: 100%; max-width: 400px; border-radius: 20px; padding: 40px; text-align: center; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); animation: popIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
    @keyframes popIn { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    .btn-oke-success { background: #10b981; color: white; border: none; padding: 14px 24px; border-radius: 12px; font-weight: 800; cursor: pointer; width: 100%; font-size: 14px; transition: background 0.2s; margin-top: 8px;}
    .btn-oke-success:hover { background: #059669; }
</style>

<div class="modal-overlay-success" id="successPopup">
    <div class="modal-box-success">
        <div style="font-size: 56px; margin-bottom: 16px; animation: bounce 1s ease infinite;">✅</div>
        <h3 style="font-size: 20px; font-weight: 900; color: #1e293b; margin: 0 0 8px 0;">Analisis Tersimpan!</h3>
        <p style="font-size: 14px; color: #64748b; margin: 0 0 24px 0; line-height: 1.5;">{{ session('success') }}</p>
        <button class="btn-oke-success" onclick="document.getElementById('successPopup').style.display='none'">Oke, Saya Mengerti</button>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const sortSelect = document.getElementById('sortSelect');
        const tbody = document.getElementById('patientTableBody');
        const rows = Array.from(document.querySelectorAll('.patient-row'));

        function filterAndSort() {
            const query = searchInput.value.toLowerCase();
            const sortType = sortSelect.value;

            // 1. Logika Pencarian
            let visibleCount = 0;
            rows.forEach(row => {
                const name = row.getAttribute('data-name');
                const parent = row.getAttribute('data-parent');
                
                if (name.includes(query) || parent.includes(query)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // 2. Logika Pengurutan (Sorting)
            const visibleRows = rows.filter(row => row.style.display !== 'none');
            
            visibleRows.sort((a, b) => {
                if (sortType === 'nama_a_z') {
                    const nameA = a.getAttribute('data-name');
                    const nameB = b.getAttribute('data-name');
                    return nameA.localeCompare(nameB);
                } else {
                    // Sorting berdasarkan Tanggal (Terbaru / Terlama)
                    const dateA = new Date(a.getAttribute('data-date'));
                    const dateB = new Date(b.getAttribute('data-date'));
                    return sortType === 'terbaru' ? dateB - dateA : dateA - dateB;
                }
            });

            // Re-append baris yang sudah diurutkan ke tabel
            visibleRows.forEach(row => tbody.appendChild(row));
        }

        searchInput.addEventListener('input', filterAndSort);
        sortSelect.addEventListener('change', filterAndSort);
    });
</script>
@endpush