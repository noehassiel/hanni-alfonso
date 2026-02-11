@extends('layouts.app')

@section('content')
{{-- Desktop: centered card floating on ivory. Mobile: full bleed --}}
<div class="min-h-svh flex items-center justify-center p-0 md:p-8 lg:p-12" style="background: var(--ivory);">

    {{-- The invitation card --}}
    <div class="invitation-card relative w-full md:max-w-[460px] lg:max-w-[500px] md:rounded-sm overflow-hidden" style="min-height: 100svh; max-height: 100svh; md:min-height: auto; md:max-height: none;">

        {{-- Illustration fills the card --}}
        <picture>
            <source media="(min-width: 768px)" srcset="/img/img-4.png">
            <img src="/img/img-6.png"
                 alt="Hanni y Alfonso — Ilustración acuarela"
                 class="absolute inset-0 w-full h-full object-cover object-bottom anim-fade-in"
                 style="animation-duration: 1.4s;">
        </picture>

        {{-- Light veil behind text — desktop only --}}
        <div class="hidden md:block absolute inset-0 z-[5] pointer-events-none" style="background: linear-gradient(to bottom, rgba(245,240,232,0.75) 0%, rgba(245,240,232,0.55) 35%, rgba(245,240,232,0.25) 55%, transparent 70%);"></div>

        {{-- Text overlay — positioned on the sky portion --}}
        <div class="relative z-10 text-center flex flex-col items-center pt-[5.5vh] md:pt-10 lg:pt-12 px-8">

            {{-- Overline --}}
            <p class="font-body text-[8px] sm:text-[9px] tracking-[0.5em] uppercase anim-fade-up delay-1" style="color: var(--olive-light);">
                Junto a sus familias
            </p>

            {{-- Thin rule --}}
            <div class="mx-auto mt-3 mb-4 sm:mt-4 sm:mb-5 anim-line delay-2" style="width: 28px; height: 1px; background: var(--olive-light); opacity: 0.35;"></div>

            {{-- Names in script --}}
            <div class="anim-fade-up delay-2">
                <h1 class="font-script landing-script text-[3.2rem] sm:text-[4.2rem] md:text-[4.8rem] leading-[0.85]" style="color: var(--olive);">
                    Hanni
                </h1>
            </div>

            <p class="font-accent text-lg sm:text-xl italic anim-fade-up delay-3" style="color: var(--olive-light); font-weight: 300; margin: 0 0 0.1em;">
                y
            </p>

            <div class="anim-fade-up delay-3">
                <h1 class="font-script landing-script text-[3.2rem] sm:text-[4.2rem] md:text-[4.8rem] leading-[0.85]" style="color: var(--olive);">
                    Alfonso
                </h1>
            </div>

            {{-- Invitation text --}}
            <div class="mt-3 sm:mt-4 anim-fade-up delay-4">
                <p class="font-body text-[8px] sm:text-[9px] tracking-[0.4em] uppercase leading-[2] landing-small-text" style="color: var(--olive-light);">
                    Te invitan a celebrar<br>su boda
                </p>
            </div>

            {{-- Date --}}
            <div class="mt-4 sm:mt-5 anim-fade-up delay-5">
                <p class="font-script landing-script text-[2.2rem] sm:text-[2.8rem] md:text-[3.2rem]" style="color: var(--olive); line-height: 1;">
                    24.10.25
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Card behavior: full viewport on mobile, elegant card on desktop */
    @media (min-width: 768px) {
        .invitation-card {
            min-height: 0 !important;
            max-height: none !important;
            aspect-ratio: 3 / 4.5;
            box-shadow:
                0 2px 20px rgba(74, 80, 67, 0.06),
                0 8px 40px rgba(74, 80, 67, 0.04);
        }
    }

    /* On very tall mobile screens, prevent text from being too low */
    @media (max-width: 767px) and (min-height: 800px) {
        .invitation-card > .relative {
            padding-top: 6vh;
        }
    }

    /* Short mobile screens: tighten spacing */
    @media (max-width: 767px) and (max-height: 680px) {
        .invitation-card > .relative {
            padding-top: 3vh;
        }
        .invitation-card .font-script {
            font-size: 2.6rem;
        }
    }

    /* Desktop: text shadows for readability over illustration */
    @media (min-width: 768px) {
        .landing-script {
            text-shadow:
                0 0 20px rgba(245, 240, 232, 1),
                0 0 40px rgba(245, 240, 232, 0.9),
                0 0 60px rgba(245, 240, 232, 0.6);
            color: var(--charcoal) !important;
        }
        .landing-small-text {
            text-shadow:
                0 0 14px rgba(245, 240, 232, 1),
                0 0 28px rgba(245, 240, 232, 0.85);
            color: var(--charcoal) !important;
        }
    }
</style>
@endsection
