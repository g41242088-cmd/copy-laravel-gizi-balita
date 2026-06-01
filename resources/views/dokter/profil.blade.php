@extends('layouts.app')

@section('title', 'Profil Dokter - GiziAnak')

@section('custom_css')
    <style>
        /* --- HEADER HALAMAN --- */
        .page-header {
            margin-bottom: 24px;
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

        /* --- LAYOUT PROFIL --- */
        .profile-grid {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 24px;
            align-items: start;
        }

        /* --- KARTU KIRI (FOTO & INFO SINGKAT) --- */
        .card-photo {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            border: 1px solid #f1f5f9;
            padding: 32px 24px;
            text-align: center;
            position: sticky;
            top: 24px;
        }

        .avatar-wrapper {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 20px auto;
        }

        .avatar-circle {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: #eff6ff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            border: 4px solid white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .btn-edit-photo {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #3b82f6;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            cursor: pointer;
            border: 2px solid white;
            transition: all 0.2s;
        }

        .btn-edit-photo:hover {
            background: #2563eb;
            transform: scale(1.1);
        }

        .doctor-name {
            font-size: 20px;
            font-weight: 900;
            color: #1e293b;
            margin: 0 0 4px 0;
        }

        .doctor-spec {
            font-size: 14px;
            font-weight: 700;
            color: #3b82f6;
            margin: 0 0 16px 0;
        }

        .badge-sip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: #f0fdf4;
            border: 1px solid #dcfce7;
            color: #16a34a;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 24px;
        }

        .stat-row {
            display: flex;
            justify-content: space-around;
            border-top: 1px dashed #e2e8f0;
            padding-top: 20px;
        }

        .stat-item h4 {
            font-size: 20px;
            font-weight: 900;
            color: #1e293b;
            margin: 0 0 4px 0;
        }

        .stat-item p {
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            margin: 0;
        }

        /* --- FORM KANAN --- */
        .card-form {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            border: 1px solid #f1f5f9;
            padding: 32px;
            margin-bottom: 24px;
        }

        .form-title {
            font-size: 16px;
            font-weight: 800;
            color: #1e293b;
            margin: 0 0 20px 0;
            padding-bottom: 12px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
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
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            color: #334155;
            outline: none;
            transition: border-color 0.2s;
            background: #f8fafc;
            font-family: inherit;
            box-sizing: border-box;
        }

        .form-input:focus {
            border-color: #3b82f6;
            background: white;
        }

        .form-input:disabled {
            background: #f1f5f9;
            cursor: not-allowed;
            color: #94a3b8;
        }

        textarea.form-input {
            resize: vertical;
            min-height: 80px;
        }

        .btn-save {
            background: #3b82f6;
            color: white;
            padding: 14px 28px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
        }

        .btn-save:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }

        /* --- TOAST POPUP --- */
        .toast-popup {
            position: fixed;
            top: 30px;
            right: 30px;
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 9999;
            animation: slideIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards, fadeOut 0.5s ease-in forwards 3.5s;
        }

        .toast-success {
            background-color: #10b981;
        }

        .toast-error {
            background-color: #ef4444;
        }

        @keyframes slideIn {
            0% {
                transform: translateX(120%);
                opacity: 0;
            }

            100% {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            0% {
                transform: translateX(0);
                opacity: 1;
            }

            100% {
                transform: translateX(120%);
                opacity: 0;
                visibility: hidden;
            }
        }

        /* --- RESPONSIVE --- */
        @media (max-width: 992px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }

            .card-photo {
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

    <!-- POP UP NOTIFIKASI -->
    @if (session('success'))
        <div class="toast-popup toast-success">
            <span style="font-size: 20px;">✅</span>
            <span style="font-weight: 600; font-size: 14px;">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error') || $errors->any())
        <div class="toast-popup toast-error">
            <span style="font-size: 20px;">⚠️</span>
            <span style="font-weight: 600; font-size: 14px;">
                {{ session('error') ?? $errors->first() }}
            </span>
        </div>
    @endif

    <div class="page-header">
        <h2>Profil Dokter</h2>
        <p>Kelola informasi pribadi, data profesional, dan keamanan akun Anda.</p>
    </div>

    <div class="profile-grid">

        <!-- KARTU KIRI (FOTO) -->
        <div>
            <div class="card-photo">
                <div class="avatar-wrapper">
                    <div class="avatar-circle">👨‍⚕️</div>
                    <div class="btn-edit-photo" title="Ubah Foto Profil">📷</div>
                </div>

                <h3 class="doctor-name">{{ $user->name }}</h3>
                <p class="doctor-spec">{{ $user->spesialisasi ?? 'Dokter Spesialis' }}</p>

                <div class="badge-sip">
                    <span>✅</span> SIP: {{ $user->no_sip ?? 'Belum Diatur' }}
                </div>

                
            </div>
        </div>

        <!-- FORM KANAN -->
        <div>
            <!-- FORM 1: DATA PRIBADI & PROFESIONAL -->
            <form action="{{ route('dokter.profil.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-form">
                    <h3 class="form-title"><span>📝</span> Informasi Dasar & Profesional</h3>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap (Sesuai Gelar)</label>
                            <input type="text" name="name" class="form-input" value="{{ $user->name }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Akun</label>
                            <input type="email" class="form-input" value="{{ $user->email }}" disabled>
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Spesialisasi</label>
                            <input type="text" name="spesialisasi" class="form-input" value="{{ $user->spesialisasi }}"
                                placeholder="Contoh: Dokter Spesialis Anak">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nomor SIP (Surat Izin Praktik)</label>
                            <input type="text" name="no_sip" class="form-input" value="{{ $user->no_sip }}"
                                placeholder="Contoh: 123.456.789/2024">
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Nomor Handphone</label>
                            <input type="text" name="no_telepon" class="form-input" value="{{ $user->no_telepon }}"
                                placeholder="Contoh: 081234567890">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Alamat Klinik / Rumah Sakit Praktik</label>
                            <input type="text" name="alamat_praktik" class="form-input"
                                value="{{ $user->alamat_praktik }}" placeholder="Contoh: RSUD Dr. Soetomo, Surabaya">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Biografi Singkat (Tentang Anda)</label>
                        <textarea name="bio" class="form-input" placeholder="Ceritakan pengalaman dan keahlian Anda secara singkat...">{{ $user->bio }}</textarea>
                    </div>

                    <div style="text-align: right; margin-top: 10px;">
                        <!-- Pastikan type="submit" agar form terkirim -->
                        <button type="submit" class="btn-save">Simpan Perubahan</button>
                    </div>
                </div>
            </form>

            <!-- FORM 2: UBAH PASSWORD -->
            <form action="{{ route('dokter.profil.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-form">
                    <h3 class="form-title"><span>🔒</span> Keamanan Akun</h3>

                    <div class="form-group">
                        <label class="form-label">Password Lama</label>
                        <input type="password" name="current_password" class="form-input"
                            placeholder="Masukkan password saat ini" required>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-input" placeholder="Buat password baru"
                                required minlength="8">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-input"
                                placeholder="Ulangi password baru" required minlength="8">
                        </div>
                    </div>

                    <div style="text-align: right; margin-top: 10px;">
                        <!-- Pastikan type="submit" agar form terkirim -->
                        <button type="submit" class="btn-save" style="background: #1e293b;">Perbarui Password</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

@endsection
