<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GiziAnak — Pantau Tumbuh Kembang Anak</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,700;1,9..144,400&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --blue-950: #020B18;
            --blue-900: #041830;
            --blue-800: #082344;
            --blue-700: #0D3460;
            --blue-600: #1046A0;
            --blue-500: #1a5fcc;
            --blue-400: #3B82F6;
            --blue-300: #93C5FD;
            --blue-200: #BFDBFE;
            --blue-100: #DBEAFE;
            --blue-50:  #EFF6FF;
            --white: #FFFFFF;
            --cream: #F8FAFF;
            --text-primary: #041830;
            --text-secondary: #3D5A80;
            --text-muted: #7A9CC4;
            --border: rgba(59,130,246,0.15);
        }

        html { scroll-behavior: smooth; scroll-padding-top: 80px; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--white);
            color: var(--text-primary);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ─── TYPOGRAPHY ─── */
        .display { font-family: 'Fraunces', serif; font-weight: 700; line-height: 1.08; letter-spacing: -0.02em; }
        .display-italic { font-family: 'Fraunces', serif; font-style: italic; font-weight: 400; }

        /* ─── SCROLLBAR ─── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--blue-50); }
        ::-webkit-scrollbar-thumb { background: var(--blue-400); border-radius: 99px; }

        /* ─── NAVBAR ─── */
        .nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            padding: 0 40px;
            height: 72px;
            display: flex; align-items: center; justify-content: space-between;
            background: rgba(2,11,24,0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(59,130,246,0.12);
            transition: background 0.3s, border-color 0.3s;
        }
        .nav.scrolled {
            background: rgba(255,255,255,0.95);
            border-bottom-color: var(--border);
        }
        .nav.scrolled .nav-logo-text { color: var(--blue-800); }
        .nav.scrolled .nav-logo-sub { color: var(--text-muted); }
        .nav.scrolled .nav-links a { color: var(--text-secondary); }
        .nav.scrolled .nav-links a:hover { color: var(--blue-600); }
        .nav.scrolled .btn-outline { border-color: var(--blue-200); color: var(--blue-700); }
        .nav.scrolled .btn-outline:hover { background: var(--blue-50); }
        .nav.scrolled .hamburger span { background: var(--blue-800); }

        .nav-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .nav-logo-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--blue-600) 0%, var(--blue-400) 100%);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }
        .nav-logo-text { font-family: 'Fraunces', serif; font-size: 22px; font-weight: 700; color: #fff; transition: color 0.3s; }
        .nav-logo-text span { color: var(--blue-300); }
        .nav-logo-sub { font-size: 10px; color: rgba(255,255,255,0.45); font-weight: 500; letter-spacing: 0.08em; text-transform: uppercase; transition: color 0.3s; }

        .nav-links { display: flex; align-items: center; gap: 36px; list-style: none; }
        .nav-links a { text-decoration: none; font-size: 14px; font-weight: 500; color: rgba(255,255,255,0.7); transition: color 0.2s; }
        .nav-links a:hover { color: #fff; }

        .nav-actions { display: flex; align-items: center; gap: 12px; }

        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 22px; border-radius: 10px;
            font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 600;
            cursor: pointer; text-decoration: none; border: none;
            transition: all 0.25s cubic-bezier(.4,0,.2,1);
            white-space: nowrap;
        }
        .btn-outline {
            background: transparent;
            border: 1.5px solid rgba(255,255,255,0.25);
            color: rgba(255,255,255,0.9);
        }
        .btn-outline:hover { border-color: rgba(255,255,255,0.5); background: rgba(255,255,255,0.08); }
        .btn-solid {
            background: var(--blue-600);
            color: white;
            box-shadow: 0 4px 20px rgba(16,70,160,0.3);
        }
        .btn-solid:hover { background: var(--blue-700); box-shadow: 0 6px 28px rgba(16,70,160,0.4); transform: translateY(-1px); }
        .btn-lg { padding: 16px 34px; font-size: 16px; border-radius: 14px; }
        .btn-white { background: white; color: var(--blue-700); box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .btn-white:hover { background: var(--blue-50); transform: translateY(-1px); }

        /* ─── HAMBURGER ─── */
        .hamburger { display: none; flex-direction: column; gap: 5px; cursor: pointer; background: none; border: none; padding: 4px; }
        .hamburger span { display: block; width: 24px; height: 2px; background: rgba(255,255,255,0.85); border-radius: 2px; transition: 0.3s; }

        /* ─── MOBILE MENU ─── */
        .mobile-nav {
            display: none; position: fixed; inset: 72px 0 0;
            background: rgba(255,255,255,0.97); backdrop-filter: blur(20px);
            z-index: 99; padding: 32px; flex-direction: column; gap: 24px;
            transform: translateY(-20px); opacity: 0;
            transition: all 0.3s cubic-bezier(.4,0,.2,1);
        }
        .mobile-nav.open { transform: translateY(0); opacity: 1; }
        .mobile-nav a { text-decoration: none; font-size: 18px; font-weight: 500; color: var(--text-primary); padding: 12px 0; border-bottom: 1px solid var(--border); }

        /* ─── HERO ─── */
        .hero-section {
            background: linear-gradient(160deg, #020B18 0%, #041830 55%, #071e40 100%);
            position: relative; overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 60% 50% at 70% 40%, rgba(59,130,246,0.12) 0%, transparent 70%),
                radial-gradient(ellipse 40% 60% at 10% 80%, rgba(16,70,160,0.15) 0%, transparent 60%);
            pointer-events: none;
        }
        .hero-section::after {
            content: '';
            position: absolute; bottom: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(59,130,246,0.3), transparent);
        }
        /* Grid dot overlay */
        .hero-dots {
            position: absolute; inset: 0; pointer-events: none; opacity: 0.35;
            background-image: radial-gradient(circle, rgba(59,130,246,0.4) 1px, transparent 1px);
            background-size: 32px 32px;
            mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 30%, transparent 100%);
        }

        .hero {
            padding: 160px 40px 120px;
            max-width: 1200px; margin: 0 auto;
            position: relative; z-index: 1;
        }

        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 6px 16px; border-radius: 99px;
            background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.3);
            font-size: 13px; font-weight: 600; color: var(--blue-300);
            margin-bottom: 32px;
            animation: fadeUp 0.8s ease both;
        }
        .hero-badge-dot {
            width: 8px; height: 8px; border-radius: 50%; background: var(--blue-500);
            box-shadow: 0 0 0 3px rgba(26,95,204,0.2);
            animation: ping 2s ease-in-out infinite;
        }
        @keyframes ping {
            0%, 100% { box-shadow: 0 0 0 3px rgba(26,95,204,0.2); }
            50% { box-shadow: 0 0 0 6px rgba(26,95,204,0.1); }
        }

        .hero-heading {
            font-size: clamp(48px, 7vw, 88px);
            margin-bottom: 24px;
            animation: fadeUp 0.8s 0.1s ease both;
            max-width: 720px;
            color: #ffffff;
        }
        .hero-heading em { color: var(--blue-300); font-style: normal; }

        .hero-sub {
            font-size: clamp(16px, 2vw, 19px);
            line-height: 1.7;
            color: rgba(191,219,254,0.75);
            max-width: 560px;
            margin-bottom: 40px;
            animation: fadeUp 0.8s 0.2s ease both;
        }

        .hero-actions {
            display: flex; align-items: center; gap: 16px; flex-wrap: wrap;
            margin-bottom: 64px;
            animation: fadeUp 0.8s 0.3s ease both;
        }

        .hero-trust {
            display: flex; align-items: center; gap: 12px; font-size: 13px; color: rgba(255,255,255,0.45);
            animation: fadeUp 0.8s 0.35s ease both;
        }
        .hero-avatars { display: flex; }
        .hero-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            border: 2px solid white;
            background: linear-gradient(135deg, var(--blue-400), var(--blue-600));
            margin-left: -8px; font-size: 11px; font-weight: 700;
            color: white; display: flex; align-items: center; justify-content: center;
        }
        .hero-avatar:first-child { margin-left: 0; }

        /* Hero visual */
        .hero-visual {
            position: absolute; right: 40px; top: 140px;
            width: 460px;
            animation: fadeLeft 1s 0.2s ease both;
        }

        .hero-card-main {
            background: white;
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 20px 80px rgba(16,70,160,0.1), 0 4px 20px rgba(0,0,0,0.04);
        }
        .hero-card-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: var(--text-muted); margin-bottom: 12px; }
        .hero-card-name { font-size: 18px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px; }
        .hero-card-meta { font-size: 13px; color: var(--text-muted); margin-bottom: 20px; }

        /* Chart bars */
        .chart-wrap { display: flex; align-items: flex-end; gap: 8px; height: 100px; margin-bottom: 16px; }
        .chart-bar {
            flex: 1; border-radius: 6px 6px 0 0;
            background: var(--blue-100);
            position: relative; overflow: hidden;
        }
        .chart-bar-fill {
            position: absolute; bottom: 0; left: 0; right: 0;
            border-radius: 6px 6px 0 0;
            background: linear-gradient(180deg, var(--blue-400) 0%, var(--blue-600) 100%);
        }
        .chart-bar.active .chart-bar-fill { background: linear-gradient(180deg, #60A5FA 0%, var(--blue-500) 100%); }

        .chart-labels { display: flex; gap: 8px; font-size: 10px; color: var(--text-muted); margin-bottom: 16px; }
        .chart-labels span { flex: 1; text-align: center; }

        .hero-stat-row { display: flex; gap: 12px; }
        .hero-stat {
            flex: 1; background: var(--blue-50); border-radius: 12px; padding: 12px;
            border: 1px solid var(--blue-100);
        }
        .hero-stat-val { font-size: 20px; font-weight: 700; color: var(--blue-700); }
        .hero-stat-lbl { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
        .hero-stat-change { font-size: 11px; font-weight: 600; color: #16A34A; }

        /* Floating cards */
        .float-card {
            position: absolute;
            background: white; border: 1px solid var(--border);
            border-radius: 16px; padding: 14px 18px;
            box-shadow: 0 8px 32px rgba(16,70,160,0.12);
            display: flex; align-items: center; gap: 10px;
        }
        .float-card-icon {
            width: 38px; height: 38px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }
        .float-card-title { font-size: 13px; font-weight: 600; color: var(--text-primary); }
        .float-card-sub { font-size: 11px; color: var(--text-muted); }

        .float-1 {
            top: -20px; left: -60px;
            animation: floatA 4s ease-in-out infinite;
        }
        .float-2 {
            bottom: 40px; left: -70px;
            animation: floatB 4s 2s ease-in-out infinite;
        }

        @keyframes floatA {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        @keyframes floatB {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(8px); }
        }

        /* ─── ANIMATIONS ─── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeLeft {
            from { opacity: 0; transform: translateX(40px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .reveal {
            opacity: 0; transform: translateY(28px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }
        .reveal-delay-4 { transition-delay: 0.4s; }

        /* ─── SECTION COMMONS ─── */
        section { padding: 96px 40px; }
        .container { max-width: 1160px; margin: 0 auto; }

        .section-label {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 12px; font-weight: 700; letter-spacing: 0.12em;
            text-transform: uppercase; color: var(--blue-600);
            margin-bottom: 16px;
        }
        .section-label::before {
            content: ''; display: block; width: 20px; height: 2px;
            background: var(--blue-500); border-radius: 2px;
        }
        .section-title { margin-bottom: 16px; font-size: clamp(32px, 4vw, 52px); }
        .section-sub { font-size: 18px; color: var(--text-secondary); line-height: 1.7; max-width: 560px; }

        /* ─── LOGOS STRIP ─── */
        .logos-strip {
            background: var(--blue-950);
            padding: 32px 40px;
            overflow: hidden;
        }
        .logos-strip-label {
            text-align: center; font-size: 12px; font-weight: 600;
            letter-spacing: 0.1em; text-transform: uppercase;
            color: var(--blue-400); margin-bottom: 28px;
        }
        .logos-track {
            display: flex; gap: 56px; align-items: center;
            animation: marquee 20s linear infinite;
            width: max-content;
        }
        .logos-track-wrap { overflow: hidden; mask-image: linear-gradient(90deg, transparent, black 15%, black 85%, transparent); }
        .logo-item {
            display: flex; align-items: center; gap: 8px;
            font-size: 14px; font-weight: 700; color: rgba(255,255,255,0.4);
            white-space: nowrap;
        }
        .logo-item-icon { font-size: 20px; opacity: 0.6; }
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* ─── FEATURES ─── */
        .features-section { background: var(--cream); }

        .features-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 24px;
            margin-top: 64px;
        }

        .feature-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 32px;
            transition: all 0.3s cubic-bezier(.4,0,.2,1);
            position: relative; overflow: hidden;
        }
        .feature-card::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(16,70,160,0.03) 0%, transparent 60%);
            opacity: 0; transition: opacity 0.3s;
        }
        .feature-card:hover { transform: translateY(-6px); box-shadow: 0 24px 60px rgba(16,70,160,0.1); border-color: var(--blue-200); }
        .feature-card:hover::after { opacity: 1; }

        .feature-card.featured {
            background: linear-gradient(135deg, var(--blue-700) 0%, var(--blue-600) 100%);
            border-color: transparent;
        }
        .feature-card.featured .feature-icon-wrap { background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.2); }
        .feature-card.featured h3 { color: white; }
        .feature-card.featured p { color: rgba(255,255,255,0.75); }
        .feature-card.featured .feature-tag { background: rgba(255,255,255,0.15); color: rgba(255,255,255,0.9); }

        .feature-card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; }
        .feature-icon-wrap {
            width: 52px; height: 52px; border-radius: 14px;
            background: var(--blue-50); border: 1px solid var(--blue-100);
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
        }
        .feature-tag {
            font-size: 11px; font-weight: 700; letter-spacing: 0.08em;
            text-transform: uppercase; padding: 4px 10px; border-radius: 6px;
            background: var(--blue-50); color: var(--blue-600);
        }
        .feature-card h3 { font-size: 20px; font-weight: 700; margin-bottom: 10px; color: var(--text-primary); }
        .feature-card p { font-size: 15px; line-height: 1.6; color: var(--text-secondary); }

        /* Large span */
        .feature-span-2 { grid-column: span 2; display: flex; gap: 32px; align-items: center; }
        .feature-span-2 .feature-content { flex: 1; }
        .feature-span-2 .feature-visual { flex: 1; }

        /* Mini chart in large card */
        .mini-chart { display: flex; align-items: flex-end; gap: 6px; height: 64px; padding: 0 4px; }
        .mini-bar { flex: 1; border-radius: 4px 4px 0 0; background: rgba(255,255,255,0.2); position: relative; overflow: hidden; }
        .mini-bar-fill { position: absolute; bottom: 0; left: 0; right: 0; background: rgba(255,255,255,0.7); border-radius: 4px 4px 0 0; }

        /* ─── HOW IT WORKS ─── */
        .how-section { background: white; }
        .steps-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0; margin-top: 64px; position: relative; }
        .steps-grid::before {
            content: '';
            position: absolute; top: 28px; left: calc(12.5% + 28px); right: calc(12.5% + 28px);
            height: 2px; background: linear-gradient(90deg, var(--blue-200), var(--blue-400), var(--blue-200));
            z-index: 0;
        }

        .step-item { text-align: center; padding: 0 24px; position: relative; z-index: 1; }
        .step-num {
            width: 56px; height: 56px; border-radius: 50%;
            background: white; border: 2px solid var(--blue-200);
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; margin: 0 auto 24px;
            transition: all 0.3s;
        }
        .step-item:hover .step-num { border-color: var(--blue-500); background: var(--blue-50); transform: scale(1.1); }
        .step-item h4 { font-size: 16px; font-weight: 700; margin-bottom: 8px; }
        .step-item p { font-size: 14px; color: var(--text-secondary); line-height: 1.6; }

        /* ─── STATS ─── */
        .stats-section {
            background: var(--blue-800);
            padding: 80px 40px;
        }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0; }
        .stat-item {
            text-align: center; padding: 32px 20px;
            border-right: 1px solid rgba(255,255,255,0.1);
        }
        .stat-item:last-child { border-right: none; }
        .stat-num {
            font-family: 'Fraunces', serif; font-size: 56px; font-weight: 700;
            color: white; line-height: 1; margin-bottom: 8px;
        }
        .stat-num span { color: var(--blue-300); font-size: 36px; }
        .stat-desc { font-size: 15px; color: rgba(255,255,255,0.6); }

        /* ─── TESTIMONIALS ─── */
        .testi-section { background: var(--cream); }
        .testi-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-top: 64px; }

        .testi-card {
            background: white; border: 1px solid var(--border);
            border-radius: 20px; padding: 32px;
            transition: all 0.3s;
            display: flex; flex-direction: column; gap: 20px;
        }
        .testi-card:hover { transform: translateY(-4px); box-shadow: 0 16px 48px rgba(16,70,160,0.08); }
        .testi-card:nth-child(2) { background: var(--blue-700); border-color: transparent; }
        .testi-card:nth-child(2) .testi-text { color: rgba(255,255,255,0.85); }
        .testi-card:nth-child(2) .testi-name { color: white; }
        .testi-card:nth-child(2) .testi-meta { color: rgba(255,255,255,0.5); }
        .testi-card:nth-child(2) .testi-stars { color: #FCD34D; }
        .testi-card:nth-child(2) .testi-quote { color: rgba(255,255,255,0.2); }

        .testi-stars { font-size: 16px; color: #F59E0B; letter-spacing: 2px; }
        .testi-quote { font-size: 48px; font-family: 'Fraunces', serif; color: var(--blue-100); line-height: 1; height: 32px; }
        .testi-text { font-size: 15px; line-height: 1.7; color: var(--text-secondary); flex: 1; }
        .testi-author { display: flex; align-items: center; gap: 12px; padding-top: 20px; border-top: 1px solid var(--border); }
        .testi-card:nth-child(2) .testi-author { border-color: rgba(255,255,255,0.1); }
        .testi-avatar {
            width: 44px; height: 44px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 16px; color: white;
            flex-shrink: 0;
        }
        .testi-name { font-size: 14px; font-weight: 700; color: var(--text-primary); }
        .testi-meta { font-size: 12px; color: var(--text-muted); margin-top: 2px; }

        /* ─── CTA SECTION ─── */
        .cta-section {
            background: var(--blue-900);
            position: relative; overflow: hidden;
            padding: 120px 40px;
            text-align: center;
        }
        .cta-bg-circle {
            position: absolute; border-radius: 50%;
            background: radial-gradient(circle, rgba(59,130,246,0.15) 0%, transparent 70%);
        }
        .cta-bg-1 { width: 600px; height: 600px; top: -200px; left: -100px; }
        .cta-bg-2 { width: 500px; height: 500px; bottom: -200px; right: -50px; }
        .cta-grid {
            background: linear-gradient(135deg, var(--blue-700) 0%, var(--blue-600) 100%);
            border-radius: 32px; padding: 80px;
            max-width: 900px; margin: 0 auto;
            position: relative; z-index: 1;
            box-shadow: 0 40px 100px rgba(16,70,160,0.4);
        }
        .cta-grid::before {
            content: '';
            position: absolute; inset: 0; border-radius: 32px;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .cta-tag {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 16px; border-radius: 99px;
            background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2);
            font-size: 12px; font-weight: 700; letter-spacing: 0.1em;
            text-transform: uppercase; color: rgba(255,255,255,0.9);
            margin-bottom: 24px;
        }
        .cta-title { font-size: clamp(32px, 4vw, 52px); color: white; margin-bottom: 20px; position: relative; }
        .cta-sub { font-size: 18px; color: rgba(255,255,255,0.7); margin-bottom: 40px; line-height: 1.6; }
        .cta-actions { display: flex; justify-content: center; gap: 16px; flex-wrap: wrap; margin-bottom: 32px; }
        .cta-footnote { font-size: 13px; color: rgba(255,255,255,0.5); }
        .cta-footnote span { color: rgba(255,255,255,0.7); font-weight: 600; }

        /* ─── FOOTER ─── */
        footer {
            background: var(--blue-950);
            color: rgba(255,255,255,0.5);
            padding: 64px 40px 40px;
        }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 48px; margin-bottom: 56px; }
        .footer-brand p { font-size: 14px; line-height: 1.7; margin-top: 16px; max-width: 280px; }
        .footer-brand-logo { display: flex; align-items: center; gap: 10px; margin-bottom: 4px; }
        .footer-brand-logo-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, var(--blue-600), var(--blue-400));
            display: flex; align-items: center; justify-content: center; font-size: 18px;
        }
        .footer-brand-logo span { font-family: 'Fraunces', serif; font-size: 20px; font-weight: 700; color: white; }
        .footer-col h5 { font-size: 13px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: rgba(255,255,255,0.8); margin-bottom: 20px; }
        .footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 12px; }
        .footer-col a { text-decoration: none; font-size: 14px; color: rgba(255,255,255,0.5); transition: color 0.2s; }
        .footer-col a:hover { color: white; }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,0.08); padding-top: 28px; display: flex; justify-content: space-between; align-items: center; }
        .footer-bottom p { font-size: 13px; }
        .footer-socials { display: flex; gap: 16px; }
        .social-btn {
            width: 36px; height: 36px; border-radius: 8px;
            background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,0.5); text-decoration: none; font-size: 14px;
            transition: all 0.2s;
        }
        .social-btn:hover { background: var(--blue-600); border-color: var(--blue-500); color: white; }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 1100px) {
            .hero-visual { width: 380px; }
            .hero { padding-right: 440px; }
        }
        @media (max-width: 900px) {
            .hero-visual { display: none; }
            .hero { padding: 120px 24px 80px; }
            .features-grid { grid-template-columns: 1fr 1fr; }
            .feature-span-2 { grid-column: span 2; flex-direction: column; }
            .steps-grid { grid-template-columns: 1fr 1fr; gap: 40px; }
            .steps-grid::before { display: none; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .stat-item { border-right: none; border-bottom: 1px solid rgba(255,255,255,0.1); }
            .stat-item:nth-child(odd) { border-right: 1px solid rgba(255,255,255,0.1); }
            .stat-item:last-child, .stat-item:nth-last-child(2) { border-bottom: none; }
            .testi-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
            .cta-grid { padding: 48px 32px; }
            section { padding: 64px 24px; }
        }
        @media (max-width: 640px) {
            .nav { padding: 0 20px; }
            .nav-links, .nav-actions { display: none; }
            .hamburger { display: flex; }
            .mobile-nav { display: flex; }
            .features-grid { grid-template-columns: 1fr; }
            .feature-span-2 { grid-column: span 1; }
            .steps-grid { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .footer-grid { grid-template-columns: 1fr; }
            .footer-bottom { flex-direction: column; gap: 16px; }
            .logos-strip { padding: 24px 20px; }
            .cta-grid { border-radius: 20px; padding: 40px 24px; }
            section { padding: 56px 20px; }
        }
    </style>
</head>
<body>

<!-- ─── NAVBAR ─── -->
<nav class="nav" id="navbar">
    <a href="/" class="nav-logo">
        <div class="nav-logo-icon">🌱</div>
        <div>
            <div class="nav-logo-text">Gizi<span>Anak</span></div>
            <div class="nav-logo-sub">Tumbuh Kembang Optimal</div>
        </div>
    </a>
    <ul class="nav-links">
        <li><a href="#fitur">Fitur</a></li>
        <li><a href="#cara-kerja">Cara Kerja</a></li>
        <li><a href="#testimoni">Testimoni</a></li>
        <li><a href="#tentang">Tentang</a></li>
    </ul>
    <div class="nav-actions">
        @auth
            <a href="{{ url('/dashboard') }}" class="btn btn-outline">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-outline">Masuk</a>
            <a href="{{ route('register') }}" class="btn btn-solid">Daftar Gratis</a>
        @endauth
    </div>
    <button class="hamburger" id="hamburger" aria-label="Menu">
        <span></span><span></span><span></span>
    </button>
</nav>

<!-- Mobile Nav -->
<div class="mobile-nav" id="mobileNav">
    <a href="#fitur">Fitur</a>
    <a href="#cara-kerja">Cara Kerja</a>
    <a href="#testimoni">Testimoni</a>
    <a href="#tentang">Tentang</a>
    @auth
        <a href="{{ url('/dashboard') }}" class="btn btn-solid" style="text-align:center">Dashboard</a>
    @else
        <a href="{{ route('login') }}" class="btn btn-outline" style="text-align:center">Masuk</a>
        <a href="{{ route('register') }}" class="btn btn-solid" style="text-align:center">Daftar Gratis</a>
    @endauth
</div>

<!-- ─── HERO ─── -->
<section class="hero-section">
    <div class="hero-dots"></div>
<section class="hero" style="position:relative; padding-top:160px; padding-bottom:120px; max-width:none;">
    <div style="max-width:1160px; margin:0 auto; position:relative;">

        <!-- Text -->
        <div style="max-width: 640px;">
            <div class="hero-badge">
                <div class="hero-badge-dot"></div>
                Dipercaya 50.000+ Keluarga Indonesia
            </div>

            <h1 class="display hero-heading">
                Pantau Tumbuh<br>
                Kembang Anak<br>
                <em>Lebih Cerdas</em>
            </h1>

            <p class="hero-sub">
                Platform kesehatan anak berbasis data yang membantu orang tua memantau gizi, pertumbuhan, dan konsultasi dengan dokter profesional — kapan saja dan di mana saja.
            </p>

            <div class="hero-actions">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-solid btn-lg">Buka Dashboard →</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-solid btn-lg">Mulai Gratis →</a>
                    <a href="#fitur" class="btn btn-outline btn-lg">Lihat Fitur</a>
                @endauth
            </div>

            <div class="hero-trust">
                <div class="hero-avatars">
                    <div class="hero-avatar">SN</div>
                    <div class="hero-avatar">BR</div>
                    <div class="hero-avatar">DK</div>
                    <div class="hero-avatar">+</div>
                </div>
                <span>50.000+ orang tua sudah bergabung</span>
            </div>
        </div>

        <!-- Visual -->
        <div class="hero-visual">
            <div class="hero-card-main">
                <div class="hero-card-label">Perkembangan Anak</div>
                <div class="hero-card-name">Aqila Putri Santoso</div>
                <div class="hero-card-meta">4 tahun 2 bulan · Perempuan</div>

                <!-- Chart -->
                <div class="chart-wrap">
                    <div class="chart-bar"><div class="chart-bar-fill" style="height:45%"></div></div>
                    <div class="chart-bar"><div class="chart-bar-fill" style="height:52%"></div></div>
                    <div class="chart-bar"><div class="chart-bar-fill" style="height:58%"></div></div>
                    <div class="chart-bar"><div class="chart-bar-fill" style="height:55%"></div></div>
                    <div class="chart-bar"><div class="chart-bar-fill" style="height:62%"></div></div>
                    <div class="chart-bar"><div class="chart-bar-fill" style="height:70%"></div></div>
                    <div class="chart-bar active"><div class="chart-bar-fill" style="height:78%"></div></div>
                </div>
                <div class="chart-labels">
                    <span>Okt</span><span>Nov</span><span>Des</span>
                    <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span>
                </div>

                <div class="hero-stat-row">
                    <div class="hero-stat">
                        <div class="hero-stat-val">16.8 kg</div>
                        <div class="hero-stat-lbl">Berat Badan</div>
                        <div class="hero-stat-change">↑ Normal</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-val">103 cm</div>
                        <div class="hero-stat-lbl">Tinggi Badan</div>
                        <div class="hero-stat-change">↑ Ideal WHO</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-val">15.8</div>
                        <div class="hero-stat-lbl">IMT</div>
                        <div class="hero-stat-change">✓ Sehat</div>
                    </div>
                </div>
            </div>

            <!-- Floating cards -->
            <div class="float-card float-1">
                <div class="float-card-icon" style="background:#EFF6FF;">💊</div>
                <div>
                    <div class="float-card-title">Vaksin Besok</div>
                    <div class="float-card-sub">DPT Booster · 09.00 WIB</div>
                </div>
            </div>
            <div class="float-card float-2">
                <div class="float-card-icon" style="background:#F0FDF4;">👨‍⚕️</div>
                <div>
                    <div class="float-card-title">Dr. Rina Susanti</div>
                    <div class="float-card-sub">Online sekarang</div>
                </div>
            </div>
        </div>

    </div>
</section>
</section>

<!-- ─── LOGOS STRIP ─── -->
<div class="logos-strip">
    <div class="logos-strip-label">Dipercaya oleh komunitas terbaik Indonesia</div>
    <div class="logos-track-wrap">
        <div class="logos-track" id="logosTrack">
            <div class="logo-item"><span class="logo-item-icon">🏥</span> RSIA Bunda Jakarta</div>
            <div class="logo-item"><span class="logo-item-icon">🎗️</span> IDI Jakarta</div>
            <div class="logo-item"><span class="logo-item-icon">🌿</span> Kemenkes RI</div>
            <div class="logo-item"><span class="logo-item-icon">⚕️</span> IDAI Indonesia</div>
            <div class="logo-item"><span class="logo-item-icon">🏨</span> RS Hermina</div>
            <div class="logo-item"><span class="logo-item-icon">📋</span> WHO Indonesia</div>
            <div class="logo-item"><span class="logo-item-icon">💙</span> UNICEF</div>
            <!-- duplicate for infinite -->
            <div class="logo-item"><span class="logo-item-icon">🏥</span> RSIA Bunda Jakarta</div>
            <div class="logo-item"><span class="logo-item-icon">🎗️</span> IDI Jakarta</div>
            <div class="logo-item"><span class="logo-item-icon">🌿</span> Kemenkes RI</div>
            <div class="logo-item"><span class="logo-item-icon">⚕️</span> IDAI Indonesia</div>
            <div class="logo-item"><span class="logo-item-icon">🏨</span> RS Hermina</div>
            <div class="logo-item"><span class="logo-item-icon">📋</span> WHO Indonesia</div>
            <div class="logo-item"><span class="logo-item-icon">💙</span> UNICEF</div>
        </div>
    </div>
</div>

<!-- ─── FEATURES ─── -->
<section class="features-section" id="fitur">
    <div class="container">
        <div class="reveal">
            <div class="section-label">Fitur Unggulan</div>
            <h2 class="display section-title">Semua yang Anda <span class="display-italic">butuhkan</span></h2>
            <p class="section-sub">Dirancang khusus bersama dokter spesialis anak dan ahli gizi untuk memastikan buah hati tumbuh optimal.</p>
        </div>

        <div class="features-grid">
            <!-- Card 1 - Featured large -->
            <div class="feature-card featured feature-span-2 reveal reveal-delay-1">
                <div class="feature-content">
                    <div class="feature-card-top">
                        <div class="feature-icon-wrap">📊</div>
                        <div class="feature-tag">Real-time</div>
                    </div>
                    <h3>Monitoring Gizi & Pertumbuhan</h3>
                    <p>Catat berat badan dan tinggi anak secara berkala. Visualisasi interaktif berstandar WHO dengan deteksi dini kelainan pertumbuhan secara otomatis.</p>
                </div>
                <div class="feature-visual">
                    <div class="mini-chart">
                        <div class="mini-bar"><div class="mini-bar-fill" style="height:30%"></div></div>
                        <div class="mini-bar"><div class="mini-bar-fill" style="height:45%"></div></div>
                        <div class="mini-bar"><div class="mini-bar-fill" style="height:40%"></div></div>
                        <div class="mini-bar"><div class="mini-bar-fill" style="height:60%"></div></div>
                        <div class="mini-bar"><div class="mini-bar-fill" style="height:55%"></div></div>
                        <div class="mini-bar"><div class="mini-bar-fill" style="height:75%"></div></div>
                        <div class="mini-bar"><div class="mini-bar-fill" style="height:70%"></div></div>
                        <div class="mini-bar"><div class="mini-bar-fill" style="height:88%"></div></div>
                    </div>
                    <div style="display:flex; gap:12px; margin-top:16px;">
                        <div style="flex:1; background:rgba(255,255,255,0.12); border-radius:12px; padding:14px; border:1px solid rgba(255,255,255,0.15);">
                            <div style="font-size:22px; font-weight:700; color:white;">16.8 kg</div>
                            <div style="font-size:12px; color:rgba(255,255,255,0.6);">Berat Badan</div>
                        </div>
                        <div style="flex:1; background:rgba(255,255,255,0.12); border-radius:12px; padding:14px; border:1px solid rgba(255,255,255,0.15);">
                            <div style="font-size:22px; font-weight:700; color:white;">103 cm</div>
                            <div style="font-size:12px; color:rgba(255,255,255,0.6);">Tinggi Badan</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="feature-card reveal reveal-delay-2">
                <div class="feature-card-top">
                    <div class="feature-icon-wrap">👨‍⚕️</div>
                    <div class="feature-tag">24/7</div>
                </div>
                <h3>Konsultasi Dokter Spesialis</h3>
                <p>Terhubung langsung dengan dokter spesialis anak dan ahli gizi bersertifikat kapan saja dibutuhkan.</p>
            </div>

            <!-- Card 3 -->
            <div class="feature-card reveal reveal-delay-1">
                <div class="feature-card-top">
                    <div class="feature-icon-wrap">💊</div>
                </div>
                <h3>Reminder Vaksinasi & Kontrol</h3>
                <p>Pengingat otomatis untuk jadwal imunisasi, kontrol rutin, dan minum obat. Tidak ada lagi yang terlewat.</p>
            </div>

            <!-- Card 4 -->
            <div class="feature-card reveal reveal-delay-2">
                <div class="feature-card-top">
                    <div class="feature-icon-wrap">🍎</div>
                </div>
                <h3>Panduan Nutrisi Harian</h3>
                <p>Rekomendasi menu makanan bergizi yang dipersonalisasi berdasarkan usia, berat, dan kondisi kesehatan anak.</p>
            </div>

            <!-- Card 5 -->
            <div class="feature-card reveal reveal-delay-3">
                <div class="feature-card-top">
                    <div class="feature-icon-wrap">📈</div>
                </div>
                <h3>Grafik Kurva WHO</h3>
                <p>Analisis pertumbuhan berstandar internasional WHO. Lihat posisi anak Anda dibandingkan standar global.</p>
            </div>

            <!-- Card 6 -->
            <div class="feature-card reveal reveal-delay-4">
                <div class="feature-card-top">
                    <div class="feature-icon-wrap">🔒</div>
                </div>
                <h3>Data Aman & Terenkripsi</h3>
                <p>Enkripsi tingkat medis dan sesuai standar GDPR. Data kesehatan anak 100% privat dan aman.</p>
            </div>
        </div>
    </div>
</section>

<!-- ─── HOW IT WORKS ─── -->
<section class="how-section" id="cara-kerja">
    <div class="container">
        <div class="reveal" style="text-align:center; max-width:500px; margin:0 auto;">
            <div class="section-label" style="justify-content:center;">Cara Kerja</div>
            <h2 class="display section-title">Mulai dalam <span class="display-italic">4 langkah</span></h2>
        </div>

        <div class="steps-grid">
            <div class="step-item reveal reveal-delay-1">
                <div class="step-num">📝</div>
                <h4>Daftar Akun</h4>
                <p>Buat akun gratis dalam 2 menit. Tidak perlu kartu kredit.</p>
            </div>
            <div class="step-item reveal reveal-delay-2">
                <div class="step-num">👶</div>
                <h4>Tambah Profil Anak</h4>
                <p>Isi data dasar anak Anda — nama, tanggal lahir, dan jenis kelamin.</p>
            </div>
            <div class="step-item reveal reveal-delay-3">
                <div class="step-num">📊</div>
                <h4>Catat Perkembangan</h4>
                <p>Input berat badan dan tinggi secara berkala. Grafik otomatis diperbarui.</p>
            </div>
            <div class="step-item reveal reveal-delay-4">
                <div class="step-num">💬</div>
                <h4>Konsultasi Ahli</h4>
                <p>Dapatkan saran langsung dari dokter spesialis anak profesional.</p>
            </div>
        </div>
    </div>
</section>

<!-- ─── STATS ─── -->
<div class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item reveal">
                <div class="stat-num">50K<span>+</span></div>
                <div class="stat-desc">Keluarga Indonesia</div>
            </div>
            <div class="stat-item reveal reveal-delay-1">
                <div class="stat-num">200<span>+</span></div>
                <div class="stat-desc">Dokter Terverifikasi</div>
            </div>
            <div class="stat-item reveal reveal-delay-2">
                <div class="stat-num">99<span>%</span></div>
                <div class="stat-desc">Tingkat Kepuasan</div>
            </div>
            <div class="stat-item reveal reveal-delay-3">
                <div class="stat-num">24<span>/7</span></div>
                <div class="stat-desc">Layanan Aktif</div>
            </div>
        </div>
    </div>
</div>

<!-- ─── TESTIMONIALS ─── -->
<section class="testi-section" id="testimoni">
    <div class="container">
        <div class="reveal" style="max-width:500px;">
            <div class="section-label">Testimoni</div>
            <h2 class="display section-title">Cerita nyata dari <span class="display-italic">orang tua</span></h2>
        </div>

        <div class="testi-grid">
            <div class="testi-card reveal reveal-delay-1">
                <div class="testi-stars">★★★★★</div>
                <div class="testi-quote">"</div>
                <p class="testi-text">GiziAnak benar-benar mengubah cara saya memantau kesehatan Aqila. Sekarang grafik pertumbuhannya selalu terpantau dan konsultasi ke dokter jadi jauh lebih mudah dan terarah.</p>
                <div class="testi-author">
                    <div class="testi-avatar" style="background: linear-gradient(135deg, #3B82F6, #1D4ED8);">SN</div>
                    <div>
                        <div class="testi-name">Siti Nurhaliza</div>
                        <div class="testi-meta">Ibu dari Aqila, 3 tahun · Jakarta</div>
                    </div>
                </div>
            </div>

            <div class="testi-card reveal reveal-delay-2">
                <div class="testi-stars">★★★★★</div>
                <div class="testi-quote">"</div>
                <p class="testi-text">Fitur reminder vaksinasi yang paling saya suka! Tidak pernah lagi lupa jadwal imunisasi Rafa. Aplikasinya canggih tapi mudah banget dipakai, bahkan oleh saya yang gagap teknologi.</p>
                <div class="testi-author">
                    <div class="testi-avatar" style="background: linear-gradient(135deg, #10B981, #059669);">BS</div>
                    <div>
                        <div class="testi-name">Budi Santoso</div>
                        <div class="testi-meta">Ayah dari Rafa, 5 tahun · Surabaya</div>
                    </div>
                </div>
            </div>

            <div class="testi-card reveal reveal-delay-3">
                <div class="testi-stars" style="color:#F59E0B;">★★★★★</div>
                <div class="testi-quote">"</div>
                <p class="testi-text">Sebagai ibu baru yang banyak pertanyaan, GiziAnak jadi teman terpercaya saya. Dokternya responsif dan selalu memberikan penjelasan yang mudah dipahami.</p>
                <div class="testi-author">
                    <div class="testi-avatar" style="background: linear-gradient(135deg, #F59E0B, #D97706);">DK</div>
                    <div>
                        <div class="testi-name">Dina Kusuma</div>
                        <div class="testi-meta">Ibu dari Zahra, 2 tahun · Bandung</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ─── CTA ─── -->


<!-- ─── FOOTER ─── -->
<footer>
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="footer-brand-logo">
                    <div class="footer-brand-logo-icon">🌱</div>
                    <span>GiziAnak</span>
                </div>
                <p>Platform terdepan untuk memantau kesehatan dan gizi anak di Indonesia. Bersama dokter spesialis anak terbaik.</p>
            </div>

            

            <div class="footer-col">
                <h5>Perusahaan</h5>
                <ul>
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="#">Blog Kesehatan</a></li>
                    <li><a href="#">Karir</a></li>
                    <li><a href="#">Press Kit</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h5>Dukungan</h5>
                <ul>
                    <li><a href="#">Pusat Bantuan</a></li>
                    <li><a href="#">Kebijakan Privasi</a></li>
                    <li><a href="#">Syarat & Ketentuan</a></li>
                    <li><a href="#">Hubungi Kami</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© {{ date('Y') }} GiziAnak · Semua hak dilindungi undang-undang 🇮🇩</p>
            <div class="footer-socials">
                <a href="#" class="social-btn">𝕏</a>
                <a href="#" class="social-btn">in</a>
                <a href="#" class="social-btn">📷</a>
                <a href="#" class="social-btn">▶</a>
            </div>
        </div>
    </div>
</footer>

<script>
    // Mobile nav
    const hamburger = document.getElementById('hamburger');
    const mobileNav = document.getElementById('mobileNav');
    let open = false;

    hamburger.addEventListener('click', () => {
        open = !open;
        if (open) {
            mobileNav.style.display = 'flex';
            requestAnimationFrame(() => mobileNav.classList.add('open'));
        } else {
            mobileNav.classList.remove('open');
            setTimeout(() => mobileNav.style.display = 'none', 300);
        }
    });

    mobileNav.querySelectorAll('a').forEach(a => {
        a.addEventListener('click', () => {
            open = false;
            mobileNav.classList.remove('open');
            setTimeout(() => mobileNav.style.display = 'none', 300);
        });
    });

    // Navbar scroll style
    const navbar = document.getElementById('navbar');
    const heroSectionEl = document.querySelector('.hero-section');
    function updateNav() {
        const heroBottom = heroSectionEl ? heroSectionEl.getBoundingClientRect().bottom : 0;
        if (heroBottom <= 72) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    }
    window.addEventListener('scroll', updateNav);
    updateNav();

    // Reveal on scroll
    const reveals = document.querySelectorAll('.reveal');
    const io = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); } });
    }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });
    reveals.forEach(el => io.observe(el));

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const target = document.querySelector(a.getAttribute('href'));
            if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
        });
    });
</script>
</body>
</html>