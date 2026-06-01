@extends('layouts.app')

@section('title', 'Riwayat Booking Dokter - GiziAnak')

@section('custom_css')
<style>
    /* --- CSS Tetap Sama Seperti Milik Anda --- */
    .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; flex-wrap: wrap; gap: 16px; }
    .header-title-group h2 { margin: 0 0 8px 0; font-size: 28px; font-weight: 900; color: #0f1c2e; font-family: Georgia, serif; }
    .header-title-group p { margin: 0; color: #64748b; font-size: 15px; }
    .badge-total { padding: 8px 16px; border: 1px solid #e2e8f0; border-radius: 20px; font-size: 14px; font-weight: 600; color: #475569; background: white; }
    .filter-row { display: flex; gap: 16px; margin-bottom: 24px; align-items: center; flex-wrap: wrap; }
    .search-input-wrapper { width: 100%; max-width: 320px; position: relative; }
    .search-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px;}
    .search-input { width: 100%; padding: 12px 16px 12px 42px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 14px; outline: none; transition: border-color 0.2s; color: #334155; box-sizing: border-box; }
    .search-input:focus { border-color: #3b82f6; }
    .status-select { padding: 12px 36px 12px 16px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 14px; outline: none; background-color: white; color: #1e293b; font-weight: 500; cursor: pointer; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%2364748b" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/></svg>'); background-repeat: no-repeat; background-position: right 12px center; }
    .table-card { background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; text-align: left; }
    th { padding: 16px 24px; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #f1f5f9; background-color: #f8fafc; }
    td { padding: 20px 24px; border-bottom: 1px solid #f1f5f9; font-size: 14px; vertical-align: middle; color: #334155; }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover { background-color: #f8fafc; }
    .text-dark { font-weight: 700; color: #1e293b; display: block; }
    .text-muted-sm { font-size: 13px; color: #94a3b8; margin-top: 4px; display: block; }
    .badge-status { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; }
    .badge-status::before { content: ''; display: block; width: 6px; height: 6px; border-radius: 50%; }
    .status-selesai { background-color: #dcfce7; color: #16a34a; }
    .status-selesai::before { background-color: #16a34a; }
    .status-disetujui { background-color: #e0f2fe; color: #0284c7; }
    .status-disetujui::before { background-color: #0284c7; }
    .status-menunggu { background-color: #fef3c7; color: #b45309; }
    .status-menunggu::before { background-color: #b45309; }
    .status-ditolak { background-color: #fee2e2; color: #dc2626; }
    .status-ditolak::before { background-color: #dc2626; }
</style>
@endsection

@section('content')

<div class="page-header">
    <div class="header-title-group">
        <h2>Riwayat Booking Dokter</h2>
        <p>Pantau status janji temu Anda dengan dokter spesialis anak</p>
    </div>
    <div class="badge-total">
        <!-- Menggunakan count dari variabel jadwals yang dikirim controller -->
        {{ $jadwals->count() }} Jadwal
    </div>
</div>

<div class="filter-row">
    <div class="search-input-wrapper">
        <span class="search-icon">🔍</span>
        <!-- Ditambahkan id="searchInput" -->
        <input type="text" id="searchInput" class="search-input" placeholder="Cari nama anak atau nama dokter...">
    </div>
    <!-- Ditambahkan id="statusFilter" -->
    <select id="statusFilter" class="status-select">
        <option value="semua">Semua Status</option>
        <option value="selesai">Selesai</option>
        <option value="disetujui">Disetujui</option>
        <option value="menunggu">Menunggu</option>
        <option value="ditolak">Ditolak</option>
    </select>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>TANGGAL & JAM</th>
                <th>NAMA ORANG TUA</th>
                <th>NAMA ANAK</th>
                <th>DOKTER SPESIALIS</th>
                <th>CATATAN KELUHAN</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwals as $jadwal)
            <!-- Ditambahkan class history-item dan data attributes untuk JavaScript -->
            <tr class="history-item" 
                data-status="{{ strtolower($jadwal->status) }}" 
                data-search="{{ strtolower($jadwal->nama_anak . ' ' . ($jadwal->ahli->name ?? '')) }}">
                <td>
                    <span class="text-dark">{{ \Carbon\Carbon::parse($jadwal->tanggal_jadwal)->translatedFormat('d M Y') }}</span>
                    <span class="text-muted-sm">{{ \Carbon\Carbon::parse($jadwal->jam_jadwal)->format('H:i') }} WIB</span>
                </td>
                <td class="text-dark">{{ $jadwal->nama_ortu }}</td>
                <td>{{ $jadwal->nama_anak }}</td>
                <td>
                    {{ $jadwal->ahli->name ?? 'Dokter Tidak Ditemukan' }}
                </td>
                <td style="color: {{ $jadwal->catatan ? '#334155' : '#94a3b8' }}; font-style: {{ $jadwal->catatan ? 'italic' : 'normal' }};">
                    {{ $jadwal->catatan ? '"'.$jadwal->catatan.'"' : '-' }}
                </td>
                <td>
                    @if($jadwal->status == 'menunggu')
                        <span class="badge-status status-menunggu">Menunggu</span>
                    @elseif($jadwal->status == 'disetujui')
                        <span class="badge-status status-disetujui">Disetujui</span>
                    @elseif($jadwal->status == 'selesai')
                        <span class="badge-status status-selesai">Selesai</span>
                    @elseif($jadwal->status == 'ditolak')
                        <span class="badge-status status-ditolak">Ditolak</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr id="emptyStateRow">
                <td colspan="6" style="text-align: center; padding: 48px 32px; color: #64748b;">
                    <span style="font-size: 32px; display: block; margin-bottom: 12px;">🗓️</span>
                    Belum ada riwayat booking konsultasi.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const historyItems = document.querySelectorAll('.history-item');

        function filterHistory() {
            const query = searchInput.value.toLowerCase();
            const status = statusFilter.value.toLowerCase();

            historyItems.forEach(item => {
                // Ambil data dari atribut yang kita buat di HTML
                const itemSearch = item.getAttribute('data-search') || '';
                const itemStatus = item.getAttribute('data-status') || '';

                // Cek apakah data cocok dengan inputan user
                const matchesSearch = itemSearch.includes(query);
                const matchesStatus = (status === 'semua' || status === itemStatus);

                // Tampilkan baris jika cocok, sembunyikan jika tidak
                if (matchesSearch && matchesStatus) {
                    item.style.display = ''; 
                } else {
                    item.style.display = 'none'; 
                }
            });
        }

        // Jalankan fungsi filter setiap kali user mengetik atau mengganti pilihan
        searchInput.addEventListener('input', filterHistory);
        statusFilter.addEventListener('change', filterHistory);
    });
</script>
@endpush