@extends('layouts.app')

@section('content')
<div class="min-h-screen relative overflow-hidden flex flex-col items-center justify-center px-6" style="background: radial-gradient(ellipse at 30% 20%, #ece5d8 0%, var(--ivory) 50%), radial-gradient(ellipse at 70% 80%, #e8dfd3 0%, transparent 50%);">

    {{-- Floating botanical SVG decorations --}}
    <svg class="absolute top-8 left-8 sm:top-12 sm:left-16 w-28 sm:w-40 opacity-[0.07] anim-fade-in delay-5" viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="0.8" style="color: var(--olive); animation: floatGentle 8s ease-in-out infinite;">
        <path d="M100 180 C100 180 60 140 60 100 C60 60 100 20 100 20 C100 20 140 60 140 100 C140 140 100 180 100 180Z"/>
        <path d="M100 20 L100 180"/>
        <path d="M72 60 Q100 80 128 60"/>
        <path d="M65 90 Q100 110 135 90"/>
        <path d="M68 120 Q100 140 132 120"/>
    </svg>

    <svg class="absolute bottom-12 right-8 sm:bottom-16 sm:right-16 w-32 sm:w-44 opacity-[0.06] anim-fade-in delay-6" viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="0.8" style="color: var(--olive); animation: floatGentle 10s ease-in-out infinite 2s; transform: scaleX(-1);">
        <path d="M100 180 C100 180 50 130 50 90 C50 50 100 10 100 10 C100 10 150 50 150 90 C150 130 100 180 100 180Z"/>
        <path d="M100 10 L100 180"/>
        <path d="M65 50 Q100 70 135 50"/>
        <path d="M58 80 Q100 105 142 80"/>
        <path d="M62 115 Q100 135 138 115"/>
    </svg>

    {{-- Decorative spinning star --}}
    <div class="absolute top-1/4 right-[15%] hidden sm:block anim-fade-in delay-7" style="animation: spinSlow 30s linear infinite; opacity: 0.08;">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="var(--bronze)">
            <path d="M12 0L13.5 10.5L24 12L13.5 13.5L12 24L10.5 13.5L0 12L10.5 10.5Z"/>
        </svg>
    </div>

    {{-- Main content --}}
    <div class="text-center relative z-10 max-w-3xl mx-auto">

        {{-- Overline --}}
        <p class="font-body text-[10px] sm:text-xs tracking-[0.4em] uppercase anim-fade-up delay-1" style="color: var(--bronze-light); letter-spacing: 0.4em;">
            Celebra con nosotros
        </p>

        {{-- Decorative line --}}
        <div class="mx-auto mt-6 mb-8 sm:mt-8 sm:mb-12 anim-line delay-2" style="width: 48px; height: 1px; background: var(--bronze-light);"></div>

        {{-- Names --}}
        <div class="space-y-1 sm:space-y-2">
            <h1 class="font-display text-[3.5rem] sm:text-[5.5rem] md:text-[7rem] lg:text-[8.5rem] leading-[0.85] tracking-[-0.02em] anim-fade-up delay-2" style="color: var(--charcoal); font-weight: 400;">
                Hanni
            </h1>

            <div class="anim-scale delay-3 py-2 sm:py-3">
                <svg class="mx-auto w-8 h-8 sm:w-10 sm:h-10" viewBox="0 0 40 40" fill="none" style="color: var(--bronze-glow);">
                    <circle cx="20" cy="20" r="14" stroke="currentColor" stroke-width="0.5"/>
                    <text x="20" y="24" text-anchor="middle" fill="currentColor" font-family="'Cormorant', serif" font-size="16" font-style="italic">&amp;</text>
                </svg>
            </div>

            <h1 class="font-display text-[3.5rem] sm:text-[5.5rem] md:text-[7rem] lg:text-[8.5rem] leading-[0.85] tracking-[-0.02em] anim-fade-up delay-4" style="color: var(--charcoal); font-weight: 400;">
                Alfonso
            </h1>
        </div>

        {{-- Divider --}}
        <div class="mx-auto mt-10 sm:mt-14 mb-8 sm:mb-10 anim-line delay-5" style="width: 80px; height: 1px; background: linear-gradient(90deg, transparent, var(--bronze-light), transparent);"></div>

        {{-- Date --}}
        <div class="anim-fade-up delay-5">
            <p class="font-accent text-xl sm:text-2xl md:text-3xl italic tracking-wide" style="color: var(--olive-light); font-weight: 300;">
                Veinticuatro de Octubre
            </p>
            <div class="flex items-center justify-center gap-4 sm:gap-6 mt-3">
                <div style="width: 32px; height: 0.5px; background: var(--parchment);"></div>
                <p class="font-body text-[11px] sm:text-xs tracking-[0.35em] uppercase" style="color: var(--bronze);">2025</p>
                <div style="width: 32px; height: 0.5px; background: var(--parchment);"></div>
            </div>
        </div>

        {{-- Decorative bottom flourish --}}
        <div class="mt-16 sm:mt-24 anim-fade-in delay-7">
            <svg class="mx-auto w-12 h-12 opacity-20" viewBox="0 0 48 48" fill="none" stroke="var(--bronze)" stroke-width="0.5">
                <path d="M24 4 C24 4 10 16 10 26 C10 34 16 40 24 44 C32 40 38 34 38 26 C38 16 24 4 24 4Z"/>
                <path d="M24 12 L24 36"/>
                <path d="M17 22 Q24 28 31 22"/>
                <path d="M18 30 Q24 35 30 30"/>
            </svg>
        </div>
    </div>

    {{-- Bottom decorative border --}}
    <div class="absolute bottom-0 left-0 right-0 h-px anim-line delay-7" style="background: linear-gradient(90deg, transparent, var(--parchment) 20%, var(--bronze-light) 50%, var(--parchment) 80%, transparent);"></div>
</div>
@endsection
