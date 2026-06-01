@extends('layouts.app')

@section('title', 'Atur Jam Praktik - GiziAnak')

@section('custom_css')
<style>
    .page-header { margin-bottom: 24px; }
    .page-header h2 { font-size: 28px; font-weight: 900; color: #0f1c2e; margin: 0 0 8px 0; font-family: Georgia, serif; }
    .page-header p { font-size: 15px; color: #64748b; margin: 0; }

    .info-alert { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 16px 20px; margin-bottom: 24px; font-size: 13px; color: #1e3a8a; display: flex; gap: 12px; align-items: flex-start; }
    .info-icon { font-size: 20px; }
    .info-text p { margin: 0 0 4px 0; font-weight: 700; }
    .info-text span { color: #3b82f6; }

    .setting-card { background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; padding: 32px; max-width: 800px; position: relative; }
    
    .day-row { display: flex; align-items: center; justify-content: space-between; padding: 20px 0; border-bottom: 1px solid #f1f5f9; transition: background 0.2s; }
    .day-row:last-of-type { border-bottom: none; }
    
    .day-info { display: flex; align-items: center; gap: 16px; min-width: 150px; }
    .day-name { font-size: 15px; font-weight: 800; color: #1e293b; text-transform: capitalize; }

    .time-inputs { display: flex; align-items: center; gap: 12px; }
    .time-input { padding: 10px 16px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 14px; color: #334155; font-weight: 600; outline: none; transition: border-color 0.2s; background: #f8fafc; font-family: inherit; }
    .time-input:focus { border-color: #3b82f6; background: white; }
    .time-input:disabled { background: #f1f5f9; color: #94a3b8; cursor: not-allowed; border-color: #f1f5f9; }
    .time-separator { font-size: 13px; font-weight: 600; color: #94a3b8; }

    .switch { position: relative; display: inline-block; width: 44px; height: 24px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #e2e8f0; transition: .3s; border-radius: 24px; }
    .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    input:checked + .slider { background-color: #10b981; } 
    input:checked + .slider:before { transform: translateX(20px); }

    .form-actions { display: flex; justify-content: flex-end; margin-top: 32px; padding-top: 24px; border-top: 1px solid #f1f5f9; }
    .btn-save { background: #3b82f6; color: white; padding: 14px 28px; border-radius: 10px; font-size: 14px; font-weight: 700; cursor: pointer; border: none; transition: all 0.2s; box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2); }
    .btn-save:hover { background: #2563eb; transform: translateY(-2px); box-shadow: 0 6px 12px rgba(59, 130, 246, 0.3); }

    /* Success Popup Overlay */
    .popup-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 28, 46, 0.6); z-index: 9999; display: flex; align-items: center; justify-content: center; opacity: 0; visibility: hidden; transition: 0.3s; }
    .popup-overlay.active { opacity: 1; visibility: visible; }
    .popup-content { background: white; padding: 32px; border-radius: 20px; text-align: center; max-width: 400px; transform: scale(0.9); transition: 0.3s; }
    .popup-overlay.active .popup-content { transform: scale(1); }
    
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

<div class="page-header">
    <h2>Atur Jam Praktik</h2>
    <p>Tentukan hari dan jam operasional Anda untuk menerima jadwal konsultasi.</p>
</div>

<div class="info-alert">
    <div class="info-icon">💡</div>
    <div class="info-text">
        <p>Bagaimana ini bekerja?</p>
        <span>Orang tua pasien hanya dapat memilih tanggal dan jam konsultasi pada jadwal aktif yang Anda tentukan di bawah ini. Hari yang dimatikan (Tutup) tidak akan bisa dipilih oleh pasien.</span>
    </div>
</div>

<div class="setting-card">
    <form action="{{ route('ahligizi.jampraktik.store') }}" method="POST">
        @csrf

        @foreach($hariStandar as $hari)
            @php
                // Cek apakah hari ini sudah ada di database, jika ada ambil nilainya
                $dataHari = $jadwal->has($hari) ? $jadwal[$hari] : null;
                $isAktif = $dataHari ? $dataHari->is_aktif : false;
                
                // Set default value jika belum diatur
                $jamBuka = $dataHari && $dataHari->jam_buka ? \Carbon\Carbon::parse($dataHari->jam_buka)->format('H:i') : '09:00';
                $jamTutup = $dataHari && $dataHari->jam_tutup ? \Carbon\Carbon::parse($dataHari->jam_tutup)->format('15:00') : '15:00';
                
                // Hari Senin-Jumat nyala default jika data kosong
                if(!$dataHari && in_array($hari, ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'])) {
                    $isAktif = true;
                }
            @endphp

            <div class="day-row">
                <div class="day-info">
                    <label class="switch">
                        <input type="checkbox" name="hari[{{ $hari }}][is_aktif]" {{ $isAktif ? 'checked' : '' }} onchange="toggleTimeInputs(this, '{{ strtolower($hari) }}')">
                        <span class="slider"></span>
                    </label>
                    <span class="day-name">{{ $hari }}</span>
                </div>
                <div class="time-inputs" id="time-{{ strtolower($hari) }}">
                    <input type="time" class="time-input" name="hari[{{ $hari }}][jam_buka]" value="{{ $jamBuka }}" {{ $isAktif ? '' : 'disabled' }}>
                    <span class="time-separator">s/d</span>
                    <input type="time" class="time-input" name="hari[{{ $hari }}][jam_tutup]" value="{{ $jamTutup }}" {{ $isAktif ? '' : 'disabled' }}>
                </div>
            </div>
        @endforeach

        <div class="form-actions">
            <button type="submit" class="btn-save">💾 Simpan Jadwal Praktik</button>
        </div>
    </form>
</div>

@if(session('success'))
<div class="popup-overlay active" id="successPopup">
    <div class="popup-content">
        <div style="font-size: 50px; margin-bottom: 16px;">✅</div>
        <h3 style="font-size: 20px; font-weight: 900; color: #1e293b; margin: 0 0 8px 0;">Berhasil Disimpan!</h3>
        <p style="font-size: 14px; color: #64748b; margin: 0 0 24px 0;">{{ session('success') }}</p>
        <button class="btn-save" style="width: 100%;" onclick="document.getElementById('successPopup').classList.remove('active')">Oke</button>
    </div>
</div>
@endif

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