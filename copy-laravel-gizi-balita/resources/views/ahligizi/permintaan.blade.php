@extends('layouts.app')

@section('title', 'Permintaan Masuk - GiziAnak')

@section('custom_css')
<style>
    /* --- HEADER HALAMAN --- */
    .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; flex-wrap: wrap; gap: 16px; }
    .header-title-group h2 { margin: 0 0 8px 0; font-size: 28px; font-weight: 900; color: #0f1c2e; font-family: Georgia, serif; }
    .header-title-group p { margin: 0; color: #64748b; font-size: 15px; }
    
    .badge-total { padding: 8px 16px; border: 1px solid #fbd5ce; border-radius: 20px; font-size: 14px; font-weight: 700; color: #ea580c; background: #fff7ed; display: flex; align-items: center; gap: 8px; }

    /* --- FILTER BAR --- */
    .filter-bar { display: flex; gap: 12px; margin-bottom: 24px; }
    .btn-filter { padding: 10px 20px; border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer; border: 1px solid #e2e8f0; background: white; color: #64748b; transition: all 0.2s; }
    .btn-filter.active { background: #3b82f6; color: white; border-color: #3b82f6; }
    .btn-filter:hover:not(.active) { background: #f8fafc; }

    /* --- REQUEST GRID --- */
    .request-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }

    /* --- CARD STYLING --- */
    .req-card { background: white; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; padding: 24px; display: flex; flex-direction: column; transition: transform 0.2s; }
    .req-card:hover { transform: translateY(-4px); box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.05); }

    .card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px dashed #e2e8f0; }
    .patient-info { display: flex; align-items: center; gap: 16px; }
    .avatar { width: 48px; height: 48px; border-radius: 12px; background: #eff6ff; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0; }
    .patient-name { font-size: 16px; font-weight: 800; color: #1e293b; margin: 0 0 4px 0; }
    .parent-name { font-size: 12px; color: #64748b; margin: 0; display: flex; align-items: center; gap: 4px; }
    
    .wa-info { font-size: 12px; font-weight: 700; color: #10b981; margin: 4px 0 0 0; display: flex; align-items: center; gap: 4px; }

    .time-badge { background: #eff6ff; color: #2563eb; padding: 8px 12px; border-radius: 10px; text-align: center; white-space: nowrap; }
    .time-date { font-size: 12px; font-weight: 800; display: block; margin-bottom: 2px; }
    .time-hour { font-size: 11px; font-weight: 600; color: #3b82f6; }

    .card-body { flex-grow: 1; margin-bottom: 24px; }
    .info-label { font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; display: block; }
    .note-box { background: #f8fafc; border-left: 3px solid #fbbf24; padding: 12px 16px; border-radius: 0 8px 8px 0; font-size: 13px; color: #475569; font-style: italic; line-height: 1.5; margin: 0; }

    .card-actions { display: flex; gap: 12px; width: 100%; }
    .btn { flex: 1; padding: 12px; border-radius: 10px; font-size: 14px; font-weight: 700; cursor: pointer; transition: all 0.2s; border: none; text-align: center; }
    .btn-reject { background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2; }
    .btn-reject:hover { background: #fee2e2; }
    .btn-accept { background: #3b82f6; color: white; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3); }
    .btn-accept:hover { background: #2563eb; transform: translateY(-1px); }

    /* --- MODAL POPUP STYLING --- */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15, 28, 46, 0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
    .modal-content { background: white; width: 90%; max-width: 400px; border-radius: 20px; padding: 32px; text-align: center; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); animation: slideUp 0.3s ease-out; }
    .modal-icon { font-size: 56px; margin-bottom: 16px; line-height: 1; }
    .modal-title { font-size: 20px; font-weight: 900; color: #1e293b; margin: 0 0 12px 0; }
    .modal-desc { font-size: 14px; color: #64748b; line-height: 1.6; margin: 0 0 24px 0; }
    .btn-modal-selesai { background: #10b981; color: white; width: 100%; padding: 14px; border: none; border-radius: 12px; font-size: 15px; font-weight: 800; cursor: pointer; transition: background 0.2s; margin-bottom: 12px; }
    .btn-modal-selesai:hover { background: #059669; }
    .btn-modal-batal { background: transparent; color: #94a3b8; width: 100%; padding: 10px; border: none; font-size: 14px; font-weight: 600; cursor: pointer; transition: color 0.2s; }
    .btn-modal-batal:hover { color: #64748b; }

    @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    /* --- RESPONSIVE --- */
    @media (max-width: 992px) { .request-grid { grid-template-columns: 1fr; } }
    @media (max-width: 640px) { .card-actions { flex-direction: column; } .card-top { flex-direction: column; gap: 16px; } .time-badge { width: 100%; display: flex; justify-content: space-between; align-items: center; } }
</style>
@endsection

@section('content')

<!-- Notifikasi Sukses/Error (Opsional jika Anda punya komponen alert bawaan) -->
@if(session('success'))
    <div style="background: #dcfce7; color: #166534; padding: 16px; border-radius: 12px; margin-bottom: 24px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
        <span>✅</span> {{ session('success') }}
    </div>
@endif

<div class="page-header">
    <div class="header-title-group">
        <h2>Permintaan Masuk</h2>
        <p>Kelola dan konfirmasi jadwal konsultasi dari orang tua pasien</p>
    </div>
    <div class="badge-total">
        <span>🔔</span> {{ $permintaans->count() }} Menunggu Konfirmasi
    </div>
</div>

<div class="filter-bar">
    <button class="btn-filter active">Menunggu ({{ $permintaans->count() }})</button>
</div>

<div class="request-grid">

    <!-- Looping data permintaan masuk -->
    @forelse($permintaans as $req)
        <div class="req-card">
            <div class="card-top">
                <div class="patient-info">
                    <!-- Menentukan avatar berdasarkan jenis kelamin -->
                    @php
                        $avatar = (strtolower($req->jenis_kelamin_anak) == 'perempuan') ? '👧' : '👦';
                    @endphp
                    <div class="avatar">{{ $avatar }}</div>
                    <div>
                        <h3 class="patient-name">{{ $req->nama_anak }} <span style="font-weight: 500; font-size: 14px; color: #64748b;">({{ $req->umur_anak }} bln)</span></h3>
                        <p class="parent-name"><span>👤</span> Wali: {{ $req->nama_ortu }}</p>
                        <p class="wa-info"><span>📱</span> WA: {{ $req->no_wa }}</p>
                    </div>
                </div>
                <div class="time-badge">
                    <span class="time-date">{{ \Carbon\Carbon::parse($req->tanggal_jadwal)->translatedFormat('d M Y') }}</span>
                    <span class="time-hour">{{ \Carbon\Carbon::parse($req->jam_jadwal)->format('H:i') }} WIB</span>
                </div>
            </div>
            
            <div class="card-body">
                <span class="info-label">Catatan Keluhan / Tujuan:</span>
                <p class="note-box">"{{ $req->catatan ?? 'Tidak ada catatan yang dilampirkan.' }}"</p>
            </div>
            
            <!-- Tambahkan ID form spesifik untuk dipanggil via Javascript -->
            <form id="form-req-{{ $req->id }}" action="{{ route('ahligizi.permintaan.update', $req->id) }}" method="POST" style="width: 100%;">
                @csrf
                @method('PUT')
                <div class="card-actions">
                    <!-- Tombol Tolak tetap bertindak sebagai submit form biasa -->
                    <button type="submit" name="status" value="ditolak" class="btn btn-reject">Tolak</button>
                    
                    <!-- Tombol Terima diubah menjadi type="button" agar form tidak langsung dikirim -->
                    <button type="button" class="btn btn-accept" 
                        onclick="bukaWaDanModal('{{ $req->id }}', '{{ $req->no_wa }}', '{{ $req->nama_ortu }}', '{{ $req->nama_anak }}', '{{ \Carbon\Carbon::parse($req->tanggal_jadwal)->translatedFormat('d M Y') }}', '{{ \Carbon\Carbon::parse($req->jam_jadwal)->format('H:i') }}')">
                        ✅ Terima & Chat WA
                    </button>
                </div>
            </form>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 48px; background: white; border-radius: 16px; border: 1px dashed #cbd5e1;">
            <div style="font-size: 40px; margin-bottom: 12px;">☕</div>
            <h3 style="color: #1e293b; margin: 0 0 8px 0;">Belum ada permintaan masuk</h3>
            <p style="color: #64748b; margin: 0; font-size: 14px;">Saat ini tidak ada jadwal konsultasi yang perlu dikonfirmasi.</p>
        </div>
    @endforelse

</div>

<!-- MODAL POPUP KONFIRMASI WA -->
<div id="modalKonfirmasiWA" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-icon">💬</div>
        <h3 class="modal-title">Konfirmasi WhatsApp</h3>
        <p class="modal-desc">Tab WhatsApp telah terbuka di browser Anda.<br>Apakah Anda sudah selesai mengirimkan pesan konfirmasi kepada orang tua pasien?</p>
        
        <button type="button" class="btn-modal-selesai" onclick="konfirmasiSelesai()">✅ Selesai & Simpan Data</button>
        <button type="button" class="btn-modal-batal" onclick="tutupModal()">Batal</button>
    </div>
</div>

@push('scripts')
<script>
    // Variabel global untuk menyimpan ID form yang sedang diproses
    let currentFormIdToSubmit = null;

    function bukaWaDanModal(formId, noWa, namaOrtu, namaAnak, tanggal, jam) {
        // 1. Simpan ID form yang ditekan
        currentFormIdToSubmit = formId;

        // 2. Logika membuka WhatsApp
        let cleanNoWa = noWa.replace(/\D/g, '');
        if (cleanNoWa.startsWith('0')) {
            cleanNoWa = '62' + cleanNoWa.substring(1);
        }
        let textPesan = `Halo Bapak/Ibu ${namaOrtu}, saya Ahli Gizi dari GiziAnak. Mengonfirmasi jadwal konsultasi untuk ananda ${namaAnak} pada tanggal ${tanggal} pukul ${jam} WIB telah disetujui. Silakan persiapkan dokumen atau pertanyaan yang dibutuhkan sebelum sesi dimulai ya!`;
        let urlWa = `https://wa.me/${cleanNoWa}?text=${encodeURIComponent(textPesan)}`;
        
        // Buka tab WhatsApp
        window.open(urlWa, '_blank');

        // 3. Tampilkan Modal Popup di halaman ini
        document.getElementById('modalKonfirmasiWA').style.display = 'flex';
    }

    function tutupModal() {
        // Sembunyikan modal dan reset ID form
        document.getElementById('modalKonfirmasiWA').style.display = 'none';
        currentFormIdToSubmit = null;
    }

    function konfirmasiSelesai() {
        if (currentFormIdToSubmit) {
            // Ambil form berdasarkan ID yang disimpan
            let form = document.getElementById('form-req-' + currentFormIdToSubmit);
            
            // Tambahkan elemen <input> tersembunyi ke dalam form yang menyatakan status disetujui
            let inputStatus = document.createElement('input');
            inputStatus.type = 'hidden';
            inputStatus.name = 'status';
            inputStatus.value = 'disetujui';
            
            form.appendChild(inputStatus);
            
            // Tembak (submit) form tersebut ke database
            form.submit();
        }
    }
</script>
@endpush

@endsection