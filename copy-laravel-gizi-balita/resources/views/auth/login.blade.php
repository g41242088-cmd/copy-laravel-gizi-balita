<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GiziAnak</title>
    @vite('resources/css/app.css')
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        .gradient-left {
            background: linear-gradient(135deg, #0f3460 0%, #1a4d7a 100%);
        }
        .password-toggle {
            cursor: pointer;
            user-select: none;
        }
    </style>
</head>

<body class="h-screen flex bg-gray-50 m-0 p-0">

    <!-- LEFT SIDE - SIDEBAR -->
    <div class="hidden md:flex w-2/5 gradient-left text-white flex-col justify-between p-12 relative overflow-hidden">
        
        <!-- Decorative background elements -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-600 rounded-full opacity-10 -mr-48 -mt-48"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-blue-500 rounded-full opacity-10 -ml-40 -mb-40"></div>

        <!-- LOGO & TOP -->
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-16">
                <div class="w-10 h-10 bg-blue-400 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                    🌱
                </div>
                <h1 class="text-2xl font-bold">
                    Gizi<span class="text-yellow-300">Anak</span>
                </h1>
            </div>
        </div>

        <!-- MIDDLE CONTENT -->
        <div class="relative z-10">
            <div class="inline-block bg-blue-800 bg-opacity-60 px-4 py-2 rounded-full text-sm font-semibold mb-6 border border-blue-600">
                👋 SELAMAT DATANG KEMBALI
            </div>

            <h2 class="text-4xl font-black leading-tight mb-4">
                Pantau Gizi Anak
            </h2>
            
            <p class="text-yellow-300 text-3xl font-bold italic mb-6">
                Lebih Mudah
            </p>

            <p class="text-blue-100 text-sm leading-relaxed max-w-xs">
                Masuk ke akun Anda dan mulai pantau tumbuh kembang anak dengan fitur lengkap GiziAnak.
            </p>
        </div>

        <!-- FOOTER -->
        <div class="relative z-10 text-xs text-blue-200">
            © 2025 GiziAnak · Semua hak dilindungi
        </div>
    </div>

    <!-- RIGHT SIDE - LOGIN FORM -->
    <div class="w-full md:w-3/5 flex items-center justify-center p-8 bg-gray-50">
        
        <div class="w-full max-w-md">

            <!-- TITLE -->
            <h2 class="text-3xl font-bold text-gray-900 mb-3">
                Masuk ke Akun
            </h2>

            <!-- SUBTITLE & REGISTER LINK -->
            <p class="text-gray-600 text-sm mb-8">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-500 font-semibold hover:text-blue-600 transition">
                    Daftar gratis sekarang
                </a>
            </p>

            <!-- FORM LOGIN -->
            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                <!-- EMAIL INPUT -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                            📧
                        </span>
                        <input 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-gray-900 placeholder-gray-400"
                            placeholder="nama@email.com"
                        >
                        @error('email')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- PASSWORD INPUT -->
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                            🔒
                        </span>
                        <input 
                            type="password" 
                            id="password"
                            name="password" 
                            required
                            autocomplete="current-password"
                            class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-gray-900 placeholder-gray-400"
                            placeholder="Masukkan password Anda"
                        >
                        <button 
                            type="button"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 password-toggle"
                            onclick="togglePassword()"
                        >
                            👁️
                        </button>
                        @error('password')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- REMEMBER ME CHECKBOX -->
                <div class="flex items-center mb-6 mt-4">
                    <input 
                        type="checkbox" 
                        id="remember"
                        name="remember" 
                        class="w-4 h-4 text-blue-500 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer"
                    >
                    <label for="remember" class="ml-2 text-sm text-gray-600 cursor-pointer">
                        Ingat saya
                    </label>
                </div>

                <!-- LOGIN BUTTON -->
                <button 
                    type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2"
                >
                    Masuk Sekarang
                    <span>→</span>
                </button>

            </form>

            <!-- FORGOT PASSWORD -->
            <div class="mt-6 text-center">
                <a href="{{ route('password.request') }}" class="text-blue-500 text-sm hover:text-blue-600 font-medium transition">
                    Lupa password?
                </a>
            </div>

        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const button = event.target;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                button.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                button.textContent = '👁️';
            }
        }
    </script>

</body>
</html>