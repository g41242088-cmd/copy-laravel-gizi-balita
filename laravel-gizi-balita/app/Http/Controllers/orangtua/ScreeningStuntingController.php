<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Anak;
use App\Models\ScreeningStunting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScreeningStuntingController extends Controller
{
    public function index()
    {
        $anaks = Anak::where('orangtua_id', Auth::id())->get();
        $riwayat = ScreeningStunting::where('orangtua_id', Auth::id())
            ->with('anak')
            ->latest()
            ->get();

        return view('orangtua.screening.index', compact('anaks', 'riwayat'));
    }

    public function riwayat()
{
    $riwayat = ScreeningStunting::where('orangtua_id', Auth::id())
        ->with('anak')
        ->latest()
        ->get();

    return view('orangtua.screening.riwayat', compact('riwayat'));
}

    public function store(Request $request)
    {
        $request->validate([
            'anak_id'            => 'required|exists:anaks,id',
            'tanggal_pengukuran' => 'required|date',
            'berat_badan'        => 'required|numeric|min:1|max:100',
            'tinggi_badan'       => 'required|numeric|min:30|max:200',
            'lingkar_kepala'     => 'nullable|numeric|min:20|max:70',
            'lila'               => 'nullable|numeric|min:5|max:40',
            'berat_lahir'        => 'nullable|numeric|min:0.5|max:6',
            'jadwal_kontrol_berikutnya' => 'nullable|date|after:today',
        ]);

        $anak = Anak::findOrFail($request->anak_id);

        // Hitung usia dalam bulan dari tanggal pengukuran
        $usiaBulan = (int) \Carbon\Carbon::parse($anak->tanggal_lahir)
            ->diffInMonths(\Carbon\Carbon::parse($request->tanggal_pengukuran));

        // Hitung Z-Score TB/U WHO
        $zscore = $this->hitungZScore($request->tinggi_badan, $usiaBulan, $anak->jenis_kelamin);

        // Tentukan status
        $status = $this->tentukanStatus($zscore);

        // Rekomendasi berdasarkan status + faktor risiko
        $faktorRisiko = $this->analisisFaktorRisiko($request);
        $rekomendasi  = $this->buatRekomendasi($status, $faktorRisiko);

        ScreeningStunting::create([
            'anak_id'            => $anak->id,
            'orangtua_id'        => Auth::id(),
            'tanggal_pengukuran' => $request->tanggal_pengukuran,
            'berat_badan'        => $request->berat_badan,
            'tinggi_badan'       => $request->tinggi_badan,
            'lingkar_kepala'     => $request->lingkar_kepala,
            'lila'               => $request->lila,
            'usia_bulan'         => $usiaBulan,
            'zscore'             => $zscore,
            'status'             => $status,
            'rekomendasi'        => $rekomendasi,

            // Riwayat Kesehatan
            'berat_lahir'            => $request->berat_lahir,
            'riwayat_prematur'       => $request->riwayat_prematur,
            'asi_eksklusif'          => $request->asi_eksklusif,
            'imunisasi_lengkap'      => $request->imunisasi_lengkap,
            'riwayat_diare_berulang' => $request->riwayat_diare_berulang,
            'riwayat_rawat_inap'     => $request->riwayat_rawat_inap,
            'riwayat_penyakit'       => $request->riwayat_penyakit,

            // Pola Makan & Sanitasi
            'frekuensi_makan'      => $request->frekuensi_makan,
            'mpasi_sesuai_usia'    => $request->mpasi_sesuai_usia,
            'konsumsi_protein'     => $request->konsumsi_protein,
            'konsumsi_suplemen'    => $request->konsumsi_suplemen,
            'akses_air_bersih'     => $request->akses_air_bersih,
            'kebiasaan_cuci_tangan'=> $request->kebiasaan_cuci_tangan,
            'kondisi_sanitasi'     => $request->kondisi_sanitasi,

            'jadwal_kontrol_berikutnya' => $request->jadwal_kontrol_berikutnya,
        ]);

        return redirect()->route('orangtua.screening.index')
            ->with('success', "Skrining selesai. Status: {$status}.");
    }

    public function exportPdf($id)
    {
        $screening = ScreeningStunting::with('anak')->findOrFail($id);

        // Pastikan hanya pemilik yang bisa download
        abort_if($screening->orangtua_id !== Auth::id(), 403);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'orangtua.screening.pdf',
            compact('screening')
        )->setPaper('a4', 'portrait');

        return $pdf->download('skrining-stunting-' . $screening->anak->nama . '-' . $screening->created_at->format('Ymd') . '.pdf');
    }

    // ─── Private Helpers ───────────────────────────────────────────

    /**
     * Hitung Z-Score TB/U menggunakan tabel referensi WHO.
     * Data: WHO Child Growth Standards (2006).
     * Sumber: https://www.who.int/tools/child-growth-standards
     */
    private function hitungZScore(float $tinggiBadan, int $usiaBulan, string $jenisKelamin): float
    {
        // Tabel median & SD TB/U WHO (0–60 bulan), disampling per interval kunci.
        // Nilai antar bulan diinterpolasi secara linear.
        $refLaki = [
             0 => ['median' => 49.9, 'sd' => 1.9],
             1 => ['median' => 54.7, 'sd' => 2.1],
             2 => ['median' => 58.4, 'sd' => 2.3],
             3 => ['median' => 61.4, 'sd' => 2.4],
             4 => ['median' => 63.9, 'sd' => 2.4],
             5 => ['median' => 65.9, 'sd' => 2.5],
             6 => ['median' => 67.6, 'sd' => 2.5],
             9 => ['median' => 72.0, 'sd' => 2.6],
            12 => ['median' => 75.7, 'sd' => 2.6],
            15 => ['median' => 79.1, 'sd' => 2.7],
            18 => ['median' => 82.3, 'sd' => 2.9],
            21 => ['median' => 85.1, 'sd' => 3.0],
            24 => ['median' => 87.8, 'sd' => 3.1],
            30 => ['median' => 92.7, 'sd' => 3.3],
            36 => ['median' => 96.1, 'sd' => 3.4],
            42 => ['median' => 99.9, 'sd' => 3.5],
            48 => ['median' => 103.3, 'sd' => 3.6],
            54 => ['median' => 106.7, 'sd' => 3.7],
            60 => ['median' => 110.0, 'sd' => 3.7],
        ];

        $refPerempuan = [
             0 => ['median' => 49.1, 'sd' => 1.9],
             1 => ['median' => 53.7, 'sd' => 2.1],
             2 => ['median' => 57.1, 'sd' => 2.2],
             3 => ['median' => 59.8, 'sd' => 2.3],
             4 => ['median' => 62.1, 'sd' => 2.4],
             5 => ['median' => 64.0, 'sd' => 2.4],
             6 => ['median' => 65.7, 'sd' => 2.4],
             9 => ['median' => 70.1, 'sd' => 2.5],
            12 => ['median' => 74.0, 'sd' => 2.6],
            15 => ['median' => 77.5, 'sd' => 2.7],
            18 => ['median' => 80.7, 'sd' => 2.8],
            21 => ['median' => 83.7, 'sd' => 3.0],
            24 => ['median' => 86.4, 'sd' => 3.1],
            30 => ['median' => 91.3, 'sd' => 3.2],
            36 => ['median' => 95.1, 'sd' => 3.4],
            42 => ['median' => 98.7, 'sd' => 3.5],
            48 => ['median' => 102.7, 'sd' => 3.5],
            54 => ['median' => 106.2, 'sd' => 3.6],
            60 => ['median' => 109.4, 'sd' => 3.7],
        ];

        $ref  = strtolower($jenisKelamin) === 'l' ? $refLaki : $refPerempuan;
        $keys = array_keys($ref);

        // Clamp usia ke rentang tabel
        $usiaBulan = max(0, min(60, $usiaBulan));

        // Interpolasi linear antar dua titik terdekat
        $lower = $keys[0];
        $upper = $keys[count($keys) - 1];

        foreach ($keys as $k) {
            if ($k <= $usiaBulan) $lower = $k;
            if ($k >= $usiaBulan && $k < $upper) $upper = $k;
        }
        // Pastikan upper >= usia
        foreach ($keys as $k) {
            if ($k >= $usiaBulan) { $upper = $k; break; }
        }

        if ($lower === $upper) {
            $median = $ref[$lower]['median'];
            $sd     = $ref[$lower]['sd'];
        } else {
            $frac   = ($usiaBulan - $lower) / ($upper - $lower);
            $median = $ref[$lower]['median'] + $frac * ($ref[$upper]['median'] - $ref[$lower]['median']);
            $sd     = $ref[$lower]['sd']     + $frac * ($ref[$upper]['sd']     - $ref[$lower]['sd']);
        }

        return round(($tinggiBadan - $median) / $sd, 2);
    }

    private function tentukanStatus(float $zscore): string
    {
        if ($zscore < -3) return 'Severely Stunting';
        if ($zscore < -2) return 'Stunting';
        if ($zscore < -1) return 'Berisiko Stunting';
        return 'Normal';
    }

    /**
     * Kumpulkan faktor risiko dari data yang diisi.
     * Digunakan untuk memperkaya teks rekomendasi.
     */
    private function analisisFaktorRisiko(Request $request): array
    {
        $risiko = [];

        if ($request->berat_lahir && $request->berat_lahir < 2.5)
            $risiko[] = 'BBLR';
        if ($request->riwayat_prematur == '1')
            $risiko[] = 'lahir prematur';
        if ($request->asi_eksklusif == '0')
            $risiko[] = 'tidak ASI eksklusif';
        if ($request->imunisasi_lengkap == '0')
            $risiko[] = 'imunisasi tidak lengkap';
        if ($request->riwayat_diare_berulang == '1')
            $risiko[] = 'diare berulang';
        if ($request->frekuensi_makan && $request->frekuensi_makan < 3)
            $risiko[] = 'frekuensi makan kurang';
        if ($request->konsumsi_protein == '0')
            $risiko[] = 'kurang protein hewani';
        if ($request->akses_air_bersih == '0')
            $risiko[] = 'akses air bersih terbatas';
        if ($request->kondisi_sanitasi === 'buruk')
            $risiko[] = 'sanitasi buruk';

        return $risiko;
    }

    private function buatRekomendasi(string $status, array $faktorRisiko): string
    {
        $base = match ($status) {
            'Severely Stunting' =>
                'Anak memerlukan penanganan segera. Segera rujuk ke fasilitas kesehatan atau dokter spesialis anak untuk tata laksana gizi buruk.',
            'Stunting' =>
                'Konsultasikan kondisi anak ke tenaga gizi atau dokter anak. Tingkatkan asupan protein, zinc, zat besi, dan kalsium secara konsisten.',
            'Berisiko Stunting' =>
                'Perbaiki pola makan anak dengan gizi seimbang. Pastikan anak mendapat protein hewani, sayuran, dan buah setiap hari.',
            default =>
                'Pertumbuhan anak dalam batas normal. Pertahankan pola makan bergizi seimbang dan lakukan pemantauan rutin setiap bulan.',
        };

        if (!empty($faktorRisiko)) {
            $base .= ' Faktor risiko yang ditemukan: ' . implode(', ', $faktorRisiko) . '. Perhatikan faktor-faktor ini dalam pemantauan berikutnya.';
        }

        return $base;
    }
}