@extends('layouts.app')

@section('title', 'Akun Saya - GiziAnak')

@section('custom_css')
<style>
    /* --- HEADER HALAMAN --- */
    .page-header { margin-bottom: 24px; }
    .page-header h2 { font-size: 28px; font-weight: 900; color: #0f1c2e; margin: 0 0 8px 0; font-family: Georgia, serif; }
    .page-header p { font-size: 15px; color: #64748b; margin: 0; }

    /* --- LAYOUT UTAMA --- */
    .account-grid { display: grid; grid-template-columns: 300px 1fr; gap: 24px; align-items: start; }

    /* --- KOLOM KIRI (SIDEBAR PROFIL) --- */
    .profile-card { background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; overflow: hidden; margin-bottom: 16px; }
    .profile-cover { height: 100px; background: #1e3a8a; }
    .profile-info { padding: 0 20px 20px; text-align: center; margin-top: -40px; }
    .profile-avatar { width: 80px; height: 80px; border-radius: 50%; border: 4px solid white; background: #3b82f6; color: white; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; margin-bottom: 12px; text-transform: uppercase;}
    .profile-name { font-size: 18px; font-weight: 800; color: #1e293b; margin: 0 0 4px 0; }
    .profile-email { font-size: 13px; color: #64748b; margin: 0 0 12px 0; }
    .profile-badge { display: inline-flex; align-items: center; gap: 6px; background: #eff6ff; color: #2563eb; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: capitalize;}
    
    .profile-stats { display: flex; border-top: 1px solid #f1f5f9; padding: 16px 0; }
    .stat-box { flex: 1; text-align: center; border-right: 1px solid #f1f5f9; }
    .stat-box:last-child { border-right: none; }
    .stat-val { font-size: 20px; font-weight: 900; color: #1e3a8a; font-family: Georgia, serif; line-height: 1; margin: 0 0 4px 0; }
    .stat-label { font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin: 0; }

    /* Menu List di Kolom Kiri */
    .menu-list-card { background: white; border-radius: 16px; border: 1px solid #f1f5f9; padding: 8px; margin-bottom: 16px; }
    .menu-list-item { display: flex; align-items: center; padding: 12px; border-radius: 10px; text-decoration: none; color: #334155; transition: background 0.2s; cursor: pointer; border: none; width: 100%; text-align: left; background: transparent;}
    .menu-list-item:hover { background: #f8fafc; }
    .menu-icon { width: 36px; height: 36px; border-radius: 8px; background: #eff6ff; display: flex; align-items: center; justify-content: center; font-size: 16px; margin-right: 12px; flex-shrink: 0;}
    .menu-text { flex: 1; }
    .menu-title { font-size: 13px; font-weight: 700; margin: 0 0 2px 0; }
    .menu-desc { font-size: 11px; color: #94a3b8; margin: 0; }
    .menu-arrow { color: #cbd5e1; font-size: 12px; font-weight: bold; }

    .btn-logout { width: 100%; padding: 14px; background: #fef2f2; border: 1px solid #fee2e2; color: #dc2626; border-radius: 12px; font-size: 14px; font-weight: 700; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 8px; transition: all 0.2s; }
    .btn-logout:hover { background: #fee2e2; }

    /* --- KOLOM KANAN (FORM) --- */
    .form-card { background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; overflow: hidden; }
    
    /* Tabs System */
    .tabs-header { display: flex; border-bottom: 1px solid #e2e8f0; background: #f8fafc; overflow-x: auto; }
    .tab-item { padding: 16px 20px; font-size: 13px; font-weight: 700; color: #64748b; cursor: pointer; display: flex; align-items: center; gap: 8px; border-bottom: 2px solid transparent; white-space: nowrap; transition: all 0.2s; }
    .tab-item.active { color: #2563eb; border-bottom-color: #2563eb; background: white; }
    .tab-item:hover:not(.active) { color: #3b82f6; }

    .tab-content { display: none; animation: fadeIn 0.3s ease; }
    .tab-content.active { display: block; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

    .info-alert { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 16px; margin: 24px; font-size: 13px; color: #475569; line-height: 1.5; }
    .info-alert strong { color: #1e40af; }

    .form-body { padding: 0 24px 24px; }
    .akun-section-title { font-size: 12px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
    
    .form-group { margin-bottom: 20px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .form-label { display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
    
    .input-wrapper { position: relative; display: flex; align-items: center; }
    .input-icon { position: absolute; left: 16px; font-size: 14px; color: #94a3b8; }
    .form-input { width: 100%; padding: 12px 16px 12px 42px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 14px; color: #334155; outline: none; transition: all 0.2s; font-family: inherit; box-sizing: border-box;}
    .form-input:focus { border-color: #3b82f6; background-color: white; }
    .form-input:disabled, .form-input[readonly] { background-color: #f1f5f9; cursor: not-allowed; }
    .form-input.no-icon { padding-left: 16px; }
    .input-hint { font-size: 11px; color: #94a3b8; font-style: italic; margin-top: 6px; display: block; }
    
    .form-actions { display: flex; justify-content: flex-end; gap: 12px; padding-top: 24px; margin-top: 24px; border-top: 1px solid #f1f5f9; }
    .btn { padding: 12px 24px; border-radius: 10px; font-size: 14px; font-weight: 700; cursor: pointer; transition: all 0.2s; border: none; display: flex; align-items: center; gap: 8px; text-decoration: none;}
    .btn-outline { background: white; border: 1px solid #e2e8f0; color: #475569; }
    .btn-outline:hover { background: #f8fafc; }
    .btn-primary { background: #3b82f6; color: white; }
    .btn-primary:hover { background: #2563eb; }
    .btn-green { background: #10b981; color: white; }
    .btn-green:hover { background: #059669; }

    /* Custom Toggle Switch */
    .switch { position: relative; display: inline-block; width: 44px; height: 24px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #e2e8f0; transition: .3s; border-radius: 24px; }
    .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    input:checked + .slider { background-color: #10b981; } 
    input:checked + .slider:before { transform: translateX(20px); }

    /* Child Cards */
    .child-card { display: flex; align-items: center; justify-content: space-between; padding: 16px; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 12px; background: #f8fafc; }
    .child-info { display: flex; align-items: center; gap: 12px; }
    .child-ava { width: 48px; height: 48px; background: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }

    /* Data Display List */
    .data-display-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    .data-item p:first-child { font-size: 11px; color: #94a3b8; font-weight: 700; text-transform: uppercase; margin: 0 0 4px 0; }
    .data-item p:last-child { font-size: 14px; font-weight: 700; color: #1e293b; margin: 0; }

    /* Aksi Data Anak */
    .child-actions { display: flex; gap: 8px; }
    .btn-icon { width: 32px; height: 32px; border-radius: 8px; border: 1px solid #e2e8f0; background: white; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s; font-size: 14px; padding: 0; color: #64748b;}
    .btn-icon:hover { background: #f8fafc; border-color: #cbd5e1; transform: translateY(-2px); }
    .btn-icon-delete:hover { background: #fef2f2; border-color: #fecaca; }

    /* Modal Khusus Data Anak */
    .anak-modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 28, 46, 0.6); z-index: 10000; display: none; align-items: center; justify-content: center; animation: fadeIn 0.2s; }
    .anak-modal.active { display: flex; }
    .anak-modal-content { background: white; padding: 24px; border-radius: 20px; width: 100%; max-width: 450px; position: relative; }
    .close-modal-btn { position: absolute; top: 16px; right: 16px; background: none; border: none; font-size: 24px; cursor: pointer; color: #94a3b8; line-height: 1;}
    .close-modal-btn:hover { color: #ef4444; }

    /* Success/Error Popup Overlay */
    .popup-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 28, 46, 0.6); z-index: 9999; display: flex; align-items: center; justify-content: center; opacity: 0; visibility: hidden; transition: 0.3s; }
    .popup-overlay.active { opacity: 1; visibility: visible; }
    .popup-content { background: white; padding: 32px; border-radius: 20px; text-align: center; max-width: 400px; transform: scale(0.9); transition: 0.3s; }
    .popup-overlay.active .popup-content { transform: scale(1); }

    @media (max-width: 992px) { .account-grid { grid-template-columns: 1fr; } }
    @media (max-width: 640px) { .form-row, .data-display-row { grid-template-columns: 1fr; gap: 16px; } .form-actions { flex-direction: column; } .btn { width: 100%; justify-content: center; } }
</style>
@endsection

@section('content')

<div class="page-header">
    <h2>Akun Saya</h2>
    <p>Kelola profil, data anak, dan pengaturan akun Anda</p>
</div>

<div class="account-grid">
    
    <!-- KOLOM KIRI -->
    <div>
        <div class="profile-card">
            <div class="profile-cover"></div>
            <div class="profile-info">
                <div class="profile-avatar">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <h3 class="profile-name">{{ $user->name }}</h3>
                <p class="profile-email">{{ $user->email }}</p>
                <div class="profile-badge">
                    <span>👨‍👩‍👧</span> {{ str_replace('_', ' ', $user->role) }}
                </div>
            </div>
            <div class="profile-stats">
                <div class="stat-box">
                    <p class="stat-val">{{ $totalAnak }}</p>
                    <p class="stat-label">Anak</p>
                </div>
                <div class="stat-box">
                    <p class="stat-val">{{ $totalCekGizi }}</p>
                    <p class="stat-label">Cek Gizi</p>
                </div>
                <div class="stat-box">
                    <p class="stat-val">{{ $totalKonsultasi }}</p>
                    <p class="stat-label">Jadwal</p>
                </div>
            </div>
        </div>

        <div class="menu-list-card">
            <button type="button" class="menu-list-item" onclick="switchTab('tab-privasi')">
                <div class="menu-icon" style="background: #e0e7ff; color: #4f46e5;">🛡️</div>
                <div class="menu-text">
                    <p class="menu-title">Privasi & Data</p>
                    <p class="menu-desc">Kelola data pribadi Anda</p>
                </div>
                <span class="menu-arrow">❯</span>
            </button>
            <button type="button" class="menu-list-item" onclick="switchTab('tab-keamanan')">
                <div class="menu-icon" style="background: #fef9c3; color: #ca8a04;">🔒</div>
                <div class="menu-text">
                    <p class="menu-title">Ubah Kata Sandi</p>
                    <p class="menu-desc">Perbarui keamanan akun</p>
                </div>
                <span class="menu-arrow">❯</span>
            </button>
            <button type="button" class="menu-list-item" onclick="switchTab('tab-notifikasi')">
                <div class="menu-icon" style="background: #ffedd5; color: #ea580c;">🔔</div>
                <div class="menu-text">
                    <p class="menu-title">Notifikasi</p>
                    <p class="menu-desc">Atur preferensi notifikasi</p>
                </div>
                <span class="menu-arrow">❯</span>
            </button>
            <button type="button" class="menu-list-item">
                <div class="menu-icon" style="background: #fee2e2; color: #dc2626;">❓</div>
                <div class="menu-text">
                    <p class="menu-title">Bantuan</p>
                    <p class="menu-desc">Pusat bantuan & FAQ</p>
                </div>
                <span class="menu-arrow">❯</span>
            </button>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">🚪 Keluar dari Akun</button>
        </form>
    </div>

    <!-- KOLOM KANAN -->
    <div class="form-card">
        
        <!-- Tabs Header -->
        <div class="tabs-header">
            <div class="tab-item active" data-target="tab-profil">👤 Profil Saya</div>
            <div class="tab-item" data-target="tab-anak">👶 Data Anak</div>
            <div class="tab-item" data-target="tab-keamanan">🔒 Keamanan</div>
            <div class="tab-item" data-target="tab-notifikasi">🔔 Notifikasi</div>
            <div class="tab-item" data-target="tab-privasi">🛡️ Privasi</div>
        </div>

        <!-- TAB 1: PROFIL SAYA (FORM EDIT) -->
        <div id="tab-profil" class="tab-content active">
            <div class="info-alert">
                📄 <strong>Info Profil</strong> — Pastikan nama Anda sesuai dengan identitas asli agar memudahkan tenaga medis saat sesi konsultasi. Email tidak dapat diubah karena terhubung dengan akun login.
            </div>

            <div class="form-body">
                <div class="akun-section-title"><span>📌</span> INFORMASI AKUN</div>
                <form action="{{ route('orangtua.akun.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap <span style="color: red;">*</span></label>
                        <div class="input-wrapper">
                            <span class="input-icon">👤</span>
                            <input type="text" name="name" class="form-input" value="{{ $user->name }}" required>
                        </div>
                        <span class="input-hint">Nama ini akan ditampilkan di seluruh aplikasi</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email <span style="color: red;">*</span></label>
                        <div class="input-wrapper">
                            <span class="input-icon">✉️</span>
                            <input type="email" class="form-input" value="{{ $user->email }}" readonly>
                        </div>
                        <span class="input-hint">Email digunakan untuk login dan tidak dapat diubah</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">No. Telepon / WhatsApp</label>
                        <div class="input-wrapper">
                            <span class="input-icon">📱</span>
                            <input type="text" name="no_telepon" class="form-input" value="{{ $user->no_telepon ?? '' }}" placeholder="Contoh: 08123456789">
                        </div>
                        <span class="input-hint">Format: 08xxxxxxxxx atau +628xxxxxxxxx</span>
                    </div>

                    <!-- BARIS 1: KOTA & JENIS KELAMIN -->
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Kota / Domisili</label>
                            <div class="input-wrapper">
                                <span class="input-icon">📍</span>
                                <input type="text" name="kota" class="form-input" value="{{ $user->kota ?? '' }}" placeholder="Contoh: Surabaya">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-input no-icon">
                                <option value="" {{ empty($user->jenis_kelamin) ? 'selected' : '' }}>-- Pilih --</option>
                                <option value="L" {{ ($user->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ ($user->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <!-- BARIS 2: TANGGAL LAHIR & ROLE AKUN -->
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-input no-icon" value="{{ $user->tanggal_lahir ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Role Akun</label>
                            <input type="text" class="form-input no-icon" value="Orang Tua" readonly disabled>
                            <span class="input-hint">Role tidak dapat diubah sendiri</span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="reset" class="btn btn-outline">↩ Batal</button>
                        <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TAB 2: DATA ANAK -->
        <div id="tab-anak" class="tab-content">
            <div class="form-body" style="padding-top: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <div class="akun-section-title" style="margin: 0;"><span>👶</span> DAFTAR ANAK ANDA</div>
                    <a href="{{ route('orangtua.anak.create') }}" class="btn btn-green" style="padding: 8px 16px; font-size: 12px;">+ Tambah Anak</a>
                </div>

                @forelse($anaks as $anak)
                    @php
                        // Menghitung Usia Anak (Contoh: 2 Tahun, 3 Bulan)
                        $usia = \Carbon\Carbon::parse($anak->tanggal_lahir)->diff(\Carbon\Carbon::now())->format('%y Thn, %m Bln');
                        $jenisKelaminText = $anak->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
                        $tglLahirText = \Carbon\Carbon::parse($anak->tanggal_lahir)->translatedFormat('d F Y');
                    @endphp
                    <div class="child-card">
                        <div class="child-info">
                            <div class="child-ava">{{ $anak->jenis_kelamin == 'L' ? '👦' : '👧' }}</div>
                            <div>
                                <h4 style="margin: 0 0 4px 0; font-size: 15px; font-weight: 800; color: #1e293b; text-transform: capitalize;">{{ $anak->nama }}</h4>
                                <p style="margin: 0; font-size: 12px; color: #64748b;">Lahir: {{ $tglLahirText }}</p>
                            </div>
                        </div>
                        <div class="child-actions">
                            <!-- Tombol Lihat Detail -->
                            <button class="btn-icon" onclick="openDetailAnak('{{ $anak->nama }}', '{{ $anak->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}', '{{ \Carbon\Carbon::parse($anak->tanggal_lahir)->translatedFormat('d F Y') }}', '{{ $anak->berat_lahir ?? '-' }} kg', '{{ $anak->panjang_lahir ?? '-' }} cm')">👁️</button>
                            
                            <!-- Tombol Edit -->
                            <button class="btn-icon" title="Edit Data" onclick="openEditAnak({{ $anak->id }}, '{{ $anak->nama }}', '{{ $anak->jenis_kelamin }}', '{{ $anak->tanggal_lahir }}')">✏️</button>
                            
                            <!-- Tombol Hapus -->
                            <form action="{{ url('orangtua/tambah-anak/'.$anak->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus data anak ini? Data cek gizi yang terhubung juga akan ikut terhapus!');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-icon-delete" title="Hapus Data">🗑️</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 40px 20px; border: 1px dashed #cbd5e1; border-radius: 12px;">
                        <span style="font-size: 32px; display: block; margin-bottom: 12px; opacity: 0.5;">👶</span>
                        <p style="color: #64748b; margin-bottom: 16px; font-size: 14px;">Anda belum menambahkan data anak.</p>
                        <a href="{{ route('orangtua.anak.create') }}" class="btn btn-primary" style="display: inline-flex; width: auto;">+ Tambah Data Sekarang</a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- TAB 3: KEAMANAN (GANTI PASSWORD) -->
        <div id="tab-keamanan" class="tab-content">
            <div class="form-body" style="padding-top: 24px;">
                <div class="akun-section-title"><span>🔒</span> UBAH KATA SANDI</div>
                
                <form action="{{ route('orangtua.akun.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label class="form-label">Password Saat Ini <span style="color: red;">*</span></label>
                        <div class="input-wrapper">
                            <span class="input-icon">🔑</span>
                            <input type="password" name="current_password" class="form-input" placeholder="Masukkan password Anda saat ini" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Password Baru <span style="color: red;">*</span></label>
                            <div class="input-wrapper">
                                <span class="input-icon">🔐</span>
                                <input type="password" name="password" class="form-input" placeholder="Minimal 6 karakter" required minlength="6">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Konfirmasi Password Baru <span style="color: red;">*</span></label>
                            <div class="input-wrapper">
                                <span class="input-icon">🔐</span>
                                <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password baru" required minlength="6">
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">🔐 Perbarui Password</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TAB 4: NOTIFIKASI -->
        <div id="tab-notifikasi" class="tab-content">
            <div class="form-body" style="padding-top: 24px;">
                <div class="akun-section-title"><span>🔔</span> PENGATURAN NOTIFIKASI</div>
                
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <div>
                            <h4 style="font-size: 14px; font-weight: 800; color: #1e293b; margin: 0 0 4px 0;">Pemberitahuan WhatsApp</h4>
                            <p style="font-size: 12px; color: #64748b; margin: 0;">Terima notifikasi jadwal dan status konsultasi via WA</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h4 style="font-size: 14px; font-weight: 800; color: #1e293b; margin: 0 0 4px 0;">Pemberitahuan Email</h4>
                            <p style="font-size: 12px; color: #64748b; margin: 0;">Terima ringkasan bulanan tumbuh kembang anak</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
                <span class="input-hint">Catatan: Pengaturan ini akan otomatis tersimpan.</span>
            </div>
        </div>

        <!-- TAB 5: PRIVASI & DATA (BACA SAJA) -->
        <div id="tab-privasi" class="tab-content">
            <div class="form-body" style="padding-top: 24px;">
                <div class="akun-section-title"><span>🛡️</span> DATA PRIBADI YANG TERSIMPAN</div>
                
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px;">
                    
                    <div class="data-display-row">
                        <div class="data-item">
                            <p>Nama Lengkap</p>
                            <p>{{ $user->name ?? '-' }}</p>
                        </div>
                        <div class="data-item">
                            <p>Email</p>
                            <p>{{ $user->email ?? '-' }}</p>
                        </div>
                    </div>
                    
                    <div class="data-display-row">
                        <div class="data-item">
                            <p>No. Telepon / WhatsApp</p>
                            <p>{{ $user->no_telepon ?? 'Belum diatur' }}</p>
                        </div>
                        <div class="data-item">
                            <p>Kota / Domisili</p>
                            <p>{{ $user->kota ?? 'Belum diatur' }}</p>
                        </div>
                    </div>
                    
                    <div class="data-display-row">
                        <div class="data-item">
                            <p>Jenis Kelamin</p>
                            <p>{{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : ($user->jenis_kelamin == 'P' ? 'Perempuan' : 'Belum diatur') }}</p>
                        </div>
                        <div class="data-item">
                            <p>Tanggal Lahir</p>
                            <p>{{ $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->translatedFormat('d F Y') : 'Belum diatur' }}</p>
                        </div>
                    </div>
                    
                </div>
                
                <div style="margin-top: 24px; text-align: right;">
                    <button type="button" class="btn btn-outline" onclick="switchTab('tab-profil')">✏️ Edit Data Profil</button>
                </div>
            </div>
        </div>
        
    </div>
</div>

<!-- POPUP SUCCESS & ERROR -->
@if(session('success') || session('error'))
<div class="popup-overlay active" id="notificationPopup">
    <div class="popup-content">
        <div style="font-size: 50px; margin-bottom: 16px;">{{ session('success') ? '✅' : '❌' }}</div>
        <h3 style="font-size: 20px; font-weight: 900; color: {{ session('success') ? '#1e293b' : '#ef4444' }}; margin: 0 0 8px 0;">
            {{ session('success') ? 'Berhasil!' : 'Gagal!' }}
        </h3>
        <p style="font-size: 14px; color: #64748b; margin: 0 0 24px 0;">{{ session('success') ?? session('error') }}</p>
        <button class="btn btn-primary" style="width: 100%; justify-content: center; {{ session('error') ? 'background: #ef4444;' : '' }}" onclick="document.getElementById('notificationPopup').classList.remove('active')">Oke, Tutup</button>
    </div>
</div>
@endif
<!-- MODAL: LIHAT DETAIL ANAK -->
<!-- MODAL DETAIL ANAK -->
<div class="anak-modal" id="detailAnakModal">
    <div class="anak-modal-content">
        <button class="close-modal-btn" onclick="closeAnakModal('detailAnakModal')">&times;</button>
        <div class="akun-section-title" style="margin-top: 0;"><span>👁️</span> DETAIL DATA ANAK</div>
        
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px;">
            <div class="data-display-row">
                <div class="data-item"><p>Nama Lengkap</p><p id="det-nama">-</p></div>
                <div class="data-item"><p>Jenis Kelamin</p><p id="det-jk">-</p></div>
            </div>
            <div class="data-display-row">
                <div class="data-item"><p>Tanggal Lahir</p><p id="det-tgl">-</p></div>
                <div class="data-item"><p>Berat Lahir</p><p id="det-berat">-</p></div>
            </div>
            <div class="data-display-row" style="margin-bottom: 0;">
                <div class="data-item"><p>Panjang Lahir</p><p id="det-panjang">-</p></div>
            </div>
        </div>
    </div>
</div>
<!-- MODAL: EDIT DATA ANAK -->
<div class="anak-modal" id="editAnakModal">
    <div class="anak-modal-content">
        <button class="close-modal-btn" onclick="closeAnakModal('editAnakModal')">&times;</button>
        <div class="akun-section-title" style="margin-top: 0;"><span>✏️</span> EDIT DATA ANAK</div>
        
        <!-- Form akan dikirim ke /orangtua/tambah-anak/{id} -->
        <form id="formEditAnak" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label">Nama Anak <span style="color: red;">*</span></label>
                <input type="text" name="nama" id="edit_nama_anak" class="form-input no-icon" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tanggal Lahir <span style="color: red;">*</span></label>
                    <input type="date" name="tanggal_lahir" id="edit_tgl_anak" class="form-input no-icon" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Kelamin <span style="color: red;">*</span></label>
                    <select name="jenis_kelamin" id="edit_jk_anak" class="form-input no-icon" required>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="form-actions" style="margin-top: 16px; padding-top: 16px;">
                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">💾 Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // --- SCRIPT UNTUK TAB NAVIGATION ---
    const tabs = document.querySelectorAll('.tab-item');
    const contents = document.querySelectorAll('.tab-content');

    // Fungsi global agar bisa dipanggil dari tombol sidebar kiri
    window.switchTab = function(targetId) {
        // Hilangkan semua status active
        tabs.forEach(t => t.classList.remove('active'));
        contents.forEach(c => c.classList.remove('active'));

        // Cari tab header yang sesuai dengan targetId dan aktifkan
        const targetTab = document.querySelector(`.tab-item[data-target="${targetId}"]`);
        if(targetTab) targetTab.classList.add('active');

        // Tampilkan konten tabnya
        document.getElementById(targetId).classList.add('active');
    };

    // Event listener untuk klik pada Header Tabs (atas)
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const targetId = tab.getAttribute('data-target');
            switchTab(targetId);
        });
    });
    // --- FUNGSI UNTUK MODAL DATA ANAK ---
    function openDetailAnak(nama, jk, tgl, berat, panjang) {
    document.getElementById('det-nama').innerText = nama;
    document.getElementById('det-jk').innerText = jk;
    document.getElementById('det-tgl').innerText = tgl;
    document.getElementById('det-berat').innerText = berat;
    document.getElementById('det-panjang').innerText = panjang;
    document.getElementById('detailAnakModal').classList.add('active');
}

    function openEditAnak(id, nama, jk, tgl) {
        // Arahkan form URL ke rute update menggunakan ID
        document.getElementById('formEditAnak').action = "/orangtua/tambah-anak/" + id; 
        
        document.getElementById('edit_nama_anak').value = nama;
        document.getElementById('edit_jk_anak').value = jk;
        document.getElementById('edit_tgl_anak').value = tgl;
        document.getElementById('editAnakModal').classList.add('active');
    }

    function closeAnakModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
    }
</script>
@endpush