<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Gizi Anak - {{ $periode }}</title>
    <style>
        /* CSS Sederhana Khusus untuk DOMPDF */
        body { 
            font-family: Helvetica, Arial, sans-serif; 
            color: #333; 
            font-size: 12px; 
            margin: 0; 
            padding: 0;
        }
        .header { 
            text-align: center; 
            border-bottom: 2px solid #1e293b; 
            padding-bottom: 15px; 
            margin-bottom: 25px; 
        }
        .header h1 { 
            margin: 0; 
            color: #1e293b; 
            font-size: 22px; 
            text-transform: uppercase;
        }
        .header p { 
            margin: 5px 0 0 0; 
            color: #64748b; 
            font-size: 13px;
        }
        
        /* Kotak Ringkasan */
        .summary-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 25px; 
        }
        .summary-table td { 
            border: 1px solid #cbd5e1; 
            padding: 15px; 
            text-align: center; 
            width: 33.33%;
        }
        .summary-value { 
            font-size: 24px; 
            font-weight: bold; 
            margin-bottom: 5px; 
            display: block; 
        }
        .sv-blue { color: #2563eb; }
        .sv-green { color: #16a34a; }
        .sv-red { color: #dc2626; }
        .summary-label { 
            font-size: 11px; 
            color: #64748b; 
            text-transform: uppercase; 
            font-weight: bold; 
        }

        /* Tabel Data Utama */
        .data-table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        .data-table th, .data-table td { 
            border: 1px solid #cbd5e1; 
            padding: 8px 10px; 
            text-align: left; 
        }
        .data-table th { 
            background-color: #1e293b; 
            color: #ffffff; 
            font-weight: bold; 
            font-size: 11px;
            text-transform: uppercase;
        }
        .data-table tr:nth-child(even) { 
            background-color: #f8fafc; 
        }
        
        /* Pewarnaan Status */
        .status-normal { color: #16a34a; font-weight: bold; }
        .status-stunting { color: #dc2626; font-weight: bold; }
        .status-kurang { color: #d97706; font-weight: bold; }
        
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 11px;
            color: #64748b;
        }
    </style>
</head>
<body>

    @php
        // Menghitung Ulang Statistik Spesifik untuk PDF ini
        $normal = 0;
        $stunting = 0;
        
        foreach($dataPengukuran as $item) {
            $status = strtolower($item->status_gizi ?? '');
            if(str_contains($status, 'normal') || str_contains($status, 'baik')) {
                $normal++;
            } elseif(str_contains($status, 'stunting') || str_contains($status, 'buruk') || str_contains($status, 'kurang')) {
                $stunting++;
            }
        }
    @endphp

    <div class="header">
        <h1>Laporan Status Gizi Bulanan</h1>
        <p>Periode: <strong>{{ $periode }}</strong></p>
    </div>

    <table class="summary-table">
        <tr>
            <td>
                <span class="summary-value sv-blue">{{ $total }}</span>
                <span class="summary-label">Total Anak Dipantau</span>
            </td>
            <td>
                <span class="summary-value sv-green">{{ $normal }}</span>
                <span class="summary-label">Gizi Normal</span>
            </td>
            <td>
                <span class="summary-value sv-red">{{ $stunting }}</span>
                <span class="summary-label">Indikasi Stunting / Kurang</span>
            </td>
        </tr>
    </table>

    <h3 style="font-size: 14px; margin-bottom: 10px; color: #1e293b;">Rincian Data Pengukuran:</h3>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 25%;">Nama Anak</th>
                <th style="width: 15%;">Jenis Kelamin</th>
                <th style="width: 10%;">Tinggi (cm)</th>
                <th style="width: 10%;">Berat (kg)</th>
                <th style="width: 20%;">Status Gizi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dataPengukuran as $index => $pengukuran)
                @php
                    $jkRaw = strtolower($pengukuran->anak->jenis_kelamin ?? '');
                    $jk = ($jkRaw == 'p' || $jkRaw == 'perempuan') ? 'Perempuan' : 'Laki-laki';
                    
                    $statusRaw = strtolower($pengukuran->status_gizi ?? '');
                    $classStatus = '';
                    if (str_contains($statusRaw, 'normal') || str_contains($statusRaw, 'baik')) {
                        $classStatus = 'status-normal';
                    } elseif (str_contains($statusRaw, 'stunting') || str_contains($statusRaw, 'buruk')) {
                        $classStatus = 'status-stunting';
                    } elseif (str_contains($statusRaw, 'kurang')) {
                        $classStatus = 'status-kurang';
                    }
                    
                    $teksStatus = ucwords(str_replace('_', ' ', $statusRaw));
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($pengukuran->created_at)->format('d/m/Y') }}</td>
                    <td>{{ $pengukuran->anak->nama ?? 'Nama Tidak Diketahui' }}</td>
                    <td>{{ $jk }}</td>
                    <td>{{ $pengukuran->tinggi_badan }}</td>
                    <td>{{ $pengukuran->berat_badan }}</td>
                    <td class="{{ $classStatus }}">{{ $teksStatus ?: 'Belum Diukur' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">
                        Tidak ada data pengukuran di bulan ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak dari Sistem GiziAnak pada {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</p>
    </div>

</body>
</html>