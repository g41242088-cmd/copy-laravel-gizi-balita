@extends('layouts.app')

@section('title', 'Permintaan Konsultasi - GiziAnak')

@section('custom_css')
    <style>
        .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; flex-wrap: wrap; gap: 16px; }
        .header-title-group h2 { margin: 0 0 8px 0; font-size: 28px; font-weight: 900; color: #0f1c2e; font-family: Georgia, serif; }
        .header-title-group p { margin: 0; color: #64748b; font-size: 15px; }
        .badge-total { padding: 8px 16px; border: 1px solid #e2e8f0; border-radius: 20px; font-size: 14px; font-weight: 700; color: #3b82f6; background: #eff6ff; display: flex; align-items: center; gap: 8px; }
        .filter-bar { display: flex; gap: 12px; margin-bottom: 24px; }
        .btn-filter { padding: 10px 20px; border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer; border: 1px solid #e2e8f0; background: white; color: #64748b; transition: all 0.2s; }
        .btn-filter.active { background: #3b82f6; color: white; border-color: #3b82f6; }
        .request-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }
        .req-card { background: white; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; padding: 24px; display: flex; flex-direction: column; transition: transform 0.2s; position: relative; }
        .req-card:hover { transform: translateY(-4px); box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.05); }
        .card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px dashed #e2e8f0; }
        .patient-info { display: flex; align-items: center; gap: 16px; }
        .avatar { width: 48px; height: 48px; border-radius: 12px; background: #f8fafc; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 24px; }
        .patient-name { font-size: 16px; font-weight: 800; color: #1e293b; margin: 0 0 4px 0; }
        .parent-name { font-size: 12px; color: #64748b; margin: 0; display: flex; align-items: center; gap: 4px; }
        .wa-info { font-size: 12px; font-weight: 700; color: #10b981; margin: 4px 0 0 0; display: flex; align-items: center; gap: 4px; }
        .time-badge { background: #f8fafc; border: 1px solid #e2e8f0; color: #1e293b; padding: 8px 12px; border-radius: 10px; text-align: center; }
        .time-date { font-size: 12px; font-weight: 800; display: block; margin-bottom: 2px; }
        .time-hour { font-size: 11px; font-weight: 600; color: #64748b; }
        .card-body { flex-grow: 1; margin-bottom: 24px; }
        .info-label { font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; display: block; }
        .note-box { background: #f8fafc; border-left: 3px solid #3b82f6; padding: 12px 16px; border-radius: 0 8px 8px 0; font-size: 13px; color: #475569; font-style: italic; line-height: 1.5; margin: 0; }
        .card-actions { display: flex; gap: 12px; }
        .btn { flex: 1; padding: 12px; border-radius: 10px; font-size: 14px; font-weight: 700; cursor: pointer; transition: all 0.2s; border: none; text-align: center; }
        .btn-reject { background: white; color: #dc2626; border: 1px solid #fecaca; }
        .btn-reject:hover { background: #fef2f2; }
        .btn-accept { background: #10b981; color: white; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3); }
        .btn-accept:hover { background: #059669; transform: translateY(-1px); }

        /* Modal Overlay Style */
        #modalKonfirmasi {
            display: none; 
            position: fixed; 
            inset: 0; 
            background: rgba(15, 28, 46, 0.7); 
            z-index: 9999; 
            align-items: center; 
            justify-content: center; 
            backdrop-filter: blur(4px);
        }

        @media (max-width: 992px) { .request-grid { grid-template-columns: 1fr; } }
        @media (max-width: 640px) {
            .card-actions { flex-direction: column; }
            .card-top { flex-direction: column; gap: 16px; }
            .time-badge { width: 100%; display: flex; justify-content: space-between; align-items: center; }
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="header-title-group">
            <h2>Permintaan Konsultasi</h2>
            <p>Kelola daftar pengajuan booking dari orang tua pasien.</p>
        </div>
        <div class="badge-total">
            <span>📅</span> {{ $permintaans->count() }} Permintaan Baru
        </div>
    </div>

    <div class="filter-bar">
        <button class="btn-filter active">Menunggu Konfirmasi ({{ $permintaans->count() }})</button>
    </div>

    <div class="request-grid">
        @forelse($permintaans as $req)
            <div class="req-card">
                <div class="card-top">
                    <div class="patient-info">
                        <div class="avatar">{{ $req->anak->jenis_kelamin == 'L' ? '👦' : '👧' }}</div>
                        <div>
                            <h3 class="patient-name">
                                {{ $req->anak->nama }}
                                <span style="font-weight: 500; font-size: 14px; color: #64748b;">
                                    ({{ \Carbon\Carbon::parse($req->anak->tanggal_lahir)->diffInMonths(now()) }} bln)
                                </span>
                            </h3>
                            <p class="parent-name"><span>👤</span> Wali: {{ $req->orangtua->name }}</p>
                            <p class="wa-info"><span>📱</span> WA: {{ $req->no_wa }}</p>
                        </div>
                    </div>
                    <div class="time-badge">
                        <span class="time-date">{{ \Carbon\Carbon::parse($req->created_at)->translatedFormat('d M Y') }}</span>
                        <span class="time-hour">{{ \Carbon\Carbon::parse($req->created_at)->format('H:i') }} WIB</span>
                    </div>
                </div>
                <div class="card-body">
                    <span class="info-label">Catatan Keluhan / Tujuan:</span>
                    <p class="note-box">"{{ $req->catatan ?? 'Tidak ada catatan tambahan.' }}"</p>
                </div>
                <div class="card-actions">
                    <!-- Form Tolak Jadwal -->
                    <form action="{{ route('dokter.permintaan.update', $req->id) }}" method="POST" style="flex: 1;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="ditolak">
                        <button type="submit" class="btn btn-reject" style="width: 100%;"
                            onclick="return confirm('Tolak jadwal ini?')">Tolak Jadwal</button>
                    </form>

                    <!-- Tombol Terima & Hubungi -->
                    <button type="button" class="btn btn-accept" style="flex: 1;"
                        onclick="terimaDanHubungi('{{ $req->no_wa }}', '{{ $req->id }}', '{{ $req->anak->nama }}')">
                        ✅ Terima & Hubungi
                    </button>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 64px 24px; background: white; border-radius: 16px; border: 2px dashed #e2e8f0;">
                <span style="font-size: 48px; display: block; margin-bottom: 16px;">☕</span>
                <h3 style="color: #1e293b; margin: 0 0 8px 0;">Belum Ada Permintaan</h3>
                <p style="color: #64748b; margin: 0;">Anda belum memiliki jadwal baru yang perlu dikonfirmasi saat ini.</p>
            </div>
        @endforelse
    </div>

    <!-- MODAL POPUP -->
    <div id="modalKonfirmasi">
        <div style="background: white; padding: 32px; border-radius: 20px; max-width: 400px; width: 90%; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
            <div style="font-size: 48px; margin-bottom: 16px;">✅</div>
            <h3 style="margin: 0 0 8px 0; font-weight: 800; color: #1e293b; font-family: Georgia, serif;">Selesaikan Sesi?</h3>
            <p style="color: #64748b; font-size: 14px; margin-bottom: 24px;">Jika Anda sudah menghubungi orang tua dan menyelesaikan konsultasi, klik tombol di bawah untuk memindahkan pasien ke daftar riwayat.</p>

            <form id="formSelesai" action="" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="selesai">
                <button type="submit" class="btn btn-accept" style="width: 100%; padding: 14px; border-radius: 12px; margin-bottom: 12px; border: none; cursor: pointer;">
                    Tandai Sudah Selesai
                </button>
            </form>

            <button onclick="tutupModal()" style="background: none; border: none; color: #94a3b8; font-weight: 700; cursor: pointer; font-size: 13px;">Kembali</button>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function terimaDanHubungi(phone, id, namaAnak) {
        // 1. Format nomor WA agar standar internasional
        let cleanPhone = phone.replace(/\D/g, '');
        if (cleanPhone.startsWith('0')) {
            cleanPhone = '62' + cleanPhone.slice(1);
        }

        // 2. Pesan Otomatis
        let text = encodeURIComponent(`Halo, saya Dokter dari GiziAnak. Saya ingin mengonfirmasi jadwal konsultasi untuk anak ${namaAnak}.`);

        // 3. Buka WhatsApp
        window.open(`https://wa.me/${cleanPhone}?text=${text}`, '_blank');

        // 4. Munculkan Modal Selesai
        const modal = document.getElementById('modalKonfirmasi');
        const form = document.getElementById('formSelesai');
        
        // Update URL form secara dinamis
        form.action = `/dokter/permintaan/${id}/update-status`;
        
        modal.style.display = 'flex';
    }

    function tutupModal() {
        document.getElementById('modalKonfirmasi').style.display = 'none';
    }
</script>
@endpush