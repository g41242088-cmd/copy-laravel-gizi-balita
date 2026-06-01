@extends('layouts.app')

@section('title', 'Riwayat Konsultasi Ahli - GiziAnak')

@section('custom_css')
    <style>
        /* --- HEADER HALAMAN --- */
        .page-header {
            margin-bottom: 32px;
        }

        .page-header h2 {
            font-size: 28px;
            font-weight: 900;
            color: #0f1c2e;
            margin: 0 0 8px 0;
            font-family: Georgia, serif;
        }

        .page-header p {
            font-size: 15px;
            color: #64748b;
            margin: 0;
        }

        /* --- FILTER CHIPS --- */
        .filter-chips {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .chip {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            border: 1px solid #e2e8f0;
            background: white;
            color: #64748b;
            transition: all 0.2s;
        }

        .chip:hover {
            background: #f8fafc;
        }

        .chip.active {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        /* --- HISTORY CARDS --- */
        .history-grid {
            display: flex;
            flex-direction: column;
            gap: 16px;
            max-width: 800px;
        }

        .history-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            border: 1px solid #f1f5f9;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            transition: transform 0.2s;
        }

        .history-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px -3px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 1px dashed #e2e8f0;
            padding-bottom: 16px;
        }

        .expert-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        /* Warna Avatar Dinamis */
        .ava-active {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .ava-inactive {
            background: #e2e8f0;
            color: #64748b;
        }

        .expert-name {
            font-size: 15px;
            font-weight: 800;
            color: #1e293b;
            margin: 0 0 4px 0;
            text-transform: capitalize;
        }

        .expert-specialty {
            font-size: 13px;
            font-weight: 600;
            color: #3b82f6;
            margin: 0;
        }

        /* Badges Status Dinamis */
        .status-badge {
            font-size: 12px;
            font-weight: 800;
            padding: 6px 12px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .st-menunggu {
            background: #fffbeb;
            color: #d97706;
            border: 1px solid #fde68a;
        }

        .st-disetujui {
            background: #dcfce7;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .st-selesai {
            background: #f1f5f9;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        .st-batal {
            background: #fef2f2;
            color: #ef4444;
            border: 1px solid #fecaca;
        }

        .card-body {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .detail-label {
            font-size: 11px;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
        }

        .detail-value {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* --- RESPONSIVE --- */
        @media (max-width: 640px) {
            .card-header {
                flex-direction: column;
                gap: 16px;
            }

            .card-body {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')

    <div class="page-header">
        <h2>Riwayat Konsultasi Ahli</h2>
        <p>Pantau status pengajuan booking Anda dengan tenaga medis atau ahli gizi.</p>
    </div>

    <!-- FILTER CHIPS (Tersambung dengan JavaScript di bawah) -->
    <div class="filter-chips">
        <div class="chip active" data-filter="semua">Semua</div>
        <div class="chip" data-filter="menunggu">⏳ Menunggu Konfirmasi</div>
        <div class="chip" data-filter="disetujui">✅ Disetujui</div>
        <div class="chip" data-filter="selesai">📁 Selesai</div>
    </div>

    <div class="history-grid" id="historyGrid">

        @forelse($riwayat as $item)
            @php
                // Menentukan teks, warna status, dan icon secara dinamis
                if ($item->status == 'menunggu') {
                    $statusClass = 'st-menunggu';
                    $statusText = 'Menunggu Konfirmasi';
                    $icon = '⏳';
                } elseif ($item->status == 'disetujui') {
                    $statusClass = 'st-disetujui';
                    $statusText = 'Disetujui (Cek WA)';
                    $icon = '✅';
                } elseif ($item->status == 'selesai') {
                    $statusClass = 'st-selesai';
                    $statusText = 'Selesai';
                    $icon = '📁';
                } else {
                    $statusClass = 'st-batal';
                    $statusText = 'Dibatalkan';
                    $icon = '❌';
                }

                // Membuat tampilan pudar jika sudah selesai/batal
                $isInactive = in_array($item->status, ['selesai', 'batal']);
            @endphp

            <div class="history-card" data-status="{{ $item->status }}">
                <div class="card-header">
                    <div class="expert-info">
                        <div class="avatar {{ $isInactive ? 'ava-inactive' : 'ava-active' }}">
                            {{ $item->ahli->role == 'dokter' ? '👨‍⚕️' : '👩‍⚕️' }}
                        </div>
                        <div>
                            <h3 class="expert-name" style="{{ $isInactive ? 'color: #64748b;' : '' }}">
                                {{ $item->ahli->name }}
                            </h3>
                            <p class="expert-specialty" style="{{ $isInactive ? 'color: #94a3b8;' : '' }}">
                                {{ $item->ahli->spesialisasi ?? 'Gizi Umum' }}
                            </p>
                        </div>
                    </div>
                    <div class="status-badge {{ $statusClass }}">
                        {{ $icon }} {{ $statusText }}
                    </div>
                </div>

                <div class="card-body">
                    <div class="detail-item">
                        <span class="detail-label">Pengajuan Tanggal</span>
                        <span class="detail-value" style="{{ $isInactive ? 'color: #64748b;' : '' }}">
                            📅 {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y • H:i') }} WIB
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Pasien (Anak)</span>
                        <span class="detail-value" style="{{ $isInactive ? 'color: #64748b;' : '' }}">
                            {{ $item->jenis_kelamin_anak == 'L' ? '👦' : '👧' }} {{ $item->nama_anak }}
                            ({{ round(\Carbon\Carbon::parse($item->anak->tanggal_lahir)->diffInMonths(now())) }} Bln)
                        </span>
                    </div>
                </div>
            </div>

        @empty
            <div
                style="text-align: center; padding: 40px; color: #64748b; background: white; border-radius: 16px; border: 1px dashed #e2e8f0;">
                <span style="font-size: 32px; display: block; margin-bottom: 12px;">📭</span>
                Anda belum memiliki riwayat pengajuan konsultasi.
            </div>
        @endforelse

    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chips = document.querySelectorAll('.chip');
            const cards = document.querySelectorAll('.history-card');

            chips.forEach(chip => {
                chip.addEventListener('click', function() {
                    // 1. Reset class 'active' di semua chip
                    chips.forEach(c => c.classList.remove('active'));

                    // 2. Tambahkan class 'active' ke chip yang diklik
                    this.classList.add('active');

                    // 3. Filter data card
                    const filterValue = this.getAttribute('data-filter');

                    cards.forEach(card => {
                        const status = card.getAttribute('data-status');

                        if (filterValue === 'semua' || status === filterValue) {
                            card.style.display = 'flex';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
@endpush
