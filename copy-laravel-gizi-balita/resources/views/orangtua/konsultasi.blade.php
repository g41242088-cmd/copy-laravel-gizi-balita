@extends('layouts.app')

@section('title', 'Konsultasi Ahli - GiziAnak')

@section('custom_css')
<style>
    /* --- HERO BANNER --- */
    .hero-banner { background: linear-gradient(135deg, #0f1c2e 0%, #1e3a5f 100%); border-radius: 20px; padding: 40px 48px; color: white; margin-bottom: 24px; box-shadow: 0 10px 25px -5px rgba(15, 28, 46, 0.2); }
    .hero-badge { display: inline-flex; align-items: center; gap: 6px; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); padding: 6px 16px; border-radius: 20px; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 20px; }
    .hero-title { font-size: 36px; font-weight: 900; line-height: 1.2; margin: 0 0 12px 0; font-family: Georgia, serif; }
    .hero-title-highlight { color: #fbbf24; font-style: italic; }
    .hero-subtitle { font-size: 14px; color: #94a3b8; max-width: 600px; line-height: 1.6; margin: 0; }

    /* --- FILTERS & SEARCH --- */
    .filter-chips { display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
    .chip { padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; cursor: pointer; border: 1px solid #e2e8f0; background: white; color: #64748b; display: flex; align-items: center; gap: 6px; transition: all 0.2s; }
    .chip:hover { background: #f8fafc; }
    .chip.active { background: #3b82f6; color: white; border-color: #3b82f6; }

    .search-row { display: flex; gap: 16px; margin-bottom: 32px; flex-wrap: wrap; align-items: center; }
    .search-wrapper { flex: 1; min-width: 280px; position: relative; } 
    .search-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px; }
    .search-input { width: 100%; padding: 12px 16px 12px 42px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 13px; outline: none; color: #334155; box-sizing: border-box; } 
    
    .day-select { padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 13px; font-weight: 700; color: #3b82f6; background: #eff6ff; outline: none; cursor: pointer; transition: 0.2s; white-space: nowrap; flex-shrink: 0; } 
    .day-select:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }

    .section-label { font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }

    /* --- EXPERT CARDS GRID --- */
    .expert-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px; }
    .expert-card { background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; border-top: 4px solid #10b981; padding: 20px; display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s; }
    .expert-card:hover { transform: translateY(-4px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); }
    .border-purple { border-top-color: #a855f7; }

    .card-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; }
    .avatar { width: 48px; height: 48px; background: #eff6ff; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
    .status-badge { font-size: 10px; font-weight: 700; padding: 4px 8px; border-radius: 12px; display: flex; align-items: center; gap: 4px; background: #dcfce7; color: #16a34a; }
    .status-badge::before { content: ''; display: block; width: 6px; height: 6px; border-radius: 50%; background: #16a34a; }

    .expert-name { font-size: 15px; font-weight: 800; color: #1e293b; margin: 0 0 4px 0; line-height: 1.3; text-transform: capitalize; }
    .expert-specialty { font-size: 12px; font-weight: 600; color: #3b82f6; margin: 0 0 12px 0; }

    .tags-container { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 16px; flex-grow: 1; }
    .tag { background: #f1f5f9; color: #64748b; font-size: 11px; font-weight: 600; padding: 4px 8px; border-radius: 6px; }

    .card-footer { display: flex; flex-direction: column; gap: 12px; padding-top: 16px; border-top: 1px dashed #e2e8f0; }
    .time-info { font-size: 13px; font-weight: 800; color: #ea580c; display: flex; align-items: center; gap: 6px; background: #fff7ed; padding: 6px 10px; border-radius: 8px; justify-content: center;}
    
    .btn-booking { background: #3b82f6; color: white; font-size: 13px; font-weight: 700; padding: 10px; border-radius: 8px; border: none; cursor: pointer; transition: background 0.2s; width: 100%; }
    .btn-booking:hover { background: #2563eb; }

    /* --- MODAL BOOKING STYLES --- */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 28, 46, 0.6); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; z-index: 1000; opacity: 0; visibility: hidden; transition: all 0.3s ease; }
    .modal-overlay.active { opacity: 1; visibility: visible; }
    .modal-box { background: white; width: 100%; max-width: 550px; border-radius: 20px; padding: 32px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); transform: translateY(20px); transition: all 0.3s ease; max-height: 90vh; overflow-y: auto; }
    .modal-overlay.active .modal-box { transform: translateY(0); }

    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .modal-title { font-size: 20px; font-weight: 800; color: #1e293b; margin: 0; }
    .btn-close { background: none; border: none; font-size: 24px; color: #94a3b8; cursor: pointer; line-height: 1; padding: 0; }
    .btn-close:hover { color: #ef4444; }

    .expert-target-info { background: #eff6ff; border: 1px solid #bfdbfe; padding: 12px 16px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; }
    .expert-target-name { font-size: 14px; font-weight: 800; color: #1e293b; margin: 0; text-transform: capitalize;}
    .expert-target-desc { font-size: 12px; color: #3b82f6; margin: 0; }

    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-size: 12px; font-weight: 800; color: #475569; margin-bottom: 6px; }
    .form-input, .form-select, .form-textarea { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 13px; font-family: inherit; color: #1e293b; background: #f8fafc; box-sizing: border-box; outline: none; }
    .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: #3b82f6; background: white; }
    .form-textarea { resize: vertical; min-height: 80px; }

    /* CSS tambahan untuk pilihan Jam di Modal */
    .time-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 8px; }
    .time-slot { display: block; cursor: pointer; }
    .time-slot input[type="radio"] { display: none; }
    .time-content { padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; text-align: center; font-size: 13px; font-weight: 700; color: #64748b; background: white; transition: 0.2s; }
    .time-slot input[type="radio"]:checked+.time-content { background: #3b82f6; color: white; border-color: #3b82f6; }

    .btn-submit-booking { width: 100%; background: #10b981; color: white; padding: 14px; border-radius: 12px; font-size: 14px; font-weight: 800; border: none; cursor: pointer; transition: all 0.2s; margin-top: 8px; }
    .btn-submit-booking:hover { background: #059669; }
    .btn-submit-booking:disabled { background: #94a3b8; cursor: not-allowed; }
    .modal-note { font-size: 11px; color: #64748b; text-align: center; margin-top: 12px; display: block; }

    @media (max-width: 1400px) { .expert-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 1024px) { .expert-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px) { .expert-grid { grid-template-columns: 1fr; } .hero-banner { padding: 32px 24px; } .modal-box { margin: 16px; padding: 24px; } }
</style>
@endsection

@section('content')

<div class="hero-banner">
    <div class="hero-badge">🧪 TIM AHLI TERPERCAYA</div>
    <h1 class="hero-title">
        Booking Konsultasi <br>dengan <span class="hero-title-highlight">Ahli Gizi Anak</span>
    </h1>
    <p class="hero-subtitle">
        Pilih hari praktik untuk melihat jadwal tenaga ahli yang tersedia. Setelah melakukan booking, sesi konsultasi seputar tumbuh kembang anak akan dilakukan langsung melalui WhatsApp.
    </p>
</div>

<div class="search-row">
    <div class="search-wrapper">
        <span class="search-icon">🔍</span>
        <input type="text" id="searchInput" class="search-input" placeholder="Cari nama atau spesialisasi ahli gizi...">
    </div>

    <select id="dayFilter" class="day-select">
        <option value="Semua">📅 Pilih Hari Praktik (Semua)</option>
        <option value="Senin">Praktik Hari Senin</option>
        <option value="Selasa">Praktik Hari Selasa</option>
        <option value="Rabu">Praktik Hari Rabu</option>
        <option value="Kamis">Praktik Hari Kamis</option>
        <option value="Jumat">Praktik Hari Jumat</option>
        <option value="Sabtu">Praktik Hari Sabtu</option>
        <option value="Minggu">Praktik Hari Minggu</option>
    </select>
</div>

<div class="section-label"><span>👤</span> DAFTAR AHLI GIZI</div>

<div class="expert-grid" id="expertGrid">
    @forelse($dokters as $ahli)
        @php
            $jadwalExpert = $ahli->jamPraktiks->keyBy('hari');
        @endphp

    <div class="expert-card expert-item" 
         data-name="{{ strtolower($ahli->name) }}" 
         data-specialty="{{ strtolower($ahli->spesialisasi ?? '') }}"
         data-schedule="{{ json_encode($jadwalExpert) }}">
        
        <div class="card-header">
            <div class="avatar">🥗</div>
            <div class="status-badge">Tersedia</div>
        </div>
        <h3 class="expert-name">{{ $ahli->name }}</h3>
        <p class="expert-specialty">{{ $ahli->spesialisasi ?? 'Ahli Gizi Anak' }}</p>
        
        <div class="tags-container">
            <span class="tag">Konsultasi WA</span>
            <span class="tag">Gizi Umum</span>
        </div>
        
        <div class="card-footer" style="margin-top: auto;">
            <div class="time-info time-display">🕒 Pilih hari di atas</div>
            <button class="btn-booking" data-id="{{ $ahli->id }}" data-name="{{ $ahli->name }}">Booking Konsultasi</button>
        </div>
    </div>
    @empty
    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #64748b; background: white; border-radius: 16px; border: 1px solid #e2e8f0;">
        <span style="font-size: 32px; display: block; margin-bottom: 12px;">🥗</span>
        Belum ada Ahli Gizi yang terdaftar.
    </div>
    @endforelse
    
    <div id="noExpertFound" style="display:none; grid-column: 1 / -1; text-align: center; padding: 40px; color: #64748b; background: white; border-radius: 16px; border: 1px dashed #cbd5e1;">
        <span style="font-size: 32px; display: block; margin-bottom: 12px;">🗓️</span>
        Tidak ada Ahli Gizi yang sesuai pencarian atau praktik pada hari tersebut.
    </div>
</div>

<!-- MODAL BOOKING YANG SUDAH DIPERBAIKI -->
<div class="modal-overlay" id="bookingModal">
    <div class="modal-box">
        <div class="modal-header">
            <h2 class="modal-title">Pengajuan Konsultasi</h2>
            <button class="btn-close" id="closeModal">&times;</button>
        </div>

        <div class="expert-target-info">
            <div style="font-size: 24px;">🥗</div>
            <div>
                <p class="expert-target-desc">Mengajukan konsultasi kepada:</p>
                <p class="expert-target-name" id="targetExpertName">Memuat...</p> 
            </div>
        </div>

        <form action="{{ route('orangtua.konsultasi.store') }}" method="POST" id="bookingForm">
            @csrf
            <!-- Input Hidden ID Ahli Gizi -->
            <input type="hidden" name="ahli_id" id="ahliGiziId">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Nama Orang Tua</label>
                    <input type="text" class="form-input input-readonly" value="{{ Auth::user()->name }}" readonly>
                </div>
                
                <!-- Menggunakan Dropdown Anak agar ID tersimpan di database -->
                <div class="form-group">
                    <label class="form-label">Pilih Anak</label>
                    <select class="form-select" name="anak_id" required>
                        <option value="" disabled selected>-- Pilih Anak --</option>
                        @foreach ($anaks as $anak)
                            <option value="{{ $anak->id }}">{{ $anak->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Tambahan Tanggal dan Jam untuk validasi Controller -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Tanggal Konsultasi</label>
                    <input type="date" name="tanggal_jadwal" id="inputTanggal" class="form-input" min="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">No. WhatsApp</label>
                    <input type="tel" class="form-input" name="no_wa" placeholder="08xxxxxxxxxx" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Pilih Jam (Slot Tersedia)</label>
                <div id="pesan-jadwal" style="font-size: 12px; color: #dc2626; margin-bottom: 8px;">Pilih tanggal terlebih dahulu untuk melihat jam.</div>
                <div class="time-grid" id="containerJam">
                    <!-- Jam akan muncul disini via AJAX -->
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan Gizi (Opsional)</label>
                <textarea class="form-textarea" name="catatan" placeholder="Ceritakan singkat keluhan gizi anak..."></textarea>
            </div>

            <button type="submit" class="btn-submit-booking" id="btnSubmitBooking" disabled>Kirim Permintaan Booking</button>
            <span class="modal-note">Status booking akan menjadi "Menunggu Konfirmasi". Ahli Gizi akan membalas via WA.</span>
        </form>
    </div>
</div>

@if(session('success'))
<div class="modal-overlay active" id="successPopup" style="z-index: 1001;">
    <div class="modal-box" style="text-align: center; max-width: 400px; padding: 40px;">
        <div style="font-size: 50px; margin-bottom: 16px;">✅</div>
        <h3 style="font-size: 20px; font-weight: 900; color: #1e293b; margin: 0 0 8px 0;">Pengajuan Berhasil!</h3>
        <p style="font-size: 14px; color: #64748b; margin: 0 0 24px 0; line-height: 1.5;">{{ session('success') }}</p>
        <button class="btn-booking" style="width: 100%; padding: 12px; font-size: 14px;" onclick="document.getElementById('successPopup').classList.remove('active')">Oke, Saya Mengerti</button>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- LOGIKA FILTER HARI & PENCARIAN (Bawaan Asli Desain Anda) ---
        const searchInput = document.getElementById('searchInput');
        const dayFilter = document.getElementById('dayFilter');
        const expertCards = document.querySelectorAll('.expert-item');
        const noExpertFound = document.getElementById('noExpertFound');

        function applyFilters() {
            const query = searchInput.value.toLowerCase();
            const selectedDay = dayFilter.value;
            let visibleCount = 0;

            expertCards.forEach(card => {
                const name = card.getAttribute('data-name');
                const spec = card.getAttribute('data-specialty');
                const scheduleDataRaw = card.getAttribute('data-schedule');
                
                let schedules = {};
                try { schedules = JSON.parse(scheduleDataRaw); } catch(e) {}

                const matchesSearch = name.includes(query) || spec.includes(query);
                let matchesDay = false;
                let displayTimeText = '🕒 Pilih hari praktik';

                if (selectedDay === 'Semua') {
                    matchesDay = true;
                } else {
                    if (schedules[selectedDay] && (schedules[selectedDay].is_aktif == 1 || schedules[selectedDay].is_aktif === true)) {
                        matchesDay = true;
                        const jamBuka = schedules[selectedDay].jam_buka.substring(0, 5);
                        const jamTutup = schedules[selectedDay].jam_tutup.substring(0, 5);
                        displayTimeText = `🕒 ${jamBuka} - ${jamTutup} WIB`;
                    } else {
                        matchesDay = false;
                    }
                }

                if (matchesSearch && matchesDay) {
                    card.style.display = '';
                    card.querySelector('.time-display').innerText = displayTimeText;
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            noExpertFound.style.display = visibleCount === 0 ? 'block' : 'none';
        }

        searchInput.addEventListener('input', applyFilters);
        dayFilter.addEventListener('change', applyFilters);


        // --- LOGIKA MODAL & AJAX CEK JAM ---
        const modal = document.getElementById('bookingModal');
        const closeModalBtn = document.getElementById('closeModal');
        const bookingButtons = document.querySelectorAll('.btn-booking[data-id]');
        const targetExpertNameLabel = document.getElementById('targetExpertName');
        const inputAhliId = document.getElementById('ahliGiziId');
        
        const inputTanggal = document.getElementById('inputTanggal');
        const containerJam = document.getElementById('containerJam');
        const pesanJadwal = document.getElementById('pesan-jadwal');
        const btnSubmit = document.getElementById('btnSubmitBooking');

        // Buka Modal
        bookingButtons.forEach(button => {
            button.addEventListener('click', function() {
                targetExpertNameLabel.textContent = this.getAttribute('data-name');
                inputAhliId.value = this.getAttribute('data-id'); 
                
                // Reset form saat modal dibuka
                inputTanggal.value = '';
                containerJam.innerHTML = '';
                pesanJadwal.innerHTML = 'Pilih tanggal terlebih dahulu untuk melihat jam.';
                pesanJadwal.style.color = '#dc2626';
                btnSubmit.disabled = true;

                modal.classList.add('active');
            });
        });

        // Tutup Modal
        closeModalBtn.addEventListener('click', function() { modal.classList.remove('active'); });
        modal.addEventListener('click', function(e) { if (e.target === modal) modal.classList.remove('active'); });

        // Fungsi Ambil Jam via AJAX
        inputTanggal.addEventListener('change', function() {
            let tanggal = this.value;
            let ahliId = inputAhliId.value;

            if (!tanggal || !ahliId) return;

            pesanJadwal.innerHTML = '🔄 Mencari jadwal...';
            pesanJadwal.style.color = '#3b82f6';
            containerJam.innerHTML = '';
            btnSubmit.disabled = true;

            fetch(`{{ route('cek.jam.tersedia') }}?tanggal=${tanggal}&ahli_id=${ahliId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'error') {
                        pesanJadwal.innerHTML = '❌ ' + data.message;
                        pesanJadwal.style.color = '#dc2626';
                    } else if (data.status === 'success') {
                        pesanJadwal.innerHTML = '✅ Silakan pilih jam di bawah:';
                        pesanJadwal.style.color = '#10b981';
                        
                        let htmlSlots = '';
                        if (data.slots.length === 0) {
                            pesanJadwal.innerHTML = '❌ Jam praktik penuh.';
                            pesanJadwal.style.color = '#dc2626';
                            return;
                        }

                        data.slots.forEach(slot => {
                            let disabledAttr = slot.is_booked ? 'disabled' : '';
                            let opacityStyle = slot.is_booked ? 'opacity: 0.5; cursor: not-allowed;' : '';
                            
                            htmlSlots += `
                                <label class="time-slot" style="${opacityStyle}">
                                    <input type="radio" name="jam_jadwal" value="${slot.time}" required ${disabledAttr} onchange="document.getElementById('btnSubmitBooking').disabled = false;">
                                    <div class="time-content">${slot.time}</div>
                                </label>
                            `;
                        });

                        containerJam.innerHTML = htmlSlots;
                    }
                })
                .catch(error => {
                    pesanJadwal.innerHTML = '⚠️ Terjadi kesalahan. Silakan coba lagi.';
                    pesanJadwal.style.color = '#dc2626';
                });
        });
    });
</script>
@endpush