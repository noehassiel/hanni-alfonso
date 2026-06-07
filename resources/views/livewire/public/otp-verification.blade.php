<div class="min-h-screen flex items-center justify-center px-6 py-16 relative"
    style="background: radial-gradient(ellipse at 50% 0%, #ece5d8 0%, var(--ivory) 60%);">

    {{-- Top decorative border --}}
    <div class="absolute top-0 left-0 right-0 h-px"
        style="background: linear-gradient(90deg, transparent, var(--parchment) 20%, var(--bronze-light) 50%, var(--parchment) 80%, transparent);">
    </div>

    <div class="w-full max-w-md anim-fade-up">

        {{-- Header --}}
        <div class="text-center mb-10">
            <p class="font-body text-[10px] tracking-[0.4em] uppercase mb-4" style="color: var(--bronze-light);">
                Verificación
            </p>
            <h1 class="font-display text-3xl sm:text-4xl" style="color: var(--charcoal); font-weight: 400;">
                Hanni <span class="font-accent italic text-2xl sm:text-3xl" style="color: var(--bronze-glow);">&</span> Alfonso
            </h1>
            <div class="mx-auto mt-4" style="width: 40px; height: 1px; background: var(--bronze-light);"></div>
        </div>

        {{-- Card --}}
        <div class="relative py-10 px-8 sm:px-10"
            style="background: linear-gradient(135deg, rgba(245,240,232,0.7), rgba(236,229,216,0.5)); border: 1px solid var(--parchment); border-radius: 2px;">

            {{-- Corner ornaments --}}
            <div class="absolute top-3 left-3 w-5 h-5"
                style="border-top: 1px solid var(--bronze-light); border-left: 1px solid var(--bronze-light);"></div>
            <div class="absolute top-3 right-3 w-5 h-5"
                style="border-top: 1px solid var(--bronze-light); border-right: 1px solid var(--bronze-light);"></div>
            <div class="absolute bottom-3 left-3 w-5 h-5"
                style="border-bottom: 1px solid var(--bronze-light); border-left: 1px solid var(--bronze-light);"></div>
            <div class="absolute bottom-3 right-3 w-5 h-5"
                style="border-bottom: 1px solid var(--bronze-light); border-right: 1px solid var(--bronze-light);"></div>

            {{-- ── Step 1: Email ─────────────────────────────────────────────── --}}
            @if ($step === 'email')
                <div>
                    <p class="font-accent text-center text-lg italic mb-1" style="color: var(--olive-light);">
                        Bienvenido/a
                    </p>
                    <p class="font-display text-center text-2xl mb-6" style="color: var(--charcoal); font-weight: 400;">
                        {{ $invitation->group_name }}
                    </p>

                    <div class="mx-auto mb-8" style="width: 40px; height: 1px; background: linear-gradient(90deg, transparent, var(--bronze-light), transparent);"></div>

                    <p class="font-body text-sm text-center mb-8 leading-relaxed" style="color: var(--olive-light);">
                        Para acceder a tu invitación necesitamos verificar tu identidad.
                        Ingresa tu correo y te enviaremos un código.
                    </p>

                    <form wire:submit="sendOtp">
                        <div class="mb-6">
                            <label class="block font-body text-[10px] tracking-[0.2em] uppercase mb-3"
                                style="color: var(--bronze);">Correo electrónico</label>
                            <input
                                type="email"
                                wire:model="email"
                                class="wedding-input"
                                placeholder="tu@correo.com"
                                autocomplete="email"
                                autofocus>
                            @error('email')
                                <p class="font-body text-xs mt-2" style="color: #a0522d;">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-60 cursor-not-allowed"
                            class="w-full font-body text-xs tracking-[0.3em] uppercase py-4 transition-all duration-300"
                            style="background: var(--charcoal); color: var(--cream); border: none; border-radius: 2px;"
                            onmouseover="this.style.background='var(--charcoal-soft)'"
                            onmouseout="this.style.background='var(--charcoal)'">
                            <span wire:loading.remove>Enviar Código</span>
                            <span wire:loading>Enviando…</span>
                        </button>
                    </form>
                </div>
            @endif

            {{-- ── Step 2: OTP ────────────────────────────────────────────────── --}}
            @if ($step === 'otp')
                <div>
                    <p class="font-body text-sm text-center mb-1 leading-relaxed" style="color: var(--olive-light);">
                        Enviamos un código a
                    </p>
                    <p class="font-body text-sm text-center font-medium mb-8" style="color: var(--bronze);">
                        {{ $sentTo }}
                    </p>

                    <div class="mx-auto mb-8" style="width: 40px; height: 1px; background: linear-gradient(90deg, transparent, var(--bronze-light), transparent);"></div>

                    <form wire:submit="verifyOtp" x-data="otpInput()" x-init="init()">

                        {{-- 6-box OTP input --}}
                        <div class="flex justify-center gap-3 mb-8">
                            <template x-for="(digit, index) in digits" :key="index">
                                <input
                                    type="text"
                                    inputmode="numeric"
                                    maxlength="1"
                                    x-model="digits[index]"
                                    x-ref="digit"
                                    :data-index="index"
                                    x-on:input="handleInput(index, $event)"
                                    x-on:keydown="handleKeydown(index, $event)"
                                    x-on:paste.prevent="handlePaste($event)"
                                    x-on:focus="$event.target.select()"
                                    class="otp-box font-body text-xl text-center font-medium transition-all duration-200"
                                    style="width: 44px; height: 56px; border: 1px solid var(--parchment); border-radius: 2px; background: rgba(245,240,232,0.4); color: var(--charcoal); outline: none;">
                            </template>
                        </div>

                        @if ($otpError)
                            <p class="font-body text-xs text-center mb-4" style="color: #a0522d;">{{ $otpError }}</p>
                        @endif
                        @error('otp')
                            <p class="font-body text-xs text-center mb-4" style="color: #a0522d;">{{ $message }}</p>
                        @enderror

                        <button type="submit"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-60 cursor-not-allowed"
                            class="w-full font-body text-xs tracking-[0.3em] uppercase py-4 transition-all duration-300"
                            style="background: var(--charcoal); color: var(--cream); border: none; border-radius: 2px;"
                            onmouseover="this.style.background='var(--charcoal-soft)'"
                            onmouseout="this.style.background='var(--charcoal)'">
                            <span wire:loading.remove>Verificar Código</span>
                            <span wire:loading>Verificando…</span>
                        </button>
                    </form>

                    <p class="font-body text-xs text-center mt-6" style="color: var(--bronze-light);">
                        ¿No recibiste el código?
                        <button wire:click="resend" class="underline transition-colors" style="color: var(--bronze);"
                            onmouseover="this.style.color='var(--charcoal)'"
                            onmouseout="this.style.color='var(--bronze)'">
                            Intentar de nuevo
                        </button>
                    </p>
                </div>
            @endif

        </div>
    </div>

    {{-- Toast --}}
    <div
        x-data="toast()"
        x-on:toast.window="show($event.detail)"
        x-show="visible"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-cloak
        class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 px-6 py-3 font-body text-sm"
        style="background: var(--charcoal); color: var(--cream); border-radius: 2px; min-width: 260px; text-align: center; box-shadow: 0 4px 24px rgba(44,42,38,0.18);">
        <span x-text="message"></span>
    </div>

    <style>
        .otp-box:focus {
            border-color: var(--bronze) !important;
            background: rgba(245,240,232,0.8) !important;
        }
    </style>

    <script>
        function otpInput() {
            return {
                digits: ['', '', '', '', '', ''],
                get fullOtp() {
                    return this.digits.join('');
                },
                init() {
                    this.$nextTick(() => {
                        const first = this.$el.querySelector('input[data-index="0"]');
                        if (first) first.focus();
                    });
                },
                sync() {
                    this.$wire.set('otp', this.fullOtp);
                },
                handleInput(index, event) {
                    const val = event.target.value.replace(/\D/g, '');
                    this.digits[index] = val.slice(-1);
                    event.target.value = this.digits[index];
                    this.sync();
                    if (this.digits[index] && index < 5) {
                        this.$el.querySelector(`input[data-index="${index + 1}"]`).focus();
                    }
                },
                handleKeydown(index, event) {
                    if (event.key === 'Backspace' && !this.digits[index] && index > 0) {
                        this.$el.querySelector(`input[data-index="${index - 1}"]`).focus();
                    }
                },
                handlePaste(event) {
                    const text = (event.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
                    text.split('').forEach((char, i) => {
                        this.digits[i] = char;
                    });
                    this.sync();
                    const nextEmpty = Math.min(text.length, 5);
                    this.$nextTick(() => {
                        this.$el.querySelector(`input[data-index="${nextEmpty}"]`).focus();
                    });
                },
            };
        }

        function toast() {
            return {
                visible: false,
                message: '',
                timeout: null,
                show(detail) {
                    this.message = detail.message;
                    this.visible = true;
                    clearTimeout(this.timeout);
                    this.timeout = setTimeout(() => { this.visible = false; }, 4000);
                },
            };
        }
    </script>
</div>
