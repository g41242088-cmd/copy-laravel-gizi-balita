<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Catatan Medis - {{ $pasien->nama_anak }}</title>
    <style>
        body { font-family: sans-serif; color: #333; font-size: 14px; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #2c3e50; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #2c3e50; }
        .header p { margin: 5px 0 0; font-size: 12px; color: #7f8c8d; }
        
        .section-title { background-color: #ecf0f1; padding: 8px; font-weight: bold; font-size: 14px; border-left: 4px solid #3498db; margin-top: 20px; margin-bottom: 10px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table td { padding: 8px; vertical-align: top; border-bottom: 1px solid #bdc3c7; }
        .label-col { width: 35%; font-weight: bold; color: #7f8c8d; }
        
        .soap-box { border: 1px solid #bdc3c7; padding: 12px; border-radius: 5px; margin-bottom: 10px; }
        .soap-label { font-weight: bold; color: #2980b9; margin-bottom: 5px; display: block;}
    </style>
</head>
<body>

    <div class="header">
        <h2>REKAM MEDIS PASIEN (SOAP)</h2>
        <p>Klinik Gizi Anak - Dicetak pada: {{ now()->format('d M Y H:i') }}</p>
    </div>

    <div class="section-title">Informasi Pasien</div>
    <table>
        <tr>
            <td class="label-col">Nama Anak</td>
            <td>: {{ $pasien->nama_anak }}</td>
        </tr>
        <tr>
            <td class="label-col">Jenis Kelamin / Umur</td>
            <td>: {{ $pasien->jenis_kelamin_anak == 'L' ? 'Laki-laki' : 'Perempuan' }} / {{ $pasien->umur_anak }} Bulan</td>
        </tr>
        <tr>
            <td class="label-col">Nama Wali / Orang Tua</td>
            <td>: {{ $pasien->nama_ortu }}</td>
        </tr>
        <tr>
            <td class="label-col">Tanggal Konsultasi</td>
            <td>: {{ \Carbon\Carbon::parse($pasien->tanggal_jadwal)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label-col">Tinggi / Berat Badan Terakhir</td>
            <td>: {{ $cekGiziTerbaru->tinggi_badan ?? '-' }} cm / {{ $cekGiziTerbaru->berat_badan ?? '-' }} kg</td>
        </tr>
    </table>

    <div class="section-title">Pemeriksaan Medis (S O A P)</div>
    
    <div class="soap-box">
        <span class="soap-label">S - Subjective (Keluhan Utama)</span>
        {!! nl2br(e($pasien->subjective ?? 'Belum ada catatan.')) !!}
    </div>

    <div class="soap-box">
        <span class="soap-label">O - Objective (Hasil Pemeriksaan)</span>
        Suhu: {{ $pasien->suhu ?? '-' }} °C <br>
        Fisik: {{ $pasien->objective ?? '-' }}
    </div>

    <div class="soap-box">
        <span class="soap-label">A - Assessment (Diagnosis)</span>
        {{ $pasien->assessment ?? 'Belum ada diagnosis.' }}
    </div>

    <div class="soap-box">
        <span class="soap-label">P - Plan (Tindakan / Resep Obat)</span>
        {!! nl2br(e($pasien->plan ?? 'Belum ada tindakan.')) !!}
    </div>

    @if($pasien->vaksin)
    <div class="section-title">Riwayat Imunisasi Tambahan</div>
    <p>Vaksin yang diberikan pada kunjungan ini: <strong style="text-transform: uppercase;">{{ $pasien->vaksin }}</strong></p>
    @endif

    <!-- KODE BARU YANG LEBIH HEMAT RUANG -->
    <table style="border: none; margin-top: 15px; width: 100%; page-break-inside: avoid;">
        <tr>
            <td style="border: none; width: 60%;"></td>
            <td style="border: none; text-align: center;">
                <p style="margin: 0; padding: 0;">Dokter Pemeriksa,</p>
                <!-- Jarak untuk tanda tangan (tinggi bisa dikurangi jika masih turun ke hal 2) -->
                <div style="height: 60px;"></div>
                <p style="margin: 0; padding: 0; text-decoration: underline;"><strong>{{ Auth::user()->name }}</strong></p>
            </td>
        </tr>
    </table>

</body>
</html>