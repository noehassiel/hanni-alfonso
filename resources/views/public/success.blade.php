@extends('layouts.app')

@section('content')
<div class="min-h-screen relative overflow-hidden flex flex-col items-center justify-center px-6" style="background: radial-gradient(ellipse at 50% 30%, #ece5d8 0%, var(--ivory) 60%);">

    {{-- Top decorative border --}}
    <div class="absolute top-0 left-0 right-0 h-px" style="background: linear-gradient(90deg, transparent, var(--parchment) 20%, var(--bronze-light) 50%, var(--parchment) 80%, transparent);"></div>

    {{-- Floating botanical SVG --}}
    <svg class="absolute top-12 left-8 sm:left-16 w-24 sm:w-32 opacity-[0.06]" viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="0.8" style="color: var(--olive); animation: floatGentle 9s ease-in-out infinite;">
        <path d="M100 180 C100 180 60 140 60 100 C60 60 100 20 100 20 C100 20 140 60 140 100 C140 140 100 180 100 180Z"/>
        <path d="M100 20 L100 180"/>
        <path d="M72 60 Q100 80 128 60"/>
        <path d="M65 90 Q100 110 135 90"/>
    </svg>

    <svg class="absolute bottom-16 right-8 sm:right-16 w-20 sm:w-28 opacity-[0.05]" viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="0.8" style="color: var(--olive); animation: floatGentle 11s ease-in-out infinite 3s; transform: scaleX(-1);">
        <path d="M100 180 C100 180 50 130 50 90 C50 50 100 10 100 10 C100 10 150 50 150 90 C150 130 100 180 100 180Z"/>
        <path d="M100 10 L100 180"/>
        <path d="M65 50 Q100 70 135 50"/>
        <path d="M58 80 Q100 105 142 80"/>
    </svg>

    {{-- Main content --}}
    <div class="text-center relative z-10 max-w-lg mx-auto">

        {{-- Animated check mark --}}
        <div class="mb-10 anim-scale delay-1">
            <svg class="mx-auto w-16 h-16 sm:w-20 sm:h-20" viewBox="0 0 80 80" fill="none">
                <circle cx="40" cy="40" r="36" stroke="var(--sage)" stroke-width="0.8" opacity="0.4"/>
                <circle cx="40" cy="40" r="28" stroke="var(--sage)" stroke-width="0.5" opacity="0.2"/>
                <path d="M28 40 L36 48 L52 32" stroke="var(--olive)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

        {{-- Heading --}}
        <h1 class="font-display text-5xl sm:text-6xl md:text-7xl tracking-[-0.02em] anim-fade-up delay-2" style="color: var(--charcoal); font-weight: 400;">
            Gracias
        </h1>

        <div class="mx-auto mt-6 mb-6 anim-line delay-3" style="width: 48px; height: 1px; background: var(--bronze-light);"></div>

        {{-- Message --}}
        <p class="font-accent text-lg sm:text-xl italic leading-relaxed anim-fade-up delay-3" style="color: var(--olive-light); font-weight: 300;">
            Tu respuesta ha sido registrada exitosamente
        </p>
        <p class="font-body text-xs tracking-[0.2em] uppercase mt-4 anim-fade-up delay-4" style="color: var(--bronze-light);">
            Te enviaremos un recordatorio antes del evento
        </p>

        {{-- Date card --}}
        <div class="mt-12 anim-fade-up delay-5">
            <div class="relative inline-block py-8 px-12" style="border: 1px solid var(--parchment);">
                {{-- Corner ornaments --}}
                <div class="absolute top-2 left-2 w-4 h-4" style="border-top: 1px solid var(--bronze-light); border-left: 1px solid var(--bronze-light);"></div>
                <div class="absolute top-2 right-2 w-4 h-4" style="border-top: 1px solid var(--bronze-light); border-right: 1px solid var(--bronze-light);"></div>
                <div class="absolute bottom-2 left-2 w-4 h-4" style="border-bottom: 1px solid var(--bronze-light); border-left: 1px solid var(--bronze-light);"></div>
                <div class="absolute bottom-2 right-2 w-4 h-4" style="border-bottom: 1px solid var(--bronze-light); border-right: 1px solid var(--bronze-light);"></div>

                <p class="font-display text-2xl sm:text-3xl" style="color: var(--charcoal); font-weight: 400;">Hanni & Alfonso</p>
                <div class="flex items-center justify-center gap-3 mt-3">
                    <div style="width: 24px; height: 0.5px; background: var(--parchment);"></div>
                    <p class="font-body text-[10px] tracking-[0.35em] uppercase" style="color: var(--bronze);">24 Octubre 2025</p>
                    <div style="width: 24px; height: 0.5px; background: var(--parchment);"></div>
                </div>
            </div>
        </div>

        {{-- Back link --}}
        <div class="mt-12 anim-fade-in delay-6">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 font-body text-[10px] tracking-[0.25em] uppercase transition-colors duration-300 group" style="color: var(--bronze-light);"
               onmouseover="this.style.color='var(--bronze)'"
               onmouseout="this.style.color='var(--bronze-light)'">
                <svg class="w-4 h-4 transition-transform duration-300 group-hover:-translate-x-1" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M5 12l5-5M5 12l5 5"/>
                </svg>
                Volver al inicio
            </a>
        </div>

        {{-- Bottom flourish --}}
        <div class="mt-16 anim-fade-in delay-7">
            <svg class="mx-auto w-10 h-10 opacity-20" viewBox="0 0 48 48" fill="none" stroke="var(--bronze)" stroke-width="0.5">
                <path d="M24 4 C24 4 10 16 10 26 C10 34 16 40 24 44 C32 40 38 34 38 26 C38 16 24 4 24 4Z"/>
                <path d="M24 12 L24 36"/>
                <path d="M17 22 Q24 28 31 22"/>
                <path d="M18 30 Q24 35 30 30"/>
            </svg>
        </div>
    </div>

    {{-- Bottom decorative border --}}
    <div class="absolute bottom-0 left-0 right-0 h-px" style="background: linear-gradient(90deg, transparent, var(--parchment) 20%, var(--bronze-light) 50%, var(--parchment) 80%, transparent);"></div>
</div>
@endsection
