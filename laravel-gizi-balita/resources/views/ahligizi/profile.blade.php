@extends('layouts.app')

@section('title', 'Profil Ahli Gizi - GiziAnak')

@section('custom_css')
<style>
    /* KUNCI CSS: Semua class diawali dengan #profile-page-content agar tidak bocor ke sidebar */
    
    #profile-page-content .profile-grid-container {
        display: grid;
        grid-template-columns: 320px 1fr;
        grid-template-rows: auto auto;
        gap: 24px;
        padding: 24px;
        background: #f1f5f9;
        min-height: 100vh;
    }

    #profile-page-content .card-panel {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
    }

    /* --- PANEL KIRI: PROFIL --- */
    #profile-page-content .panel-left {
        grid-row: span 2;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        height: fit-content;
    }

    #profile-page-content .avatar-wrapper {
        width: 120px; height: 120px;
        background: #f8fafc;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 50px; margin-bottom: 16px;
        border: 4px solid #fff;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
    }

    /* --- FORM STYLING --- */
    #profile-page-content .profil-section-title {
        display: flex; align-items: center; gap: 10px;
        font-size: 16px; font-weight: 800; color: #1e293b;
        margin-bottom: 20px; border-bottom: 1px solid #f1f5f9;
        padding-bottom: 12px;
    }

    #profile-page-content .input-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    #profile-page-content .form-group-custom { margin-bottom: 16px; text-align: left; width: 100%; }
    
    #profile-page-content .label-custom {
        display: block; font-size: 11px; font-weight: 700;
        color: #64748b; text-transform: uppercase; margin-bottom: 6px;
    }

    #profile-page-content .input-custom {
        width: 100%; padding: 10px 14px;
        border: 1px solid #e2e8f0; border-radius: 8px;
        font-size: 14px; background: #f8fafc;
        transition: all 0.2s; box-sizing: border-box;
    }

    #profile-page-content .input-custom:focus { border-color: #3b82f6; background: white; outline: none; }

    #profile-page-content .btn-blue {
        background: #3b82f6; color: white; border: none;
        padding: 12px 24px; border-radius: 8px; font-weight: 700;
        cursor: pointer; transition: 0.2s; width: 100%;
        margin-top: 20px;
    }
    
    #profile-page-content .btn-dark {
        background: #1e293b; color: white; border: none;
        padding: 12px 24px; border-radius: 8px; font-weight: 700;
        cursor: pointer; width: 100%;
    }

    @media (max-width: 900px) {
        #profile-page-content .profile-grid-container { grid-template-columns: 1fr; }
        #profile-page-content .panel-left { grid-row: span 1; }
    }
</style>
@endsection

@section('content')
<div id="profile-page-content">

    @if(session('success'))
    <div style="background: #dcfce7; color: #16a34a; padding: 16px 24px; border-radius: 12px; margin: 24px 24px 0 24px; font-weight: 700; border: 1px solid #bbf7d0;">
        ✅ {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('ahligizi.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="profile-grid-container">
            
            <div class="card-panel panel-left">
                <div class="avatar-wrapper">
                    {{ $user->jenis_kelamin == 'P' ? '👩‍⚕️' : '👨‍⚕️' }}
                </div>
                <h3 style="margin: 0; font-weight: 800;">{{ $user->name }}</h3>
                <p style="color: #3b82f6; font-size: 13px; font-weight: 700; margin: 4px 0 16px 0;">{{ $user->spesialisasi ?? 'Spesialis Gizi' }}</p>
                
                <div style="background: #ecfdf5; color: #059669; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; margin-bottom: 24px;">
                    ✓ SIP: {{ $user->no_sip ?? '-' }}
                </div>

                <div class="form-group-custom">
                    <label class="label-custom">Biografi Singkat (Tentang Anda)</label>
                    <textarea name="bio" class="input-custom" style="min-height: 150px; resize: none;" placeholder="Tuliskan biografi singkat...">{{ old('bio', $user->bio) }}</textarea>
                </div>

                <button type="submit" class="btn-blue">Simpan Perubahan</button>
            </div>

            <div class="card-panel panel-right-top">
                <div class="profil-section-title">📝 Informasi Dasar & Profesional</div>
                
                <div class="input-grid">
                    <div class="form-group-custom">
                        <label class="label-custom">Nama Lengkap (Sesuai Gelar)</label>
                        <input type="text" name="name" class="input-custom" value="{{ old('name', $user->name) }}">
                    </div>
                    <div class="form-group-custom">
                        <label class="label-custom">Email Akun</label>
                        <input type="email" name="email" class="input-custom" value="{{ old('email', $user->email) }}">
                    </div>
                    <div class="form-group-custom">
                        <label class="label-custom">Spesialisasi</label>
                        <input type="text" name="spesialisasi" class="input-custom" value="{{ old('spesialisasi', $user->spesialisasi) }}">
                    </div>
                    <div class="form-group-custom">
                        <label class="label-custom">Nomor SIP (Surat Izin Praktik)</label>
                        <input type="text" name="no_sip" class="input-custom" value="{{ old('no_sip', $user->no_sip) }}">
                    </div>
                    <div class="form-group-custom">
                        <label class="label-custom">Nomor Handphone</label>
                        <input type="text" name="no_telepon" class="input-custom" value="{{ old('no_telepon', $user->no_telepon) }}">
                    </div>
                    <div class="form-group-custom">
                        <label class="label-custom">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="input-custom">
                            <option value="L" {{ (old('jenis_kelamin', $user->jenis_kelamin) == 'L') ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ (old('jenis_kelamin', $user->jenis_kelamin) == 'P') ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group-custom" style="grid-column: span 2;">
                        <label class="label-custom">Alamat Klinik / Rumah Sakit Praktik</label>
                        <input type="text" name="alamat_praktik" class="input-custom" value="{{ old('alamat_praktik', $user->alamat_praktik) }}">
                    </div>
                </div>
            </div>

            <div class="card-panel panel-right-bottom">
                <div class="profil-section-title">🔒 Keamanan Akun</div>
                
                <div class="input-grid">
                    <div class="form-group-custom" style="grid-column: span 2;">
                        <label class="label-custom">Password Baru</label>
                        <input type="password" name="password" class="input-custom" placeholder="Kosongkan jika tidak ingin ganti">
                    </div>
                    <div class="form-group-custom">
                        <label class="label-custom">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="input-custom" placeholder="Ulangi password baru">
                    </div>
                    <div class="form-group-custom" style="display: flex; align-items: flex-end;">
                        <button type="submit" class="btn-dark">Perbarui Password</button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection