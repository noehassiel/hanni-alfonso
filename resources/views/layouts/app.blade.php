<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Hanni & Alfonso — 24 Octubre 2025' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pinyon+Script&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600&family=Outfit:wght@300;400;500;600&family=Cormorant:ital,wght@0,300;0,400;0,500;1,300;1,400;1,500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --ivory: #f5f0e8;
            --cream: #ece5d8;
            --parchment: #e3dace;
            --olive: #4a5043;
            --olive-light: #6b7262;
            --sage: #8a9a7b;
            --bronze: #957b5a;
            --bronze-light: #b8a082;
            --bronze-glow: #c9a96e;
            --blush: #d4bfb0;
            --charcoal: #2c2a26;
            --charcoal-soft: #3d3a34;
        }

        .font-display { font-family: 'Playfair Display', Georgia, serif; }
        .font-script { font-family: 'Pinyon Script', cursive; }
        .font-body { font-family: 'Outfit', system-ui, sans-serif; }
        .font-accent { font-family: 'Cormorant', Georgia, serif; }

        body {
            background-color: var(--ivory);
            font-family: 'Outfit', system-ui, sans-serif;
            color: var(--charcoal);
        }

        /* Noise texture overlay */
        .noise::before {
            content: '';
            position: fixed;
            inset: 0;
            opacity: 0.025;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            background-size: 256px;
            pointer-events: none;
            z-index: 9999;
        }

        /* Staggered entrance animations */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes scaleFade {
            from { opacity: 0; transform: scale(0.92); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes drawLine {
            from { transform: scaleX(0); }
            to { transform: scaleX(1); }
        }
        @keyframes floatGentle {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-8px) rotate(1deg); }
        }
        @keyframes spinSlow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .anim-fade-up {
            animation: fadeUp 0.9s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        .anim-fade-in {
            animation: fadeIn 1s ease both;
        }
        .anim-scale {
            animation: scaleFade 0.8s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        .anim-line {
            animation: drawLine 1.2s cubic-bezier(0.22, 1, 0.36, 1) both;
            transform-origin: center;
        }

        .delay-1 { animation-delay: 0.15s; }
        .delay-2 { animation-delay: 0.3s; }
        .delay-3 { animation-delay: 0.5s; }
        .delay-4 { animation-delay: 0.7s; }
        .delay-5 { animation-delay: 0.9s; }
        .delay-6 { animation-delay: 1.1s; }
        .delay-7 { animation-delay: 1.4s; }

        /* Botanical decorative elements */
        .botanical-corner {
            position: absolute;
            width: 120px;
            height: 120px;
            opacity: 0.12;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--ivory); }
        ::-webkit-scrollbar-thumb { background: var(--bronze-light); border-radius: 3px; }

        /* Form elements styling */
        .wedding-input {
            background: transparent;
            border: none;
            border-bottom: 1px solid var(--parchment);
            padding: 0.75rem 0;
            font-family: 'Outfit', system-ui, sans-serif;
            font-size: 0.938rem;
            color: var(--charcoal);
            transition: border-color 0.3s ease;
            width: 100%;
            outline: none;
        }
        .wedding-input:focus {
            border-bottom-color: var(--bronze);
        }
        .wedding-input::placeholder {
            color: var(--bronze-light);
            font-weight: 300;
        }
    </style>
</head>
<body class="noise antialiased min-h-screen">
    {{ $slot ?? '' }}
    @yield('content')
</body>
</html>
