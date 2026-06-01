@extends('layouts.app')

@section('title', 'Atur Jam Praktik Dokter - GiziAnak')

@section('custom_css')
<style>
    /* --- HEADER HALAMAN --- */
    .page-header { margin-bottom: 24px; }
    .page-header h2 { font-size: 28px; font-weight: 900; color: #0f1c2e; margin: 0 0 8px 0; font-family: Georgia, serif; }
    .page-header p { font-size: 15px; color: #64748b; margin: 0; }

    /* --- ALERT INFO --- */
    .info-alert { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 16px 20px; margin-bottom: 24px; font-size: 13px; color: #1e3a8a; display: flex; gap: 12px; align-items: flex-start; }
    .info-icon { font-size: 20px; }
    .info-text p { margin: 0 0 4px 0; font-weight: 700; }
    .info-text span { color: #3b82f6; }

    /* --- FORM CARD --- */
    .setting-card { background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; padding: 32px; max-width: 800px; }
    
    /* --- DAY ROW --- */
    .day-row { display: flex; align-items: center; justify-content: space-between; padding: 20px 0; border-bottom: 1px solid #f1f5f9; transition: background 0.2s; }
    .day-row:last-of-type { border-bottom: none; }
    
    .day-info { display: flex; align-items: center; gap: 16px; min-width: 150px; }
    .day-name { font-size: 15px; font-weight: 800; color: #1e293b; text-transform: capitalize; }

    .time-inputs { display: flex; align-items: center; gap: 12px; }
    .time-input { padding: 10px 16px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 14px; color: #334155; font-weight: 600; outline: none; transition: border-color 0.2s; background: #f8fafc; font-family: inherit; }
    .time-input:focus { border-color: #3b82f6; background: white; }
    .time-input:disabled { background: #f1f5f9; color: #94a3b8; cursor: not-allowed; border-color: #f1f5f9; }
    .time-separator { font-size: 13px; font-weight: 600; color: #94a3b8; }

    /* --- CUSTOM TOGGLE SWITCH --- */
    .switch { position: relative; display: inline-block; width: 44px; height: 24px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #e2e8f0; transition: .3s; border-radius: 24px; }
    .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    input:checked + .slider { background-color: #10b981; } /* Hijau saat nyala */
    input:checked + .slider:before { transform: translateX(20px); }

    /* --- TOMBOL SIMPAN --- */
    .form-actions { display: flex; justify-content: flex-end; margin-top: 32px; padding-top: 24px; border-top: 1px solid #f1f5f9; }
    .btn-save { background: #3b82f6; color: white; padding: 14px 28px; border-radius: 10px; font-size: 14px; font-weight: 700; cursor: pointer; border: none; transition: all 0.2s; box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2); }
    .btn-save:hover { background: #2563eb; transform: translateY(-2px); box-shadow: 0 6px 12px rgba(59, 130, 246, 0.3); }

    /* --- POP UP TOAST NOTIFICATION --- */
    .toast-popup {
        position: fixed;
        top: 30px;
        right: 30px;
        background-color: #10b981; /* Hijau Emerald */
        color: white;
        padding: 16px 24px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 9999;
        /* Animasi masuk dari kanan, lalu menghilang setelah 3.5 detik */
        animation: slideIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards, fadeOut 0.5s ease-in forwards 3.5s;
    }
    @keyframes slideIn {
        0% { transform: translateX(120%); opacity: 0; }
        100% { transform: translateX(0); opacity: 1; }
    }
    @keyframes fadeOut {
        0% { transform: translateX(0); opacity: 1; }
        100% { transform: translateX(120%); opacity: 0; visibility: hidden; }
    }

    /* --- RESPONSIVE --- */
    @media (max-width: 640px) {
        .day-row { flex-direction: column; align-items: flex-start; gap: 16px; padding: 24px 0; }
        .time-inputs { width: 100%; justify-content: space-between; }
        .time-input { flex: 1; text-align: center; }
        .setting-card { padding: 20px; }
        .btn-save { width: 100%; }
    }
</style>
@endsection

@section('content')

<!-- POP UP NOTIFIKASI SUKSES -->
@if(session('success'))
    <div class="toast-popup">
        <span style="font-size: 20px;">✅</span>
        <span style="font-weight: 600; font-size: 14px; letter-spacing: 0.3px;">{{ session('success') }}</span>
    </div>
@endif

<div class="page-header">
    <h2>Atur Jam Praktik Medis</h2>
    <p>Tentukan hari dan jam operasional Anda untuk menerima jadwal pemeriksaan atau rujukan.</p>
</div>

<!-- INFO ALERT -->
<div class="info-alert">
    <div class="info-icon">💡</div>
    <div class="info-text">
        <p>Bagaimana ini bekerja?</p>
        <span>Pasien dan Ahli Gizi hanya dapat memilih tanggal rujukan/pemeriksaan pada jadwal aktif yang Anda tentukan di bawah ini. Hari yang dimatikan (Tutup) tidak akan bisa dipilih.</span>
    </div>
</div>

<!-- FORM PENGATURAN -->
<div class="setting-card">
    <form action="{{ route('dokter.jam.update') }}" method="POST">
        @csrf

        @php
            $daftarHari = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
        @endphp

        @foreach($daftarHari as $hari)
            @php
                // Cek data dari database (dari controller $jadwal)
                $dataHari = isset($jadwal[$hari]) ? $jadwal[$hari] : null;
                
                // Jika tidak ada di DB, setelan defaultnya Senin-Jumat nyala, Sabtu-Minggu mati
                $isActive = $dataHari ? $dataHari->is_active : !in_array($hari, ['sabtu', 'minggu']);
                
                // Jam Default
                $jamMulai = $dataHari && $dataHari->jam_mulai ? \Carbon\Carbon::parse($dataHari->jam_mulai)->format('H:i') : '08:00';
                $jamSelesai = $dataHari && $dataHari->jam_selesai ? \Carbon\Carbon::parse($dataHari->jam_selesai)->format('H:i') : '14:00';
            @endphp

            <div class="day-row">
                <div class="day-info">
                    <label class="switch">
                        <!-- Checkbox untuk mendeteksi Buka/Tutup -->
                        <input type="checkbox" name="jadwal[{{ $hari }}][is_active]" value="1" 
                               {{ $isActive ? 'checked' : '' }} 
                               onchange="toggleTimeInputs(this, '{{ $hari }}')">
                        <span class="slider"></span>
                    </label>
                    <span class="day-name">{{ $hari }}</span>
                </div>
                <div class="time-inputs" id="time-{{ $hari }}">
                    <input type="time" name="jadwal[{{ $hari }}][jam_mulai]" class="time-input" 
                           value="{{ $jamMulai }}" {{ $isActive ? '' : 'disabled' }}>
                    
                    <span class="time-separator">s/d</span>
                    
                    <input type="time" name="jadwal[{{ $hari }}][jam_selesai]" class="time-input" 
                           value="{{ $jamSelesai }}" {{ $isActive ? '' : 'disabled' }}>
                </div>
            </div>
        @endforeach

        <div class="form-actions">
            <button type="submit" class="btn-save">💾 Simpan Jam Praktik</button>
        </div>
    </form>
</div>

<!-- Script mendisable jam jika hari dimatikan -->
<script>
    function toggleTimeInputs(checkbox, day) {
        const timeDiv = document.getElementById('time-' + day);
        const inputs = timeDiv.querySelectorAll('input[type="time"]');
        
        inputs.forEach(input => {
            input.disabled = !checkbox.checked;
        });
    }
</script>

@endsection