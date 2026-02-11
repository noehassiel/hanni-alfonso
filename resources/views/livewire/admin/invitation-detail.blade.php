<div>
    <div class="mb-6">
        <a href="{{ route('admin.dashboard') }}"
            class="text-sm text-gray-500 hover:text-gray-700 transition inline-flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Volver al listado
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $invitation->group_name }}</h2>
                        <p class="text-sm text-gray-500 mt-1">Código: {{ $invitation->access_code }}</p>
                    </div>
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'confirmed' => 'bg-green-100 text-green-800',
                            'declined' => 'bg-red-100 text-red-800',
                            'maybe' => 'bg-blue-100 text-blue-800',
                        ];
                        $statusLabels = [
                            'pending' => 'Pendiente',
                            'confirmed' => 'Confirmado',
                            'declined' => 'Declinado',
                            'maybe' => 'Tal vez',
                        ];
                    @endphp
                    <span
                        class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$invitation->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusLabels[$invitation->status] ?? $invitation->status }}
                    </span>
                </div>

                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="font-medium text-gray-500">Email</dt>
                        <dd class="text-gray-900 mt-0.5">{{ $invitation->email ?? 'No proporcionado' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Teléfono</dt>
                        <dd class="text-gray-900 mt-0.5">{{ $invitation->phone ?? 'No proporcionado' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Máx. Invitados</dt>
                        <dd class="text-gray-900 mt-0.5">{{ $invitation->max_guests }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Accesos</dt>
                        <dd class="text-gray-900 mt-0.5">{{ $invitation->access_count }} veces</dd>
                    </div>
                    @if ($invitation->confirmed_at)
                        <div>
                            <dt class="font-medium text-gray-500">Confirmado el</dt>
                            <dd class="text-gray-900 mt-0.5">{{ $invitation->confirmed_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    @endif
                    @if ($invitation->last_accessed_at)
                        <div>
                            <dt class="font-medium text-gray-500">Último acceso</dt>
                            <dd class="text-gray-900 mt-0.5">{{ $invitation->last_accessed_at->format('d/m/Y H:i') }}
                            </dd>
                        </div>
                    @endif
                </dl>

                @if ($invitation->personal_message)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-sm font-medium text-gray-500 mb-1">Mensaje Personal</p>
                        <p class="text-sm text-gray-700 italic">{{ $invitation->personal_message }}</p>
                    </div>
                @endif
            </div>

            {{-- Guests --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Invitados</h3>
                <div class="space-y-3">
                    @foreach ($invitation->guests as $guest)
                        <div wire:key="guest-detail-{{ $guest->id }}"
                            class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $guest->name ?? 'Sin nombre' }}
                                    @if ($guest->is_primary)
                                        <span
                                            class="ml-1 text-xs bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded">Principal</span>
                                    @endif
                                </p>
                                @if ($guest->dietary_restrictions)
                                    <p class="text-xs text-gray-500 mt-0.5">Dieta: {{ $guest->dietary_restrictions }}
                                    </p>
                                @endif
                            </div>
                            <div>
                                @if ($guest->attending === true)
                                    <span
                                        class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Asiste</span>
                                @elseif($guest->attending === false)
                                    <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full">No
                                        asiste</span>
                                @else
                                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full">Sin
                                        respuesta</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Sidebar Actions --}}
        <div class="space-y-6">
            {{-- Actions --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Acciones</h3>
                <div class="space-y-3">
                    <button wire:click="sendInvitation" wire:loading.attr="disabled"
                        class="w-full bg-amber-600 hover:bg-amber-700 disabled:opacity-50 text-white text-sm font-medium py-2 px-4 rounded-lg transition">
                        <span wire:loading.remove wire:target="sendInvitation">
                            {{ $invitation->invitation_sent_at ? 'Reenviar Invitación' : 'Enviar Invitación' }}
                        </span>
                        <span wire:loading wire:target="sendInvitation">Enviando...</span>
                    </button>

                    <button wire:click="copyMagicLink"
                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium py-2 px-4 rounded-lg transition">
                        Copiar Magic Link
                    </button>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500 mb-2">Magic Link:</p>
                    <p class="text-xs text-blue-600 break-all bg-gray-50 p-2 rounded">{{ $invitation->magic_link }}</p>
                </div>
            </div>

            {{-- WhatsApp Message --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Mensaje WhatsApp</h3>
                <div class="bg-gray-50 rounded-lg p-3 mb-3 overflow-hidden">
                    <p class="text-xs text-gray-700 whitespace-pre-wrap break-all font-sans leading-relaxed">{{ $this->getWhatsappMessage() }}</p>
                </div>
                <div class="flex gap-2">
                    <button wire:click="copyWhatsappMessage"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition inline-flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                        </svg>
                        Copiar Mensaje
                    </button>
                    <a href="https://wa.me/52{{ preg_replace('/[^0-9]/', '', $invitation->phone ?? '') }}?text={{ urlencode($this->getWhatsappMessage()) }}"
                        target="_blank"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium py-2 px-4 rounded-lg transition inline-flex items-center justify-center gap-1 {{ !$invitation->phone ? 'opacity-50 pointer-events-none' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        Abrir
                    </a>
                </div>
            </div>

            {{-- Notification Logs --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Historial de Notificaciones</h3>
                @if ($invitation->notificationLogs->count())
                    <div class="space-y-2">
                        @foreach ($invitation->notificationLogs->sortByDesc('created_at') as $log)
                            <div class="text-xs p-2 bg-gray-50 rounded">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="font-medium text-gray-700">{{ str_replace('_', ' ', ucfirst($log->notification_type)) }}</span>
                                    <span
                                        class="px-1.5 py-0.5 rounded text-[10px] font-medium {{ $log->status === 'sent' ? 'bg-green-100 text-green-700' : ($log->status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                        {{ ucfirst($log->status) }}
                                    </span>
                                </div>
                                <p class="text-gray-500 mt-1">{{ $log->created_at->format('d/m/Y H:i') }}</p>
                                @if ($log->error_message)
                                    <p class="text-red-500 mt-1">{{ $log->error_message }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-500">No hay notificaciones registradas.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Clipboard JS --}}
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('copy-to-clipboard', (data) => {
                navigator.clipboard.writeText(data.text).then(() => {
                    // Success handled by flash message
                });
            });
        });
    </script>
</div>
