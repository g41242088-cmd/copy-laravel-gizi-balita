@extends('layouts.app')

@section('title', 'Skrining Stunting - GiziAnak')

@section('custom_css')
<style>
    .page-header { margin-bottom: 24px; }
    .page-header h2 { font-size: 26px; font-weight: 900; color: #0f1c2e; margin: 0 0 6px 0; font-family: Georgia, serif; }
    .page-header p { font-size: 14px; color: #64748b; margin: 0; }

    /* WIZARD STEPS */
    .wizard-steps { display: flex; align-items: center; background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px 28px; margin-bottom: 24px; gap: 0; }
    .step { display: flex; align-items: center; flex: 1; }
    .step-circle { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 800; flex-shrink: 0; border: 2px solid #e2e8f0; background: white; color: #94a3b8; transition: all 0.3s; }
    .step-circle.active { background: #1e3a8a; border-color: #1e3a8a; color: white; }
    .step-circle.done { background: #10b981; border-color: #10b981; color: white; }
    .step-label { margin-left: 10px; }
    .step-label span:first-child { display: block; font-size: 10px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px; }
    .step-label span:last-child { display: block; font-size: 13px; font-weight: 700; color: #1e293b; }
    .step-line { flex: 1; height: 2px; background: #e2e8f0; margin: 0 12px; }
    .step-line.done { background: #10b981; }

    /* CARD */
    .card { background: white; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; }
    .card-header { padding: 20px 24px; border-bottom: 1px solid #f1f5f9; }
    .card-header h3 { font-size: 16px; font-weight: 800; color: #0f1c2e; margin: 0 0 4px 0; }
    .card-header p { font-size: 13px; color: #64748b; margin: 0; }
    .card-body { padding: 24px; }

    /* FORM */
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 7px; }
    .form-label .req { color: #ef4444; }
    .form-label .opt { font-weight: 400; color: #94a3b8; font-size: 11px; }
    .input-wrap { position: relative; }
    .input-icon { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); font-size: 14px; pointer-events: none; }
    .form-control { width: 100%; padding: 11px 14px 11px 38px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 14px; color: #1e293b; background: #f8fafc; outline: none; transition: all 0.2s; font-family: inherit; box-sizing: border-box; }
    .form-control.no-icon { padding-left: 14px; }
    .form-control:focus { border-color: #3b82f6; background: white; }
    .form-hint { font-size: 11px; color: #94a3b8; margin-top: 5px; display: block; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
    .form-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 18px; }

    /* RADIO/CHECKBOX GROUPS */
    .radio-group { display: flex; gap: 10px; flex-wrap: wrap; }
    .radio-pill input { display: none; }
    .radio-pill label { display: flex; align-items: center; gap: 7px; padding: 9px 16px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 13px; font-weight: 600; color: #475569; cursor: pointer; transition: all 0.2s; background: #f8fafc; }
    .radio-pill input:checked + label { border-color: #1e3a8a; background: #eff6ff; color: #1e3a8a; }
    .radio-pill label:hover { border-color: #93c5fd; }

    /* SECTION DIVIDER */
    .section-divider { font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin: 28px 0 18px; padding-bottom: 10px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 8px; }

    /* NAVIGATION BUTTONS */
    .wizard-nav { display: flex; justify-content: space-between; align-items: center; padding-top: 24px; margin-top: 24px; border-top: 1px solid #f1f5f9; }
    .btn { padding: 11px 22px; border-radius: 10px; font-size: 14px; font-weight: 700; cursor: pointer; transition: all 0.2s; border: none; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
    .btn-prev { background: #f1f5f9; color: #475569; }
    .btn-prev:hover { background: #e2e8f0; }
    .btn-next { background: #1e3a8a; color: white; }
    .btn-next:hover { background: #1e40af; }
    .btn-submit { background: #10b981; color: white; }
    .btn-submit:hover { background: #059669; }

    /* POPUP */
    .popup-overlay { position: fixed; inset: 0; background: rgba(15,28,46,0.55); z-index: 9999; display: flex; align-items: center; justify-content: center; opacity: 0; visibility: hidden; transition: 0.3s; }
    .popup-overlay.active { opacity: 1; visibility: visible; }
    .popup-box { background: white; padding: 32px; border-radius: 20px; text-align: center; max-width: 400px; width: 90%; transform: scale(0.92); transition: 0.3s; }
    .popup-overlay.active .popup-box { transform: scale(1); }

    @media (max-width: 640px) { .form-row, .form-row-3 { grid-template-columns: 1fr; } .wizard-steps { gap: 4px; padding: 16px; } .step-label { display: none; } .step-line { margin: 0 6px; } }
</style>
@endsection

@section('content')

<div class="page-header">
    <h2>Skrining Stunting</h2>
    <p>Pemantauan pertumbuhan anak berbasis standar antropometri WHO — Kemenkes RI</p>
</div>

{{-- STEP INDICATOR --}}
<div class="wizard-steps" id="wizardSteps">
    <div class="step">
        <div class="step-circle active" id="circle-1">1</div>
        <div class="step-label">
            <span>Langkah 1</span>
            <span>Antropometri</span>
        </div>
    </div>
    <div class="step-line" id="line-1"></div>
    <div class="step">
        <div class="step-circle" id="circle-2">2</div>
        <div class="step-label">
            <span>Langkah 2</span>
            <span>Riwayat Kesehatan</span>
        </div>
    </div>
    <div class="step-line" id="line-2"></div>
    <div class="step">
        <div class="step-circle" id="circle-3">3</div>
        <div class="step-label">
            <span>Langkah 3</span>
            <span>Pola Makan</span>
        </div>
    </div>
    <div class="step-line" id="line-3"></div>
    <div class="step">
        <div class="step-circle" id="circle-4">4</div>
        <div class="step-label">
            <span>Langkah 4</span>
            <span>Konfirmasi</span>
        </div>
    </div>
</div>

<form action="{{ route('orangtua.screening.store') }}" method="POST" id="screeningForm">
    @csrf

    {{-- ======= STEP 1: ANTROPOMETRI ======= --}}
    <div class="card" id="step-1">
        <div class="card-header">
            <h3>📏 Data Antropometri</h3>
            <p>Data pengukuran fisik anak — dasar perhitungan Z-Score WHO</p>
        </div>
        <div class="card-body">

            <div class="form-group">
                <label class="form-label">Pilih Anak <span class="req">*</span></label>
                <div class="input-wrap">
                    <select name="anak_id" class="form-control" required id="selectAnak">
                        <option value="">-- Pilih Nama Anak --</option>
                        @foreach($anaks as $anak)
                            <option value="{{ $anak->id }}"
                                data-jk="{{ $anak->jenis_kelamin }}"
                                data-tgl="{{ $anak->tanggal_lahir }}"
                                {{ old('anak_id') == $anak->id ? 'selected' : '' }}>
                                {{ ucfirst($anak->nama) }}
                                ({{ $anak->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @if($anaks->isEmpty())
                    <span class="form-hint" style="color:#ef4444">⚠ Belum ada data anak. <a href="{{ route('orangtua.anak.create') }}" style="color:#2563eb">Daftarkan anak terlebih dahulu.</a></span>
                @endif
            </div>

            {{-- Info otomatis --}}
            <div id="infoAnak" style="display:none; background:#f0f9ff; border:1px solid #bae6fd; border-radius:10px; padding:13px 16px; margin-bottom:20px; font-size:13px; color:#0369a1;">
                <strong id="infoNama">-</strong> &bull; <span id="infoJK">-</span> &bull; Usia: <strong id="infoUsia">-</strong>
            </div>

            <div class="section-divider">📅 Tanggal Pengukuran</div>

            <div class="form-group">
                <label class="form-label">Tanggal Pengukuran <span class="req">*</span></label>
                <input type="date" name="tanggal_pengukuran" class="form-control no-icon" value="{{ old('tanggal_pengukuran', date('Y-m-d')) }}" required>
            </div>

            <div class="section-divider">⚖️ Pengukuran Fisik</div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Berat Badan <span class="req">*</span></label>
                    <div class="input-wrap">
                        <span class="input-icon">⚖️</span>
                        <input type="number" name="berat_badan" class="form-control" placeholder="cth: 12.5" step="0.1" min="1" max="100" value="{{ old('berat_badan') }}" required>
                    </div>
                    <span class="form-hint">Kilogram (kg)</span>
                </div>
                <div class="form-group">
                    <label class="form-label">
                        Tinggi / Panjang Badan <span class="req">*</span>
                    </label>
                    <div class="input-wrap">
                        <span class="input-icon">📐</span>
                        <input type="number" name="tinggi_badan" class="form-control" placeholder="cth: 85.0" step="0.1" min="30" max="200" value="{{ old('tinggi_badan') }}" required>
                    </div>
                    <span class="form-hint">Sentimeter (cm) — panjang badan jika usia &lt; 2 tahun</span>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Lingkar Kepala <span class="opt">(opsional)</span></label>
                    <div class="input-wrap">
                        <span class="input-icon">🔵</span>
                        <input type="number" name="lingkar_kepala" class="form-control" placeholder="cth: 45.0" step="0.1" min="20" max="70" value="{{ old('lingkar_kepala') }}">
                    </div>
                    <span class="form-hint">Sentimeter (cm)</span>
                </div>
                <div class="form-group">
                    <label class="form-label">LILA <span class="opt">(Lingkar Lengan Atas, opsional)</span></label>
                    <div class="input-wrap">
                        <span class="input-icon">💪</span>
                        <input type="number" name="lila" class="form-control" placeholder="cth: 13.5" step="0.1" min="5" max="40" value="{{ old('lila') }}">
                    </div>
                    <span class="form-hint">Sentimeter (cm) — normal ≥ 12.5 cm</span>
                </div>
            </div>

            <div class="wizard-nav">
                <div></div>
                <button type="button" class="btn btn-next" onclick="goStep(2)">Lanjut →</button>
            </div>
        </div>
    </div>

    {{-- ======= STEP 2: RIWAYAT KESEHATAN ======= --}}
    <div class="card" id="step-2" style="display:none;">
        <div class="card-header">
            <h3>🏥 Riwayat Kesehatan Anak</h3>
            <p>Faktor riwayat kesehatan yang memengaruhi risiko stunting</p>
        </div>
        <div class="card-body">

            <div class="section-divider">🤱 Riwayat Lahir & Menyusui</div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Berat Badan Lahir <span class="opt">(opsional)</span></label>
                    <div class="input-wrap">
                        <span class="input-icon">🏥</span>
                        <input type="number" name="berat_lahir" class="form-control" placeholder="cth: 3.2" step="0.1" min="0.5" max="6" value="{{ old('berat_lahir') }}">
                    </div>
                    <span class="form-hint">Kilogram (kg) — BBLR jika &lt; 2.5 kg</span>
                </div>
                <div class="form-group">
                    <label class="form-label">Lahir Prematur?</label>
                    <div class="radio-group" style="margin-top:4px;">
                        <div class="radio-pill">
                            <input type="radio" name="riwayat_prematur" id="prem_ya" value="1" {{ old('riwayat_prematur') == '1' ? 'checked' : '' }}>
                            <label for="prem_ya">Ya</label>
                        </div>
                        <div class="radio-pill">
                            <input type="radio" name="riwayat_prematur" id="prem_tidak" value="0" {{ old('riwayat_prematur') == '0' ? 'checked' : '' }}>
                            <label for="prem_tidak">Tidak</label>
                        </div>
                        <div class="radio-pill">
                            <input type="radio" name="riwayat_prematur" id="prem_tk" value="" {{ old('riwayat_prematur') === null ? 'checked' : '' }}>
                            <label for="prem_tk">Tidak tahu</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">ASI Eksklusif (6 bulan pertama)?</label>
                <div class="radio-group">
                    <div class="radio-pill">
                        <input type="radio" name="asi_eksklusif" id="asi_ya" value="1" {{ old('asi_eksklusif') == '1' ? 'checked' : '' }}>
                        <label for="asi_ya">✅ Ya, eksklusif 6 bulan</label>
                    </div>
                    <div class="radio-pill">
                        <input type="radio" name="asi_eksklusif" id="asi_tidak" value="0" {{ old('asi_eksklusif') == '0' ? 'checked' : '' }}>
                        <label for="asi_tidak">❌ Tidak</label>
                    </div>
                    <div class="radio-pill">
                        <input type="radio" name="asi_eksklusif" id="asi_sebagian" value="2" {{ old('asi_eksklusif') == '2' ? 'checked' : '' }}>
                        <label for="asi_sebagian">⚡ Sebagian</label>
                    </div>
                </div>
            </div>

            <div class="section-divider">💉 Imunisasi & Penyakit</div>

            <div class="form-group">
                <label class="form-label">Status Imunisasi Dasar</label>
                <div class="radio-group">
                    <div class="radio-pill">
                        <input type="radio" name="imunisasi_lengkap" id="imun_lengkap" value="1" {{ old('imunisasi_lengkap') == '1' ? 'checked' : '' }}>
                        <label for="imun_lengkap">✅ Lengkap</label>
                    </div>
                    <div class="radio-pill">
                        <input type="radio" name="imunisasi_lengkap" id="imun_tidak" value="0" {{ old('imunisasi_lengkap') == '0' ? 'checked' : '' }}>
                        <label for="imun_tidak">❌ Tidak lengkap</label>
                    </div>
                    <div class="radio-pill">
                        <input type="radio" name="imunisasi_lengkap" id="imun_tk" value="">
                        <label for="imun_tk">Tidak tahu</label>
                    </div>
                </div>
                <span class="form-hint">BCG, Hepatitis B, DPT, Polio, Campak</span>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Riwayat Diare Berulang?</label>
                    <div class="radio-group">
                        <div class="radio-pill">
                            <input type="radio" name="riwayat_diare_berulang" id="diare_ya" value="1" {{ old('riwayat_diare_berulang') == '1' ? 'checked' : '' }}>
                            <label for="diare_ya">Ya</label>
                        </div>
                        <div class="radio-pill">
                            <input type="radio" name="riwayat_diare_berulang" id="diare_tidak" value="0" {{ old('riwayat_diare_berulang') == '0' ? 'checked' : '' }}>
                            <label for="diare_tidak">Tidak</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Pernah Dirawat Inap?</label>
                    <div class="radio-group">
                        <div class="radio-pill">
                            <input type="radio" name="riwayat_rawat_inap" id="rawat_ya" value="1" {{ old('riwayat_rawat_inap') == '1' ? 'checked' : '' }}>
                            <label for="rawat_ya">Ya</label>
                        </div>
                        <div class="radio-pill">
                            <input type="radio" name="riwayat_rawat_inap" id="rawat_tidak" value="0" {{ old('riwayat_rawat_inap') == '0' ? 'checked' : '' }}>
                            <label for="rawat_tidak">Tidak</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Riwayat Penyakit Infeksi <span class="opt">(opsional)</span></label>
                <textarea name="riwayat_penyakit" class="form-control no-icon" rows="2" placeholder="Contoh: TB, pneumonia berulang, infeksi telinga kronis..." style="resize:vertical;">{{ old('riwayat_penyakit') }}</textarea>
            </div>

            <div class="wizard-nav">
                <button type="button" class="btn btn-prev" onclick="goStep(1)">← Kembali</button>
                <button type="button" class="btn btn-next" onclick="goStep(3)">Lanjut →</button>
            </div>
        </div>
    </div>

    {{-- ======= STEP 3: POLA MAKAN & SANITASI ======= --}}
    <div class="card" id="step-3" style="display:none;">
        <div class="card-header">
            <h3>🥗 Pola Makan & Sanitasi</h3>
            <p>Faktor gizi dan lingkungan yang berkontribusi pada status pertumbuhan</p>
        </div>
        <div class="card-body">

            <div class="section-divider">🍽️ Pola Makan</div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Frekuensi Makan per Hari</label>
                    <div class="input-wrap">
                        <span class="input-icon">🍽️</span>
                        <select name="frekuensi_makan" class="form-control">
                            <option value="">-- Pilih --</option>
                            <option value="1" {{ old('frekuensi_makan') == '1' ? 'selected' : '' }}>1 kali</option>
                            <option value="2" {{ old('frekuensi_makan') == '2' ? 'selected' : '' }}>2 kali</option>
                            <option value="3" {{ old('frekuensi_makan') == '3' ? 'selected' : '' }}>3 kali</option>
                            <option value="4" {{ old('frekuensi_makan') == '4' ? 'selected' : '' }}>4 kali atau lebih</option>
                        </select>
                    </div>
                    <span class="form-hint">Rekomendasi balita: 3–4x makan utama + 2x selingan</span>
                </div>
                <div class="form-group">
                    <label class="form-label">MPASI Sesuai Usia?</label>
                    <div class="radio-group" style="margin-top:4px;">
                        <div class="radio-pill">
                            <input type="radio" name="mpasi_sesuai_usia" id="mpasi_ya" value="1" {{ old('mpasi_sesuai_usia') == '1' ? 'checked' : '' }}>
                            <label for="mpasi_ya">Ya</label>
                        </div>
                        <div class="radio-pill">
                            <input type="radio" name="mpasi_sesuai_usia" id="mpasi_tidak" value="0" {{ old('mpasi_sesuai_usia') == '0' ? 'checked' : '' }}>
                            <label for="mpasi_tidak">Tidak</label>
                        </div>
                        <div class="radio-pill">
                            <input type="radio" name="mpasi_sesuai_usia" id="mpasi_belum" value="">
                            <label for="mpasi_belum">Belum MPASI</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Konsumsi Protein Hewani?</label>
                    <div class="radio-group" style="margin-top:4px;">
                        <div class="radio-pill">
                            <input type="radio" name="konsumsi_protein" id="prot_ya" value="1" {{ old('konsumsi_protein') == '1' ? 'checked' : '' }}>
                            <label for="prot_ya">✅ Rutin</label>
                        </div>
                        <div class="radio-pill">
                            <input type="radio" name="konsumsi_protein" id="prot_kdg" value="2" {{ old('konsumsi_protein') == '2' ? 'checked' : '' }}>
                            <label for="prot_kdg">⚡ Kadang</label>
                        </div>
                        <div class="radio-pill">
                            <input type="radio" name="konsumsi_protein" id="prot_tidak" value="0" {{ old('konsumsi_protein') == '0' ? 'checked' : '' }}>
                            <label for="prot_tidak">❌ Jarang</label>
                        </div>
                    </div>
                    <span class="form-hint">Daging, ikan, telur, ayam</span>
                </div>
                <div class="form-group">
                    <label class="form-label">Suplemen / Vitamin?</label>
                    <div class="radio-group" style="margin-top:4px;">
                        <div class="radio-pill">
                            <input type="radio" name="konsumsi_suplemen" id="sup_ya" value="1" {{ old('konsumsi_suplemen') == '1' ? 'checked' : '' }}>
                            <label for="sup_ya">Ya</label>
                        </div>
                        <div class="radio-pill">
                            <input type="radio" name="konsumsi_suplemen" id="sup_tidak" value="0" {{ old('konsumsi_suplemen') == '0' ? 'checked' : '' }}>
                            <label for="sup_tidak">Tidak</label>
                        </div>
                    </div>
                    <span class="form-hint">Zinc, zat besi, vitamin A, atau multivitamin</span>
                </div>
            </div>

            <div class="section-divider">🏠 Sanitasi & Lingkungan</div>

            <div class="form-row-3">
                <div class="form-group">
                    <label class="form-label">Akses Air Bersih?</label>
                    <div class="radio-group" style="margin-top:4px; flex-direction:column; gap:8px;">
                        <div class="radio-pill">
                            <input type="radio" name="akses_air_bersih" id="air_ya" value="1" {{ old('akses_air_bersih') == '1' ? 'checked' : '' }}>
                            <label for="air_ya">Ya</label>
                        </div>
                        <div class="radio-pill">
                            <input type="radio" name="akses_air_bersih" id="air_tidak" value="0" {{ old('akses_air_bersih') == '0' ? 'checked' : '' }}>
                            <label for="air_tidak">Tidak</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Kebiasaan Cuci Tangan?</label>
                    <div class="radio-group" style="margin-top:4px; flex-direction:column; gap:8px;">
                        <div class="radio-pill">
                            <input type="radio" name="kebiasaan_cuci_tangan" id="ct_ya" value="1" {{ old('kebiasaan_cuci_tangan') == '1' ? 'checked' : '' }}>
                            <label for="ct_ya">Ya, pakai sabun</label>
                        </div>
                        <div class="radio-pill">
                            <input type="radio" name="kebiasaan_cuci_tangan" id="ct_tidak" value="0" {{ old('kebiasaan_cuci_tangan') == '0' ? 'checked' : '' }}>
                            <label for="ct_tidak">Tidak rutin</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Kondisi Sanitasi Rumah</label>
                    <select name="kondisi_sanitasi" class="form-control no-icon" style="margin-top:4px;">
                        <option value="">-- Pilih --</option>
                        <option value="baik" {{ old('kondisi_sanitasi') == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="cukup" {{ old('kondisi_sanitasi') == 'cukup' ? 'selected' : '' }}>Cukup</option>
                        <option value="buruk" {{ old('kondisi_sanitasi') == 'buruk' ? 'selected' : '' }}>Buruk</option>
                    </select>
                    <span class="form-hint">Jamban, drainase, kebersihan lingkungan</span>
                </div>
            </div>

            <div class="wizard-nav">
                <button type="button" class="btn btn-prev" onclick="goStep(2)">← Kembali</button>
                <button type="button" class="btn btn-next" onclick="goStep(4)">Lanjut →</button>
            </div>
        </div>
    </div>

    {{-- ======= STEP 4: KONFIRMASI ======= --}}
    <div class="card" id="step-4" style="display:none;">
        <div class="card-header">
            <h3>✅ Konfirmasi Data</h3>
            <p>Periksa kembali data sebelum menyimpan hasil skrining</p>
        </div>
        <div class="card-body">

            <div id="summaryBox" style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:20px; margin-bottom:24px; font-size:13px; color:#334155; line-height:2;">
                <strong>Ringkasan data akan ditampilkan di sini.</strong>
            </div>

            <div class="form-group">
                <label class="form-label">Jadwal Kontrol Berikutnya <span class="opt">(opsional)</span></label>
                <input type="date" name="jadwal_kontrol_berikutnya" class="form-control no-icon" value="{{ old('jadwal_kontrol_berikutnya') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                <span class="form-hint">Rekomendasi: 1 bulan untuk pemantauan rutin, atau sesuai arahan petugas kesehatan</span>
            </div>

            <div class="wizard-nav">
                <button type="button" class="btn btn-prev" onclick="goStep(3)">← Kembali</button>
                <button type="submit" class="btn btn-submit" {{ $anaks->isEmpty() ? 'disabled' : '' }}>
                    💾 Simpan Hasil Skrining
                </button>
            </div>
        </div>
    </div>

</form>

{{-- POPUP NOTIFIKASI --}}
@if(session('success') || session('error'))
<div class="popup-overlay active" id="notifPopup">
    <div class="popup-box">
        <div style="font-size:50px; margin-bottom:16px;">{{ session('success') ? '✅' : '❌' }}</div>
        <h3 style="font-size:18px; font-weight:900; color:{{ session('success') ? '#1e293b' : '#ef4444' }}; margin:0 0 8px;">
            {{ session('success') ? 'Skrining Tersimpan' : 'Terjadi Kesalahan' }}
        </h3>
        <p style="font-size:14px; color:#64748b; margin:0 0 24px; line-height:1.6;">{{ session('success') ?? session('error') }}</p>
        <button class="btn btn-next" style="width:100%; justify-content:center;" onclick="document.getElementById('notifPopup').classList.remove('active')">Tutup</button>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
let currentStep = 1;

function goStep(n) {
    document.getElementById('step-' + currentStep).style.display = 'none';
    document.getElementById('step-' + n).style.display = 'block';
    
    // Update circles
    for (let i = 1; i <= 4; i++) {
        const c = document.getElementById('circle-' + i);
        c.classList.remove('active', 'done');
        if (i < n) c.classList.add('done'), c.innerHTML = '✓';
        else if (i === n) c.classList.add('active'), c.innerHTML = i;
        else c.innerHTML = i;
    }

    // Update lines
    for (let i = 1; i <= 3; i++) {
        const l = document.getElementById('line-' + i);
        l.classList.toggle('done', i < n);
    }

    if (n === 4) buildSummary();
    currentStep = n;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function buildSummary() {
    const get = name => {
        const el = document.querySelector(`[name="${name}"]`);
        if (!el) return '-';
        if (el.tagName === 'SELECT') return el.options[el.selectedIndex]?.text || '-';
        return el.value || '-';
    };
    const getRadio = name => {
        const el = document.querySelector(`[name="${name}"]:checked`);
        return el ? (el.nextElementSibling?.innerText || el.value) : 'Tidak diisi';
    };
    const anakOpt = document.querySelector('#selectAnak option:checked');

    document.getElementById('summaryBox').innerHTML = `
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px 24px;font-size:13px;">
            <div><span style="color:#94a3b8">Anak</span><br><strong>${anakOpt?.text || '-'}</strong></div>
            <div><span style="color:#94a3b8">Tgl Pengukuran</span><br><strong>${get('tanggal_pengukuran')}</strong></div>
            <div><span style="color:#94a3b8">Berat Badan</span><br><strong>${get('berat_badan')} kg</strong></div>
            <div><span style="color:#94a3b8">Tinggi/Panjang Badan</span><br><strong>${get('tinggi_badan')} cm</strong></div>
            <div><span style="color:#94a3b8">Lingkar Kepala</span><br><strong>${get('lingkar_kepala') !== '-' ? get('lingkar_kepala') + ' cm' : 'Tidak diisi'}</strong></div>
            <div><span style="color:#94a3b8">LILA</span><br><strong>${get('lila') !== '-' ? get('lila') + ' cm' : 'Tidak diisi'}</strong></div>
            <div><span style="color:#94a3b8">ASI Eksklusif</span><br><strong>${getRadio('asi_eksklusif')}</strong></div>
            <div><span style="color:#94a3b8">Imunisasi Dasar</span><br><strong>${getRadio('imunisasi_lengkap')}</strong></div>
            <div><span style="color:#94a3b8">Frekuensi Makan</span><br><strong>${get('frekuensi_makan')}</strong></div>
            <div><span style="color:#94a3b8">Sanitasi</span><br><strong>${get('kondisi_sanitasi') !== '-' ? get('kondisi_sanitasi') : 'Tidak diisi'}</strong></div>
        </div>
    `;
}

// Info anak otomatis
document.getElementById('selectAnak')?.addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    const infoBox = document.getElementById('infoAnak');
    if (!opt.value) { infoBox.style.display = 'none'; return; }

    const tgl = opt.dataset.tgl;
    const jk = opt.dataset.jk === 'L' ? 'Laki-laki' : 'Perempuan';
    const nama = opt.text.split('(')[0].trim();

    if (tgl) {
        const lahir = new Date(tgl);
        const now = new Date();
        let bulan = (now.getFullYear() - lahir.getFullYear()) * 12 + (now.getMonth() - lahir.getMonth());
        const tahun = Math.floor(bulan / 12);
        bulan = bulan % 12;
        const usiaStr = tahun > 0 ? `${tahun} tahun ${bulan} bulan` : `${bulan} bulan`;
        document.getElementById('infoUsia').textContent = usiaStr;
    }
    document.getElementById('infoNama').textContent = nama;
    document.getElementById('infoJK').textContent = jk;
    infoBox.style.display = 'block';
});

// Auto close popup
const popup = document.getElementById('notifPopup');
if (popup) setTimeout(() => popup.classList.remove('active'), 6000);

// Trigger jika ada old input (validasi gagal)
@if(old('anak_id'))
    document.getElementById('selectAnak').dispatchEvent(new Event('change'));
@endif
</script>
@endpush