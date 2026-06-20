<div class="max-w-xl mx-auto px-6 pb-20 anim-fade-up delay-4">

    {{-- Honeypot --}}
    <div aria-hidden="true" style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden;">
        <input type="text" wire:model="website" name="website" tabindex="-1" autocomplete="off">
    </div>

    {{-- Rate-limit error --}}
    @error('rate_limit')
        <div class="mb-6 p-4" style="background:rgba(196,78,10,0.05);border:1px solid rgba(196,78,10,0.2);border-radius:2px;">
            <p class="font-body text-xs" style="color:var(--autumn-sienna);">{{ $message }}</p>
        </div>
    @enderror

    {{-- Already responded banner --}}
    @if ($invitation->status !== 'pending')
        <div class="mb-6 text-center p-5"
            style="background:{{ $invitation->status === 'confirmed' ? 'rgba(138,154,123,0.08)' : 'rgba(196,78,10,0.06)' }};
                    border:1px solid {{ $invitation->status === 'confirmed' ? 'var(--sage)' : 'rgba(196,78,10,0.3)' }};
                    border-radius:2px;">
            <div class="inline-flex items-center gap-2 mb-2">
                @if ($invitation->status === 'confirmed')
                    <svg class="w-4 h-4" fill="none" stroke="var(--sage)" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="font-body text-xs font-medium tracking-widest uppercase"
                        style="color:var(--olive);">Asistencia confirmada</span>
                @else
                    <span class="font-body text-xs font-medium tracking-widest uppercase"
                        style="color:var(--autumn-sienna);">No podrás asistir</span>
                @endif
            </div>
            <p class="font-body text-xs px-4 leading-relaxed" style="color:var(--olive);">
                Ya has respondido. Puedes actualizar tu respuesta enviando el formulario nuevamente.
            </p>
        </div>
    @endif

    {{-- Card --}}
    <div class="relative"
        style="background:rgba(245,240,232,0.55);border:1px solid var(--parchment);border-radius:2px;padding:2.25rem 1.75rem;">

        <h2 class="font-display text-2xl sm:text-3xl text-center mb-2"
            style="color:var(--charcoal);font-weight:400;letter-spacing:-0.01em;">Confirma tu asistencia</h2>
        <div class="mx-auto mb-8"
            style="width:60px;height:1px;background:linear-gradient(90deg,transparent,var(--bronze-light),transparent);">
        </div>

        {{-- ── Main attending toggle ─────────────────────────── --}}
        <div class="mb-8" x-data="{ sel: '{{ $attending }}' }">
            <label class="block font-body text-[10px] tracking-[0.25em] uppercase mb-4"
                style="color:var(--bronze);">¿Podrás acompañarnos?</label>

            <div class="grid grid-cols-2 gap-3">

                {{-- Sí --}}
                <label class="relative cursor-pointer select-none" style="-webkit-tap-highlight-color:transparent;">
                    <input type="radio" wire:model="attending" value="1" class="sr-only"
                        @change="sel = $event.target.value; window.haptics?.trigger('selection')">
                    <div class="flex items-center justify-center gap-2 transition-all duration-250"
                        style="min-height:52px;border-radius:2px;padding:0.875rem 1rem;"
                        :style="sel === '1'
                            ?
                            'border:1px solid var(--olive);color:var(--olive);background:rgba(138,154,123,0.07);' :
                            'border:1px solid var(--parchment);color:var(--olive);'">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="font-body text-sm font-medium">Sí, asistiré</span>
                    </div>
                </label>

                {{-- No --}}
                <label class="relative cursor-pointer select-none" style="-webkit-tap-highlight-color:transparent;">
                    <input type="radio" wire:model="attending" value="0" class="sr-only"
                        @change="sel = $event.target.value; window.haptics?.trigger('selection')">
                    <div class="flex items-center justify-center gap-2 transition-all duration-250"
                        style="min-height:52px;border-radius:2px;padding:0.875rem 1rem;"
                        :style="sel === '0'
                            ?
                            'border:1px solid var(--autumn-sienna);color:var(--autumn-sienna);background:rgba(196,78,10,0.05);' :
                            'border:1px solid var(--parchment);color:var(--olive);'">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span class="font-body text-sm font-medium">No podré</span>
                    </div>
                </label>
            </div>

            @error('attending')
                <p class="font-body text-xs mt-2" style="color:var(--autumn-sienna);">{{ $message }}</p>
            @enderror
        </div>

        {{-- ── Guest list ────────────────────────────────────── --}}
        <div class="space-y-5">
            <div class="flex items-center gap-4">
                <div class="grow" style="height:1px;background:var(--parchment);"></div>
                <h3 class="font-body text-[10px] tracking-[0.3em] uppercase shrink-0" style="color:var(--bronze);">
                    Invitados &mdash; {{ $invitation->max_guests }}
                    {{ $invitation->max_guests > 1 ? 'lugares' : 'lugar' }}
                </h3>
                <div class="grow" style="height:1px;background:var(--parchment);"></div>
            </div>

            <p style="margin: 20px 0; font-size:16px; font-weight:300; text-align:center">
                Debido a que el evento es de cupo limitado, <strong>NO</strong> se podrá añadir ningún pase adicional a
                los marcados
                en tu invitación.
            </p>

            @foreach ($guests as $i => $guest)
                <div class="p-5 space-y-5" wire:key="guest-{{ $guest['id'] }}"
                    style="background:rgba(236,229,216,0.35);border:1px solid rgba(227,218,206,0.65);border-radius:2px;">

                    {{-- Guest label --}}
                    <p class="font-body text-[9px] tracking-[0.3em] uppercase" style="color:var(--olive);">
                        @if ($i === 0)
                            Titular de la invitación
                        @else
                            Acompañante {{ $i }}
                        @endif
                    </p>

                    {{-- Name --}}
                    <div>
                        <label class="block font-body text-[10px] tracking-[0.2em] uppercase mb-2"
                            style="color:var(--olive);">Nombre completo</label>
                        <input type="text" wire:model="guests.{{ $i }}.name" class="wedding-input"
                            placeholder="Nombre completo" autocomplete="name">
                        @error('guests.' . $i . '.name')
                            <p class="font-body text-xs mt-1" style="color:var(--autumn-sienna);">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Per-guest attending — hidden when main toggle is No --}}
                    <div x-show="$wire.attending == 1" x-transition.opacity.duration.200ms>
                        <label class="block font-body text-[10px] tracking-[0.2em] uppercase mb-3"
                            style="color:var(--olive);">¿Asistirá?</label>

                        <div x-data="{ guestAtt: {{ (int) ($guest['attending'] ?? 1) }} }" class="flex gap-6">

                            {{-- Sí radio --}}
                            <label class="flex items-center gap-2.5 cursor-pointer select-none"
                                style="min-height:44px;-webkit-tap-highlight-color:transparent;">
                                <input type="radio" wire:model="guests.{{ $i }}.attending"
                                    value="1" class="sr-only"
                                    @change="guestAtt = 1; window.haptics?.trigger('selection')">
                                <div class="relative rounded-full border-2 shrink-0 transition-all duration-200 flex items-center justify-center"
                                    style="width:20px;height:20px;"
                                    :style="guestAtt === 1 ?
                                        'border-color:var(--olive);' :
                                        'border-color:var(--parchment);'">
                                    <div class="rounded-full transition-all duration-200"
                                        style="width:9px;height:9px;background:var(--olive);"
                                        :style="guestAtt === 1 ? 'opacity:1;transform:scale(1)' : 'opacity:0;transform:scale(0)'">
                                    </div>
                                </div>
                                <span class="font-body text-sm" style="color:var(--charcoal-soft);">Sí</span>
                            </label>

                            {{-- No radio --}}
                            <label class="flex items-center gap-2.5 cursor-pointer select-none"
                                style="min-height:44px;-webkit-tap-highlight-color:transparent;">
                                <input type="radio" wire:model="guests.{{ $i }}.attending"
                                    value="0" class="sr-only"
                                    @change="guestAtt = 0; window.haptics?.trigger('selection')">
                                <div class="relative rounded-full border-2 shrink-0 transition-all duration-200 flex items-center justify-center"
                                    style="width:20px;height:20px;"
                                    :style="guestAtt === 0 ?
                                        'border-color:var(--bronze);' :
                                        'border-color:var(--parchment);'">
                                    <div class="rounded-full transition-all duration-200"
                                        style="width:9px;height:9px;background:var(--bronze);"
                                        :style="guestAtt === 0 ? 'opacity:1;transform:scale(1)' : 'opacity:0;transform:scale(0)'">
                                    </div>
                                </div>
                                <span class="font-body text-sm" style="color:var(--charcoal-soft);">No</span>
                            </label>
                        </div>

                        @error('guests.' . $i . '.attending')
                            <p class="font-body text-xs mt-1" style="color:var(--autumn-sienna);">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Not attending message --}}
                    <div x-show="$wire.attending == 0" x-transition.opacity.duration.200ms>
                        <p class="font-body text-xs italic" style="color:var(--olive);">
                            Marcado como no asistente
                        </p>
                    </div>

                    {{-- Dietary --}}
                    <div>
                        <label class="block font-body text-[10px] tracking-[0.2em] uppercase mb-2"
                            style="color:var(--olive);">Restricciones alimentarias</label>
                        <input type="text" wire:model="guests.{{ $i }}.dietary_restrictions"
                            class="wedding-input" placeholder="Alergias, vegetariano, etc." autocomplete="off">
                        @error('guests.' . $i . '.dietary_restrictions')
                            <p class="font-body text-xs mt-1" style="color:var(--autumn-sienna);">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            @endforeach
        </div>

        {{-- General errors (excluding per-field, already shown inline) --}}
        @if ($errors->has('attending'))
            <div class="mt-6 p-4"
                style="background:rgba(196,78,10,0.05);border:1px solid rgba(196,78,10,0.2);border-radius:2px;">
                <p class="font-body text-xs" style="color:var(--autumn-sienna);">
                    {{ $errors->first('attending') }}
                </p>
            </div>
        @endif

        {{-- Submit --}}
        <button type="button" wire:click="submit" wire:loading.attr="disabled"
            class="mt-10 w-full font-body text-xs tracking-[0.3em] uppercase transition-all duration-300 flex items-center justify-center gap-2"
            style="background:var(--autumn-burgundy);color:var(--cream);border:none;border-radius:2px;
                       min-height:52px;letter-spacing:0.3em;cursor:pointer;"
            x-data @mouseenter="$el.style.background='#9a2424'"
            @mouseleave="$el.style.background='var(--autumn-burgundy)'" wire:loading.class="opacity-50 cursor-not-allowed">
            <span wire:loading.remove wire:target="submit">Confirmar asistencia</span>
            <span wire:loading wire:target="submit" class="flex items-center gap-2">
                <svg class="w-3.5 h-3.5 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path
                        d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" />
                </svg>
                Enviando...
            </span>
        </button>

    </div>

    {{-- Bottom flourish --}}
    <div class="mt-12 text-center">
        <img src="{{ asset('img/wax-seal-removebg-preview.png') }}" class="mx-auto w-20 h-20" alt=""
            aria-hidden="true">
    </div>

</div>
