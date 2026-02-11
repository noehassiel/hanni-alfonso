<?php

namespace App\Livewire\Admin;

use App\Models\Invitation;
use Livewire\Component;
use Livewire\WithPagination;

class InvitationsList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Invitation::with(['guests', 'primaryGuest'])
            ->when($this->search, function ($q) {
                $q->where('group_name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter !== 'all', function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->latest();

        return view('livewire.admin.invitations-list', [
            'invitations' => $query->paginate(15),
            'stats' => [
                'total' => Invitation::count(),
                'pending' => Invitation::pending()->count(),
                'confirmed' => Invitation::confirmed()->count(),
                'declined' => Invitation::declined()->count(),
                'total_guests_confirmed' => Invitation::confirmed()->get()->sum('confirmed_guests_count'),
            ],
        ]);
    }
}
