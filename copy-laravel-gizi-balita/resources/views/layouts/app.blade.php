<!-- File: resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GiziAnak Dashboard')</title>
    <!-- @vite('resources/css/app.css') -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- CSS GLOBAL & SIDEBAR (DESAIN DASHBOARD) -->
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #F0F7FF; 
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* --- SIDEBAR --- */
        /* PERUBAHAN: Menambahkan display: flex dan flex-direction: column */
        .sidebar { background-color: #0A2540; width: 220px; height: 100vh; position: fixed; left: 0; top: 0; transition: transform 0.3s ease; z-index: 50; display: flex; flex-direction: column; }
        
        /* PERUBAHAN: Membuat kontainer khusus untuk menu yang bisa di-scroll */
        .sidebar-scrollable { flex: 1; overflow-y: auto; overflow-x: hidden; padding-bottom: 20px; }
        
        .sidebar-scrollable::-webkit-scrollbar { width: 6px; }
        .sidebar-scrollable::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scrollable::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }
        .sidebar-scrollable::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.4); }

        /* --- MOBILE HEADER & OVERLAY --- */
        .mobile-header { display: none; justify-content: space-between; align-items: center; background-color: #1a3a52; padding: 16px 24px; color: white; position: sticky; top: 0; z-index: 30; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .hamburger-btn { background: none; border: none; color: white; font-size: 24px; cursor: pointer; }
        .sidebar-overlay { display: none; position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 40; transition: opacity 0.3s ease; }
        .sidebar-overlay.show { display: block; }
        
        /* --- MENU ITEMS --- */
        .menu-item { padding: 12px 20px; color: #9ca3af; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 12px; margin: 4px 12px; border-radius: 8px; font-size: 14px; text-decoration: none; }
        .menu-item:hover { background-color: rgba(255, 255, 255, 0.1); color: white; }
        .menu-item.active { background-color: #60a5fa; color: white; } 
        
        .section-title { font-size: 12px; font-weight: 700; text-transform: uppercase; color: #6b7280; padding: 20px 20px 12px; letter-spacing: 0.5px; }
        .logo-section { padding: 20px; display: flex; align-items: center; gap: 12px; color: white; font-weight: 700; font-size: 18px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); margin-bottom: 12px; flex-shrink: 0;}
        .logo-icon { width: 40px; height: 40px; background: #60a5fa; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
        
        .badge-admin { background-color: #fbbf24; color: #78350f; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; margin-top: 4px; margin-left: 20px; display: inline-block; border: none; }

        /* --- MAIN CONTENT WRAPPER --- */
        .main-content { margin-left: 220px; padding: 24px; transition: margin 0.3s ease; }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .mobile-header { display: flex; }
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 16px; }
        }
    </style>

    @yield('custom_css')
</head>

<body>
    @include('layouts.sidebar')

    <div class="main-content">
        @yield('content')
    </div>

    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
            document.querySelector('.sidebar-overlay').classList.toggle('show');
        }
    </script>
    @stack('scripts')
</body>
</html>