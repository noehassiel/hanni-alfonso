@extends('layouts.app')

@section('content')

    {{-- ── Envelope Opening Overlay ─────────────────────────────────────────── --}}
    <audio id="env-audio" src="{{ asset('audi.mp3') }}" preload="auto"></audio>

    <div id="envelope-overlay" style="position: fixed; inset: 0; z-index: 50; background: rgba(245, 240, 232, 0.97);">
        {{-- Stage extends -12% past viewport edges to bleed over any transparent PNG padding --}}
        <div style="position: absolute; inset: -12%;">

            {{-- Layer 1: envelope back — static --}}
            <img id="env-back" src="{{ asset('img/envelop-removebg-preview.png') }}"
                style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center center; z-index: 0;"
                alt="" draggable="false">

            {{-- Layer 2: flap — 3D-rotates open --}}
            <img id="env-flap" src="{{ asset('img/flap-removebg-preview.png') }}"
                style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center center; transform-origin: center top; z-index: 10;"
                alt="" draggable="false">

            {{-- Layer 3: wax seal — click target --}}
            <div id="seal-wrapper"
                style="position: absolute; left: 50%; top: 51.5%; transform: translate(-50%, -50%); width: 36%; aspect-ratio: 1; z-index: 20;">
                <img id="env-seal" src="{{ asset('img/wax-seal-removebg-preview.png') }}"
                    style="width: 100%; height: 100%; object-fit: contain; cursor: pointer; display: block; user-select: none; -webkit-user-select: none; touch-action: manipulation;"
                    alt="Sello de cera" draggable="false">
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.getElementById('envelope-overlay');
            const sealWrapper = document.getElementById('seal-wrapper');
            const seal = document.getElementById('env-seal');
            const flap = document.getElementById('env-flap');
            const audio = document.getElementById('env-audio');

            if (!overlay || !seal || !window.gsap) return;

            const gsap = window.gsap;

            let triggered = false;

            seal.addEventListener('click', function() {
                if (triggered) return;
                triggered = true;
                overlay.style.pointerEvents = 'none';

                // Haptic buzz sincronizado con el inicio de la animación
                if (window.haptics) {
                    window.haptics.trigger([{
                        duration: 1000
                    }], {
                        intensity: 1
                    });
                }

                if (audio) {
                    audio.currentTime = 0;
                    const p = audio.play();
                    if (p) p.catch(function() {});
                }

                const tl = gsap.timeline();

                // Seal y flap suben juntos fuera de pantalla, luego la invitación aparece
                tl.to([sealWrapper, flap], {
                        y: '-120vh',
                        duration: 2.6,
                        ease: 'power2.inOut',
                        stagger: 0.12
                    })
                    // Empieza a difuminar al 50% del levantamiento (1.3s dentro de los 2.6s)
                    .to(overlay, {
                        opacity: 0,
                        duration: 1.3,
                        ease: 'power2.inOut',
                        onComplete: function() {
                            overlay.style.display = 'none';
                        }
                    }, .8);
            });
        });
    </script>
    {{-- ── End Envelope Overlay ──────────────────────────────────────────────── --}}

    <div class="min-h-screen relative"
        style="background: radial-gradient(ellipse at 50% 0%, #ece5d8 0%, var(--ivory) 60%);">

        {{-- Top decorative border --}}
        <div class="absolute top-0 left-0 right-0 h-px"
            style="background: linear-gradient(90deg, transparent, var(--parchment) 20%, var(--bronze-light) 50%, var(--parchment) 80%, transparent);">
        </div>

        {{-- Floating botanical decoration --}}
        <svg class="absolute top-16 right-6 sm:right-16 w-24 sm:w-32 opacity-[0.06]" viewBox="0 0 200 200" fill="none"
            stroke="currentColor" stroke-width="0.8"
            style="color: var(--olive); animation: floatGentle 9s ease-in-out infinite;">
            <path
                d="M100 180 C100 180 60 140 60 100 C60 60 100 20 100 20 C100 20 140 60 140 100 C140 140 100 180 100 180Z" />
            <path d="M100 20 L100 180" />
            <path d="M72 60 Q100 80 128 60" />
            <path d="M65 90 Q100 110 135 90" />
            <path d="M68 120 Q100 140 132 120" />
        </svg>

        {{-- Header --}}
        <div class="text-center pt-14 sm:pt-20 pb-6 sm:pb-10 px-6">
            <p class="font-body text-[10px] sm:text-xs tracking-[0.4em] uppercase anim-fade-up delay-1"
                style="color: var(--bronze-light);">
                Celebra con nosotros
            </p>

            <div class="mx-auto mt-5 mb-6 anim-line delay-2"
                style="width: 48px; height: 1px; background: var(--bronze-light);"></div>

            <h1 class="font-display text-4xl sm:text-5xl md:text-6xl leading-[0.9] tracking-[-0.02em] anim-fade-up delay-2"
                style="color: var(--charcoal); font-weight: 400;">
                Hanni <span class="font-accent italic text-3xl sm:text-4xl md:text-5xl"
                    style="color: var(--bronze-glow);">&</span> Alfonso
            </h1>

            <div class="flex items-center justify-center gap-4 mt-5 anim-fade-up delay-3">
                <div style="width: 32px; height: 0.5px; background: var(--parchment);"></div>
                <p class="font-accent text-base sm:text-lg italic" style="color: var(--olive-light); font-weight: 300;">24
                    de Octubre, 2026</p>
                <div style="width: 32px; height: 0.5px; background: var(--parchment);"></div>
            </div>
        </div>

        {{-- Personal greeting card --}}
        <div class="max-w-xl mx-auto px-6 pb-8 sm:pb-10 anim-fade-up delay-3">
            <div class="relative text-center py-10 px-8"
                style="background: linear-gradient(135deg, rgba(245,240,232,0.6), rgba(236,229,216,0.4)); border: 1px solid var(--parchment); border-radius: 2px;">
                {{-- Corner ornaments --}}
                <div class="absolute top-3 left-3 w-5 h-5"
                    style="border-top: 1px solid var(--bronze-light); border-left: 1px solid var(--bronze-light);"></div>
                <div class="absolute top-3 right-3 w-5 h-5"
                    style="border-top: 1px solid var(--bronze-light); border-right: 1px solid var(--bronze-light);"></div>
                <div class="absolute bottom-3 left-3 w-5 h-5"
                    style="border-bottom: 1px solid var(--bronze-light); border-left: 1px solid var(--bronze-light);"></div>
                <div class="absolute bottom-3 right-3 w-5 h-5"
                    style="border-bottom: 1px solid var(--bronze-light); border-right: 1px solid var(--bronze-light);">
                </div>

                <p class="font-accent text-lg italic" style="color: var(--olive-light);">Querido/a</p>
                <p class="font-display text-3xl sm:text-4xl mt-2" style="color: var(--charcoal); font-weight: 500;">
                    {{ $invitation->group_name }}</p>

                @if ($invitation->personal_message)
                    <div class="mx-auto mt-6"
                        style="width: 40px; height: 1px; background: linear-gradient(90deg, transparent, var(--bronze-light), transparent);">
                    </div>
                    <p class="font-accent text-base italic mt-6 leading-relaxed" style="color: var(--olive-light);">
                        {{ $invitation->personal_message }}</p>
                @endif
            </div>
        </div>

        {{-- Event details + Add to Calendar --}}
        @php
            $googleCalUrl = 'https://calendar.google.com/calendar/render?' . http_build_query([
                'action'   => 'TEMPLATE',
                'text'     => 'Boda Hannia & Alfonso',
                'dates'    => '20261024T163000/20261024T220000',
                'ctz'      => 'America/Mexico_City',
                'location' => 'Carr. Querétaro-Tequisquiapan 707, Purísima de Cubos, 76295 Querétaro, Qro.',
                'details'  => "¡Los esperamos en nuestra boda!\nMapa: https://maps.app.goo.gl/x1WDacDi6BWHyx9H6",
            ]);
        @endphp
        <div class="max-w-xl mx-auto px-6 pb-10 anim-fade-up delay-4">
            <div class="relative text-center py-9 px-8"
                style="background: linear-gradient(135deg, rgba(245,240,232,0.6), rgba(236,229,216,0.4)); border: 1px solid var(--parchment); border-radius: 2px;">
                {{-- Corner ornaments --}}
                <div class="absolute top-3 left-3 w-5 h-5"
                    style="border-top: 1px solid var(--bronze-light); border-left: 1px solid var(--bronze-light);"></div>
                <div class="absolute top-3 right-3 w-5 h-5"
                    style="border-top: 1px solid var(--bronze-light); border-right: 1px solid var(--bronze-light);"></div>
                <div class="absolute bottom-3 left-3 w-5 h-5"
                    style="border-bottom: 1px solid var(--bronze-light); border-left: 1px solid var(--bronze-light);"></div>
                <div class="absolute bottom-3 right-3 w-5 h-5"
                    style="border-bottom: 1px solid var(--bronze-light); border-right: 1px solid var(--bronze-light);"></div>

                {{-- Calendar icon --}}
                <svg class="mx-auto mb-4 w-7 h-7 opacity-50" viewBox="0 0 24 24" fill="none" stroke="var(--bronze)"
                    stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" />
                    <path d="M16 2v4M8 2v4M3 10h18" />
                    <path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01" />
                </svg>

                <p class="font-body text-[10px] tracking-[0.35em] uppercase mb-4"
                    style="color: var(--bronze-light);">Detalles del Evento</p>

                <p class="font-display text-2xl sm:text-3xl mb-1"
                    style="color: var(--charcoal); font-weight: 400;">Viernes 24 de Octubre</p>
                <p class="font-accent text-lg italic mb-6" style="color: var(--olive-light);">4:30 pm</p>

                <div class="mx-auto mb-6"
                    style="width: 40px; height: 1px; background: linear-gradient(90deg, transparent, var(--bronze-light), transparent);">
                </div>

                <a href="https://maps.app.goo.gl/x1WDacDi6BWHyx9H6" target="_blank" rel="noopener"
                    class="inline-flex items-center gap-2 font-body text-xs mb-1"
                    style="color: var(--olive);">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7Z" />
                        <circle cx="12" cy="9" r="2.5" />
                    </svg>
                    Carr. Querétaro-Tequisquiapan 707
                </a>
                <p class="font-body text-[11px] mb-8" style="color: var(--olive-light);">
                    Purísima de Cubos, 76295 Querétaro, Qro.</p>

                {{-- Calendar buttons --}}
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ $googleCalUrl }}" target="_blank" rel="noopener"
                        class="inline-flex items-center justify-center gap-2 px-5 py-3 font-body text-[11px] tracking-[0.2em] uppercase transition-all duration-300"
                        style="border: 1px solid var(--parchment); border-radius: 2px; color: var(--olive-light); background: transparent;"
                        onmouseover="this.style.borderColor='var(--sage)'; this.style.color='var(--olive)'"
                        onmouseout="this.style.borderColor='var(--parchment)'; this.style.color='var(--olive-light)'">
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        Google Calendar
                    </a>
                    <a href="{{ route('invitation.calendar', $invitation->magic_link_token) }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-3 font-body text-[11px] tracking-[0.2em] uppercase transition-all duration-300"
                        style="border: 1px solid var(--parchment); border-radius: 2px; color: var(--olive-light); background: transparent;"
                        onmouseover="this.style.borderColor='var(--sage)'; this.style.color='var(--olive)'"
                        onmouseout="this.style.borderColor='var(--parchment)'; this.style.color='var(--olive-light)'">
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <path d="M16 2v4M8 2v4M3 10h18" />
                            <path d="M8 14h.01M12 14h.01M8 18h.01" />
                        </svg>
                        Apple / iCal / Outlook
                    </a>
                </div>
            </div>
        </div>

        {{-- RSVP Form --}}
        <div class="max-w-xl mx-auto px-6 pb-20 anim-fade-up delay-4">
            <div class="relative"
                style="background: rgba(245,240,232,0.5); border: 1px solid var(--parchment); border-radius: 2px; padding: 2.5rem 2rem;">

                <h2 class="font-display text-2xl sm:text-3xl text-center tracking-[-0.01em] mb-2"
                    style="color: var(--charcoal); font-weight: 400;">Confirma tu Asistencia</h2>
                <div class="mx-auto mb-8"
                    style="width: 60px; height: 1px; background: linear-gradient(90deg, transparent, var(--bronze-light), transparent);">
                </div>

                @if ($invitation->status !== 'pending')
                    <div class="text-center py-6 mb-6"
                        style="background: {{ $invitation->status === 'confirmed' ? 'rgba(138,154,123,0.08)' : 'rgba(149,123,90,0.08)' }}; border: 1px solid {{ $invitation->status === 'confirmed' ? 'var(--sage)' : 'var(--bronze-light)' }}; border-radius: 2px;">
                        <div class="inline-flex items-center gap-2 mb-3">
                            @if ($invitation->status === 'confirmed')
                                <svg class="w-5 h-5" fill="none" stroke="var(--sage)" stroke-width="1.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="font-body text-sm font-medium tracking-wide uppercase"
                                    style="color: var(--olive);">Asistencia Confirmada</span>
                            @else
                                <span class="font-body text-sm font-medium tracking-wide uppercase"
                                    style="color: var(--bronze);">No podrás asistir</span>
                            @endif
                        </div>
                        <p class="font-body text-xs px-6" style="color: var(--olive-light);">Ya has respondido a esta
                            invitación. Si necesitas cambiar tu respuesta, completa el formulario nuevamente.</p>
                    </div>
                @endif

                <form action="{{ route('rsvp.store') }}" method="POST" id="rsvp-form">
                    @csrf
                    <input type="hidden" name="invitation_id" value="{{ $invitation->id }}">

                    {{-- Attending toggle --}}
                    <div class="mb-10">
                        <label class="block font-body text-xs tracking-[0.2em] uppercase mb-4"
                            style="color: var(--bronze);">¿Podrás acompañarnos?</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="attending" value="1" class="peer sr-only" required
                                    {{ old('attending') == '1' ? 'checked' : '' }}>
                                <div class="flex items-center justify-center gap-2 py-3.5 px-4 transition-all duration-300"
                                    style="border: 1px solid var(--parchment); border-radius: 2px; color: var(--olive-light);"
                                    onmouseover="this.style.borderColor='var(--sage)'"
                                    onmouseout="if(!this.previousElementSibling.checked) this.style.borderColor='var(--parchment)'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="font-body text-sm font-medium">Sí, asistiré</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="attending" value="0" class="peer sr-only"
                                    {{ old('attending') == '0' ? 'checked' : '' }}>
                                <div class="flex items-center justify-center gap-2 py-3.5 px-4 transition-all duration-300"
                                    style="border: 1px solid var(--parchment); border-radius: 2px; color: var(--olive-light);"
                                    onmouseover="this.style.borderColor='var(--bronze-light)'"
                                    onmouseout="if(!this.previousElementSibling.checked) this.style.borderColor='var(--parchment)'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span class="font-body text-sm font-medium">No podré</span>
                                </div>
                            </label>
                        </div>
                        @error('attending')
                            <p class="font-body text-xs mt-2" style="color: #a0522d;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Guest details --}}
                    <div class="space-y-6" id="guests-section">
                        <div class="flex items-center gap-4">
                            <div class="grow" style="height: 1px; background: var(--parchment);"></div>
                            <h3 class="font-body text-[10px] tracking-[0.3em] uppercase shrink-0"
                                style="color: var(--bronze);">Invitados — {{ $invitation->max_guests }}
                                {{ $invitation->max_guests > 1 ? 'lugares' : 'lugar' }}</h3>
                            <div class="grow" style="height: 1px; background: var(--parchment);"></div>
                        </div>

                        @foreach ($invitation->guests as $index => $guest)
                            <div class="p-5 space-y-4"
                                style="background: rgba(236,229,216,0.3); border: 1px solid rgba(227,218,206,0.6); border-radius: 2px;">
                                <input type="hidden" name="guests[{{ $index }}][id]"
                                    value="{{ $guest->id }}">

                                <div>
                                    <label class="block font-body text-[10px] tracking-[0.2em] uppercase mb-2"
                                        style="color: var(--bronze-light);">Nombre</label>
                                    <input type="text" name="guests[{{ $index }}][name]"
                                        value="{{ old("guests.{$index}.name", $guest->name) }}" class="wedding-input"
                                        placeholder="Nombre completo">
                                </div>

                                <div class="flex items-center gap-6">
                                    <label class="font-body text-[10px] tracking-[0.2em] uppercase"
                                        style="color: var(--bronze-light);">¿Asistirá?</label>
                                    <div class="flex items-center gap-4">
                                        <label class="flex items-center gap-1.5 cursor-pointer">
                                            <input type="radio" name="guests[{{ $index }}][attending]"
                                                value="1"
                                                class="w-3.5 h-3.5 border-2 appearance-none rounded-full checked:border-4 transition-all"
                                                style="border-color: var(--parchment); --tw-ring-color: transparent;"
                                                {{ old("guests.{$index}.attending", $guest->attending) == '1' ? 'checked' : '' }}>
                                            <span class="font-body text-sm" style="color: var(--charcoal-soft);">Sí</span>
                                        </label>
                                        <label class="flex items-center gap-1.5 cursor-pointer">
                                            <input type="radio" name="guests[{{ $index }}][attending]"
                                                value="0"
                                                class="w-3.5 h-3.5 border-2 appearance-none rounded-full checked:border-4 transition-all"
                                                style="border-color: var(--parchment); --tw-ring-color: transparent;"
                                                {{ old("guests.{$index}.attending") == '0' || (old("guests.{$index}.attending") === null && $guest->attending === false) ? 'checked' : '' }}>
                                            <span class="font-body text-sm" style="color: var(--charcoal-soft);">No</span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label class="block font-body text-[10px] tracking-[0.2em] uppercase mb-2"
                                        style="color: var(--bronze-light);">Restricciones alimentarias</label>
                                    <input type="text" name="guests[{{ $index }}][dietary_restrictions]"
                                        value="{{ old("guests.{$index}.dietary_restrictions", $guest->dietary_restrictions) }}"
                                        class="wedding-input" placeholder="Alergias, vegetariano, etc.">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($errors->any())
                        <div class="mt-6 p-4"
                            style="background: rgba(160,82,45,0.05); border: 1px solid rgba(160,82,45,0.2); border-radius: 2px;">
                            <ul class="font-body text-xs space-y-1" style="color: #a0522d;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <button type="submit"
                        class="mt-10 w-full font-body text-xs tracking-[0.3em] uppercase py-4 transition-all duration-300"
                        style="background: var(--charcoal); color: var(--cream); border: none; border-radius: 2px; letter-spacing: 0.3em;"
                        onmouseover="this.style.background='var(--charcoal-soft)'"
                        onmouseout="this.style.background='var(--charcoal)'">
                        Enviar Confirmación
                    </button>
                </form>
            </div>

            {{-- Bottom flourish --}}
            <div class="mt-12 text-center anim-fade-in delay-6">
                <svg class="mx-auto w-10 h-10 opacity-20" viewBox="0 0 48 48" fill="none" stroke="var(--bronze)"
                    stroke-width="0.5">
                    <path d="M24 4 C24 4 10 16 10 26 C10 34 16 40 24 44 C32 40 38 34 38 26 C38 16 24 4 24 4Z" />
                    <path d="M24 12 L24 36" />
                    <path d="M17 22 Q24 28 31 22" />
                    <path d="M18 30 Q24 35 30 30" />
                </svg>
            </div>
        </div>

        {{-- Bottom decorative border --}}
        <div class="absolute bottom-0 left-0 right-0 h-px"
            style="background: linear-gradient(90deg, transparent, var(--parchment) 20%, var(--bronze-light) 50%, var(--parchment) 80%, transparent);">
        </div>
    </div>

    <style>
        input[type="radio"].peer:checked+div {
            border-color: var(--olive) !important;
            background: rgba(138, 154, 123, 0.06);
            color: var(--olive);
        }

        input[type="radio"][value="0"].peer:checked+div {
            border-color: var(--bronze) !important;
            background: rgba(149, 123, 90, 0.06);
            color: var(--bronze);
        }

        input[type="radio"].appearance-none:checked {
            border-color: var(--olive) !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('input[type="radio"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    window.haptics?.trigger('selection');
                });
            });

            document.getElementById('rsvp-form').addEventListener('submit', function() {
                window.haptics?.trigger('medium');
            });

            @if ($errors->any())
                window.haptics?.trigger('error');
            @endif
        });
    </script>
@endsection
