@extends('layouts.app')

@section('title', 'Booking Jadwal - GiziAnak')

@section('custom_css')
    <style>
        /* --- CSS Tetap Sama Seperti Milik Anda --- */
        .page-header {
            margin-bottom: 32px;
        }

        .page-header h2 {
            font-size: 28px;
            font-weight: 900;
            color: #0f1c2e;
            margin: 0 0 8px 0;
            font-family: Georgia, serif;
        }

        .page-header p {
            font-size: 15px;
            color: #64748b;
            margin: 0;
        }

        .booking-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
            border: 1px solid #f1f5f9;
            padding: 32px;
            max-width: 800px;
        }

        .stepper-wrapper {
            display: flex;
            align-items: center;
            margin-bottom: 40px;
            max-width: 600px;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
        }

        .step-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .step-label {
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .step.active .step-circle {
            background-color: #3b82f6;
            color: white;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .step.active .step-label {
            color: #2563eb;
        }

        .step.completed .step-circle {
            background-color: #10b981;
            color: white;
        }

        .step.completed .step-label {
            color: #10b981;
        }

        .step.inactive .step-circle {
            background-color: #f1f5f9;
            color: #94a3b8;
        }

        .step.inactive .step-label {
            color: #94a3b8;
        }

        .step-line {
            flex-grow: 1;
            height: 2px;
            background-color: #f1f5f9;
            margin: 0 16px;
            transition: all 0.3s;
        }

        .step-line.active {
            background-color: #10b981;
        }

        .form-section {
            display: none;
            animation: fadeIn 0.4s ease;
        }

        .form-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            color: #1e293b;
            transition: all 0.2s;
            outline: none;
            font-family: inherit;
            box-sizing: border-box;
        }

        .form-input:focus {
            border-color: #3b82f6;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .input-readonly {
            background-color: #f1f5f9;
            color: #64748b;
            cursor: not-allowed;
        }

        .doctor-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .doctor-card {
            display: block;
            position: relative;
            cursor: pointer;
        }

        .doctor-card input[type="radio"] {
            display: none;
        }

        .doc-content {
            border: 2px solid #f1f5f9;
            border-radius: 16px;
            padding: 16px;
            display: flex;
            gap: 16px;
            align-items: center;
            transition: all 0.2s;
            background: white;
        }

        .doctor-card input[type="radio"]:checked+.doc-content {
            border-color: #3b82f6;
            background: #eff6ff;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }

        .doc-avatar {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .doc-info h4 {
            margin: 0 0 4px 0;
            font-size: 15px;
            font-weight: 800;
            color: #1e293b;
        }

        .doc-info p {
            margin: 0;
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
        }

        .checkmark {
            display: none;
            position: absolute;
            top: 16px;
            right: 16px;
            color: #3b82f6;
            font-size: 20px;
        }

        .doctor-card input[type="radio"]:checked~.doc-content .checkmark {
            display: block;
        }

        .time-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-top: 12px;
        }

        .time-slot {
            display: block;
            cursor: pointer;
        }

        .time-slot input[type="radio"] {
            display: none;
        }

        .time-content {
            padding: 12px;
            border: 2px solid #f1f5f9;
            border-radius: 10px;
            text-align: center;
            font-size: 14px;
            font-weight: 700;
            color: #64748b;
            transition: all 0.2s;
            background: white;
        }

        .time-slot input[type="radio"]:checked+.time-content {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .nav-buttons {
            display: flex;
            gap: 16px;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #f1f5f9;
        }

        .btn {
            padding: 14px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            border: none;
        }

        .btn-primary {
            background-color: #3b82f6;
            color: white;
            flex-grow: 1;
        }

        .btn-secondary {
            background-color: white;
            border: 1px solid #e2e8f0;
            color: #64748b;
            width: 120px;
        }

        .btn-success {
            background-color: #10b981;
            color: white;
            flex-grow: 1;
        }

        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 28, 46, 0.6);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .popup-content {
            background: white;
            padding: 32px;
            border-radius: 20px;
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        .loader-bar {
            width: 100%;
            height: 4px;
            background: #f1f5f9;
            border-radius: 4px;
            margin-top: 24px;
            overflow: hidden;
            position: relative;
        }

        .loader-fill {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            background: #3b82f6;
            animation: loading 2s linear forwards;
        }

        @keyframes loading {
            from {
                width: 0%;
            }

            to {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')

    <div class="page-header">
        <h2>Booking Jadwal Konsultasi</h2>
        <p>Isi formulir berikut untuk membuat janji konsultasi gizi secara online</p>
    </div>

    <div class="booking-card">

        <div class="stepper-wrapper">
            <div class="step active" id="indicator-1">
                <div class="step-circle">1</div>
                <span class="step-label">Data Pasien</span>
            </div>
            <div class="step-line" id="line-1"></div>

            <div class="step inactive" id="indicator-2">
                <div class="step-circle">2</div>
                <span class="step-label">Pilih Dokter</span>
            </div>
            <div class="step-line" id="line-2"></div>

            <div class="step inactive" id="indicator-3">
                <div class="step-circle">3</div>
                <span class="step-label">Tanggal & Jam</span>
            </div>
        </div>

        <form action="{{ route('orangtua.booking.store') }}" method="POST" id="bookingForm">
            @csrf
            <!-- 1. SINKRONISASI: Kategori Ahli -->
            <input type="hidden" name="kategori_ahli" value="dokter">
            <!-- 2. SINKRONISASI: Nama Orang Tua untuk tabel konsultasi_ahlis -->
            <input type="hidden" name="nama_ortu" value="{{ Auth::user()->name }}">

            <div class="form-section active" id="step-1">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">NAMA ORANG TUA</label>
                        <input type="text" class="form-input input-readonly" value="{{ Auth::user()->name }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="form-label">PILIH ANAK</label>
                        <select class="form-input" name="anak_id" required>
                            <option value="" disabled selected>-- Pilih Anak --</option>
                            @foreach ($anaks as $anak)
                                <option value="{{ $anak->id }}">👶 {{ $anak->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">No. WhatsApp Aktif</label>
                        <input type="tel" class="form-input" name="no_wa" placeholder="Contoh: 081234567890" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">KELUHAN / CATATAN (OPSIONAL)</label>
                        <textarea class="form-input" name="catatan"
                            placeholder="Tuliskan detail keluhan agar dokter dapat mempersiapkan materi konsultasi..."></textarea>
                    </div>
                </div>

                <div class="nav-buttons">
                    <button type="button" class="btn btn-primary" onclick="goToStep(2)">Pilih Dokter →</button>
                </div>
            </div>

            <div class="form-section" id="step-2">
                <h3 style="margin-top:0; font-size:16px; color:#1e293b;">Pilih Dokter / Tenaga Medis</h3>
                <p style="font-size:13px; color:#64748b; margin-bottom:24px;">Pilih Dokter yang sesuai dengan keluhan anak
                    Anda.</p>

                <div class="doctor-grid">
                    @foreach ($dokters as $dokter)
                        <label class="doctor-card">
                            <!-- 3. SINKRONISASI: Ganti dokter_id menjadi ahli_id -->
                            <input type="radio" name="ahli_id" value="{{ $dokter->id }}" required>
                            <div class="doc-content">
                                <div class="doc-avatar">👩‍⚕️</div>
                                <div class="doc-info">
                                    <h4>{{ $dokter->name }}</h4>
                                    <p>{{ $dokter->spesialisasi ?? 'Dokter Spesialis' }}</p>
                                </div>
                                <div class="checkmark">✅</div>
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="nav-buttons">
                    <button type="button" class="btn btn-secondary" onclick="goToStep(1)">← Kembali</button>
                    <button type="button" class="btn btn-primary" onclick="goToStep(3)">Pilih Tanggal & Jam →</button>
                </div>
            </div>

            <div class="form-section" id="step-3">
                <div class="form-group">
                    <!-- Pastikan ada id="input_tanggal_jadwal" -->
                    <label class="form-label">PILIH TANGGAL KONSULTASI</label>
                    <input type="date" name="tanggal_jadwal" id="input_tanggal_jadwal" class="form-input"
                        style="max-width: 300px;" min="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group" style="margin-top: 32px;">
                    <label class="form-label">PILIH JAM (SLOT TERSEDIA)</label>

                    <!-- Pesan Error / Loading akan muncul di sini -->
                    <div id="pesan-jadwal" style="font-size: 14px; color: #dc2626; margin-bottom: 10px; font-weight: 600;">
                        Silakan pilih tanggal terlebih dahulu.
                    </div>

                    <!-- Container untuk slot waktu yang akan di-generate JS -->
                    <div class="time-grid" id="container-jam-jadwal">
                        <!-- Isi jam akan dimasukkan ke sini oleh JavaScript -->
                    </div>
                </div>

                <div
                    style="background: #eff6ff; padding: 16px; border-radius: 12px; border: 1px solid #bfdbfe; margin-top: 24px; display: flex; gap: 12px; align-items: flex-start;">
                    <span style="font-size: 20px;">💡</span>
                    <p style="margin: 0; font-size: 13px; color: #1e3a5f; line-height: 1.5;">
                        Permintaan booking Anda akan dikirim ke tenaga medis terkait.
                        Setelah disetujui, Anda akan menerima pesan WhatsApp berisi tautan untuk memulai sesi konsultasi.
                    </p>
                </div>

                <div class="nav-buttons">
                    <button type="button" class="btn btn-secondary" onclick="goToStep(2)">← Kembali</button>
                    <button type="submit" class="btn btn-success" id="btn-submit-booking" disabled>✅ Konfirmasi
                        Booking</button>
                </div>
            </div>

        </form>
    </div>

    <div class="popup-overlay" id="successPopup">
        <div class="popup-content">
            <div class="popup-icon">✅</div>
            <h3 class="popup-title">Booking Terkirim!</h3>
            <p class="popup-desc">Sistem sedang menyimpan jadwal Anda. Mengalihkan ke halaman riwayat secara otomatis...
            </p>
            <div class="loader-bar">
                <div class="loader-fill"></div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // ==========================================
    // 1. LOGIKA STEPPER (MAJU/MUNDUR HALAMAN)
    // ==========================================
    function goToStep(step) {
        document.querySelectorAll('.form-section').forEach(function(el) {
            el.classList.remove('active');
        });
        document.getElementById('step-' + step).classList.add('active');
        
        for (let i = 1; i <= 3; i++) {
            let indicator = document.getElementById('indicator-' + i);
            let line = document.getElementById('line-' + i);
            indicator.classList.remove('active', 'completed', 'inactive');
            if (line) line.classList.remove('active');
            
            if (i < step) {
                indicator.classList.add('completed');
                if (line) line.classList.add('active');
            } else if (i === step) {
                indicator.classList.add('active');
            } else {
                indicator.classList.add('inactive');
            }
        }
    }

    // ==========================================
    // 2. LOGIKA AJAX (CEK JADWAL OTOMATIS)
    // ==========================================
    document.addEventListener('DOMContentLoaded', function() {
        const inputTanggal = document.getElementById('input_tanggal_jadwal');
        const containerJam = document.getElementById('container-jam-jadwal');
        const pesanJadwal = document.getElementById('pesan-jadwal');
        const btnSubmit = document.getElementById('btn-submit-booking');
        const formBooking = document.getElementById('bookingForm');
        const successPopup = document.getElementById('successPopup');

        // Fungsi utama untuk mengecek jam
        function fetchJamTersedia() {
            let tanggal = inputTanggal.value;
            
            // CARA BENAR: Mengambil value dari Radio Button Dokter yang dipilih
            let selectedAhli = document.querySelector('input[name="ahli_id"]:checked');
            let ahliId = selectedAhli ? selectedAhli.value : null;

            if (!tanggal || !ahliId) return;

            // Tampilkan status loading
            pesanJadwal.innerHTML = '🔄 Mengecek ketersediaan jadwal dokter...';
            pesanJadwal.style.color = '#2563eb';
            containerJam.innerHTML = '';
            btnSubmit.disabled = true;

            // Panggil API (Controller)
            fetch(`{{ route('cek.jam.tersedia') }}?tanggal=${tanggal}&ahli_id=${ahliId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'error') {
                        // Dokter libur
                        pesanJadwal.innerHTML = '❌ ' + data.message;
                        pesanJadwal.style.color = '#dc2626';
                    } else if (data.status === 'success') {
                        // Dokter praktik
                        pesanJadwal.innerHTML = '✅ Jadwal tersedia. Silakan pilih jam di bawah:';
                        pesanJadwal.style.color = '#16a34a';
                        
                        let htmlSlots = '';
                        if (data.slots.length === 0) {
                            pesanJadwal.innerHTML = '❌ Maaf, seluruh jam praktik sudah penuh hari ini.';
                            pesanJadwal.style.color = '#dc2626';
                            return;
                        }

                        data.slots.forEach(slot => {
                            let disabledAttr = slot.is_booked ? 'disabled' : '';
                            let opacityStyle = slot.is_booked ? 'opacity: 0.5; cursor: not-allowed;' : '';
                            let textBooked = slot.is_booked ? '<span style="font-size: 10px; display:block; margin-top:2px; color:#dc2626;">Penuh</span>' : '';

                            htmlSlots += `
                                <label class="time-slot" style="${opacityStyle}">
                                    <input type="radio" name="jam_jadwal" value="${slot.time}" required ${disabledAttr} onchange="enableSubmit()">
                                    <div class="time-content">
                                        ${slot.time}
                                        ${textBooked}
                                    </div>
                                </label>
                            `;
                        });

                        containerJam.innerHTML = htmlSlots;
                    }
                })
                .catch(error => {
                    pesanJadwal.innerHTML = '⚠️ Terjadi kesalahan saat memuat jadwal. Silakan refresh halaman.';
                    pesanJadwal.style.color = '#dc2626';
                });
        }

        // Trigger 1: Saat tanggal dipilih/diubah
        inputTanggal.addEventListener('change', fetchJamTersedia);

        // Trigger 2: Jika orang tua iseng kembali ke step 2, mengganti dokter, lalu ke step 3 lagi
        // Maka jadwal di step 3 akan otomatis direfresh sesuai dokter baru yang dipilih
        const radioDokters = document.querySelectorAll('input[name="ahli_id"]');
        radioDokters.forEach(radio => {
            radio.addEventListener('change', function() {
                if(inputTanggal.value) {
                    fetchJamTersedia();
                }
            });
        });

        // ==========================================
        // 3. LOGIKA ANIMASI POP-UP SAAT SUBMIT
        // ==========================================
        // Saya aktifkan kembali fitur pop-up keren Anda!
        formBooking.addEventListener('submit', function(e) {
            e.preventDefault(); // Tahan pengiriman form sejenak
            successPopup.style.display = 'flex'; // Munculkan popup loading animasi
            
            // Tunggu 2 detik agar animasi loading terlihat, baru kirim datanya ke Controller
            setTimeout(() => {
                this.submit(); 
            }, 2000);
        });
    });

    // Mengaktifkan tombol submit hanya jika slot jam sudah diklik
    function enableSubmit() {
        document.getElementById('btn-submit-booking').disabled = false;
    }
</script>
@endpush