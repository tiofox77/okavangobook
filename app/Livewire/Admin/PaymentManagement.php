<?php

namespace App\Livewire\Admin;

use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentManagement extends Component
{
    use WithPagination;

    public string $statusFilter = '';
    public string $search = '';
    public string $dateFilter = '';

    // Approve/Reject modal
    public bool $showActionModal = false;
    public ?int $selectedPaymentId = null;
    public string $actionType = ''; // approve or reject
    public string $adminNotes = '';
    public string $rejectionReason = '';

    // Detail modal
    public bool $showDetailModal = false;
    public ?int $detailPaymentId = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function openAction(int $paymentId, string $type)
    {
        $this->selectedPaymentId = $paymentId;
        $this->actionType = $type;
        $this->adminNotes = '';
        $this->rejectionReason = '';
        $this->showActionModal = true;
    }

    public function confirmAction()
    {
        $payment = PaymentTransaction::findOrFail($this->selectedPaymentId);
        $adminId = Auth::id();

        if ($this->actionType === 'approve') {
            $payment->approve($adminId, $this->adminNotes ?: null);
            session()->flash('message', 'Pagamento aprovado! Subscrição activada para ' . $payment->user->name . '.');
        } elseif ($this->actionType === 'reject') {
            $this->validate([
                'rejectionReason' => 'required|string|min:5',
            ], [
                'rejectionReason.required' => 'O motivo da rejeição é obrigatório.',
                'rejectionReason.min' => 'O motivo deve ter pelo menos 5 caracteres.',
            ]);
            $payment->reject($adminId, $this->rejectionReason, $this->adminNotes ?: null);
            session()->flash('message', 'Pagamento rejeitado.');
        }

        $this->showActionModal = false;
        $this->selectedPaymentId = null;
    }

    public function openDetail(int $paymentId)
    {
        $this->detailPaymentId = $paymentId;
        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->detailPaymentId = null;
    }

    public function render()
    {
        $query = PaymentTransaction::with(['user', 'plan', 'approvedByUser'])
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->search, fn($q) => $q->where(function ($q2) {
                $q2->where('reference_code', 'like', '%' . $this->search . '%')
                   ->orWhereHas('user', fn($q3) => $q3->where('name', 'like', '%' . $this->search . '%')->orWhere('email', 'like', '%' . $this->search . '%'));
            }))
            ->when($this->dateFilter, function ($q) {
                return match($this->dateFilter) {
                    'today' => $q->whereDate('created_at', today()),
                    'week' => $q->where('created_at', '>=', now()->subWeek()),
                    'month' => $q->where('created_at', '>=', now()->subMonth()),
                    default => $q,
                };
            })
            ->latest();

        $payments = $query->paginate(15);

        $stats = [
            'pending' => PaymentTransaction::pending()->count(),
            'approved' => PaymentTransaction::approved()->count(),
            'rejected' => PaymentTransaction::rejected()->count(),
            'total_revenue' => PaymentTransaction::approved()->sum('amount'),
        ];

        $detailPayment = $this->detailPaymentId ? PaymentTransaction::with(['user', 'plan', 'subscription', 'approvedByUser'])->find($this->detailPaymentId) : null;

        return view('livewire.admin.payment-management', [
            'payments' => $payments,
            'stats' => $stats,
            'detailPayment' => $detailPayment,
        ])->layout('layouts.admin');
    }
}
