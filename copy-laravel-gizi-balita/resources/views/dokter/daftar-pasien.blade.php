@extends('layouts.app')

@section('title', 'Daftar Pasien Medis - GiziAnak')

@section('custom_css')
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .header-title-group h2 {
            margin: 0 0 8px 0;
            font-size: 28px;
            font-weight: 900;
            color: #0f1c2e;
            font-family: Georgia, serif;
        }

        .header-title-group p {
            margin: 0;
            color: #64748b;
            font-size: 15px;
        }

        .filter-row {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .search-input-wrapper {
            width: 350px;
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 14px;
        }

        .search-input {
            width: 100%;
            padding: 12px 16px 12px 42px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
            color: #334155;
            box-sizing: border-box;
        }

        .search-input:focus {
            border-color: #3b82f6;
        }

        .sort-select {
            padding: 12px 36px 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            outline: none;
            background-color: white;
            color: #1e293b;
            font-weight: 600;
            cursor: pointer;
            appearance: none;
            background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%2364748b" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 12px center;
        }

        .table-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            border: 1px solid #f1f5f9;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            min-width: 800px;
        }

        th {
            padding: 16px 24px;
            font-size: 11px;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #f1f5f9;
            background-color: #f8fafc;
        }

        td {
            padding: 20px 24px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .patient-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .p-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .p-name {
            font-size: 14px;
            font-weight: 800;
            color: #1e293b;
            margin: 0 0 2px 0;
        }

        .p-age {
            font-size: 12px;
            color: #64748b;
            margin: 0;
        }

        .parent-name {
            font-size: 14px;
            font-weight: 600;
            color: #334155;
        }

        .date-text {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
            display: block;
        }

        .date-sub {
            font-size: 12px;
            color: #94a3b8;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .status-badge::before {
            content: '';
            display: block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .st-selesai {
            background: #f0fdf4;
            color: #10b981;
        }

        .st-selesai::before {
            background: #10b981;
        }

        .btn-detail {
            background: white;
            border: 1px solid #e2e8f0;
            color: #3b82f6;
            font-size: 13px;
            font-weight: 700;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-detail:hover {
            background: #eff6ff;
            border-color: #bfdbfe;
        }
        
        .filter-group-right {
            display: flex;
            gap: 12px;
            align-items: center;
        }
    </style>
@endsection

@section('content')

    <div class="page-header">
        <div class="header-title-group">
            <h2>Daftar Pasien Medis</h2>
            <p>Menampilkan riwayat pasien yang telah menyelesaikan sesi konsultasi.</p>
        </div>
    </div>

    <form class="filter-row" method="GET" action="{{ url()->current() }}">
        <div class="search-input-wrapper">
            <span class="search-icon">🔍</span>
            <input type="text" name="search" class="search-input" value="{{ request('search') }}" placeholder="Cari nama pasien atau wali (Enter)...">
        </div>
        
        <div class="filter-group-right">
            <select name="sort" class="sort-select" onchange="this.form.submit()">
                <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Pemeriksaan Terbaru</option>
                <option value="nama_a_z" {{ request('sort') == 'nama_a_z' ? 'selected' : '' }}>Nama Pasien (A-Z)</option>
            </select>

            @if(request('search') || request('sort'))
                <a href="{{ url()->current() }}" class="btn-detail" style="background: #fee2e2; color: #dc2626; border-color: #fecaca;">✕ Reset</a>
            @endif
        </div>
    </form>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>NAMA PASIEN</th>
                    <th>WALI / ORANG TUA</th>
                    <th>TANGGAL SELESAI</th>
                    <th>STATUS MEDIS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pasiens as $pasien)
                    <tr>
                        <td>
                            <div class="patient-cell">
                                <div class="p-avatar">
                                    {{ strtolower($pasien->jenis_kelamin_anak ?? '') == 'l' ? '👦' : '👧' }}
                                </div>
                                <div>
                                    <h4 class="p-name">{{ $pasien->nama_anak }}</h4>
                                    <p class="p-age">
                                        {{ strtolower($pasien->jenis_kelamin_anak ?? '') == 'l' ? 'Laki-laki' : 'Perempuan' }} 
                                        @if($pasien->anak && $pasien->anak->tanggal_lahir)
                                            • ({{ round(\Carbon\Carbon::parse($pasien->anak->tanggal_lahir)->diffInMonths(now())) }} Bln)
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="parent-name">{{ $pasien->nama_ortu }}</span>
                        </td>
                        <td>
                            <span class="date-text">
                                {{ \Carbon\Carbon::parse($pasien->tanggal_jadwal)->translatedFormat('d M Y') }}
                            </span>
                            <span class="date-sub">Catatan: {{ Str::limit($pasien->catatan ?? '-', 20) }}</span>
                        </td>
                        <td>
                            <span class="status-badge st-selesai">Selesai / Sehat</span>
                        </td>
                        <td>
                            <a href="{{ route('dokter.catatan.detail', $pasien->id) ?? '#' }}" class="btn-detail">
                                Catatan Medis
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 48px; color: #64748b;">
                            <span style="font-size: 40px; display: block; margin-bottom: 10px;">🩺</span>
                            Tidak ada data pasien yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection