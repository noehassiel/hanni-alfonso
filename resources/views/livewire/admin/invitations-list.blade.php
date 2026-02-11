<div>
    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total</p>
            <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-yellow-200 p-4">
            <p class="text-xs font-medium text-yellow-600 uppercase tracking-wide">Pendientes</p>
            <p class="text-2xl font-semibold text-yellow-700 mt-1">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-green-200 p-4">
            <p class="text-xs font-medium text-green-600 uppercase tracking-wide">Confirmados</p>
            <p class="text-2xl font-semibold text-green-700 mt-1">{{ $stats['confirmed'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-red-200 p-4">
            <p class="text-xs font-medium text-red-600 uppercase tracking-wide">Declinados</p>
            <p class="text-2xl font-semibold text-red-700 mt-1">{{ $stats['declined'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-blue-200 p-4">
            <p class="text-xs font-medium text-blue-600 uppercase tracking-wide">Invitados Confirmados</p>
            <p class="text-2xl font-semibold text-blue-700 mt-1">{{ $stats['total_guests_confirmed'] }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <div class="flex-1">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por nombre o email..."
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
        </div>
        <select wire:model.live="statusFilter"
            class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
            <option value="all">Todos los estados</option>
            <option value="pending">Pendientes</option>
            <option value="confirmed">Confirmados</option>
            <option value="declined">Declinados</option>
            <option value="maybe">Tal vez</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-4 py-3 font-medium text-gray-600">Grupo</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-600">Email</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-600">Invitados</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-600">Estado</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-600">Enviada</th>
                        <th class="text-right px-4 py-3 font-medium text-gray-600">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($invitations as $invitation)
                        <tr wire:key="inv-{{ $invitation->id }}" class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">{{ $invitation->group_name }}</div>
                                @if($invitation->primaryGuest)
                                    <div class="text-xs text-gray-500">{{ $invitation->primaryGuest->name }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $invitation->email ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-gray-900">{{ $invitation->confirmed_guests_count }}</span>
                                <span class="text-gray-400">/</span>
                                <span class="text-gray-500">{{ $invitation->max_guests }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
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
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$invitation->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$invitation->status] ?? $invitation->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($invitation->invitation_sent_at)
                                    <span class="text-green-600 text-xs">{{ $invitation->invitation_sent_at->format('d/m/Y') }}</span>
                                @else
                                    <span class="text-gray-400 text-xs">No enviada</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.dashboard') }}?view=detail&id={{ $invitation->id }}"
                                   class="text-blue-600 hover:text-blue-800 text-xs font-medium transition">
                                    Ver detalle
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                                No se encontraron invitaciones.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invitations->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $invitations->links() }}
            </div>
        @endif
    </div>
</div>
