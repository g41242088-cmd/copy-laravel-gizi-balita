@extends('layouts.app')

@section('title', 'Manajemen Akun - GiziAnak')

@section('custom_css')
<style>
    /* --- HEADER HALAMAN --- */
    .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .header-title-group h2 { margin: 0 0 8px 0; font-size: 28px; font-weight: 900; color: #0f1c2e; font-family: Georgia, serif; }
    .header-title-group p { margin: 0; color: #64748b; font-size: 15px; }

    .btn-add { background: #3b82f6; color: white; padding: 12px 20px; border-radius: 12px; font-size: 14px; font-weight: 700; cursor: pointer; border: none; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2); text-decoration: none; }
    .btn-add:hover { background: #2563eb; transform: translateY(-2px); box-shadow: 0 6px 12px rgba(59, 130, 246, 0.3); }

    /* --- FILTER & SEARCH ROW --- */
    .filter-card { background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; padding: 20px; margin-bottom: 24px; display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center; }
    
    .search-wrapper { position: relative; flex-grow: 1; max-width: 400px; }
    .search-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); font-size: 16px; color: #94a3b8; }
    .search-input { width: 100%; padding: 12px 16px 12px 42px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 14px; outline: none; transition: border-color 0.2s; background: #f8fafc; color: #1e293b; font-family: inherit; }
    .search-input:focus { border-color: #3b82f6; background: white; }

    .filter-group { display: flex; gap: 12px; }
    .select-filter { padding: 12px 36px 12px 16px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 14px; outline: none; background-color: #f8fafc; color: #1e293b; font-weight: 700; cursor: pointer; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%2364748b" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/></svg>'); background-repeat: no-repeat; background-position: right 12px center; transition: border-color 0.2s; }
    .select-filter:focus { border-color: #3b82f6; background-color: white; }

    /* --- TABLE CONTAINER --- */
    .table-container { background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; text-align: left; min-width: 900px; }
    th { padding: 16px 24px; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #f1f5f9; background-color: #f8fafc; }
    td { padding: 16px 24px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover { background-color: #f8fafc; }

    /* Elemen Profil Tabel */
    .user-profile { display: flex; align-items: center; gap: 12px; }
    .avatar { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 800; color: white; flex-shrink: 0; text-transform: uppercase;}
    .ava-admin { background: linear-gradient(135deg, #ec4899, #be185d); }
    .ava-dokter { background: linear-gradient(135deg, #10b981, #059669); }
    .ava-ahligizi { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .ava-orangtua { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
    
    .u-name { font-size: 15px; font-weight: 800; color: #1e293b; margin: 0 0 2px 0; }
    .u-email { font-size: 13px; color: #64748b; margin: 0; }

    /* Badges Role */
    .role-badge { padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; }
    .rb-admin { background: #fdf2f8; color: #db2777; border: 1px solid #fbcfe8; }
    .rb-dokter { background: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; }
    .rb-ahligizi { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .rb-orangtua { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }

    /* Status */
    .status-badge { display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 700; background: #f8fafc; padding: 6px 12px; border-radius: 8px; border: 1px solid #e2e8f0; }
    .status-badge::before { content: ''; display: inline-block; width: 8px; height: 8px; border-radius: 50%; }
    .st-aktif { color: #1e293b; } .st-aktif::before { background: #10b981; }

    /* Actions */
    .action-group { display: flex; gap: 8px; }
    .btn-action { background: white; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; cursor: pointer; color: #64748b; transition: all 0.2s; padding: 8px 12px; display: flex; align-items: center; justify-content: center; }
    .btn-action:hover { background: #f8fafc; color: #3b82f6; border-color: #cbd5e1; }
    .btn-delete:hover { color: #ef4444; background: #fef2f2; border-color: #fecaca; }

    /* --- MODAL FORM --- */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 28, 46, 0.6); z-index: 9999; display: none; align-items: center; justify-content: center; animation: fadeInOverlay 0.2s ease; }
    .modal-content { background: white; padding: 32px; border-radius: 20px; width: 100%; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); position: relative; max-height: 90vh; overflow-y: auto;}
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .modal-title { font-size: 20px; font-weight: 800; color: #1e293b; margin: 0; }
    .close-btn { background: none; border: none; font-size: 24px; color: #94a3b8; cursor: pointer; transition: 0.2s; padding: 0; }
    .close-btn:hover { color: #ef4444; }
    
    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-size: 12px; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 8px; }
    .form-input { width: 100%; padding: 12px 16px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 14px; color: #1e293b; outline: none; box-sizing: border-box; }
    .form-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); background: white;}
    .btn-submit { width: 100%; padding: 14px; background: #10b981; color: white; border: none; border-radius: 10px; font-size: 14px; font-weight: 800; cursor: pointer; transition: 0.2s; margin-top: 16px; }
    .btn-submit:hover { background: #059669; }

    /* Success Popup */
    .popup-icon-success { width: 64px; height: 64px; background: #dcfce7; color: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 16px; }

    /* --- RESPONSIVE --- */
    @media (max-width: 768px) {
        .filter-card { flex-direction: column; align-items: stretch; }
        .search-wrapper { max-width: 100%; }
        .filter-group { flex-direction: column; }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div class="header-title-group">
        <h2>Manajemen Akun</h2>
        <p>Tambah, edit, dan kelola semua pengguna sistem dalam satu panel.</p>
    </div>
    <button class="btn-add" onclick="openModal('addAccountModal')">➕ Tambah Akun Baru</button>
</div>

<div class="filter-card">
    <div class="search-wrapper">
        <span class="search-icon">🔍</span>
        <input type="text" class="search-input" id="searchInput" placeholder="Cari nama atau email...">
    </div>
    <div class="filter-group">
        <select class="select-filter" id="roleFilter">
            <option value="semua">Semua Role</option>
            <option value="admin">👨‍💻 Admin</option>
            <option value="dokter">👨‍⚕️ Dokter Anak</option>
            <option value="ahligizi">🥗 Ahli Gizi</option>
            <option value="orangtua">👨‍👩‍👧 Orang Tua</option>
        </select>
    </div>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>PROFIL PENGGUNA</th>
                <th>ROLE AKUN</th>
                <th>TANGGAL DAFTAR</th>
                <th>STATUS</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr class="data-row" data-nama="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}" data-role="{{ $user->role }}">
                <td>
                    <div class="user-profile">
                        <div class="avatar ava-{{ $user->role }}">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div>
                            <h4 class="u-name">
                                {{ $user->name }}
                                @if($user->spesialisasi)
                                    <span style="font-size: 11px; color:#3b82f6; font-weight:700;">({{ $user->spesialisasi }})</span>
                                @endif
                            </h4>
                            <p class="u-email">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="role-badge rb-{{ $user->role }}">
                        {{ $user->role == 'ahligizi' ? 'Ahli Gizi' : ($user->role == 'orangtua' ? 'Orang Tua' : $user->role) }}
                    </span>
                </td>
                <td><span style="font-size: 13px; font-weight:700; color:#1e293b;">{{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d M Y') }}</span></td>
                <td><span class="status-badge st-aktif">Aktif</span></td>
                <td>
                    <div class="action-group">
                        <button class="btn-action" title="Edit Akun" 
                                onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}', '{{ $user->spesialisasi ?? '' }}')">
                            ✏️
                        </button>

                        <form action="{{ route('admin.akun.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }} secara permanen? Semua data yang terkait dengan akun ini akan ikut terhapus!');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete" title="Hapus Akun">🗑️</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
            <tr id="noDataRow" style="display: none;">
                <td colspan="5" style="text-align: center; padding: 32px; color: #64748b;">Data tidak ditemukan.</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="modal-overlay" id="addAccountModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Tambah Akun Baru</h3>
            <button class="close-btn" onclick="closeModal('addAccountModal')">&times;</button>
        </div>
        
        <form action="{{ route('admin.akun.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">NAMA LENGKAP</label>
                <input type="text" name="name" class="form-input" placeholder="Masukkan nama..." required>
            </div>
            
            <div class="form-group">
                <label class="form-label">ALAMAT EMAIL</label>
                <input type="email" name="email" class="form-input" placeholder="email@contoh.com" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">PASSWORD</label>
                <input type="password" name="password" class="form-input" placeholder="Minimal 6 karakter" required minlength="6">
            </div>
            
            <div class="form-group">
                <label class="form-label">PILIH ROLE / HAK AKSES</label>
                <select name="role" id="roleSelect" class="form-input" required>
                    <option value="" disabled selected>-- Pilih Role --</option>
                    <option value="admin">Admin</option>
                    <option value="dokter">Dokter Anak</option>
                    <option value="ahligizi">Ahli Gizi</option>
                    <option value="orangtua">Orang Tua</option>
                </select>
            </div>

            <div class="form-group" id="spesialisasiGroup" style="display: none; background: #eff6ff; padding: 16px; border-radius: 10px; border: 1px dashed #bfdbfe;">
                <label class="form-label" style="color: #1e3a8a;">SPESIALISASI MEDIS</label>
                <input type="text" name="spesialisasi" id="spesialisasiInput" class="form-input" placeholder="Contoh: Dokter Anak & Tumbuh Kembang">
                <p style="font-size: 11px; color: #3b82f6; margin: 6px 0 0 0;">Spesialisasi ini akan muncul di halaman Booking Orang Tua.</p>
            </div>

            <button type="submit" class="btn-submit">💾 Simpan Akun Baru</button>
        </form>
    </div>
</div>
<div class="modal-overlay" id="editAccountModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Edit Data Akun</h3>
            <button class="close-btn" onclick="closeModal('editAccountModal')">&times;</button>
        </div>
        
        <form id="editForm" method="POST">
            @csrf
            @method('PUT') <div class="form-group">
                <label class="form-label">NAMA LENGKAP</label>
                <input type="text" name="name" id="editName" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">ALAMAT EMAIL</label>
                <input type="email" name="email" id="editEmail" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">GANTI PASSWORD (OPSIONAL)</label>
                <input type="password" name="password" class="form-input" placeholder="Kosongkan jika tidak ingin ganti password" minlength="6">
            </div>
            
            <div class="form-group">
                <label class="form-label">PILIH ROLE / HAK AKSES</label>
                <select name="role" id="editRoleSelect" class="form-input" required>
                    <option value="admin">Admin</option>
                    <option value="dokter">Dokter Anak</option>
                    <option value="ahligizi">Ahli Gizi</option>
                    <option value="orangtua">Orang Tua</option>
                </select>
            </div>

            <div class="form-group" id="editSpesialisasiGroup" style="display: none; background: #fffbeb; padding: 16px; border-radius: 10px; border: 1px dashed #fde68a;">
                <label class="form-label" style="color: #d97706;">SPESIALISASI MEDIS</label>
                <input type="text" name="spesialisasi" id="editSpesialisasiInput" class="form-input" placeholder="Contoh: Dokter Anak & Tumbuh Kembang">
            </div>

            <button type="submit" class="btn-submit" style="background: #3b82f6;">💾 Simpan Perubahan</button>
        </form>
    </div>
</div>

@if(session('success'))
<div class="modal-overlay" id="successPopup" style="display: flex;">
    <div class="modal-content" style="text-align: center; max-width: 350px;">
        <div class="popup-icon-success">✅</div>
        <h3 style="font-size: 20px; font-weight: 900; color: #1e293b; margin: 0 0 8px 0;">Berhasil!</h3>
        <p style="font-size: 14px; color: #64748b; margin: 0 0 24px 0; line-height: 1.5;">{{ session('success') }}</p>
        <button class="btn btn-add" style="width: 100%; justify-content: center;" onclick="closeModal('successPopup')">Oke, Mengerti</button>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
    // --- FUNGSI BUKA & TUTUP MODAL ---
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'flex';
    }
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // --- LOGIKA MUNCULKAN SPESIALISASI BERDASARKAN ROLE ---
    document.getElementById('roleSelect').addEventListener('change', function() {
        const selectedRole = this.value;
        const spesialisasiGroup = document.getElementById('spesialisasiGroup');
        const spesialisasiInput = document.getElementById('spesialisasiInput');

        if (selectedRole === 'dokter' || selectedRole === 'ahligizi') {
            spesialisasiGroup.style.display = 'block';
            spesialisasiInput.setAttribute('required', 'required'); // Wajib isi jika tenaga medis
        } else {
            spesialisasiGroup.style.display = 'none';
            spesialisasiInput.removeAttribute('required');
            spesialisasiInput.value = ''; // Kosongkan
        }
    });

    // --- LOGIKA PENCARIAN & FILTER TABEL ---
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const roleFilter = document.getElementById('roleFilter');
        const dataRows = document.querySelectorAll('.data-row');
        const noDataRow = document.getElementById('noDataRow');

        function filterTable() {
            const query = searchInput.value.toLowerCase();
            const selectedRole = roleFilter.value;
            let visibleCount = 0;

            dataRows.forEach(row => {
                const nama = row.getAttribute('data-nama');
                const email = row.getAttribute('data-email');
                const role = row.getAttribute('data-role');

                const matchesSearch = nama.includes(query) || email.includes(query);
                const matchesRole = (selectedRole === 'semua') || (role === selectedRole);

                if (matchesSearch && matchesRole) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            noDataRow.style.display = visibleCount === 0 ? '' : 'none';
        }

        searchInput.addEventListener('input', filterTable);
        roleFilter.addEventListener('change', filterTable);
    });
    // --- FUNGSI UNTUK MODAL EDIT ---
    function openEditModal(id, name, email, role, spesialisasi) {
        // 1. Set URL Action Form
        document.getElementById('editForm').action = "/admin/users/" + id; // Sesuaikan jika prefix route Anda berbeda
        
        // 2. Isi nilai ke dalam input form
        document.getElementById('editName').value = name;
        document.getElementById('editEmail').value = email;
        document.getElementById('editRoleSelect').value = role;

        // 3. Atur spesialisasi (Tampil / Sembunyi)
        const specGroup = document.getElementById('editSpesialisasiGroup');
        const specInput = document.getElementById('editSpesialisasiInput');

        if (role === 'dokter' || role === 'ahligizi') {
            specGroup.style.display = 'block';
            specInput.value = spesialisasi;
            specInput.setAttribute('required', 'required');
        } else {
            specGroup.style.display = 'none';
            specInput.value = '';
            specInput.removeAttribute('required');
        }

        // Tampilkan Modal
        openModal('editAccountModal');
    }

    // --- LOGIKA MUNCULKAN SPESIALISASI BERDASARKAN ROLE DI MODAL EDIT ---
    document.getElementById('editRoleSelect').addEventListener('change', function() {
        const selectedRole = this.value;
        const specGroup = document.getElementById('editSpesialisasiGroup');
        const specInput = document.getElementById('editSpesialisasiInput');

        if (selectedRole === 'dokter' || selectedRole === 'ahligizi') {
            specGroup.style.display = 'block';
            specInput.setAttribute('required', 'required');
        } else {
            specGroup.style.display = 'none';
            specInput.removeAttribute('required');
            specInput.value = ''; 
        }
    });
</script>
@endpush