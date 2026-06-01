@extends('layouts.app')

@section('title', 'Tips Gizi & Tumbuh Kembang - GiziAnak')

@section('custom_css')
<style>
    /* --- HEADER HALAMAN --- */
    .page-header { margin-bottom: 32px; }
    .page-header h2 { font-size: 28px; font-weight: 900; color: #0f1c2e; margin: 0 0 8px 0; font-family: Georgia, serif; }
    .page-header p { font-size: 15px; color: #64748b; margin: 0; }

    /* --- GRID CARDS --- */
    .tips-grid { 
        display: grid; 
        grid-template-columns: repeat(4, 1fr); 
        gap: 24px; 
    }

    /* --- CARD STYLING --- */
    .tip-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        border: 1px solid #f1f5f9;
        padding: 24px;
        display: flex;
        flex-direction: column;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .tip-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
    }

    /* Header dalam Card */
    .card-top {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 16px;
    }
    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }
    .icon-blue { background-color: #eff6ff; }
    .icon-green { background-color: #f0fdf4; }
    .icon-orange { background-color: #fff7ed; }

    .age-label {
        font-size: 11px;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0 0 4px 0;
    }
    .tip-title {
        font-size: 16px;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
        line-height: 1.3;
    }

    /* Konten Card */
    .tip-desc {
        font-size: 13px;
        color: #64748b;
        line-height: 1.6;
        margin: 0 0 20px 0;
        flex-grow: 1; /* Mendorong elemen bawah ke dasar card */
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Tags */
    .tags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 20px;
    }
    .tag {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
    }

    /* Link Baca Selengkapnya */
    .read-more {
        font-size: 13px;
        font-weight: 700;
        color: #3b82f6;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: color 0.2s;
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        font-family: inherit;
        text-align: left;
    }
    .read-more:hover { color: #2563eb; }

    /* =======================================
       CSS MODAL (POP-UP BACA ARTIKEL PENUH)
       ======================================= */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 28, 46, 0.7); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 9999; opacity: 0; transition: opacity 0.3s ease; }
    .modal-overlay.active { display: flex; opacity: 1; }
    
    .modal-content { background: white; width: 90%; max-width: 750px; border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.2); transform: translateY(20px); transition: transform 0.3s ease; max-height: 90vh; display: flex; flex-direction: column; position: relative; }
    .modal-overlay.active .modal-content { transform: translateY(0); }
    
    .btn-close-float { position: absolute; top: 16px; right: 16px; background: rgba(255,255,255,0.9); border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 16px; font-weight: bold; color: #1e293b; cursor: pointer; transition: 0.2s; z-index: 10; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .btn-close-float:hover { background: white; color: #dc2626; transform: scale(1.05); }
    
    .modal-cover { 
    width: 100%; 
    height: 250px; 
    background: #f8fafc; /* Latar belakang abu-abu terang */
    object-fit: contain; /* 👈 KUNCI: Membuat logo tampil utuh, tidak terpotong */
    padding: 24px; /* Memberi jarak agar logo tidak nabrak pinggiran */
    box-sizing: border-box; /* Memastikan padding tidak merusak ukuran */
}
    
    .modal-body { overflow-y: auto; padding: 32px; flex-grow: 1; }
    .modal-body::-webkit-scrollbar { width: 6px; }
    .modal-body::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    
    .art-kategori { font-size: 13px; font-weight: 800; color: #3b82f6; text-transform: uppercase; margin-bottom: 8px; display: block; }
    .art-judul { font-size: 28px; font-weight: 900; color: #1e293b; margin: 0 0 16px 0; line-height: 1.3; }
    .art-meta { display: flex; gap: 16px; font-size: 14px; color: #64748b; font-weight: 600; padding-bottom: 20px; border-bottom: 1px solid #f1f5f9; margin-bottom: 24px; }
    
    .art-konten { font-size: 16px; color: #334155; line-height: 1.8; }
    .art-konten p { margin-top: 0; margin-bottom: 20px; }
    .art-konten h3 { color: #1e293b; font-size: 20px; margin-top: 30px; margin-bottom: 12px; }

    /* --- RESPONSIVE --- */
    @media (max-width: 1400px) { .tips-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 1024px) { .tips-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px) { 
        .tips-grid { grid-template-columns: 1fr; } 
        .modal-body { padding: 20px; }
        .art-judul { font-size: 22px; }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <h2>Tips Gizi & Tumbuh Kembang</h2>
    <p>Panduan nutrisi anak per kelompok usia — klik kartu untuk detail lengkap</p>
</div>

<div class="tips-grid">
    @forelse($tipsGizi as $tips)
        @php
            // Logika Penentuan Ikon & Warna Berdasarkan Kategori
            $kategoriRaw = strtolower($tips->kategori);
            if(str_contains($kategoriRaw, 'gizi') || str_contains($kategoriRaw, 'nutrisi')) {
                $icon = '🥦'; $iconClass = 'icon-green';
            } elseif(str_contains($kategoriRaw, 'resep') || str_contains($kategoriRaw, 'mpasi')) {
                $icon = '🍼'; $iconClass = 'icon-blue';
            } else {
                $icon = '⚡'; $iconClass = 'icon-orange';
            }

            // Memotong isi artikel jadi cuplikan (maks 100 karakter tanpa tag HTML)
            $excerpt = \Illuminate\Support\Str::limit(strip_tags($tips->konten), 100, '...');
            
            // Format Tanggal
            $tanggal = \Carbon\Carbon::parse($tips->updated_at)->translatedFormat('d F Y');
            $penulis = $tips->penulis ?? 'Tim GiziAnak';
        @endphp

        <div class="tip-card">
            <div class="card-top">
                <div class="icon-box {{ $iconClass }}">{{ $icon }}</div>
                <div>
                    <p class="age-label">{{ $tips->rentang_usia ?? 'UMUM' }}</p>
                    <h3 class="tip-title">{{ $tips->judul }}</h3>
                </div>
            </div>
            
            <p class="tip-desc">{{ $excerpt }}</p>
            
            <div class="tags-container">
                @if($tips->tags)
                    @foreach(explode(',', $tips->tags) as $tag)
                        <span class="tag">{{ trim($tag) }}</span>
                    @endforeach
                @else
                    <span class="tag">{{ $tips->kategori }}</span>
                @endif
            </div>
            
            <button type="button" class="read-more" 
                data-judul="{{ $tips->judul }}"
                data-kategori="{{ $tips->kategori }}"
                data-usia="{{ $tips->rentang_usia ?? 'UMUM' }}"
                data-penulis="{{ $penulis }}"
                data-tanggal="{{ $tanggal }}"
                data-konten="{{ htmlspecialchars($tips->konten) }}"
                data-gambar="{{ $tips->gambar }}"
                data-tags="{{ $tips->tags }}"
                onclick="bukaModalBaca(this)">
                Baca selengkapnya →
            </button>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: white; border-radius: 16px; border: 1px dashed #cbd5e1;">
            <span style="font-size: 40px; display: block; margin-bottom: 16px;">📚</span>
            <h3 style="margin: 0 0 8px 0; color: #1e293b;">Belum Ada Artikel</h3>
            <p style="color: #64748b; margin: 0;">Tim GiziAnak sedang menyiapkan materi edukasi untuk Anda.</p>
        </div>
    @endforelse
</div>

<div class="modal-overlay" id="modalBacaArtikel">
    <div class="modal-content">
        <button class="btn-close-float" onclick="tutupModalBaca()">✕</button>
        
        <img src="" id="baca_gambar" class="modal-cover" alt="Cover Artikel" style="display: none;">
        
        <div class="modal-body">
            <span class="art-kategori" id="baca_usia_kategori">RENTANG USIA • KATEGORI</span>
            <h2 class="art-judul" id="baca_judul">Judul Artikel</h2>
            
            <div class="art-meta">
                <span id="baca_penulis">✍️ Penulis</span>
                <span id="baca_tanggal">📅 Tanggal</span>
            </div>
            
            <div class="art-konten" id="baca_konten"></div>

            <div style="margin-top: 40px; padding-top: 20px; border-top: 1px dashed #e2e8f0;">
                <span style="font-size: 12px; color: #94a3b8; font-weight: 800; display: block; margin-bottom: 10px;">TAGS TOPIK:</span>
                <div class="tags-container" id="baca_tags" style="margin-bottom: 0;"></div>
            </div>
        </div>
    </div>
</div>

<script>
    function bukaModalBaca(element) {
        // Tarik data yang disimpan di tag data-*
        const judul = element.getAttribute('data-judul');
        const kategori = element.getAttribute('data-kategori');
        const usia = element.getAttribute('data-usia');
        const penulis = element.getAttribute('data-penulis');
        const tanggal = element.getAttribute('data-tanggal');
        const konten = element.getAttribute('data-konten');
        const gambar = element.getAttribute('data-gambar');
        const tagsRaw = element.getAttribute('data-tags');

        // Isi ke dalam elemen pop-up
        document.getElementById('baca_judul').innerText = judul;
        document.getElementById('baca_usia_kategori').innerText = usia + ' • ' + kategori;
        document.getElementById('baca_penulis').innerText = '✍️ ' + penulis;
        document.getElementById('baca_tanggal').innerText = '📅 ' + tanggal;
        document.getElementById('baca_konten').innerHTML = konten; // Teks lengkap

        // Render Gambar Cover (Kalau kosong, pakai placeholder)
        const imgEl = document.getElementById('baca_gambar');
        if(gambar && gambar !== '') {
            imgEl.src = '/storage/' + gambar;
            imgEl.style.display = 'block';
        } else {
            // Gambar default portal edukasi
            imgEl.src = `https://via.placeholder.com/800x300/f1f5f9/64748b?text=GiziAnak+Edukasi`;
            imgEl.style.display = 'block';
        }

        // Render Kotak-kotak Tags di Pop-up
        const tagsContainer = document.getElementById('baca_tags');
        tagsContainer.innerHTML = ''; 
        if(tagsRaw && tagsRaw !== '') {
            const tagsArray = tagsRaw.split(',');
            tagsArray.forEach(tag => {
                tagsContainer.innerHTML += `<span class="tag">${tag.trim()}</span>`;
            });
        } else {
            tagsContainer.innerHTML = `<span class="tag">${kategori}</span>`;
        }

        // Tampilkan Modal dan Kunci Latar Belakang
        document.getElementById('modalBacaArtikel').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function tutupModalBaca() {
        document.getElementById('modalBacaArtikel').classList.remove('active');
        document.body.style.overflow = 'auto'; // Kembalikan scroll latar
    }
</script>

@endsection