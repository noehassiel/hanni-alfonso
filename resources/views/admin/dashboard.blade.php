@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
        <a href="{{ route('admin.dashboard') }}?view=create"
           class="inline-flex items-center gap-2 bg-gray-900 hover:bg-gray-800 text-white font-medium py-2 px-4 rounded-lg transition-colors text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nueva Invitación
        </a>
    </div>

    @if(request('view') === 'create')
        @livewire('admin.create-invitation')
    @elseif(request('view') === 'detail' && request('id'))
        @livewire('admin.invitation-detail', ['invitation' => App\Models\Invitation::findOrFail(request('id'))])
    @else
        @livewire('admin.invitations-list')
    @endif
@endsection
