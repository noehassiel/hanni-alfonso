<div>
    <div class="bg-white rounded-xl border border-gray-200 p-6 max-w-2xl">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Nueva Invitación</h2>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700 transition">
                Cancelar
            </a>
        </div>

        <form wire:submit="save" class="space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Grupo / Familia</label>
                    <input wire:model="group_name" type="text" required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition"
                        placeholder="Ej: Familia García">
                    @error('group_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input wire:model="email" type="email"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition"
                        placeholder="email@ejemplo.com">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input wire:model="phone" type="text"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition"
                        placeholder="+52 555 123 4567">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número de Invitados</label>
                    <input wire:model.live="max_guests" type="number" min="1" max="20" required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                    @error('max_guests') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje Personal (Opcional)</label>
                <textarea wire:model="personal_message" rows="3"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition resize-none"
                    placeholder="Un mensaje especial para este invitado..."></textarea>
            </div>

            {{-- Guest Names --}}
            <div class="border-t border-gray-100 pt-5">
                <h3 class="text-sm font-medium text-gray-700 mb-3">Nombres de los Invitados</h3>
                <div class="space-y-3">
                    @foreach($guests as $index => $guest)
                        <div wire:key="guest-{{ $index }}" class="flex items-center gap-3">
                            <span class="text-xs text-gray-400 w-6">{{ $index + 1 }}.</span>
                            <input wire:model="guests.{{ $index }}.name" type="text"
                                class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition"
                                placeholder="{{ $index === 0 ? 'Invitado principal' : 'Acompañante ' . $index }}">
                            @if($guest['is_primary'])
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">Principal</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Send immediately option --}}
            <div class="flex items-center gap-2 pt-2">
                <input wire:model="send_immediately" type="checkbox" id="send_immediately"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="send_immediately" class="text-sm text-gray-700">Enviar invitación por email inmediatamente</label>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.dashboard') }}"
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-6 py-2 text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 rounded-lg transition">
                    Crear Invitación
                </button>
            </div>
        </form>
    </div>
</div>
