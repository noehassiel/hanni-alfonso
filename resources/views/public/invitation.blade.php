@extends('layouts.app')

@section('content')
<div class="min-h-screen relative" style="background: radial-gradient(ellipse at 50% 0%, #ece5d8 0%, var(--ivory) 60%);">

    {{-- Top decorative border --}}
    <div class="absolute top-0 left-0 right-0 h-px" style="background: linear-gradient(90deg, transparent, var(--parchment) 20%, var(--bronze-light) 50%, var(--parchment) 80%, transparent);"></div>

    {{-- Floating botanical decoration --}}
    <svg class="absolute top-16 right-6 sm:right-16 w-24 sm:w-32 opacity-[0.06]" viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="0.8" style="color: var(--olive); animation: floatGentle 9s ease-in-out infinite;">
        <path d="M100 180 C100 180 60 140 60 100 C60 60 100 20 100 20 C100 20 140 60 140 100 C140 140 100 180 100 180Z"/>
        <path d="M100 20 L100 180"/>
        <path d="M72 60 Q100 80 128 60"/>
        <path d="M65 90 Q100 110 135 90"/>
        <path d="M68 120 Q100 140 132 120"/>
    </svg>

    {{-- Header --}}
    <div class="text-center pt-14 sm:pt-20 pb-6 sm:pb-10 px-6">
        <p class="font-body text-[10px] sm:text-xs tracking-[0.4em] uppercase anim-fade-up delay-1" style="color: var(--bronze-light);">
            Celebra con nosotros
        </p>

        <div class="mx-auto mt-5 mb-6 anim-line delay-2" style="width: 48px; height: 1px; background: var(--bronze-light);"></div>

        <h1 class="font-display text-4xl sm:text-5xl md:text-6xl leading-[0.9] tracking-[-0.02em] anim-fade-up delay-2" style="color: var(--charcoal); font-weight: 400;">
            Hanni <span class="font-accent italic text-3xl sm:text-4xl md:text-5xl" style="color: var(--bronze-glow);">&</span> Alfonso
        </h1>

        <div class="flex items-center justify-center gap-4 mt-5 anim-fade-up delay-3">
            <div style="width: 32px; height: 0.5px; background: var(--parchment);"></div>
            <p class="font-accent text-base sm:text-lg italic" style="color: var(--olive-light); font-weight: 300;">24 de Octubre, 2025</p>
            <div style="width: 32px; height: 0.5px; background: var(--parchment);"></div>
        </div>
    </div>

    {{-- Personal greeting card --}}
    <div class="max-w-xl mx-auto px-6 pb-8 sm:pb-10 anim-fade-up delay-3">
        <div class="relative text-center py-10 px-8" style="background: linear-gradient(135deg, rgba(245,240,232,0.6), rgba(236,229,216,0.4)); border: 1px solid var(--parchment); border-radius: 2px;">
            {{-- Corner ornaments --}}
            <div class="absolute top-3 left-3 w-5 h-5" style="border-top: 1px solid var(--bronze-light); border-left: 1px solid var(--bronze-light);"></div>
            <div class="absolute top-3 right-3 w-5 h-5" style="border-top: 1px solid var(--bronze-light); border-right: 1px solid var(--bronze-light);"></div>
            <div class="absolute bottom-3 left-3 w-5 h-5" style="border-bottom: 1px solid var(--bronze-light); border-left: 1px solid var(--bronze-light);"></div>
            <div class="absolute bottom-3 right-3 w-5 h-5" style="border-bottom: 1px solid var(--bronze-light); border-right: 1px solid var(--bronze-light);"></div>

            <p class="font-accent text-lg italic" style="color: var(--olive-light);">Querido/a</p>
            <p class="font-display text-3xl sm:text-4xl mt-2" style="color: var(--charcoal); font-weight: 500;">{{ $invitation->group_name }}</p>

            @if($invitation->personal_message)
                <div class="mx-auto mt-6" style="width: 40px; height: 1px; background: linear-gradient(90deg, transparent, var(--bronze-light), transparent);"></div>
                <p class="font-accent text-base italic mt-6 leading-relaxed" style="color: var(--olive-light);">{{ $invitation->personal_message }}</p>
            @endif
        </div>
    </div>

    {{-- RSVP Form --}}
    <div class="max-w-xl mx-auto px-6 pb-20 anim-fade-up delay-4">
        <div class="relative" style="background: rgba(245,240,232,0.5); border: 1px solid var(--parchment); border-radius: 2px; padding: 2.5rem 2rem;">

            <h2 class="font-display text-2xl sm:text-3xl text-center tracking-[-0.01em] mb-2" style="color: var(--charcoal); font-weight: 400;">Confirma tu Asistencia</h2>
            <div class="mx-auto mb-8" style="width: 60px; height: 1px; background: linear-gradient(90deg, transparent, var(--bronze-light), transparent);"></div>

            @if($invitation->status !== 'pending')
                <div class="text-center py-6 mb-6" style="background: {{ $invitation->status === 'confirmed' ? 'rgba(138,154,123,0.08)' : 'rgba(149,123,90,0.08)' }}; border: 1px solid {{ $invitation->status === 'confirmed' ? 'var(--sage)' : 'var(--bronze-light)' }}; border-radius: 2px;">
                    <div class="inline-flex items-center gap-2 mb-3">
                        @if($invitation->status === 'confirmed')
                            <svg class="w-5 h-5" fill="none" stroke="var(--sage)" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span class="font-body text-sm font-medium tracking-wide uppercase" style="color: var(--olive);">Asistencia Confirmada</span>
                        @else
                            <span class="font-body text-sm font-medium tracking-wide uppercase" style="color: var(--bronze);">No podrás asistir</span>
                        @endif
                    </div>
                    <p class="font-body text-xs px-6" style="color: var(--olive-light);">Ya has respondido a esta invitación. Si necesitas cambiar tu respuesta, completa el formulario nuevamente.</p>
                </div>
            @endif

            <form action="{{ route('rsvp.store') }}" method="POST" id="rsvp-form">
                @csrf
                <input type="hidden" name="invitation_id" value="{{ $invitation->id }}">

                {{-- Attending toggle --}}
                <div class="mb-10">
                    <label class="block font-body text-xs tracking-[0.2em] uppercase mb-4" style="color: var(--bronze);">¿Podrás acompañarnos?</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="attending" value="1" class="peer sr-only" required {{ old('attending') == '1' ? 'checked' : '' }}>
                            <div class="flex items-center justify-center gap-2 py-3.5 px-4 transition-all duration-300" style="border: 1px solid var(--parchment); border-radius: 2px; color: var(--olive-light);"
                                 onmouseover="this.style.borderColor='var(--sage)'"
                                 onmouseout="if(!this.previousElementSibling.checked) this.style.borderColor='var(--parchment)'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <span class="font-body text-sm font-medium">Sí, asistiré</span>
                            </div>
                        </label>
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="attending" value="0" class="peer sr-only" {{ old('attending') == '0' ? 'checked' : '' }}>
                            <div class="flex items-center justify-center gap-2 py-3.5 px-4 transition-all duration-300" style="border: 1px solid var(--parchment); border-radius: 2px; color: var(--olive-light);"
                                 onmouseover="this.style.borderColor='var(--bronze-light)'"
                                 onmouseout="if(!this.previousElementSibling.checked) this.style.borderColor='var(--parchment)'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
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
                        <h3 class="font-body text-[10px] tracking-[0.3em] uppercase shrink-0" style="color: var(--bronze);">Invitados — {{ $invitation->max_guests }} {{ $invitation->max_guests > 1 ? 'lugares' : 'lugar' }}</h3>
                        <div class="grow" style="height: 1px; background: var(--parchment);"></div>
                    </div>

                    @foreach($invitation->guests as $index => $guest)
                        <div class="p-5 space-y-4" style="background: rgba(236,229,216,0.3); border: 1px solid rgba(227,218,206,0.6); border-radius: 2px;">
                            <input type="hidden" name="guests[{{ $index }}][id]" value="{{ $guest->id }}">

                            <div>
                                <label class="block font-body text-[10px] tracking-[0.2em] uppercase mb-2" style="color: var(--bronze-light);">Nombre</label>
                                <input type="text" name="guests[{{ $index }}][name]" value="{{ old("guests.{$index}.name", $guest->name) }}"
                                    class="wedding-input"
                                    placeholder="Nombre completo">
                            </div>

                            <div class="flex items-center gap-6">
                                <label class="font-body text-[10px] tracking-[0.2em] uppercase" style="color: var(--bronze-light);">¿Asistirá?</label>
                                <div class="flex items-center gap-4">
                                    <label class="flex items-center gap-1.5 cursor-pointer">
                                        <input type="radio" name="guests[{{ $index }}][attending]" value="1"
                                            class="w-3.5 h-3.5 border-2 appearance-none rounded-full checked:border-4 transition-all"
                                            style="border-color: var(--parchment); --tw-ring-color: transparent;"
                                            {{ old("guests.{$index}.attending", $guest->attending) == '1' ? 'checked' : '' }}>
                                        <span class="font-body text-sm" style="color: var(--charcoal-soft);">Sí</span>
                                    </label>
                                    <label class="flex items-center gap-1.5 cursor-pointer">
                                        <input type="radio" name="guests[{{ $index }}][attending]" value="0"
                                            class="w-3.5 h-3.5 border-2 appearance-none rounded-full checked:border-4 transition-all"
                                            style="border-color: var(--parchment); --tw-ring-color: transparent;"
                                            {{ old("guests.{$index}.attending") == '0' || (old("guests.{$index}.attending") === null && $guest->attending === false) ? 'checked' : '' }}>
                                        <span class="font-body text-sm" style="color: var(--charcoal-soft);">No</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block font-body text-[10px] tracking-[0.2em] uppercase mb-2" style="color: var(--bronze-light);">Restricciones alimentarias</label>
                                <input type="text" name="guests[{{ $index }}][dietary_restrictions]" value="{{ old("guests.{$index}.dietary_restrictions", $guest->dietary_restrictions) }}"
                                    class="wedding-input"
                                    placeholder="Alergias, vegetariano, etc.">
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($errors->any())
                    <div class="mt-6 p-4" style="background: rgba(160,82,45,0.05); border: 1px solid rgba(160,82,45,0.2); border-radius: 2px;">
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

<style>
    input[type="radio"].peer:checked + div {
        border-color: var(--olive) !important;
        background: rgba(138,154,123,0.06);
        color: var(--olive);
    }
    input[type="radio"][value="0"].peer:checked + div {
        border-color: var(--bronze) !important;
        background: rgba(149,123,90,0.06);
        color: var(--bronze);
    }
    input[type="radio"].appearance-none:checked {
        border-color: var(--olive) !important;
    }
</style>
@endsection
