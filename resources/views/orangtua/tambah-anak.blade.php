@extends('layouts.app')

@section('title', 'Tambah Data Anak - GiziAnak')

@section('custom_css')
<style>
    .page-header { margin-bottom: 32px; }
    .page-header h2 { font-size: 28px; font-weight: 900; color: #0f1c2e; margin: 0 0 8px 0; font-family: Georgia, serif; }
    .page-header p { font-size: 15px; color: #64748b; margin: 0; }

    .form-card { background: white; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; padding: 32px; max-width: 600px; }
    
    .form-group { margin-bottom: 24px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    .form-label { display: block; font-size: 12px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
    
    .form-input { width: 100%; padding: 14px 16px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 14px; color: #1e293b; transition: all 0.2s; outline: none; box-sizing: border-box; }
    .form-input:focus { border-color: #3b82f6; background-color: white; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
    
    .btn-submit { width: 100%; padding: 14px; background: #3b82f6; color: white; border: none; border-radius: 10px; font-size: 14px; font-weight: 800; cursor: pointer; transition: 0.2s; margin-top: 16px; }
    .btn-submit:hover { background: #2563eb; }

    /* Custom Radio Button */
    .radio-group { display: flex; gap: 16px; }
    .radio-card { flex: 1; display: block; position: relative; cursor: pointer; }
    .radio-card input { display: none; }
    .radio-content { padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; text-align: center; font-weight: 700; color: #64748b; transition: 0.2s; background: #f8fafc; }
    .radio-card:hover .radio-content { border-color: #cbd5e1; }
    .radio-card input:checked + .radio-content { background: #eff6ff; border-color: #3b82f6; color: #1e293b; }

    /* --- SUCCESS POPUP CSS --- */
    .popup-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 28, 46, 0.6); z-index: 9999; display: flex; align-items: center; justify-content: center; animation: fadeInOverlay 0.3s ease; }
    .popup-content { background: white; padding: 32px; border-radius: 20px; text-align: center; max-width: 400px; width: 90%; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); animation: popUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .popup-icon { width: 64px; height: 64px; background: #dcfce7; color: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 16px; }
    .popup-title { font-size: 20px; font-weight: 900; color: #1e293b; margin: 0 0 8px 0; }
    .popup-desc { font-size: 14px; color: #64748b; margin: 0 0 24px 0; line-height: 1.5; }
    .btn-popup { background: #3b82f6; color: white; border: none; padding: 12px 24px; border-radius: 12px; font-size: 14px; font-weight: 700; cursor: pointer; width: 100%; transition: all 0.2s; }
    .btn-popup:hover { background: #2563eb; }

    @keyframes fadeInOverlay { from { opacity: 0; } to { opacity: 1; } }
    @keyframes popUp { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }

    @media (max-width: 768px) { .form-row { grid-template-columns: 1fr; gap: 16px; } }
</style>
@endsection

@section('content')

<div class="page-header">
    <h2>Daftarkan Anak</h2>
    <p>Lengkapi profil anak Anda untuk mulai memantau gizi dan melakukan konsultasi.</p>
</div>

<div class="form-card">
    <form action="{{ route('orangtua.anak.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label class="form-label">NAMA LENGKAP ANAK</label>
            <input type="text" name="nama" class="form-input" placeholder="Masukkan nama anak..." required>
        </div>

        <div class="form-group">
            <label class="form-label">JENIS KELAMIN</label>
            <div class="radio-group">
                <label class="radio-card">
                    <input type="radio" name="jenis_kelamin" value="L" required>
                    <div class="radio-content">👦 Laki-laki</div>
                </label>
                <label class="radio-card">
                    <input type="radio" name="jenis_kelamin" value="P" required>
                    <div class="radio-content">👧 Perempuan</div>
                </label>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">TANGGAL LAHIR</label>
            <input type="date" name="tanggal_lahir" class="form-input" max="{{ date('Y-m-d') }}" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">BERAT SAAT LAHIR (KG)</label>
                <input type="number" step="0.1" name="berat_lahir" class="form-input" placeholder="Contoh: 3.2" required>
            </div>
            <div class="form-group">
                <label class="form-label">PANJANG SAAT LAHIR (CM)</label>
                <input type="number" step="0.1" name="panjang_lahir" class="form-input" placeholder="Contoh: 50" required>
            </div>
        </div>

        <button type="submit" class="btn-submit">💾 Simpan Data Anak</button>
    </form>
</div>

@if(session('success'))
<div class="popup-overlay" id="successPopup">
    <div class="popup-content">
        <div class="popup-icon">✅</div>
        <h3 class="popup-title">Berhasil!</h3>
        <p class="popup-desc">{{ session('success') }}</p>
        <button class="btn-popup" onclick="closePopup()">Oke, Mengerti</button>
    </div>
</div>

<script>
    // Fungsi untuk menutup popup saat tombol "Oke, Mengerti" diklik
    function closePopup() {
        const popup = document.getElementById('successPopup');
        popup.style.opacity = '0';
        popup.style.transition = 'opacity 0.3s ease';
        setTimeout(() => {
            popup.style.display = 'none';
        }, 300);
    }
</script>
@endif

@endsection