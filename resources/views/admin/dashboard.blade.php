@extends('layouts.app')

@section('title', 'Beranda Admin - GiziAnak')

@section('custom_css')
    <style>
        /* --- HEADER CARD --- */
        .header-card {
            background: linear-gradient(135deg, #0f5a8f 0%, #1a4d7a 100%);
            border-radius: 16px;
            padding: 32px;
            color: white;
            margin-bottom: 32px;
            box-shadow: 0 10px 25px -5px rgba(15, 90, 143, 0.3);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }

        .header-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 24px;
        }

        .btn-action {
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            text-decoration: none;
        }

        .btn-primary {
            background-color: #fbbf24;
            color: #78350f;
            box-shadow: 0 4px 6px rgba(251, 191, 36, 0.2);
        }

        .btn-secondary {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(4px);
        }

        .btn-action:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        /* --- STATISTIC CARDS --- */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            border: 1px solid #f1f5f9;
            border-top: 4px solid #3b82f6;
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        .stat-icon {
            font-size: 28px;
            margin-bottom: 8px;
        }

        .stat-card.blue {
            border-top-color: #3b82f6;
        }

        .stat-card.orange {
            border-top-color: #f59e0b;
        }

        .stat-card.green {
            border-top-color: #10b981;
        }

        .stat-card.purple {
            border-top-color: #8b5cf6;
        }

        .stat-number {
            font-size: 36px;
            font-weight: 900;
            color: #1e293b;
            margin: 4px 0;
            font-family: Georgia, serif;
            line-height: 1;
        }

        .stat-label {
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 20px;
            margin-top: 12px;
            letter-spacing: 0.5px;
        }

        .badge-positive {
            background-color: #dcfce7;
            color: #16a34a;
        }

        .badge-info {
            background-color: #e0e7ff;
            color: #4338ca;
        }

        /* --- LAYOUT GRIDS --- */
        .content-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 32px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 32px;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            /* Disesuaikan agar chart gizi lebih lebar */
            gap: 20px;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            border: 1px solid #f1f5f9;
            position: relative;
            width: 100%;
            box-sizing: border-box;
        }

        .card-title {
            font-size: 16px;
            font-weight: 800;
            color: #1e293b;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: 1px dashed #e2e8f0;
        }

        .card-title a {
            color: #3b82f6;
            font-size: 13px;
            text-decoration: none;
            font-weight: 700;
        }

        .card-title a:hover {
            text-decoration: underline;
        }

        /* --- DATE BADGE --- */
        .date-badge {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 12px 24px;
            border-radius: 16px;
            text-align: center;
            line-height: 1.2;
            min-width: 110px;
            backdrop-filter: blur(8px);
        }

        .date-badge .day-num {
            font-size: 42px;
            font-weight: 900;
            color: #fbbf24;
        }

        .date-label {
            font-size: 12px;
            color: white;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* --- USER ITEMS --- */
        .user-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .user-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 16px;
            flex-shrink: 0;
        }

        .user-info h4 {
            font-size: 14px;
            font-weight: 800;
            color: #1e293b;
            margin: 0 0 2px 0;
        }

        .user-info p {
            font-size: 12px;
            color: #64748b;
            margin: 0;
            text-transform: capitalize;
        }

        /* --- RESPONSIVE --- */
        @media (max-width: 1200px) {
            .content-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column-reverse;
            }

            .date-badge {
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 16px;
                padding: 16px;
            }

            .grid-2,
            .grid-3 {
                grid-template-columns: 1fr;
            }

            .btn-action {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endsection

@section('content')
    <div class="header-card">
        <div class="header-content">
            <div>
                <p style="margin: 0 0 8px; opacity: 0.9; font-size: 12px; font-weight: 800; letter-spacing: 1px;">🛡️ PANEL
                    SISTEM ADMIN</p>
                <h1 style="margin: 0; font-size: 36px; font-weight: 900; font-family: Georgia, serif;">
                    Dashboard <span style="color: #fbbf24;">GiziAnak</span>
                </h1>
                <p style="margin: 12px 0 0; opacity: 0.9; font-size: 15px; max-width: 500px; line-height: 1.6;">
                    Pantau aktivitas platform secara real-time, kelola database pengguna, dan awasi status kesehatan anak di
                    satu tempat.
                </p>
            </div>
            <div class="date-badge">
                <div class="day-num">{{ now()->format('d') }}</div>
                <div>
                    <div class="date-label">{{ now()->translatedFormat('M Y') }}</div>
                    <div class="date-label" style="font-weight: 500; font-size: 10px; margin-top: 2px;">
                        {{ now()->translatedFormat('l') }}</div>
                </div>
            </div>
        </div>

        <div class="header-buttons">
            <a href="{{ route('admin.users.index') }}" class="btn-action btn-primary">➕ Tambah User Baru</a>

            <a href="{{ route('admin.anak.index') }}" class="btn-action btn-secondary">👶 Master Data Anak</a>

            <a href="{{ route('admin.laporan.index') }}" class="btn-action btn-secondary">📊 Laporan Bulanan</a>
        </div>
    </div>

    <div class="content-grid">
        <div class="stat-card blue">
            <div class="stat-icon">👥</div>
            <div class="stat-number">{{ number_format($totalPengguna) }}</div>
            <div class="stat-label">Total Pengguna</div>
            <span class="stat-badge badge-positive">Sistem Aktif</span>
        </div>
        <div class="stat-card orange">
            <div class="stat-icon">👶</div>
            <div class="stat-number">{{ number_format($anakTerdaftar) }}</div>
            <div class="stat-label">Anak Terdaftar</div>
            <span class="stat-badge badge-positive">Dalam Pantauan</span>
        </div>
        <div class="stat-card green">
            <div class="stat-icon">👨‍⚕️</div>
            <div class="stat-number">{{ number_format($tenagaMedis) }}</div>
            <div class="stat-label">Tenaga Medis</div>
            <span class="stat-badge badge-positive">Aktif & Siaga</span>
        </div>
        <div class="stat-card purple">
            <div class="stat-icon">📅</div>
            <div class="stat-number">{{ number_format($totalKonsultasi) }}</div>
            <div class="stat-label">Total Konsultasi</div>
            <span class="stat-badge badge-info">Keseluruhan</span>
        </div>
    </div>

    <div class="grid-2">
        <div class="card">
            <div class="card-title">
                <span>📈 Tren Konsultasi (6 Bulan Terakhir)</span>

            </div>
            <div style="height: 300px;">
                <canvas id="consultationChart"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-title">
                <span>👥 Distribusi Role Pengguna</span>
                <a href="{{ route('admin.users.index') }}">Kelola →</a>
            </div>
            <div style="height: 300px; display: flex; justify-content: center;">
                <canvas id="roleChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid-3">
        <div class="card">
            <div class="card-title">
                <span>👨‍⚕️ Tenaga Medis Terbaru</span>
                <a href="{{ route('admin.users.index') }}">Semua →</a>
            </div>
            @forelse($daftarMedis as $medis)
                <div class="user-item">
                    @php
                        $bg =
                            $medis->role == 'dokter'
                                ? 'linear-gradient(135deg, #10b981, #059669)'
                                : 'linear-gradient(135deg, #f59e0b, #d97706)';
                        $inisial = strtoupper(substr($medis->name, 0, 2));
                    @endphp
                    <div class="user-avatar" style="background: {{ $bg }};">
                        {{ $inisial }}
                    </div>
                    <div class="user-info">
                        <h4>{{ $medis->name }}</h4>
                        <p>{{ $medis->role == 'ahligizi' ? 'Ahli Gizi' : 'Dokter' }}</p>
                    </div>
                    <span
                        style="margin-left: auto; background:#f0fdf4; color:#16a34a; padding:4px 8px; border-radius:6px; font-weight:700; font-size:10px;">Aktif</span>
                </div>
            @empty
                <p style="text-align: center; color: #94a3b8; padding: 20px;">Belum ada tenaga medis aktif.</p>
            @endforelse
        </div>

        <div class="card">
            <div class="card-title">
                <span>📊 Status Gizi Platform (Total)</span>
                <a href="{{ route('admin.laporan.index') }}">Detail →</a>
            </div>
            <div style="height: 220px; display: flex; justify-content: center; align-items: center; margin-top: 20px;">
                <canvas id="giziChart"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Menyuntikkan data dari Controller ke JavaScript
        const dataLabelsBulan = {!! json_encode($bulanLabels) !!};
        const dataKonsultasi = {!! json_encode($konsultasiData) !!};
        const dataRoles = {!! json_encode($roleData) !!};
        const dataGizi = {!! json_encode($giziData) !!};

        Chart.defaults.maintainAspectRatio = false;
        Chart.defaults.font.family = "'Nunito', 'Segoe UI', Roboto, sans-serif";

        // 1. Consultation Trend Chart (Line)
        new Chart(document.getElementById('consultationChart'), {
            type: 'line',
            data: {
                labels: dataLabelsBulan,
                datasets: [{
                    label: 'Konsultasi Baru',
                    data: dataKonsultasi,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.05)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointBackgroundColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [4, 4],
                            color: '#f1f5f9'
                        },
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // 2. Role Distribution Chart (Doughnut)
        new Chart(document.getElementById('roleChart'), {
            type: 'doughnut',
            data: {
                labels: ['Orang Tua', 'Dokter', 'Ahli Gizi', 'Admin'],
                datasets: [{
                    data: dataRoles,
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#1e293b'],
                    borderWidth: 4,
                    borderColor: '#fff'
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                weight: '700'
                            }
                        }
                    }
                }
            }
        });

        // 3. Nutrition Distribution Chart (Pie)
        new Chart(document.getElementById('giziChart'), {
            type: 'pie',
            data: {
                labels: ['Normal', 'Kurang', 'Obesitas', 'Stunting'],
                datasets: [{
                    data: dataGizi,
                    backgroundColor: ['#10b981', '#f59e0b', '#3b82f6', '#ef4444'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            usePointStyle: true,
                            font: {
                                size: 11,
                                weight: '700'
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
