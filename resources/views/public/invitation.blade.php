@extends('layouts.app')

@section('content')
    @php
        $googleCalUrl =
            'https://calendar.google.com/calendar/render?' .
            http_build_query([
                'action' => 'TEMPLATE',
                'text' => 'Boda Hannia & Alfonso',
                'dates' => '20261024T163000/20261024T220000',
                'ctz' => 'America/Mexico_City',
                'location' => 'Carr. Querétaro-Tequisquiapan 707, Purísima de Cubos, 76295 Querétaro, Qro.',
                'details' => "¡Los esperamos en nuestra boda!\nMapa: https://maps.app.goo.gl/x1WDacDi6BWHyx9H6",
            ]);

        $hotels = [
            ['url' => 'https://maps.app.goo.gl/1Prb9YiZVrLGeZF99?g_st=iw'],
            ['url' => 'https://maps.app.goo.gl/ixoYv71KLv3zNzzv8?g_st=iw'],
            ['url' => 'https://maps.app.goo.gl/K7BHCf63TBWMstYF7?g_st=iw'],
            ['url' => 'https://maps.app.goo.gl/C8tVcctSse5He9Kr5?g_st=iw'],
            ['url' => 'https://maps.app.goo.gl/RDsVU5zvgMbsTHdT8?g_st=iw'],
            ['url' => 'https://maps.app.goo.gl/eKTrrgVt4fTWAqWWA?g_st=iw'],
            ['url' => 'https://maps.app.goo.gl/E1HNQBTQzEzboioj8?g_st=iw'],
            ['url' => 'https://maps.app.goo.gl/QnYJWEoeQxYvAu4i6?g_st=iw'],
        ];

        $eventDate = \Carbon\Carbon::create(2026, 10, 24);
        $eventDay = $eventDate->day;
        $monthStart = $eventDate->copy()->startOfMonth();
        $leadingBlanks = $monthStart->dayOfWeek; // 0 = Sunday
        $daysInMonth = $eventDate->daysInMonth;

        $couplePhotos = [
            'couple-photo-bw-groom-lifting-bride-spinning.jpg',
            'couple-photo-bw-embracing-hacienda-corridor.jpg',
            'couple-photo-bw-sitting-hacienda-steps.jpg',
            'couple-photo-color-descending-hacienda-steps.jpg',
            'couple-photo-color-iron-staircase-stone-wall.jpg',
            'couple-photo-color-walking-toward-chapel.jpg',
            'couple-photo-silhouette-kissing-arched-window.jpg',
        ];
    @endphp

    <style>
        :root {
            --bg-dark: #16120e;
            --autumn-burgundy: #7B1B1B;
            --autumn-amber: #D4881A;
            --autumn-forest: #1C5C3A;
            --autumn-sienna: #C44E0A;
            --autumn-caramel: #9B6A18;
            --hero-overlay: rgba(16, 10, 6, 0.55);
        }

        /* Lenis — prevent flash before init */
        html.lenis {
            height: auto;
        }

        .lenis.lenis-smooth {
            scroll-behavior: auto !important;
        }

        .lenis.lenis-smooth [data-lenis-prevent] {
            overscroll-behavior: contain;
        }

        /* ─── Section base ────────────────── */
        .section-label {
            font-family: 'Outfit', sans-serif;
            font-size: 0.75rem;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--olive);
        }

        .section-rule {
            display: block;
            width: 40px;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--bronze-light), transparent);
            margin: 0 auto;
        }

        /* ─── Hero ───────────────────────── */
        #hero {
            height: 100dvh;
        }

        #leaves-canvas {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 2;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center top;
            z-index: 0;
        }

        .hero-gradient {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom,
                    rgba(16, 10, 6, 0.15) 0%,
                    rgba(16, 10, 6, 0.10) 35%,
                    rgba(16, 10, 6, 0.55) 65%,
                    rgba(16, 10, 6, 0.92) 100%);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 3;
        }

        .hero-names {
            font-family: 'Pinyon Script', cursive;
            font-size: clamp(4rem, 18vw, 9rem);
            color: #fff;
            line-height: 1;
        }

        .hero-amp {
            font-family: 'Cormorant', serif;
            font-style: italic;
            color: var(--autumn-amber);
        }

        .scroll-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            animation: scrollBounce 1.6s ease-in-out infinite;
        }

        @keyframes scrollBounce {

            0%,
            100% {
                transform: translateY(0);
                opacity: 0.5;
            }

            50% {
                transform: translateY(8px);
                opacity: 1;
            }
        }

        /* ─── Video cinemático ───────────── */
        .video-cinematic {
            position: relative;
            height: 230vh;
            background: var(--bg-dark);
        }

        .video-sticky {
            position: sticky;
            top: 0;
            height: 100vh;
            width: 100%;
            overflow: hidden;
            background: var(--bg-dark);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .video-clip {
            position: absolute;
            inset: 0;
            /* Starts as a framed window; expanded to full-bleed via JS scroll-scrub */
            clip-path: inset(15% 9% 15% 9% round 18px);
            will-change: clip-path;
        }

        .cinema-video {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(1.1);
            /* Cover the viewport by height: video height = webview height, sides cropped.
               min-* technique works where object-fit on <video> is unreliable (iOS/in-app browsers).
               scale(1.1) adds a slight zoom so edges are always covered. */
            width: auto;
            height: auto;
            min-width: 100%;
            min-height: 100%;
            object-fit: cover;
            background: var(--bg-dark);
        }

        .video-vignette {
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: radial-gradient(ellipse at center, transparent 52%, rgba(16, 10, 6, 0.55) 100%);
        }

        .video-caption {
            position: relative;
            z-index: 3;
            text-align: center;
            pointer-events: none;
            will-change: opacity, transform;
        }

        .video-caption-names {
            font-family: 'Pinyon Script', cursive;
            font-size: clamp(2.5rem, 11vw, 4.5rem);
            color: #fff;
            line-height: 1;
            margin-top: 0.25rem;
            text-shadow: 0 2px 24px rgba(0, 0, 0, 0.45);
        }

        /* ─── Fecha ──────────────────────── */
        .date-day {
            font-family: 'Playfair Display', serif;
            font-size: clamp(5rem, 22vw, 10rem);
            font-weight: 400;
            line-height: 1;
            color: var(--charcoal);
            letter-spacing: -0.03em;
        }

        .date-month {
            font-family: 'Outfit', sans-serif;
            font-size: 0.9rem;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--autumn-sienna);
        }

        .date-year {
            font-family: 'Playfair Display', serif;
            font-size: 1.625rem;
            color: var(--olive);
            font-weight: 400;
        }

        .cal-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.875rem 1.5rem;
            font-family: 'Outfit', sans-serif;
            font-size: 0.75rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            border: 1px solid var(--bronze-light);
            border-radius: 2px;
            color: var(--charcoal-soft);
            background: transparent;
            transition: border-color 0.25s, color 0.25s, background 0.25s;
            text-decoration: none;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
            min-height: 48px;
        }

        .cal-btn:active {
            border-color: var(--autumn-sienna);
            color: var(--autumn-sienna);
            background: rgba(196, 78, 10, 0.04);
        }

        /* ─── Date headline ──────────────── */
        .date-headline {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.5rem, 7vw, 2.5rem);
            font-weight: 400;
            line-height: 1.1;
            color: var(--charcoal);
            letter-spacing: -0.01em;
        }

        .date-headline .date-headline-day {
            color: var(--autumn-sienna);
        }

        /* ─── Countdown ──────────────────── */
        .countdown {
            display: flex;
            justify-content: center;
            gap: 0.4rem;
            max-width: 26rem;
            margin: 0 auto;
        }

        .cd-unit {
            flex: 1 1 0;
            min-width: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.85rem 0.25rem 0.65rem;
            background: rgba(245, 240, 232, 0.6);
            border: 1px solid var(--parchment);
            border-radius: 2px;
        }

        .cd-num {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.5rem, 7vw, 2.5rem);
            font-weight: 400;
            line-height: 1;
            color: var(--charcoal);
            font-variant-numeric: tabular-nums;
        }

        .cd-label {
            font-family: 'Outfit', sans-serif;
            font-size: 0.55rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--olive);
            margin-top: 0.55rem;
        }

        /* ─── Calendar ───────────────────── */
        .cal-card {
            max-width: 22rem;
            margin: 0 auto;
            padding: 1.5rem 1.25rem;
            background: rgba(245, 240, 232, 0.6);
            border: 1px solid var(--parchment);
            border-radius: 2px;
        }

        .cal-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            color: var(--charcoal);
            text-align: center;
            margin-bottom: 1.1rem;
        }

        .cal-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.2rem;
            text-align: center;
        }

        .cal-head span {
            font-family: 'Outfit', sans-serif;
            font-size: 0.6rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--bronze);
            padding-bottom: 0.6rem;
        }

        .cal-day {
            font-family: 'Outfit', sans-serif;
            font-size: 0.85rem;
            color: var(--charcoal);
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cal-day.is-event {
            position: relative;
        }

        .cal-day.is-event .cal-heart {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            color: var(--autumn-sienna);
            filter: drop-shadow(0 2px 4px rgba(196, 78, 10, 0.25));
        }

        .cal-day.is-event .cal-day-num {
            position: relative;
            z-index: 1;
            color: #fff;
            font-weight: 600;
        }

        /* ─── RSVP section ───────────────── */
        .rsvp-bg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.08;
            pointer-events: none;
        }

        /* ─── Venue section ──────────────── */
        .venue-illus {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center bottom;
            z-index: 0;
        }

        .venue-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom,
                    rgba(245, 240, 232, 0.98) 0%,
                    rgba(245, 240, 232, 0.88) 50%,
                    rgba(245, 240, 232, 0.96) 100%);
            z-index: 1;
        }

        /* ─── Photo Polaroids ────────────── */
        .photo-scatter {
            position: relative;
            height: 480px;
            max-width: 380px;
            margin: 0 auto;
            overflow: visible;
        }

        .polaroid {
            position: absolute;
            width: 200px;
            background: #fff;
            padding: 8px 8px 28px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.18), 0 1px 4px rgba(0, 0, 0, 0.1);
            cursor: grab;
            user-select: none;
            touch-action: none;
            will-change: transform;
            transition: box-shadow 0.2s;
            -webkit-tap-highlight-color: transparent;
        }

        .polaroid:active {
            cursor: grabbing;
        }

        .polaroid.lifted {
            box-shadow: 0 18px 48px rgba(0, 0, 0, 0.28), 0 4px 12px rgba(0, 0, 0, 0.14);
        }

        .polaroid img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: block;
            pointer-events: none;
        }

        #envelope-wide {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: min(400px, 108%);
            opacity: 0.45;
            z-index: 0;
            pointer-events: none;
            display: block;
        }

        .drag-hint {
            font-family: 'Outfit', sans-serif;
            font-size: 0.6875rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--olive);
            text-align: center;
            margin-top: 1.5rem;
        }

        /* ─── Dress code ─────────────────── */
        .swatch {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            flex-shrink: 0;
        }

        .swatch-label {
            font-family: 'Outfit', sans-serif;
            font-size: 0.6875rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--olive);
            text-align: center;
            margin-top: 0.5rem;
        }

        .dress-warning {
            border-left: 3px solid var(--autumn-sienna);
            padding-left: 1rem;
        }

        /* ─── Hotels ─────────────────────── */
        .hotel-card {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            padding: 1.25rem 1rem;
            border: 1px solid var(--parchment);
            border-radius: 2px;
            text-decoration: none;
            background: rgba(245, 240, 232, 0.5);
            transition: border-color 0.25s, background 0.25s;
            -webkit-tap-highlight-color: transparent;
        }

        .hotel-card:active {
            border-color: var(--autumn-sienna);
            background: rgba(196, 78, 10, 0.04);
        }

        .hotel-num {
            font-family: 'Playfair Display', serif;
            font-size: 1.75rem;
            font-weight: 400;
            color: var(--autumn-sienna);
            line-height: 1;
        }

        /* ─── Section divider illustration ── */
        .illus-divider {
            width: 100%;
            height: 180px;
            object-fit: cover;
            object-position: center;
            display: block;
            opacity: 0.65;
        }

        /* ─── Corner ornaments ───────────── */
        .corner-ornament::before,
        .corner-ornament::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
        }

        /* ─── Global visibility toggle ───── */
        .js-hidden {
            opacity: 0;
        }
    </style>

    {{-- ── Envelope Overlay ─────────────────────────────────────────── --}}
    <audio id="env-audio" src="{{ asset('audi.mp3') }}" preload="auto"></audio>

    {{-- Fixed mute button — appears once audio starts --}}
    <button id="mute-btn" aria-label="Silenciar música"
        style="position:fixed;bottom:calc(1.25rem + env(safe-area-inset-bottom,0px));right:1.25rem;
               z-index:100;width:40px;height:40px;border-radius:50%;border:1px solid rgba(255,255,255,0.18);
               background:rgba(44,42,38,0.72);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);
               cursor:pointer;display:flex;align-items:center;justify-content:center;
               opacity:0;pointer-events:none;transition:border-color 0.2s,background 0.2s;
               -webkit-tap-highlight-color:transparent;">
        {{-- Sound on --}}
        <svg id="icon-sound" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.85)"
            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" />
            <path d="M19.07 4.93a10 10 0 0 1 0 14.14" />
            <path d="M15.54 8.46a5 5 0 0 1 0 7.07" />
        </svg>
        {{-- Muted --}}
        <svg id="icon-muted" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.85)"
            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
            <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" />
            <line x1="23" y1="9" x2="17" y2="15" />
            <line x1="17" y1="9" x2="23" y2="15" />
        </svg>
    </button>

    <div id="envelope-overlay" style="position:fixed;inset:0;z-index:50;background:rgba(245,240,232,0.97);">
        <div style="position:absolute;inset:-12%;">
            <img id="env-back" src="{{ asset('img/envelop-removebg-preview.png') }}"
                style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;z-index:0;"
                alt="" draggable="false">
            <img id="env-flap" src="{{ asset('img/flap-removebg-preview.png') }}"
                style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;transform-origin:center top;z-index:10;"
                alt="" draggable="false">
            <h3>
                Esta invitación es exclusiva para ti
            </h3>
            <div id="seal-wrapper"
                style="position:absolute;left:50%;top:51.5%;transform:translate(-50%,-50%);width:36%;aspect-ratio:1;z-index:20;">
                <img id="env-seal" src="{{ asset('img/wax-seal-removebg-preview.png') }}"
                    style="width:100%;height:100%;object-fit:contain;cursor:pointer;display:block;user-select:none;-webkit-user-select:none;touch-action:manipulation;"
                    alt="Sello de cera" draggable="false">
            </div>
        </div>
    </div>

    {{-- ── Main Content ──────────────────────────────────────────────── --}}
    <main id="main-content" style="opacity:0;">

        {{-- ── 1. HERO ───────────────────────────────────────────────── --}}
        <section id="hero" class="relative flex items-end overflow-hidden">

            <canvas id="leaves-canvas"></canvas>

            <img src="{{ asset('img/couple-photo-bw-groom-lifting-bride-spinning.jpg') }}" class="hero-bg"
                alt="Hannia y Alfonso" loading="eager">

            <div class="hero-gradient"></div>

            <div class="hero-content w-full text-center px-6 pb-12 sm:pb-16">

                <p class="section-label text-white mb-4 js-hidden" id="hero-label" style="color: white !important;">Celebra
                    con nosotros</p>

                <h1 class="hero-names mb-4 js-hidden" id="hero-names">
                    Hannia <span class="hero-amp">&</span> Alfonso
                </h1>

                <div class="js-hidden" id="hero-date">
                    <p class="font-display text-xl sm:text-2xl text-white/75 tracking-[0.25em] font-light">
                        24 · OCTUBRE · 2026
                    </p>
                    <p class="font-accent italic text-base sm:text-lg mt-1" style="color: rgba(212,136,26,0.8);">
                        4:30 de la tarde
                    </p>
                </div>
            </div>
        </section>

        {{-- ── 2. VIDEO CINEMÁTICO ───────────────────────────────────── --}}
        <section id="video-cinematic" class="video-cinematic">
            <div class="video-sticky">
                <div class="video-clip" id="video-clip">
                    <video id="cinema-video" class="cinema-video" playsinline muted loop preload="none"
                        disablepictureinpicture
                        poster="{{ asset('img/venue-illustration-orchard-path-sunset.jpeg') }}">
                        <source src="{{ asset('video.mp4') }}" type="video/mp4">
                        <source src="{{ asset('video.webm') }}" type="video/webm">
                    </video>
                    <div class="video-vignette"></div>
                </div>

                <div class="video-caption" id="video-caption">
                    <p class="section-label" style="color: rgba(255,255,255,0.85);">Save the date</p>
                    <p class="video-caption-names">Hannia <span class="hero-amp">&</span> Alfonso</p>
                </div>
            </div>
        </section>

        {{-- ── 3. FECHA & CALENDARIO ─────────────────────────────────── --}}
        <section id="fecha" class="py-20 sm:py-28 px-6 text-center relative overflow-hidden"
            style="background: var(--ivory);">

            {{-- Background illustration --}}
            <img src="{{ asset('img/venue-illustration-orchard-path-sunset.jpeg') }}"
                class="absolute inset-0 w-full h-full object-cover opacity-[0.05] pointer-events-none"
                style="object-position: center top;" alt="">

            <div class="relative">
                <p class="section-label mb-6 js-hidden" data-anim>Fecha del evento</p>

                <p class="date-headline mb-2 js-hidden" data-anim>
                    Sábado <span class="date-headline-day">24</span> de Octubre, 2026
                </p>

                <p class="font-accent italic text-2xl sm:text-3xl mt-2 mb-10 js-hidden" data-anim
                    style="color: var(--olive);">4:30 de la tarde</p>

                {{-- Countdown --}}
                <div class="countdown js-hidden" style="margin-bottom: 20px" data-anim id="countdown"
                    data-target="2026-10-24T16:30:00-06:00">
                    <div class="cd-unit">
                        <span class="cd-num" data-cd="months">--</span>
                        <span class="cd-label">Meses</span>
                    </div>
                    <div class="cd-unit">
                        <span class="cd-num" data-cd="days">--</span>
                        <span class="cd-label">Días</span>
                    </div>
                    <div class="cd-unit">
                        <span class="cd-num" data-cd="hours">--</span>
                        <span class="cd-label">Horas</span>
                    </div>
                    <div class="cd-unit">
                        <span class="cd-num" data-cd="minutes">--</span>
                        <span class="cd-label">Min</span>
                    </div>
                    <div class="cd-unit">
                        <span class="cd-num" data-cd="seconds">--</span>
                        <span class="cd-label">Seg</span>
                    </div>
                </div>

                {{-- Calendar --}}
                <div class="cal-card js-hidden" data-anim style="margin-bottom: 20px">
                    <p class="cal-title">Octubre 2026</p>
                    <div class="cal-grid cal-head">
                        <span>D</span><span>L</span><span>M</span><span>M</span><span>J</span><span>V</span><span>S</span>
                    </div>
                    <div class="cal-grid">
                        @for ($i = 0; $i < $leadingBlanks; $i++)
                            <span class="cal-day"></span>
                        @endfor
                        @for ($day = 1; $day <= $daysInMonth; $day++)
                            @if ($day === $eventDay)
                                <span class="cal-day is-event">
                                    <svg class="cal-heart" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path
                                            d="M12 21s-7.5-4.9-10-9.5C.6 8.9 1.7 5.7 4.6 5c1.9-.5 3.8.3 5 1.9 1.2-1.6 3.1-2.4 5-1.9 2.9.7 4 3.9 2.6 6.5C19.5 16.1 12 21 12 21z" />
                                    </svg>
                                    <span class="cal-day-num">{{ $day }}</span>
                                </span>
                            @else
                                <span class="cal-day">{{ $day }}</span>
                            @endif
                        @endfor
                    </div>
                </div>

                <span class="section-rule mb-10 block js-hidden" data-anim></span>

                <p class="section-label mb-6 js-hidden" data-anim>Agrega a tu calendario</p>

                <div class="flex flex-col sm:flex-row gap-3 justify-center js-hidden" data-anim>
                    <a href="{{ $googleCalUrl }}" target="_blank" rel="noopener" class="cal-btn"
                        onclick="window.haptics?.trigger('light')">
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                fill="#4285F4" />
                            <path
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                fill="#34A853" />
                            <path
                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"
                                fill="#FBBC05" />
                            <path
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                fill="#EA4335" />
                        </svg>
                        Google Calendar
                    </a>
                    <a href="{{ route('invitation.calendar', $invitation->magic_link_token) }}" class="cal-btn"
                        onclick="window.haptics?.trigger('light')">
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <path d="M16 2v4M8 2v4M3 10h18M8 14h.01M12 14h.01M8 18h.01" />
                        </svg>
                        Apple / iCal / Outlook
                    </a>
                </div>
            </div>
        </section>

        {{-- ── 3. MENSAJE PERSONAL ───────────────────────────────────── --}}
        <section class="py-10 px-6" style="background: var(--cream);">
            <div class="max-w-lg mx-auto">
                <div class="relative text-center py-10 px-8 js-hidden" data-anim
                    style="background: linear-gradient(135deg,rgba(245,240,232,0.7),rgba(236,229,216,0.5));
                        border: 1px solid var(--parchment); border-radius: 2px;">
                    {{-- Corner ornaments --}}
                    <div class="absolute top-3 left-3 w-5 h-5"
                        style="border-top:1px solid var(--bronze-light);border-left:1px solid var(--bronze-light);"></div>
                    <div class="absolute top-3 right-3 w-5 h-5"
                        style="border-top:1px solid var(--bronze-light);border-right:1px solid var(--bronze-light);"></div>
                    <div class="absolute bottom-3 left-3 w-5 h-5"
                        style="border-bottom:1px solid var(--bronze-light);border-left:1px solid var(--bronze-light);">
                    </div>
                    <div class="absolute bottom-3 right-3 w-5 h-5"
                        style="border-bottom:1px solid var(--bronze-light);border-right:1px solid var(--bronze-light);">
                    </div>

                    <p class="font-accent text-lg italic" style="color: var(--olive);">Estimado/a</p>
                    <p class="font-display text-3xl sm:text-4xl mt-2" style="color:var(--charcoal);font-weight:500;">
                        {{ $invitation->group_name }}
                    </p>

                    @if ($invitation->personal_message)
                        <div class="mx-auto mt-6"
                            style="width:40px;height:1px;background:linear-gradient(90deg,transparent,var(--bronze-light),transparent);">
                        </div>
                        <p class="font-accent text-base italic mt-6 leading-relaxed" style="color:var(--olive);">
                            {{ $invitation->personal_message }}</p>
                    @endif
                </div>
            </div>
        </section>

        {{-- ── 4. RSVP ───────────────────────────────────────────────── --}}
        <section id="rsvp" class="relative py-16 sm:py-24 px-0 overflow-hidden" style="background:var(--ivory);">
            <img src="{{ asset('img/venue-illustration-night-string-lights-reception.jpeg') }}" class="rsvp-bg"
                alt="">

            <div class="relative" style="z-index:1;">
                <div class="text-center mb-8 px-6 js-hidden" data-anim>
                    <p class="section-label mb-3">Confirmación de asistencia</p>
                    <span class="section-rule"></span>
                    <small>
                        Con mucho cariño hemos reservado este espacio, pensado exclusivamente para adultos, por lo que
                        agradecemos su comprensión al no asistir con niños.
                    </small>
                </div>

                <livewire:public.rsvp-form :invitation="$invitation" />
            </div>
        </section>

        {{-- ── 5. UBICACIÓN ──────────────────────────────────────────── --}}
        <section id="ubicacion" class="relative py-20 sm:py-28 overflow-hidden" style="min-height:480px;">

            <img src="{{ asset('img/venue-illustration-stone-chapel-bell-tower.jpeg') }}" class="venue-illus"
                alt="Capilla">
            <div class="venue-overlay"></div>

            <div class="relative px-6 text-center max-w-lg mx-auto" style="z-index:2;">
                <p class="section-label mb-6 js-hidden" data-anim>Lugar del evento</p>

                <div class="js-hidden" data-anim>
                    <img src="{{ asset('img/venue-illustration-stone-hacienda-tower.jpeg') }}"
                        class="w-28 h-28 object-cover rounded-full mx-auto mb-6 opacity-80"
                        style="box-shadow: 0 6px 24px rgba(0,0,0,0.15);" alt="Hacienda">
                </div>

                <h2 class="font-display text-2xl sm:text-3xl mb-2 js-hidden" data-anim
                    style="color:var(--charcoal);font-weight:400;">
                    Viñedo Tierra De Alonso
                </h2>

                <p class="font-accent italic text-lg mb-1 js-hidden" data-anim style="color:var(--olive);">
                    Querétaro, México
                </p>

                <div class="mx-auto my-6 js-hidden" data-anim
                    style="width:40px;height:1px;background:linear-gradient(90deg,transparent,var(--bronze-light),transparent);">
                </div>

                <p class="font-body text-sm mb-1 js-hidden" data-anim style="color:var(--olive);">
                    Carr. Querétaro-Tequisquiapan 707
                </p>
                <p class="font-body text-sm mb-8 js-hidden" data-anim style="color:var(--olive);">
                    Purísima de Cubos, 76295 Qro.
                </p>

                <a href="https://maps.app.goo.gl/x1WDacDi6BWHyx9H6" target="_blank" rel="noopener"
                    class="inline-flex items-center gap-2 px-8 py-4 font-body text-xs tracking-[0.25em] uppercase
                      transition-all duration-300 js-hidden"
                    data-anim
                    style="background:var(--charcoal);color:var(--cream);border-radius:2px;text-decoration:none;min-height:48px;"
                    onclick="window.haptics?.trigger('light')">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7Z" />
                        <circle cx="12" cy="9" r="2.5" />
                    </svg>
                    Cómo llegar
                </a>
            </div>
        </section>

        {{-- Illustration divider --}}
        <img src="{{ asset('img/venue-illustration-night-string-lights-reception.jpeg') }}" class="illus-divider"
            alt="" aria-hidden="true">

        {{-- ── 6. MOMENTOS — FOTOS ARRASTRABLES ──────────────────────── --}}
        <section id="fotos" class="py-16 sm:py-24 px-6 overflow-hidden" style="background:var(--cream);">
            <div class="text-center mb-12">
                <p class="section-label mb-3 js-hidden" data-anim>Momentos juntos</p>
                <span class="section-rule block mb-4 js-hidden" data-anim></span>
                <h2 class="font-display text-2xl sm:text-3xl js-hidden" data-anim
                    style="color:var(--charcoal);font-weight:400;">Nuestros recuerdos</h2>
            </div>

            {{-- Photo pile --}}
            <div id="photo-zone" class="relative">
                <div id="photo-scatter" class="photo-scatter">

                    {{-- Wide envelope SVG — landscape, contains the photos --}}
                    <svg id="envelope-wide" viewBox="0 0 560 300" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                        focusable="false">
                        <defs>
                            <linearGradient id="ew-bg" x1="0.5" y1="0" x2="0.5" y2="1">
                                <stop offset="0%" stop-color="#FEFCF8" />
                                <stop offset="100%" stop-color="#EDE7DC" />
                            </linearGradient>
                            <linearGradient id="ew-left" x1="0" y1="0.5" x2="1" y2="0.5">
                                <stop offset="0%" stop-color="#CEC7B9" stop-opacity="0.9" />
                                <stop offset="100%" stop-color="#EDE7DC" stop-opacity="0" />
                            </linearGradient>
                            <linearGradient id="ew-right" x1="1" y1="0.5" x2="0" y2="0.5">
                                <stop offset="0%" stop-color="#CEC7B9" stop-opacity="0.9" />
                                <stop offset="100%" stop-color="#EDE7DC" stop-opacity="0" />
                            </linearGradient>
                            <linearGradient id="ew-bottom" x1="0.5" y1="1" x2="0.5" y2="0">
                                <stop offset="0%" stop-color="#BEB6A6" stop-opacity="0.95" />
                                <stop offset="100%" stop-color="#EDE7DC" stop-opacity="0" />
                            </linearGradient>
                            <linearGradient id="ew-inner" x1="0.5" y1="0" x2="0.5" y2="1">
                                <stop offset="0%" stop-color="#FFFEF9" />
                                <stop offset="60%" stop-color="#F8F4EE" stop-opacity="0.4" />
                                <stop offset="100%" stop-color="#F5EFE6" stop-opacity="0" />
                            </linearGradient>
                            <linearGradient id="ew-flap" x1="0.5" y1="0" x2="0.5" y2="1">
                                <stop offset="0%" stop-color="#FEFCF8" />
                                <stop offset="100%" stop-color="#E8E2D6" />
                            </linearGradient>
                            <filter id="ew-paper" x="0%" y="0%" width="100%" height="100%"
                                color-interpolation-filters="sRGB">
                                <feTurbulence type="fractalNoise" baseFrequency="0.72" numOctaves="4" seed="11"
                                    result="noise" />
                                <feColorMatrix type="saturate" values="0" in="noise" result="gray" />
                                <feBlend in="SourceGraphic" in2="gray" mode="multiply" result="blended" />
                                <feComposite in="blended" in2="SourceGraphic" operator="in" />
                            </filter>
                            <clipPath id="ew-clip">
                                <rect width="560" height="300" rx="3" />
                            </clipPath>
                        </defs>

                        {{-- Drop shadow --}}
                        <rect x="3" y="5" width="554" height="292" rx="3" fill="rgba(0,0,0,0.08)" />

                        {{-- Body --}}
                        <rect width="560" height="300" rx="3" fill="url(#ew-bg)" filter="url(#ew-paper)"
                            clip-path="url(#ew-clip)" />

                        {{-- Back fold panels (left / bottom / right triangles) --}}
                        <g clip-path="url(#ew-clip)">
                            <polygon points="0,300 280,155 0,0" fill="url(#ew-left)" />
                            <polygon points="560,300 280,155 560,0" fill="url(#ew-right)" />
                            <polygon points="0,300 560,300 280,155" fill="url(#ew-bottom)" />
                        </g>

                        {{-- Crease lines --}}
                        <g clip-path="url(#ew-clip)" opacity="0.4">
                            <line x1="1" y1="1" x2="280" y2="155" stroke="#B8B0A0"
                                stroke-width="0.9" />
                            <line x1="559" y1="1" x2="280" y2="155" stroke="#B8B0A0"
                                stroke-width="0.9" />
                            <line x1="1" y1="299" x2="280" y2="155" stroke="#B8B0A0"
                                stroke-width="0.9" />
                            <line x1="559" y1="299" x2="280" y2="155" stroke="#B8B0A0"
                                stroke-width="0.9" />
                        </g>

                        {{-- Open top flap (folded back — darker underside visible) --}}
                        <polygon points="0,0 560,0 280,120" fill="#DDD7CC" clip-path="url(#ew-clip)" />
                        <polygon points="0,0 560,0 280,120" fill="url(#ew-flap)" opacity="0.6"
                            clip-path="url(#ew-clip)" />

                        {{-- Flap fold edge crease --}}
                        <line x1="0" y1="0" x2="280" y2="120" stroke="#B8B0A0"
                            stroke-width="0.9" opacity="0.5" />
                        <line x1="560" y1="0" x2="280" y2="120" stroke="#B8B0A0"
                            stroke-width="0.9" opacity="0.5" />

                        {{-- Inner glow (opening) --}}
                        <rect x="1" y="1" width="558" height="80" rx="2" fill="url(#ew-inner)"
                            clip-path="url(#ew-clip)" />

                        {{-- Top opening shadow line --}}
                        <rect x="0" y="0" width="560" height="5" rx="2" fill="rgba(0,0,0,0.05)"
                            clip-path="url(#ew-clip)" />

                        {{-- Outer border --}}
                        <rect x="0.5" y="0.5" width="559" height="299" rx="3" fill="none"
                            stroke="#C4BAA8" stroke-width="0.9" opacity="0.6" />
                    </svg>

                    @php
                        $photoPositions = [
                            ['top' => '20px', 'left' => '50%', 'ml' => '-100px', 'rot' => '-3deg', 'z' => 7],
                            ['top' => '15px', 'left' => '55%', 'ml' => '-90px', 'rot' => '9deg', 'z' => 6],
                            ['top' => '35px', 'left' => '42%', 'ml' => '-80px', 'rot' => '-13deg', 'z' => 5],
                            ['top' => '55px', 'left' => '50%', 'ml' => '-105px', 'rot' => '5deg', 'z' => 4],
                            ['top' => '25px', 'left' => '38%', 'ml' => '-70px', 'rot' => '-8deg', 'z' => 3],
                            ['top' => '50px', 'left' => '58%', 'ml' => '-100px', 'rot' => '14deg', 'z' => 2],
                            ['top' => '40px', 'left' => '45%', 'ml' => '-95px', 'rot' => '-6deg', 'z' => 1],
                        ];
                    @endphp

                    @foreach ($couplePhotos as $idx => $photo)
                        @php $pos = $photoPositions[$idx]; @endphp
                        <div class="polaroid"
                            style="top:{{ $pos['top'] }};left:{{ $pos['left'] }};margin-left:{{ $pos['ml'] }};
                            transform:rotate({{ $pos['rot'] }});z-index:{{ $pos['z'] }};">
                            <img src="{{ asset('img/' . $photo) }}" alt="Hannia y Alfonso">
                        </div>
                    @endforeach

                </div>

                <p class="drag-hint mt-4">
                    <svg class="inline-block w-3.5 h-3.5 mr-1 -mt-0.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 11V6a2 2 0 0 0-4 0v5" />
                        <path d="M14 10V4a2 2 0 0 0-4 0v2" />
                        <path d="M10 10.5V6a2 2 0 0 0-4 0v8" />
                        <path
                            d="M18 8a2 2 0 1 1 4 0v6a8 8 0 0 1-8 8h-2c-2.8 0-4.5-.86-5.99-2.34l-3.6-3.6a2 2 0 0 1 2.83-2.82L7 15" />
                    </svg>
                    Arrastra las fotos para explorarlas
                </p>
            </div>
        </section>

        {{-- ── 7. CÓDIGO DE VESTIMENTA ───────────────────────────────── --}}
        <section id="vestimenta" class="py-16 sm:py-24 px-6 text-center" style="background:var(--ivory);">
            <div class="max-w-lg mx-auto">

                <p class="section-label mb-3 js-hidden" data-anim>Para ese día especial</p>
                <span class="section-rule block mb-6 js-hidden" data-anim></span>
                <h2 class="font-display text-2xl sm:text-3xl mb-4 js-hidden" data-anim
                    style="color:var(--charcoal);font-weight:400;">Código de Vestimenta</h2>
                <p class="font-accent italic text-lg mb-10 js-hidden" data-anim style="color:var(--olive);">Paleta
                    otoñal · Octubre 2026</p>

                {{-- Swatches --}}
                <div class="flex justify-center gap-4 sm:gap-6 mb-10" id="swatches-row" data-anim-swatches>
                    <div>
                        <div class="swatch mx-auto" style="background:#7B1B1B;"></div>
                        <p class="swatch-label">Borgoña</p>
                    </div>
                    <div>
                        <div class="swatch mx-auto" style="background:#E8A820;"></div>
                        <p class="swatch-label">Dorado</p>
                    </div>
                    <div>
                        <div class="swatch mx-auto" style="background:#1C5C3A;"></div>
                        <p class="swatch-label">Verde</p>
                    </div>
                    <div>
                        <div class="swatch mx-auto" style="background:#C44E0A;"></div>
                        <p class="swatch-label">Terracota</p>
                    </div>
                    <div>
                        <div class="swatch mx-auto" style="background:#9B6A18;"></div>
                        <p class="swatch-label">Caramelo</p>
                    </div>
                </div>

                {{-- Men & Women --}}
                <div class="grid grid-cols-2 gap-4 mb-8 text-left js-hidden" data-anim>
                    <div class="p-5"
                        style="border:1px solid var(--parchment);border-radius:2px;background:rgba(245,240,232,0.5);">
                        <p class="font-body text-[9px] tracking-[0.3em] uppercase mb-2" style="color:var(--olive);">Ellas
                        </p>
                        <p class="font-accent italic text-base leading-snug" style="color:var(--charcoal);">
                            Vestido elegante en la paleta otoñal
                        </p>
                    </div>
                    <div class="p-5"
                        style="border:1px solid var(--parchment);border-radius:2px;background:rgba(245,240,232,0.5);">
                        <p class="font-body text-[9px] tracking-[0.3em] uppercase mb-2" style="color:var(--olive);">Ellos
                        </p>
                        <p class="font-accent italic text-base leading-snug" style="color:var(--charcoal);">
                            Traje formal, sin restricción de color
                        </p>
                    </div>
                </div>

                {{-- Important warning --}}
                <div class="dress-warning text-left py-4 js-hidden" data-anim>
                    <p class="font-body text-[9px] tracking-[0.25em] uppercase mb-1" style="color:var(--autumn-sienna);">
                        Nota importante</p>
                    <p class="font-body text-sm leading-relaxed" style="color:var(--charcoal);">
                        Los vestidos no pueden tener lentejuelas ni bordados.
                    </p>
                </div>

            </div>
        </section>

        {{-- Illustration divider --}}
        <img src="{{ asset('img/couple-illustration-floral-arch-wide.png') }}" class="illus-divider"
            style="height:220px;opacity:0.55;object-position:center 20%;" alt="" aria-hidden="true">

        {{-- ── 8. HOSPEDAJE ──────────────────────────────────────────── --}}
        <section id="hoteles" class="py-16 sm:py-24 px-6" style="background:var(--cream);">
            <div class="max-w-lg mx-auto">

                <div class="text-center mb-10">
                    <p class="section-label mb-3 js-hidden" data-anim>Para tu comodidad</p>
                    <span class="section-rule block mb-4 js-hidden" data-anim></span>
                    <h2 class="font-display text-2xl sm:text-3xl mb-2 js-hidden" data-anim
                        style="color:var(--charcoal);font-weight:400;">Hospedaje cercano</h2>
                    <p class="font-accent italic js-hidden" data-anim style="color:var(--olive);">
                        Te recomendamos reservar con anticipación
                    </p>
                </div>

                {{-- Hotel grid --}}
                <div class="grid grid-cols-2 gap-3" id="hotels-grid" data-anim-hotels>
                    @foreach ($hotels as $i => $hotel)
                        <a href="{{ $hotel['url'] }}" target="_blank" rel="noopener" class="hotel-card"
                            onclick="window.haptics?.trigger('light')">
                            <span class="hotel-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <div>
                                <p class="font-body text-[9px] tracking-[0.2em] uppercase mb-0.5"
                                    style="color:var(--olive);">Opción {{ $i + 1 }}</p>
                                <p class="inline-flex items-center gap-1 font-body text-xs"
                                    style="color:var(--charcoal-soft);">
                                    Ver en mapa
                                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M7 17L17 7M17 7H7M17 7v10" />
                                    </svg>
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>

            </div>
        </section>

        {{-- ── 9. FOOTER ─────────────────────────────────────────────── --}}
        <footer class="relative py-20 px-6 text-center overflow-hidden" style="background:var(--charcoal);">

            <img src="{{ asset('img/couple-illustration-floral-arch-tall.png') }}"
                class="absolute inset-0 w-full h-full object-cover opacity-[0.07] pointer-events-none"
                style="object-position:center top;" alt="">

            <div class="relative">
                <p class="section-label mb-6 js-hidden" data-anim style="color:rgba(255,255,255,0.35);">
                    Con todo nuestro amor
                </p>

                <h2 class="font-script js-hidden" data-anim
                    style="font-size:clamp(2.5rem,12vw,5rem);color:#fff;line-height:1;">
                    Hannia <span style="color:var(--autumn-amber);">&</span> Alfonso
                </h2>

                <div class="mx-auto my-6 js-hidden" data-anim
                    style="width:40px;height:1px;background:linear-gradient(90deg,transparent,rgba(255,255,255,0.2),transparent);">
                </div>

                <p class="font-accent italic text-lg js-hidden" data-anim style="color:rgba(255,255,255,0.45);">24 de
                    Octubre, 2026</p>

                <p class="font-body text-xs mt-8 js-hidden" data-anim
                    style="color:rgba(255,255,255,0.2);letter-spacing:0.15em;text-transform:uppercase;">
                    Viñedo Tierra De Alonso · Querétaro · México
                </p>
            </div>
        </footer>

    </main>

    {{-- ── Scripts ──────────────────────────────────────────────────── --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ─── 1. Lenis smooth scroll ───────────────────────────────────
            const lenis = new window.Lenis({
                lerp: 0.09,
                smoothWheel: true
            });
            const {
                ScrollTrigger,
                Draggable,
                gsap
            } = window;

            lenis.on('scroll', ScrollTrigger.update);
            gsap.ticker.add(function(time) {
                lenis.raf(time * 1000);
            });
            gsap.ticker.lagSmoothing(0);

            // ─── Countdown ───────────────────────────────────────────────
            (function initCountdown() {
                const el = document.getElementById('countdown');
                if (!el) {
                    return;
                }
                const target = new Date(el.dataset.target);
                const fields = {};
                el.querySelectorAll('[data-cd]').forEach(function(node) {
                    fields[node.dataset.cd] = node;
                });

                function pad(n) {
                    return String(n).padStart(2, '0');
                }

                function tick() {
                    const now = new Date();
                    if (target <= now) {
                        ['months', 'days', 'hours', 'minutes', 'seconds'].forEach(function(k) {
                            if (fields[k]) {
                                fields[k].textContent = '0';
                            }
                        });
                        return;
                    }

                    let months = (target.getFullYear() - now.getFullYear()) * 12 +
                        (target.getMonth() - now.getMonth());
                    const anchor = new Date(now);
                    anchor.setMonth(anchor.getMonth() + months);
                    if (anchor > target) {
                        months--;
                        anchor.setMonth(anchor.getMonth() - 1);
                    }

                    let diff = target - anchor;
                    const days = Math.floor(diff / 86400000);
                    diff -= days * 86400000;
                    const hours = Math.floor(diff / 3600000);
                    diff -= hours * 3600000;
                    const minutes = Math.floor(diff / 60000);
                    diff -= minutes * 60000;
                    const seconds = Math.floor(diff / 1000);

                    if (fields.months) {
                        fields.months.textContent = months;
                    }
                    if (fields.days) {
                        fields.days.textContent = days;
                    }
                    if (fields.hours) {
                        fields.hours.textContent = pad(hours);
                    }
                    if (fields.minutes) {
                        fields.minutes.textContent = pad(minutes);
                    }
                    if (fields.seconds) {
                        fields.seconds.textContent = pad(seconds);
                    }
                }

                tick();
                setInterval(tick, 1000);
            })();

            // ─── 2. Envelope → reveal ────────────────────────────────────
            const overlay = document.getElementById('envelope-overlay');
            const sealWrap = document.getElementById('seal-wrapper');
            const seal = document.getElementById('env-seal');
            const flap = document.getElementById('env-flap');
            const audio = document.getElementById('env-audio');
            const mainEl = document.getElementById('main-content');
            const muteBtn = document.getElementById('mute-btn');
            const iconSound = document.getElementById('icon-sound');
            const iconMuted = document.getElementById('icon-muted');

            // Mute toggle
            if (muteBtn && audio) {
                muteBtn.addEventListener('click', function() {
                    audio.muted = !audio.muted;
                    iconSound.style.display = audio.muted ? 'none' : '';
                    iconMuted.style.display = audio.muted ? '' : 'none';
                    muteBtn.setAttribute('aria-label', audio.muted ? 'Activar música' : 'Silenciar música');
                    window.haptics?.trigger('light');
                });
            }

            function revealMain() {
                gsap.to(mainEl, {
                    opacity: 1,
                    duration: 0.6,
                    ease: 'power2.out',
                    onComplete: function() {
                        initHeroAnims();
                        initScrollAnims();
                        initPhotos();
                        initCinemaVideo();
                    }
                });
            }

            if (!overlay || !seal || !gsap) {
                revealMain();
                return;
            }

            let triggered = false;
            seal.addEventListener('click', function() {
                if (triggered) return;
                triggered = true;
                overlay.style.pointerEvents = 'none';
                window.haptics?.trigger([{
                    duration: 1000
                }], {
                    intensity: 1
                });
                if (audio) {
                    audio.currentTime = 0;
                    const p = audio.play();
                    if (p) p.catch(function() {});
                    // Fade in mute button after a short delay
                    if (muteBtn) {
                        setTimeout(function() {
                            muteBtn.style.opacity = '1';
                            muteBtn.style.pointerEvents = 'auto';
                        }, 800);
                    }
                }

                const tl = gsap.timeline();
                tl.to([sealWrap, flap], {
                        y: '-120vh',
                        duration: 2.6,
                        ease: 'power2.inOut',
                        stagger: 0.12
                    })
                    .to(overlay, {
                        opacity: 0,
                        duration: 1.3,
                        ease: 'power2.inOut',
                        onComplete: function() {
                            overlay.style.display = 'none';
                            revealMain();
                        }
                    }, 0.8);
            });

            // ─── 3. Hero entrance animations ─────────────────────────────
            function initHeroAnims() {
                gsap.fromTo('#hero-label', {
                    opacity: 0,
                    y: 20
                }, {
                    opacity: 1,
                    y: 0,
                    duration: 1,
                    ease: 'power3.out',
                    delay: 0.1
                });
                gsap.fromTo('#hero-names', {
                    opacity: 0,
                    y: 32
                }, {
                    opacity: 1,
                    y: 0,
                    duration: 1.1,
                    ease: 'power3.out',
                    delay: 0.35
                });
                gsap.fromTo('#hero-date', {
                    opacity: 0,
                    y: 20
                }, {
                    opacity: 1,
                    y: 0,
                    duration: 0.9,
                    ease: 'power3.out',
                    delay: 0.65
                });
                gsap.fromTo('#scroll-hint', {
                    opacity: 0
                }, {
                    opacity: 1,
                    duration: 0.8,
                    delay: 1.2
                });
                initLeaves();
            }

            // ─── 4. Scroll-triggered animations ──────────────────────────
            function initScrollAnims() {
                // Generic fade-up
                document.querySelectorAll('[data-anim]').forEach(function(el) {
                    gsap.fromTo(el, {
                        opacity: 0,
                        y: 36
                    }, {
                        opacity: 1,
                        y: 0,
                        duration: 0.85,
                        ease: 'power2.out',
                        scrollTrigger: {
                            trigger: el,
                            start: 'top 88%',
                            once: true
                        }
                    });
                });

                // Swatches stagger
                const swatchRow = document.getElementById('swatches-row');
                if (swatchRow) {
                    gsap.fromTo(swatchRow.children, {
                        opacity: 0,
                        scale: 0.6,
                        y: 20
                    }, {
                        opacity: 1,
                        scale: 1,
                        y: 0,
                        duration: 0.5,
                        ease: 'back.out(1.7)',
                        stagger: 0.07,
                        scrollTrigger: {
                            trigger: swatchRow,
                            start: 'top 85%',
                            once: true
                        }
                    });
                }

                // Hotel cards stagger
                const hotelsGrid = document.getElementById('hotels-grid');
                if (hotelsGrid) {
                    gsap.fromTo(Array.from(hotelsGrid.children), {
                        opacity: 0,
                        y: 24
                    }, {
                        opacity: 1,
                        y: 0,
                        duration: 0.55,
                        ease: 'power2.out',
                        stagger: 0.06,
                        scrollTrigger: {
                            trigger: hotelsGrid,
                            start: 'top 85%',
                            once: true
                        }
                    });
                }

                // Hero photo parallax
                ScrollTrigger.create({
                    trigger: '#hero',
                    start: 'top top',
                    end: 'bottom top',
                    scrub: true,
                    onUpdate: function(self) {
                        const bg = document.querySelector('.hero-bg');
                        if (bg) gsap.set(bg, {
                            y: self.progress * 80
                        });
                    }
                });
            }

            // ─── Cinematic video (scroll-scale + autoplay on enter) ──────
            function initCinemaVideo() {
                const section = document.getElementById('video-cinematic');
                const clip = document.getElementById('video-clip');
                const caption = document.getElementById('video-caption');
                const video = document.getElementById('cinema-video');
                if (!section || !clip || !video) {
                    return;
                }

                // Initial framed window → full-bleed values
                const START = {
                    x: 9,
                    y: 15,
                    r: 18
                };

                function setClip(p) {
                    // Expansion finishes at 55% scroll so the video is full-bleed while still in view
                    const e = Math.min(p / 0.55, 1);
                    const x = START.x * (1 - e);
                    const y = START.y * (1 - e);
                    const r = START.r * (1 - e);
                    clip.style.clipPath = `inset(${y}% ${x}% ${y}% ${x}% round ${r}px)`;
                    if (caption) {
                        caption.style.opacity = String(Math.max(1 - p / 0.25, 0));
                        caption.style.transform = `translateY(${-p * 30}px)`;
                    }
                }

                setClip(0);

                ScrollTrigger.create({
                    trigger: section,
                    start: 'top top',
                    end: 'bottom bottom',
                    scrub: true,
                    onUpdate: function(self) {
                        setClip(self.progress);
                    }
                });

                // Play muted while the section is on screen; pause + free decode when off screen
                let loaded = false;
                const io = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            if (!loaded) {
                                video.load();
                                loaded = true;
                            }
                            video.muted = true;
                            const playPromise = video.play();
                            if (playPromise) {
                                playPromise.catch(function() {});
                            }
                        } else {
                            video.pause();
                        }
                    });
                }, {
                    threshold: 0.25
                });
                io.observe(section);
            }

            // ─── 5. Three.js falling leaves ──────────────────────────────
            function initLeaves() {
                const canvas = document.getElementById('leaves-canvas');
                const heroEl = document.getElementById('hero');
                const THREE = window.THREE;
                if (!canvas || !heroEl || !THREE) return;

                const W = heroEl.offsetWidth;
                const H = heroEl.offsetHeight;
                canvas.width = W;
                canvas.height = H;

                const scene = new THREE.Scene();
                const camera = new THREE.OrthographicCamera(-W / 2, W / 2, H / 2, -H / 2, 0, 100);
                camera.position.z = 10;

                const renderer = new THREE.WebGLRenderer({
                    canvas: canvas,
                    alpha: true,
                    antialias: false
                });
                renderer.setSize(W, H);
                renderer.setPixelRatio(Math.min(window.devicePixelRatio, 1.5));
                renderer.setClearColor(0x000000, 0);

                const COUNT = W < 768 ? 28 : 55;
                const COLORS = [0xC44E0A, 0xD4881A, 0x7B1B1B, 0xE8A820, 0x9B6A18, 0xB33A00, 0xE05020];

                // Leaf shape (elliptical petal)
                const shape = new THREE.Shape();
                shape.moveTo(0, 4.5);
                shape.bezierCurveTo(2.8, 2.8, 2.8, -2.8, 0, -4.5);
                shape.bezierCurveTo(-2.8, -2.8, -2.8, 2.8, 0, 4.5);

                const geo = new THREE.ShapeGeometry(shape);

                const leaves = [];
                for (let i = 0; i < COUNT; i++) {
                    const mat = new THREE.MeshBasicMaterial({
                        color: COLORS[i % COLORS.length],
                        transparent: true,
                        opacity: 0.28 + Math.random() * 0.38,
                        side: THREE.DoubleSide,
                    });
                    const leaf = new THREE.Mesh(geo, mat);
                    const scale = 1.2 + Math.random() * 1.8;
                    leaf.scale.set(scale, scale, 1);
                    leaf.position.set(
                        (Math.random() - 0.5) * W * 1.3,
                        H / 2 + Math.random() * H,
                        0
                    );
                    leaf.rotation.z = Math.random() * Math.PI * 2;
                    scene.add(leaf);
                    leaves.push({
                        mesh: leaf,
                        vy: 0.35 + Math.random() * 0.6,
                        vx: (Math.random() - 0.5) * 0.25,
                        vr: (Math.random() - 0.5) * 0.022,
                        phase: Math.random() * Math.PI * 2,
                        phaseSpeed: 0.013 + Math.random() * 0.018,
                    });
                }

                let active = true;

                function animate() {
                    if (!active) return;
                    requestAnimationFrame(animate);
                    leaves.forEach(function(l) {
                        l.phase += l.phaseSpeed;
                        l.mesh.position.y -= l.vy;
                        l.mesh.position.x += l.vx + Math.sin(l.phase) * 0.55;
                        l.mesh.rotation.z += l.vr;
                        if (l.mesh.position.y < -H / 2 - 30) {
                            l.mesh.position.y = H / 2 + 30;
                            l.mesh.position.x = (Math.random() - 0.5) * W;
                        }
                    });
                    renderer.render(scene, camera);
                }
                animate();

                const obs = new IntersectionObserver(function(entries) {
                    active = entries[0].isIntersecting;
                    if (active) animate();
                }, {
                    threshold: 0
                });
                obs.observe(heroEl);
            }

            // ─── 6. Draggable Polaroid photos ────────────────────────────
            function initPhotos() {
                const scatter = document.getElementById('photo-scatter');
                if (!scatter || !Draggable) return;

                const cards = Array.from(scatter.querySelectorAll('.polaroid'));

                // Animate in from center
                gsap.fromTo(cards, {
                    opacity: 0,
                    scale: 0.4,
                    y: -60,
                    rotation: 0
                }, {
                    opacity: 1,
                    scale: 1,
                    y: 0,
                    duration: 0.55,
                    ease: 'back.out(1.5)',
                    stagger: {
                        each: 0.08,
                        from: 'center'
                    },
                    scrollTrigger: {
                        trigger: scatter,
                        start: 'top 80%',
                        once: true
                    },
                    onComplete: function() {
                        enableDrag(cards);
                    }
                });
            }

            function enableDrag(cards) {
                cards.forEach(function(card, idx) {
                    Draggable.create(card, {
                        type: 'x,y',
                        bounds: window,
                        edgeResistance: 0.55,
                        onPress: function() {
                            window.haptics?.trigger('medium');
                            card.classList.add('lifted');
                            gsap.to(card, {
                                scale: 1.06,
                                duration: 0.18,
                                ease: 'power2.out'
                            });
                            gsap.set(card, {
                                zIndex: 200 + idx
                            });
                        },
                        onRelease: function() {
                            window.haptics?.trigger('light');
                            card.classList.remove('lifted');
                            gsap.to(card, {
                                scale: 1,
                                duration: 0.22,
                                ease: 'power2.out'
                            });
                        },
                    });
                });
            }

            // ─── 7. Haptics on interactive elements ──────────────────────
            document.addEventListener('change', function(e) {
                if (e.target.type === 'radio') window.haptics?.trigger('selection');
            });

            // ─── Livewire haptics bridge ──────────────────────────────────
            document.addEventListener('livewire:initialized', function() {
                Livewire.on('haptic', function(data) {
                    const type = Array.isArray(data) ? data[0]?.type : data?.type;
                    if (type) window.haptics?.trigger(type);
                });
            });

        });
    </script>
@endsection
