@extends('layouts.app')

@section('title', 'Kelola Artikel & Tips - GiziAnak')

@section('custom_css')
<style>
    /* --- HEADER HALAMAN --- */
    .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .header-title-group h2 { margin: 0 0 8px 0; font-size: 28px; font-weight: 900; color: #0f1c2e; font-family: Georgia, serif; }
    .header-title-group p { margin: 0; color: #64748b; font-size: 15px; }

    .btn-add { background: #3b82f6; color: white; padding: 12px 20px; border-radius: 12px; font-size: 14px; font-weight: 700; cursor: pointer; border: none; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2); }
    .btn-add:hover { background: #2563eb; transform: translateY(-2px); }

    /* --- FILTER PANEL --- */
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
    td { padding: 20px 24px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover { background-color: #f8fafc; }

    /* Elemen Artikel */
    .article-info { display: flex; align-items: center; gap: 16px; }
    .a-thumb { width: 60px; height: 60px; border-radius: 12px; background: #e2e8f0; object-fit: cover; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 24px; border: 1px solid #f1f5f9; }
    .a-title { font-size: 15px; font-weight: 800; color: #1e293b; margin: 0 0 6px 0; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .a-meta { font-size: 12px; color: #64748b; margin: 0; display: flex; gap: 12px; }
    .a-meta span { display: flex; align-items: center; gap: 4px; }

    /* Kategori & Status */
    .cat-badge { padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 800; background: #f1f5f9; color: #475569; display: inline-block; }
    .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 800; display: inline-flex; align-items: center; gap: 6px; }
    .status-badge::before { content: ''; display: inline-block; width: 6px; height: 6px; border-radius: 50%; }
    .st-terbit { background: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; }
    .st-terbit::before { background: #16a34a; }
    .st-draft { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }
    .st-draft::before { background: #94a3b8; }

    /* Actions */
    .action-group { display: flex; gap: 8px; }
    .btn-action-sm { background: white; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; cursor: pointer; color: #64748b; transition: all 0.2s; padding: 8px 12px; display: flex; align-items: center; justify-content: center; text-decoration: none;}
    .btn-action-sm:hover { background: #f8fafc; color: #3b82f6; border-color: #cbd5e1; }
    .btn-delete:hover { color: #ef4444; background: #fef2f2; border-color: #fecaca; }

    /* =======================================
       CSS MODAL (POP-UP FORM ARTIKEL)
       ======================================= */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 28, 46, 0.6); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 9999; opacity: 0; transition: opacity 0.3s ease; }
    .modal-overlay.active { display: flex; opacity: 1; }
    .modal-content { background: white; width: 90%; max-width: 650px; border-radius: 16px; padding: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); transform: translateY(20px); transition: transform 0.3s ease; max-height: 90vh; display: flex; flex-direction: column; }
    .modal-overlay.active .modal-content { transform: translateY(0); }
    
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; }
    .modal-title { font-size: 18px; font-weight: 800; color: #1e293b; margin: 0; }
    .btn-close { background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 50%; font-size: 14px; font-weight: bold; color: #64748b; cursor: pointer; transition: 0.2s; }
    .btn-close:hover { background: #e2e8f0; color: #dc2626; }
    
    .modal-body { overflow-y: auto; flex-grow: 1; padding-right: 8px; }
    .modal-body::-webkit-scrollbar { width: 6px; }
    .modal-body::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

    .modal-input { width: 100%; box-sizing: border-box; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 14px; color: #1e293b; background: #f8fafc; transition: 0.2s; margin-top: 6px; font-family: inherit; }
    .modal-input:focus { border-color: #3b82f6; background: white; outline: none; }
    .t-label { font-size: 12px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }

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
        <h2>Artikel & Tips Gizi</h2>
        <p>Kelola konten edukasi, resep MPASI, dan tips kesehatan untuk pengguna platform.</p>
    </div>
    <button class="btn-add" onclick="bukaModalForm()">✏️ Tulis Artikel Baru</button>
</div>

<form class="filter-card" method="GET" action="{{ url()->current() }}">
    <div class="search-wrapper">
        <span class="search-icon">🔍</span>
        <input type="text" name="search" class="search-input" value="{{ request('search') }}" placeholder="Cari judul artikel atau penulis (Enter)...">
    </div>
    <div class="filter-group">
        <select name="kategori" class="select-filter" onchange="this.form.submit()">
            <option value="semua" {{ request('kategori') == 'semua' ? 'selected' : '' }}>Semua Kategori</option>
            <option value="Nutrisi & Gizi" {{ request('kategori') == 'Nutrisi & Gizi' ? 'selected' : '' }}>Nutrisi & Gizi</option>
            <option value="Resep MPASI" {{ request('kategori') == 'Resep MPASI' ? 'selected' : '' }}>Resep MPASI</option>
            <option value="Pola Asuh" {{ request('kategori') == 'Pola Asuh' ? 'selected' : '' }}>Pola Asuh</option>
        </select>

        <select name="status" class="select-filter" onchange="this.form.submit()">
            <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
            <option value="terbit" {{ request('status') == 'terbit' ? 'selected' : '' }}>✅ Terbit</option>
            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>📝 Draft</option>
        </select>

        @if(request('search') || request('kategori') || request('status'))
            <a href="{{ url()->current() }}" class="btn-action-sm" style="background: #fee2e2; color: #dc2626; border-color: #fecaca;">✕ Reset</a>
        @endif
    </div>
</form>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th style="width: 45%;">JUDUL KONTEN</th>
                <th>KATEGORI</th>
                <th>STATUS</th>
                <th>STATISTIK</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($artikels as $artikel)
                @php
                    $kategoriRaw = strtolower($artikel->kategori);
                    $icon = '💡'; $bgThumb = '#f1f5f9';
                    
                    if(str_contains($kategoriRaw, 'gizi') || str_contains($kategoriRaw, 'nutrisi')) {
                        $icon = '🥦'; $bgThumb = '#dcfce7';
                    } elseif(str_contains($kategoriRaw, 'resep') || str_contains($kategoriRaw, 'mpasi')) {
                        $icon = '🍼'; $bgThumb = '#fef08a';
                    } elseif(str_contains($kategoriRaw, 'pola asuh') || str_contains($kategoriRaw, 'parenting')) {
                        $icon = '⚡'; $bgThumb = '#ffedd5';
                    }

                    $isTerbit = $artikel->status == 'terbit';
                    $classStatus = $isTerbit ? 'st-terbit' : 'st-draft';
                    $textStatus = $isTerbit ? 'Terbit' : 'Draft';
                    $tanggal = $isTerbit ? \Carbon\Carbon::parse($artikel->updated_at)->translatedFormat('d M Y') : '-- Belum Terbit --';
                @endphp

                <tr>
                    <td>
                        <div class="article-info">
                            @if($artikel->gambar)
                                <img src="{{ asset('storage/' . $artikel->gambar) }}" class="a-thumb" alt="Thumbnail">
                            @else
                                <div class="a-thumb" style="background: {{ $bgThumb }};">{{ $icon }}</div>
                            @endif
                            
                            <div>
                                <h4 class="a-title">{{ $artikel->judul }}</h4>
                                <div class="a-meta">
                                    <span>✍️ {{ $artikel->penulis ?? 'Admin GiziAnak' }}</span>
                                    <span>📅 {{ $tanggal }}</span>
                                </div>
                                <div style="margin-top: 4px; font-size: 11px; color: #94a3b8;">
                                    <b>Usia:</b> {{ $artikel->rentang_usia ?? '-' }} | <b>Tags:</b> {{ $artikel->tags ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td><span class="cat-badge">{{ $artikel->kategori }}</span></td>
                    <td><span class="status-badge {{ $classStatus }}">{{ $textStatus }}</span></td>
                    <td><span style="font-size: 13px; font-weight: 700; color: {{ $artikel->views > 0 ? '#475569' : '#94a3b8' }};">👁️ {{ number_format($artikel->views) }} Views</span></td>
                    <td>
                        <div class="action-group">
                            <button type="button" class="btn-action-sm" title="Edit Artikel" 
                                    data-id="{{ $artikel->id }}"
                                    data-judul="{{ $artikel->judul }}"
                                    data-kategori="{{ $artikel->kategori }}"
                                    data-usia="{{ $artikel->rentang_usia }}"
                                    data-tags="{{ $artikel->tags }}"
                                    data-status="{{ $artikel->status }}"
                                    data-konten="{{ htmlspecialchars($artikel->konten) }}"
                                    onclick="bukaModalForm(this)">✏️</button>
                            
                            <form action="{{ route('admin.artikel.destroy', $artikel->id) ?? '#' }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action-sm btn-delete" title="Hapus">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 60px 20px; background: white;">
                        <span style="font-size: 40px; display: block; margin-bottom: 16px;">📝</span>
                        <h3 style="margin: 0 0 8px 0; color: #1e293b;">Belum Ada Artikel Ditemukan</h3>
                        <p style="color: #64748b; margin: 0;">Silakan ubah filter pencarian atau klik "Tulis Artikel Baru".</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="modal-overlay" id="modalFormArtikel">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="formTitle">Tulis Artikel Baru</h3>
            <button class="btn-close" onclick="tutupModalForm()">✕</button>
        </div>
        <div class="modal-body">
            <form id="formArtikel" method="POST" action="{{ route('admin.artikel.store') ?? '#' }}" enctype="multipart/form-data">
                @csrf
                <div id="method_put"></div>
                
                <div style="margin-bottom: 16px;">
                    <label class="t-label">Upload Gambar Sampul</label>
                    <input type="file" name="gambar" id="input_gambar" class="modal-input" accept="image/*">
                    <small style="color: #64748b; font-size: 11px; margin-top: 4px; display: block;">Format JPG, PNG. Maksimal 2MB. (Opsional)</small>
                </div>

                <div style="margin-bottom: 16px;">
                    <label class="t-label">Judul Artikel (Tampil Besar di Kartu)</label>
                    <input type="text" name="judul" id="input_judul" class="modal-input" placeholder="Contoh: Bayi & MPASI" required>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label class="t-label">Rentang Usia (Muncul di atas Judul)</label>
                        <select name="rentang_usia" id="input_usia" class="modal-input" style="appearance: auto;" required>
                            <option value="0 - 1 TAHUN">0 - 1 TAHUN</option>
                            <option value="1 - 2 TAHUN">1 - 2 TAHUN</option>
                            <option value="2 - 3 TAHUN">2 - 3 TAHUN</option>
                            <option value="3 - 4 TAHUN">3 - 4 TAHUN</option>
                            <option value="4 - 5 TAHUN">4 - 5 TAHUN</option>
                            <option value="UMUM">UMUM</option>
                        </select>
                    </div>
                    <div>
                        <label class="t-label">Kategori Edukasi (Menentukan Ikon)</label>
                        <select name="kategori" id="input_kategori" class="modal-input" style="appearance: auto;" required>
                            <option value="Nutrisi & Gizi">🥦 Nutrisi & Gizi</option>
                            <option value="Resep MPASI">🍼 Resep MPASI</option>
                            <option value="Pola Asuh">⚡ Pola Asuh (Parenting)</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label class="t-label">Tags / Kata Kunci (Muncul di Bawah)</label>
                        <input type="text" name="tags" id="input_tags" class="modal-input" placeholder="Pisahkan dengan koma. Contoh: ASI, MPASI, Bayi">
                    </div>
                    <div>
                        <label class="t-label">Status Terbit</label>
                        <select name="status" id="input_status" class="modal-input" style="appearance: auto;" required>
                            <option value="draft">📝 Draft</option>
                            <option value="terbit">✅ Terbit</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="t-label">Isi Konten Artikel (Paragraf pertama jadi Cuplikan)</label>
                    <textarea name="konten" id="input_konten" class="modal-input" rows="7" placeholder="Tulis isi selengkapnya di sini..." required></textarea>
                </div>
                
                <button type="submit" class="btn-add" style="width: 100%; justify-content: center; height: 44px; font-size: 15px;">💾 Simpan Artikel</button>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
<div class="modal-overlay active" id="modalSukses" style="z-index: 10000;">
    <div class="modal-content" style="max-width: 320px; text-align: center; padding: 40px 24px;">
        <div style="font-size: 60px; margin-bottom: 16px;">✅</div>
        <h3 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 800; color: #1e293b;">Berhasil!</h3>
        <p style="color: #64748b; font-size: 14px; margin: 0 0 24px 0;">{{ session('success') }}</p>
        <button onclick="document.getElementById('modalSukses').remove()" class="btn-add" style="background: #10b981; width:100%; justify-content:center;">Tutup</button>
    </div>
</div>
<script>setTimeout(() => { document.getElementById('modalSukses')?.remove(); }, 3000);</script>
@endif

<script>
    function bukaModalForm(element = null) {
        const modal = document.getElementById('modalFormArtikel');
        const form = document.getElementById('formArtikel');
        const title = document.getElementById('formTitle');
        const methodPut = document.getElementById('method_put');

        // Reset semua input form
        form.reset();

        if (element) {
            // MODE EDIT
            title.innerText = "Edit Artikel";
            form.action = `/admin/artikel/${element.getAttribute('data-id')}`;
            methodPut.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            
            document.getElementById('input_judul').value = element.getAttribute('data-judul');
            document.getElementById('input_kategori').value = element.getAttribute('data-kategori');
            document.getElementById('input_usia').value = element.getAttribute('data-usia');
            document.getElementById('input_tags').value = element.getAttribute('data-tags');
            document.getElementById('input_status').value = element.getAttribute('data-status');
            document.getElementById('input_konten').value = element.getAttribute('data-konten');
        } else {
            // MODE TAMBAH
            title.innerText = "Tulis Artikel Baru";
            form.action = "{{ route('admin.artikel.store') ?? '#' }}";
            methodPut.innerHTML = ''; 
        }

        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function tutupModalForm() {
        document.getElementById('modalFormArtikel').classList.remove('active');
        document.body.style.overflow = 'auto';
    }
</script>

@endsection