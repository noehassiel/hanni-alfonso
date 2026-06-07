@props([
    'position' => 'bottom-right',
    'maxToasts' => 5,
])

@php
    $positionClass = match($position) {
        'top-right'    => 'top-4 right-4',
        'top-left'     => 'top-4 left-4',
        'top-center'   => 'top-4 left-1/2 -translate-x-1/2',
        'bottom-left'  => 'bottom-4 left-4',
        'bottom-center'=> 'bottom-4 left-1/2 -translate-x-1/2',
        default        => 'bottom-4 right-4',
    };
@endphp

<div
    x-data="{
        toasts: [],
        maxToasts: {{ $maxToasts }},
        add(event) {
            if (this.toasts.length >= this.maxToasts) {
                this.toasts.shift();
            }
            const toast = {
                id: Date.now(),
                type: event.detail.type ?? 'info',
                content: event.detail.content ?? '',
                duration: event.detail.duration ?? 4000,
                progress: 100,
                paused: false,
                intervalId: null,
            };
            this.toasts.push(toast);
            this.startTimer(toast);
        },
        startTimer(toast) {
            const steps = 100;
            const interval = toast.duration / steps;
            toast.intervalId = setInterval(() => {
                if (!toast.paused) {
                    toast.progress -= 1;
                    if (toast.progress <= 0) {
                        this.remove(toast.id);
                    }
                }
            }, interval);
        },
        remove(id) {
            const toast = this.toasts.find(t => t.id === id);
            if (toast?.intervalId) clearInterval(toast.intervalId);
            this.toasts = this.toasts.filter(t => t.id !== id);
        },
        pause(id) {
            const toast = this.toasts.find(t => t.id === id);
            if (toast) toast.paused = true;
        },
        resume(id) {
            const toast = this.toasts.find(t => t.id === id);
            if (toast) toast.paused = false;
        },
        iconFor(type) {
            return {
                success: '<svg xmlns=\'http://www.w3.org/2000/svg\' class=\'w-5 h-5\' viewBox=\'0 0 20 20\' fill=\'currentColor\'><path fill-rule=\'evenodd\' d=\'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z\' clip-rule=\'evenodd\'/></svg>',
                error:   '<svg xmlns=\'http://www.w3.org/2000/svg\' class=\'w-5 h-5\' viewBox=\'0 0 20 20\' fill=\'currentColor\'><path fill-rule=\'evenodd\' d=\'M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z\' clip-rule=\'evenodd\'/></svg>',
                warning: '<svg xmlns=\'http://www.w3.org/2000/svg\' class=\'w-5 h-5\' viewBox=\'0 0 20 20\' fill=\'currentColor\'><path fill-rule=\'evenodd\' d=\'M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z\' clip-rule=\'evenodd\'/></svg>',
                info:    '<svg xmlns=\'http://www.w3.org/2000/svg\' class=\'w-5 h-5\' viewBox=\'0 0 20 20\' fill=\'currentColor\'><path fill-rule=\'evenodd\' d=\'M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z\' clip-rule=\'evenodd\'/></svg>',
            }[type] ?? '';
        },
        colorFor(type) {
            return {
                success: { bg: 'bg-green-50 border-green-200', icon: 'text-green-500', bar: 'bg-green-400', text: 'text-green-800' },
                error:   { bg: 'bg-red-50 border-red-200',     icon: 'text-red-500',   bar: 'bg-red-400',   text: 'text-red-800'   },
                warning: { bg: 'bg-yellow-50 border-yellow-200',icon: 'text-yellow-500',bar: 'bg-yellow-400',text: 'text-yellow-800'},
                info:    { bg: 'bg-blue-50 border-blue-200',    icon: 'text-blue-500',  bar: 'bg-blue-400',  text: 'text-blue-800'  },
            }[type] ?? { bg: 'bg-gray-50 border-gray-200', icon: 'text-gray-500', bar: 'bg-gray-400', text: 'text-gray-800' };
        }
    }"
    @notify.window="add($event)"
    @if(session('notify'))
    x-init="add({ detail: {{ Js::from(session('notify')) }} })"
    @endif
    class="fixed {{ $positionClass }} z-50 flex flex-col gap-2 w-80 pointer-events-none"
    aria-live="polite"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-2 scale-95"
            :class="colorFor(toast.type).bg"
            class="relative overflow-hidden rounded-lg border shadow-lg pointer-events-auto"
            @mouseenter="pause(toast.id)"
            @mouseleave="resume(toast.id)"
        >
            <div class="flex items-start gap-3 p-4">
                <span :class="colorFor(toast.type).icon" x-html="iconFor(toast.type)" class="shrink-0 mt-0.5"></span>
                <p :class="colorFor(toast.type).text" class="flex-1 text-sm font-medium" x-text="toast.content"></p>
                <button
                    @click="remove(toast.id)"
                    :class="colorFor(toast.type).icon"
                    class="shrink-0 opacity-60 hover:opacity-100 transition-opacity"
                    aria-label="Cerrar"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            <div class="h-0.5 w-full bg-black/10">
                <div
                    :class="colorFor(toast.type).bar"
                    class="h-full transition-all duration-100 ease-linear"
                    :style="`width: ${toast.progress}%`"
                ></div>
            </div>
        </div>
    </template>
</div>
