@extends('layouts.app')

@section('title', 'Catatan Medis Pasien - GiziAnak')

@section('custom_css')
    <style>
        /* --- CSS SAMA PERSIS DENGAN MILIK ANDA --- */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .header-title-group h2 {
            margin: 0 0 8px 0;
            font-size: 28px;
            font-weight: 900;
            color: #0f1c2e;
            font-family: Georgia, serif;
        }

        .header-title-group p {
            margin: 0;
            color: #64748b;
            font-size: 15px;
        }

        .btn-back {
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            color: #64748b;
            background: white;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-back:hover {
            background: #f8fafc;
            color: #1e293b;
        }

        .medis-grid {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 24px;
            align-items: start;
        }

        .patient-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            border: 1px solid #f1f5f9;
            padding: 24px;
            position: sticky;
            top: 24px;
        }

        .p-profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 24px;
            padding-bottom: 24px;
            border-bottom: 1px dashed #e2e8f0;
        }

        .p-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin-bottom: 16px;
        }

        .p-name {
            font-size: 22px;
            font-weight: 900;
            color: #1e293b;
            margin: 0 0 4px 0;
        }

        .p-detail {
            font-size: 14px;
            color: #64748b;
            margin: 0 0 8px 0;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            background: #e0f2fe;
            color: #0284c7;
            border: 1px solid #bae6fd;
        }

        .info-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .info-label {
            font-size: 11px;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-val {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }

        .form-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            border: 1px solid #f1f5f9;
            padding: 32px;
        }

        .form-section-title {
            font-size: 16px;
            font-weight: 800;
            color: #1e293b;
            margin: 0 0 20px 0;
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            box-sizing: border-box; /* TAMBAHKAN BARIS INI */
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            color: #334155;
            outline: none;
            transition: border-color 0.2s;
            background: #f8fafc;
            font-family: inherit;
        }

        .form-input:focus {
            border-color: #3b82f6;
            background: white;
        }

        textarea.form-input {
            resize: vertical;
            min-height: 100px;
        }

        .btn-submit {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            width: 100%;
            padding: 16px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            background: #3b82f6;
            color: white;
            transition: all 0.2s;
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
            margin-top: 32px;
        }

        .btn-submit:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(59, 130, 246, 0.3);
        }

        .alert-rujukan {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .alert-icon {
            font-size: 20px;
        }

        .alert-text p {
            margin: 0 0 4px 0;
            font-size: 13px;
            font-weight: 700;
            color: #92400e;
        }

        .alert-text span {
            font-size: 13px;
            color: #b45309;
        }

        @media (max-width: 1024px) {
            .medis-grid {
                grid-template-columns: 1fr;
            }

            .patient-card {
                position: static;
            }
        }

        @media (max-width: 640px) {
            .form-grid-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')

    <div class="page-header">
        <div class="header-title-group">
            <h2>Catatan Medis (SOAP)</h2>
            <p>Rekam diagnosis klinis, imunisasi, dan resep pengobatan pasien.</p>
        </div>
        <a href="{{ route('dokter.pasien.index') }}" class="btn-back">◀ Kembali ke Daftar Pasien</a>
    </div>

    <!-- Menampilkan Pesan Sukses -->
    @if (session('success'))
        <div
            style="background: #dcfce7; color: #16a34a; padding: 16px; border-radius: 12px; margin-bottom: 24px; font-weight: bold;">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="medis-grid">

        <!-- KOLOM KIRI (INFO PASIEN DINAMIS) -->
        <div class="patient-card">
                <div class="p-profile">
                    <div class="p-avatar">
                        {{ $pasien->jenis_kelamin_anak == 'L' ? '👦' : '👧' }}
                    </div>
                    <h3 class="p-name">{{ $pasien->nama_anak }}</h3>
                    <p class="p-detail">
                        {{ $pasien->jenis_kelamin_anak == 'L' ? 'Laki-laki' : 'Perempuan' }} • ({{ round(\Carbon\Carbon::parse($pasien->anak->tanggal_lahir)->diffInMonths(now())) }} Bulan)
                        
                    </p>
                    <span class="status-badge">📅 Konsultasi:
                        {{ \Carbon\Carbon::parse($pasien->tanggal_jadwal)->format('d M Y') }}</span>
                </div>

                <div class="info-list">
                    <div class="info-item">
                        <span class="info-label">Nama Wali / Orang Tua</span>
                        <span class="info-val">{{ $pasien->nama_ortu }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tinggi / Berat Badan Terakhir</span>

                        @php
                            // Mengambil data cek gizi (pengukuran) yang paling terakhir/terbaru
                            $cekGiziTerbaru = $pasien->anak->pengukurans()->latest()->first();
                        @endphp

                        <span class="info-val">
                            {{ $cekGiziTerbaru?->tinggi_badan ?? '-' }} cm / {{ $cekGiziTerbaru?->berat_badan ?? '-' }} kg
                        </span>

                        <!-- (Opsional) Tambahkan keterangan kapan terakhir kali diukur -->
                        @if ($cekGiziTerbaru)
                            <span style="font-size: 11px; color: #94a3b8; margin-top: 2px;">
                                Update: {{ $cekGiziTerbaru->created_at->format('d M Y') }}
                            </span>
                        @endif
                    </div>
                </div>
                
                <!-- TAMBAHKAN TOMBOL PDF DI SINI -->
                <div class="info-item" style="margin-top: 8px; border-top: 1px dashed #e2e8f0; padding-top: 16px;">
                    <a href="{{ route('dokter.catatan.pdf', $pasien->id) }}" target="_blank"
                        style="display: inline-flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 700; color: #dc2626; text-decoration: none; background: #fee2e2; padding: 10px 16px; border-radius: 8px; width: fit-content; transition: all 0.2s;">
                        📄 Download PDF Catatan
                    </a>
                </div>
                <!-- ============================ -->
            </div>

        <!-- KOLOM KANAN (FORM REKAM MEDIS) -->
        <div>
            <!-- Alert Info Keluhan -->
            @if ($pasien->catatan)
                <div class="alert-rujukan">
                    <div class="alert-icon">⚠️</div>
                    <div class="alert-text">
                        <p>Keluhan Awal Pasien / Orang Tua:</p>
                        <span>"{{ $pasien->catatan }}"</span>
                    </div>
                </div>
            @endif

            <!-- Form SOAP -->
            <div class="form-card">
                <!-- Arahkan ke rute store dengan ID pasien -->
                <form action="{{ route('dokter.catatan.store', $pasien->id) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- Gunakan PUT karena kita mengupdate data konsultasi yang sudah ada -->

                    <h3 class="form-section-title"><span>🩺</span> S O A P (Pemeriksaan Medis)</h3>

                    <div class="form-group">
                        <label class="form-label">S - Subjective (Keluhan Utama / Anamnesis)</label>
                        <textarea class="form-input" name="subjective" placeholder="Tuliskan keluhan yang disampaikan pasien atau orang tua...">{{ old('subjective', $pasien->subjective) }}</textarea>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Suhu Tubuh (°C)</label>
                            <input type="text" class="form-input" name="suhu"
                                value="{{ old('suhu', $pasien->suhu) }}" placeholder="Contoh: 36.5">
                        </div>
                        <div class="form-group">
                            <label class="form-label">O - Objective (Hasil Pemeriksaan Fisik)</label>
                            <input type="text" class="form-input" name="objective"
                                value="{{ old('objective', $pasien->objective) }}"
                                placeholder="Contoh: Perut sedikit membesar, pucat">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">A - Assessment (Diagnosis)</label>
                        <input type="text" class="form-input" name="assessment"
                            value="{{ old('assessment', $pasien->assessment) }}"
                            placeholder="Tuliskan diagnosis ICD atau nama penyakit...">
                    </div>

                    <div class="form-group">
                        <label class="form-label">P - Plan (Tindakan / Resep Obat)</label>
                        <textarea class="form-input" name="plan"
                            placeholder="Tuliskan resep obat, jadwal kontrol, atau tindakan medis selanjutnya...">{{ old('plan', $pasien->plan) }}</textarea>
                    </div>

                    <h3 class="form-section-title" style="margin-top: 40px;"><span>💉</span> Riwayat Imunisasi Tambahan</h3>

                    <div class="form-group">
                        <label class="form-label">Vaksin yang Diberikan Hari Ini (Opsional)</label>
                        <select class="form-input" name="vaksin">
                            <option value="">-- Pilih Jika Ada Vaksinasi --</option>
                            <option value="polio" {{ $pasien->vaksin == 'polio' ? 'selected' : '' }}>Polio</option>
                            <option value="campak" {{ $pasien->vaksin == 'campak' ? 'selected' : '' }}>Campak</option>
                            <option value="dpt" {{ $pasien->vaksin == 'dpt' ? 'selected' : '' }}>DPT</option>
                            <option value="bcg" {{ $pasien->vaksin == 'bcg' ? 'selected' : '' }}>BCG</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-submit">💾 Simpan Rekam Medis Pasien</button>
                </form>
            </div>
        </div>
    </div>

@endsection
