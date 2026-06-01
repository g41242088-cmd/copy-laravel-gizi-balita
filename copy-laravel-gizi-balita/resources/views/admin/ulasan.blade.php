@extends('layouts.app')

@section('title', 'Ulasan Pengguna - GiziAnak')

@section('custom_css')
<style>
    /* --- HEADER HALAMAN --- */
    .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .header-title-group h2 { margin: 0 0 8px 0; font-size: 28px; font-weight: 900; color: #0f1c2e; font-family: Georgia, serif; }
    .header-title-group p { margin: 0; color: #64748b; font-size: 15px; }

    /* --- QUICK STATS --- */
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px; }
    .stat-box { background: white; border-radius: 16px; padding: 20px; border: 1px solid #f1f5f9; display: flex; align-items: center; gap: 16px; }
    .stat-icon { width: 56px; height: 56px; border-radius: 16px; background: #fffbeb; color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0; }
    .stat-info h3 { font-size: 28px; font-weight: 900; color: #1e293b; margin: 0 0 4px 0; line-height: 1; }
    .stat-info p { font-size: 13px; font-weight: 700; color: #64748b; margin: 0; text-transform: uppercase; }

    /* --- FILTER PANEL --- */
    .filter-card { background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; padding: 20px; margin-bottom: 24px; display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center; }
    
    .search-wrapper { position: relative; flex-grow: 1; max-width: 400px; }
    .search-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); font-size: 16px; color: #94a3b8; }
    .search-input { width: 100%; padding: 12px 16px 12px 42px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 14px; outline: none; background: #f8fafc; color: #1e293b; font-family: inherit; }
    .search-input:focus { border-color: #3b82f6; background: white; }

    .filter-group { display: flex; gap: 12px; }
    .select-filter { padding: 12px 36px 12px 16px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 14px; outline: none; background-color: #f8fafc; color: #1e293b; font-weight: 700; cursor: pointer; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%2364748b" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/></svg>'); background-repeat: no-repeat; background-position: right 12px center; }

    /* --- TABLE CONTAINER --- */
    .table-container { background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; text-align: left; min-width: 900px; }
    th { padding: 16px 24px; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #f1f5f9; background-color: #f8fafc; }
    td { padding: 20px 24px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover { background-color: #f8fafc; }

    /* Elemen Tabel Ulasan */
    .reviewer-info { display: flex; align-items: center; gap: 12px; }
    .r-avatar { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #1d4ed8); display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 800; color: white; flex-shrink: 0; }
    .r-name { font-size: 14px; font-weight: 800; color: #1e293b; margin: 0 0 2px 0; }
    .r-date { font-size: 12px; color: #64748b; margin: 0; }

    .review-content { max-width: 400px; }
    .stars { color: #f59e0b; font-size: 14px; margin-bottom: 6px; letter-spacing: 2px; }
    .comment { font-size: 13px; color: #334155; line-height: 1.5; margin: 0; }

    .target-badge { padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

    .btn-action { background: white; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; cursor: pointer; color: #64748b; transition: all 0.2s; padding: 6px 12px; display: inline-flex; align-items: center; justify-content: center; }
    .btn-action:hover { background: #fef2f2; color: #dc2626; border-color: #fecaca; }

    /* --- RESPONSIVE --- */
    @media (max-width: 992px) { .stats-grid { grid-template-columns: 1fr; } }
    @media (max-width: 768px) {
        .filter-card { flex-direction: column; align-items: stretch; }
        .search-wrapper { max-width: 100%; }
        .filter-group { flex-direction: column; }
    }
</style>
@endsection

@section('content')

<!-- HEADER -->
<div class="page-header">
    <div class="header-title-group">
        <h2>Ulasan Pengguna</h2>
        <p>Pantau tingkat kepuasan pengguna terhadap layanan sistem dan tenaga medis.</p>
    </div>
</div>

<!-- QUICK STATS -->
<div class="stats-grid">
    <div class="stat-box">
        <div class="stat-icon" style="background: #fffbeb; color: #f59e0b;">⭐️</div>
        <div class="stat-info">
            <h3>4.8</h3>
            <p>Rata-rata Rating</p>
        </div>
    </div>
    <div class="stat-box">
        <div class="stat-icon" style="background: #eff6ff; color: #3b82f6;">💬</div>
        <div class="stat-info">
            <h3>1,204</h3>
            <p>Total Ulasan Masuk</p>
        </div>
    </div>
    <div class="stat-box">
        <div class="stat-icon" style="background: #f0fdf4; color: #16a34a;">👨‍⚕️</div>
        <div class="stat-info">
            <h3>95%</h3>
            <p>Puas Layanan Medis</p>
        </div>
    </div>
</div>

<!-- FILTER & SEARCH BAR -->
<div class="filter-card">
    <div class="search-wrapper">
        <span class="search-icon">🔍</span>
        <input type="text" class="search-input" placeholder="Cari ulasan berdasarkan kata kunci atau nama...">
    </div>
    
    <div class="filter-group">
        <select class="select-filter">
            <option value="semua">Semua Rating</option>
            <option value="5">⭐️ 5 Bintang</option>
            <option value="4">⭐️ 4 Bintang</option>
            <option value="123">⭐️ 1-3 Bintang (Kritik)</option>
        </select>
        <select class="select-filter">
            <option value="semua">Semua Kategori</option>
            <option value="sistem">📱 Layanan Sistem/Aplikasi</option>
            <option value="medis">👨‍⚕️ Tenaga Medis</option>
        </select>
    </div>
</div>

<!-- TABEL ULASAN -->
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>PENGGUNA</th>
                <th>RATING & KOMENTAR</th>
                <th>DITUJUKAN KEPADA</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            <!-- Ulasan 1 (Sistem) -->
            <tr>
                <td>
                    <div class="reviewer-info">
                        <div class="r-avatar">AN</div>
                        <div>
                            <h4 class="r-name">Anita Ratnasari</h4>
                            <p class="r-date">06 Mei 2026, 09:15 WIB</p>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="review-content">
                        <div class="stars">★★★★★</div>
                        <p class="comment">Aplikasi GiziAnak sangat membantu saya memantau jadwal makan dan pertumbuhan anak. Fiturnya lengkap dan mudah dipahami oleh ibu-ibu!</p>
                    </div>
                </td>
                <td><span class="target-badge">📱 Sistem / Aplikasi</span></td>
                <td>
                    <button class="btn-action" title="Hapus Ulasan">🗑️ Hapus</button>
                </td>
            </tr>

            <!-- Ulasan 2 (Dokter) -->
            <tr>
                <td>
                    <div class="reviewer-info">
                        <div class="r-avatar" style="background: #8b5cf6;">BU</div>
                        <div>
                            <h4 class="r-name">Budi Santoso</h4>
                            <p class="r-date">04 Mei 2026, 14:30 WIB</p>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="review-content">
                        <div class="stars">★★★★★</div>
                        <p class="comment">Dokter Andi sangat informatif dan sabar saat menjelaskan kondisi anak saya. Terima kasih atas konsultasinya yang menenangkan.</p>
                    </div>
                </td>
                <td>
                    <div style="display: flex; flex-direction: column; gap: 4px;">
                        <span class="target-badge" style="background: #f0fdf4; color: #16a34a; border-color: #dcfce7;">👨‍⚕️ Tenaga Medis</span>
                        <span style="font-size: 11px; font-weight: 700; color: #64748b;">dr. Andi Prasetyo, Sp.A</span>
                    </div>
                </td>
                <td>
                    <button class="btn-action" title="Hapus Ulasan">🗑️ Hapus</button>
                </td>
            </tr>

            <!-- Ulasan 3 (Kritik / Bintang 3) -->
            <tr>
                <td>
                    <div class="reviewer-info">
                        <div class="r-avatar" style="background: #ec4899;">SI</div>
                        <div>
                            <h4 class="r-name">Siti Aminah</h4>
                            <p class="r-date">01 Mei 2026, 10:20 WIB</p>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="review-content">
                        <div class="stars" style="color: #64748b;">★★★<span style="color: #cbd5e1;">★★</span></div>
                        <p class="comment">Aplikasinya bagus, tapi kadang agak lambat saat mengunggah foto laporan berat badan anak. Mohon diperbaiki servernya.</p>
                    </div>
                </td>
                <td><span class="target-badge">📱 Sistem / Aplikasi</span></td>
                <td>
                    <button class="btn-action" title="Hapus Ulasan">🗑️ Hapus</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

@endsection