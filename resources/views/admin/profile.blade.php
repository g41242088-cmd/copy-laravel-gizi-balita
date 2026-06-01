@extends('layouts.app')

@section('title', 'Profile - GiziAnak')

@section('custom_css')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    .p-wrap { max-width: 780px; margin: 0 auto; font-family: 'Inter', sans-serif; }

    .p-cover {
        height: 140px;
        background: linear-gradient(120deg, #0A2540 0%, #0f5a8f 60%, #1a7abf 100%);
        border-radius: 20px 20px 0 0;
        position: relative;
    }

    .p-cover::after {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        border-radius: 20px 20px 0 0;
    }

    .p-top {
        background: white;
        border-radius: 0 0 20px 20px;
        padding: 0 36px 28px;
        margin-bottom: 20px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        position: relative;
    }

    .p-avatar {
        width: 88px;
        height: 88px;
        border-radius: 18px;
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        font-weight: 700;
        color: #78350f;
        border: 4px solid white;
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        letter-spacing: -1px;
        position: absolute;
        top: -44px;
    }

    .p-identity {
        padding-top: 52px;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .p-name { font-size: 22px; font-weight: 700; color: #0f172a; margin: 0 0 4px; letter-spacing: -0.3px; }
    .p-email { font-size: 13px; color: #64748b; margin: 0; font-weight: 400; }
    .p-badge { background: #0A2540; color: white; font-size: 11px; font-weight: 600; padding: 6px 14px; border-radius: 8px; letter-spacing: 0.5px; }

    .p-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
    .p-card { background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 16px rgba(0,0,0,0.05); }
    .p-card-full { background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 16px rgba(0,0,0,0.05); margin-bottom: 16px; }

    .p-card-label {
        font-size: 11px; font-weight: 600; color: #94a3b8;
        text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px;
        display: flex; align-items: center; gap: 6px;
    }
    .p-card-label::after { content: ''; flex: 1; height: 1px; background: #f1f5f9; }

    .p-info-item { display: flex; justify-content: space-between; align-items: center; padding: 11px 0; border-bottom: 1px solid #f8fafc; }
    .p-info-item:last-child { border-bottom: none; }
    .p-info-key { font-size: 12px; color: #94a3b8; font-weight: 500; }
    .p-info-val { font-size: 13px; color: #1e293b; font-weight: 600; text-align: right; }

    .p-field { margin-bottom: 16px; }
    .p-field label { display: block; font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 7px; }

    .p-input {
        width: 100%; padding: 10px 14px; border: 1.5px solid #e8edf2; border-radius: 10px;
        font-size: 13.5px; color: #1e293b; background: #fafbfc; transition: all 0.15s;
        box-sizing: border-box; font-family: 'Inter', sans-serif;
    }
    .p-input:focus { outline: none; border-color: #0f5a8f; background: white; box-shadow: 0 0 0 3px rgba(15,90,143,0.07); }
    .p-input::placeholder { color: #c1c9d4; }
    .p-err { font-size: 11.5px; color: #ef4444; margin-top: 5px; display: block; }

    .p-btn { width: 100%; padding: 11px; border: none; border-radius: 10px; font-size: 13.5px; font-weight: 600; cursor: pointer; transition: all 0.15s; font-family: 'Inter', sans-serif; }
    .p-btn-primary { background: #0A2540; color: white; }
    .p-btn-primary:hover { background: #0f3460; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(10,37,64,0.25); }
    .p-btn-outline { background: white; color: #0A2540; border: 1.5px solid #e2e8f0; }
    .p-btn-outline:hover { border-color: #0A2540; background: #f8fafc; }
    .p-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

    .p-alert { border-radius: 10px; padding: 11px 16px; font-size: 13px; font-weight: 500; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
    .p-alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }

    @media (max-width: 640px) {
        .p-row, .p-grid-2 { grid-template-columns: 1fr; }
        .p-top { padding: 0 20px 24px; }
        .p-identity { padding-top: 48px; }
    }
</style>
@endsection

@section('content')
<div class="p-wrap">

    @if(session('status') === 'profile-updated')
        <div class="p-alert p-alert-success">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Profil berhasil diperbarui.
        </div>
    @endif

    {{-- COVER + IDENTITY --}}
    <div class="p-cover"></div>
    <div class="p-top">
        <div class="p-avatar">
            {{ strtoupper(substr($user->name, 0, 2)) }}
        </div>
        <div class="p-identity">
            <div>
                <p class="p-name">{{ $user->name }}</p>
                <p class="p-email">{{ $user->email }}</p>
            </div>
            <span class="p-badge">Administrator</span>
        </div>
    </div>

    {{-- ROW: Info + Password --}}
    <div class="p-row">
        <div class="p-card">
            <div class="p-card-label">Info Akun</div>
            <div class="p-info-item">
                <span class="p-info-key">Nama</span>
                <span class="p-info-val">{{ $user->name }}</span>
            </div>
            <div class="p-info-item">
                <span class="p-info-key">Email</span>
                <span class="p-info-val">{{ $user->email }}</span>
            </div>
            <div class="p-info-item">
                <span class="p-info-key">Role</span>
                <span class="p-info-val">
                    <span style="background:#eff6ff; color:#1d4ed8; padding:3px 10px; border-radius:6px; font-size:11px; font-weight:600;">Admin</span>
                </span>
            </div>
            <div class="p-info-item">
                <span class="p-info-key">Bergabung</span>
                <span class="p-info-val">{{ $user->created_at->format('d M Y') }}</span>
            </div>
        </div>

        <div class="p-card">
            <div class="p-card-label">Keamanan</div>
            <form method="POST" action="{{ route('admin.profile.update') }}">
                @csrf @method('PUT')
                <input type="hidden" name="name" value="{{ $user->name }}">
                <input type="hidden" name="email" value="{{ $user->email }}">
                <div class="p-field">
                    <label>Password Baru</label>
                    <input type="password" name="password" class="p-input" placeholder="Minimal 8 karakter">
                    @error('password')<span class="p-err">{{ $message }}</span>@enderror
                </div>
                <div class="p-field">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="p-input" placeholder="Ulangi password">
                </div>
                <button type="submit" class="p-btn p-btn-primary" style="margin-top:4px;">Perbarui Password</button>
            </form>
        </div>
    </div>

    {{-- Edit Info --}}
    <div class="p-card-full">
        <div class="p-card-label">Edit Informasi</div>
        <form method="POST" action="{{ route('admin.profile.update') }}">
            @csrf @method('PUT')
            <div class="p-grid-2">
                <div class="p-field">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" class="p-input" value="{{ old('name', $user->name) }}">
                    @error('name')<span class="p-err">{{ $message }}</span>@enderror
                </div>
                <div class="p-field">
                    <label>Alamat Email</label>
                    <input type="email" name="email" class="p-input" value="{{ old('email', $user->email) }}">
                    @error('email')<span class="p-err">{{ $message }}</span>@enderror
                </div>
            </div>
            <div style="display:flex; gap:12px; margin-top:4px;">
                <button type="submit" class="p-btn p-btn-primary" style="max-width:180px;">Simpan Perubahan</button>
                <button type="reset" class="p-btn p-btn-outline" style="max-width:120px;">Reset</button>
            </div>
        </form>
    </div>

</div>
@endsection