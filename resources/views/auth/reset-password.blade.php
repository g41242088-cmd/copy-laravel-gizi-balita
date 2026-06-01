<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Password Baru - GiziAnak</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Nunito', sans-serif; }
        body { margin: 0; padding: 0; display: flex; min-height: 100vh; background-color: #f8fafc; }
        
        /* Layout Utama */
        .split-layout { display: flex; width: 100%; min-height: 100vh; }
        
        /* --- PANEL KIRI (BIRU GELAP) --- */
        .left-panel {
            width: 45%;
            background-color: #173a5e; 
            color: white;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        
        /* Hiasan background abstrak di kiri */
        .left-panel::before {
            content: ''; position: absolute; top: -10%; right: -10%; width: 400px; height: 400px;
            background: rgba(255, 255, 255, 0.03); border-radius: 50%;
        }
        .left-panel::after {
            content: ''; position: absolute; bottom: -5%; left: -10%; width: 300px; height: 300px;
            background: rgba(255, 255, 255, 0.03); border-radius: 50%;
        }

        .brand { display: flex; align-items: center; gap: 12px; font-size: 24px; font-weight: 900; z-index: 10; }
        .brand-icon { background: #3b82f6; padding: 8px; border-radius: 8px; font-size: 20px; line-height: 1; }
        
        .welcome-text { z-index: 10; margin-top: auto; margin-bottom: auto; }
        .badge { display: inline-block; background: rgba(255, 255, 255, 0.1); padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: 800; letter-spacing: 1px; margin-bottom: 24px; border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(4px); }
        .welcome-text h1 { font-size: 46px; font-weight: 900; margin: 0 0 8px 0; line-height: 1.2; }
        .welcome-text h1 span { color: #fbbf24; }
        .welcome-text p { font-size: 16px; opacity: 0.8; line-height: 1.6; max-width: 400px; margin-top: 16px; }
        
        .footer-text { font-size: 12px; opacity: 0.6; z-index: 10; }

        /* --- PANEL KANAN (FORM PUTIH) --- */
        .right-panel {
            width: 55%;
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .form-container { width: 100%; max-width: 450px; }
        .form-header h2 { font-size: 32px; font-weight: 900; color: #1e293b; margin: 0 0 8px 0; }
        .form-header p { font-size: 14px; color: #64748b; margin: 0 0 32px 0; line-height: 1.6; }

        /* Input Styles */
        .input-group { margin-bottom: 24px; }
        .input-label { display: block; font-size: 13px; font-weight: 800; color: #475569; margin-bottom: 8px; }
        .input-wrapper { position: relative; display: flex; align-items: center; }
        .input-icon { position: absolute; left: 16px; font-size: 16px; color: #94a3b8; }
        .input-field {
            width: 100%; padding: 14px 16px 14px 44px;
            border: 1px solid #e2e8f0; border-radius: 12px;
            font-size: 14px; color: #1e293b; transition: all 0.2s; outline: none;
        }
        .input-field:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        .error-text { color: #ef4444; font-size: 12px; font-weight: 600; display: block; margin-top: 6px; }

        /* Readonly Email Input Style */
        .input-readonly { background-color: #f8fafc; color: #64748b; cursor: not-allowed; border-color: #e2e8f0; }
        .input-readonly:focus { box-shadow: none; border-color: #e2e8f0; }

        /* Buttons */
        .btn-submit {
            width: 100%; background-color: #3b82f6; color: white;
            padding: 14px; border: none; border-radius: 12px;
            font-size: 16px; font-weight: 800; cursor: pointer;
            transition: all 0.2s; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2); margin-top: 8px;
        }
        .btn-submit:hover { background-color: #2563eb; transform: translateY(-2px); }

        /* Responsive */
        @media (max-width: 900px) {
            .split-layout { flex-direction: column; }
            .left-panel, .right-panel { width: 100%; }
            .left-panel { padding: 40px 24px; min-height: auto; }
            .right-panel { padding: 40px 24px; }
        }
    </style>
</head>
<body>

    <div class="split-layout">
        
        <div class="left-panel">
            <div class="brand">
                <span class="brand-icon">🌱</span> GiziAnak
            </div>
            
            <div class="welcome-text">
                <div class="badge">🔄 PEMULIHAN AKUN</div>
                <h1>Buat Sandi <br><span>Baru Anda</span></h1>
                <p>Silakan buat kata sandi baru untuk akun Anda. Gunakan kombinasi huruf dan angka agar akun Anda lebih aman.</p>
            </div>
            
            <div class="footer-text">
                &copy; {{ date('Y') }} GiziAnak - Semua hak dilindungi
            </div>
        </div>

        <div class="right-panel">
            <div class="form-container">
                
                <div class="form-header">
                    <h2>Amankan Akun Anda</h2>
                    <p>Masukkan kata sandi baru untuk melanjutkan akses ke platform GiziAnak.</p>
                </div>

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="input-group">
                        <label class="input-label">Alamat Email</label>
                        <div class="input-wrapper">
                            <span class="input-icon">📧</span>
                            <input type="email" name="email" class="input-field input-readonly" value="{{ old('email', $request->email) }}" required readonly>
                        </div>
                        @error('email') <span class="error-text">{{ $message }}</span> @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label">Kata Sandi Baru</label>
                        <div class="input-wrapper">
                            <span class="input-icon">🔒</span>
                            <input type="password" name="password" class="input-field" placeholder="Minimal 8 karakter" required autofocus autocomplete="new-password">
                        </div>
                        @error('password') <span class="error-text">{{ $message }}</span> @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label">Konfirmasi Kata Sandi</label>
                        <div class="input-wrapper">
                            <span class="input-icon">🔑</span>
                            <input type="password" name="password_confirmation" class="input-field" placeholder="Ulangi kata sandi baru Anda" required autocomplete="new-password">
                        </div>
                        @error('password_confirmation') <span class="error-text">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn-submit">Simpan Kata Sandi Baru</button>

                </form>

            </div>
        </div>

    </div>

</body>
</html>