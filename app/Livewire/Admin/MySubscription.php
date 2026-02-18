<?php

namespace App\Livewire\Admin;

use App\Models\Plan;
use App\Models\PaymentTransaction;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class MySubscription extends Component
{
    use WithFileUploads;

    public bool $showCancelModal = false;
    public string $cancellationReason = '';

    // Upgrade with transfer
    public bool $showUpgradeModal = false;
    public ?int $upgradePlanId = null;
    public string $upgradeCycle = 'monthly';

    // Bank transfer form
    public bool $showTransferModal = false;
    public string $bankName = '';
    public string $accountHolder = '';
    public string $transferReference = '';
    public ?string $transferDate = null;
    public string $userNotes = '';
    public $proofFile = null;

    // Success
    public bool $showSuccessModal = false;
    public ?string $paymentRefCode = null;

    public function cancelSubscription()
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription;

        if ($subscription) {
            $subscription->cancel($this->cancellationReason ?: null);
            session()->flash('message', 'Subscrição cancelada com sucesso. O plano permanece activo até ao final do período.');
        }

        $this->showCancelModal = false;
        $this->cancellationReason = '';
    }

    public function openUpgradeModal(int $planId)
    {
        $user = Auth::user();
        if ($user->hasPendingPayment()) {
            session()->flash('error', 'Já tem um pagamento pendente. Aguarde a validação.');
            return;
        }
        $this->upgradePlanId = $planId;
        $this->upgradeCycle = 'monthly';
        $this->showUpgradeModal = true;
    }

    public function confirmUpgrade()
    {
        $user = Auth::user();
        $plan = Plan::findOrFail($this->upgradePlanId);

        // Free plan → activate immediately
        if ($plan->is_free) {
            $user->subscribeToPlan($plan);
            if (!$user->hasRole('Propriedade') && !$user->hasRole('Admin')) {
                $user->assignRole('Propriedade');
            }
            $this->showUpgradeModal = false;
            session()->flash('message', 'Plano Gratuito activado com sucesso!');
            return;
        }

        // Paid plan → open transfer form
        $this->showUpgradeModal = false;
        $this->resetTransferForm();
        $this->showTransferModal = true;
    }

    public function submitTransfer()
    {
        $this->validate([
            'bankName' => 'required|string|max:100',
            'accountHolder' => 'required|string|max:150',
            'transferReference' => 'required|string|max:100',
            'transferDate' => 'required|date|before_or_equal:today',
            'proofFile' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'bankName.required' => 'O nome do banco é obrigatório.',
            'accountHolder.required' => 'O nome do titular é obrigatório.',
            'transferReference.required' => 'A referência da transferência é obrigatória.',
            'transferDate.required' => 'A data da transferência é obrigatória.',
            'transferDate.before_or_equal' => 'A data não pode ser no futuro.',
            'proofFile.required' => 'O comprovativo de transferência é obrigatório.',
            'proofFile.mimes' => 'O ficheiro deve ser JPG, PNG ou PDF.',
            'proofFile.max' => 'O ficheiro não pode exceder 5MB.',
        ]);

        $plan = Plan::findOrFail($this->upgradePlanId);
        $user = Auth::user();

        // Guardar comprovativo na pasta do utilizador
        $proofPath = null;
        if ($this->proofFile) {
            $proofPath = $this->proofFile->store('payment-proofs/user-' . $user->id, 'public');
        }

        $payment = $user->createPaymentRequest($plan, $this->upgradeCycle, [
            'payment_method' => 'bank_transfer',
            'bank_name' => $this->bankName,
            'account_holder' => $this->accountHolder,
            'transfer_reference' => $this->transferReference,
            'transfer_date' => $this->transferDate,
            'user_notes' => $this->userNotes ?: null,
            'proof_file' => $proofPath,
        ]);

        $this->showTransferModal = false;
        $this->paymentRefCode = $payment->reference_code;
        $this->showSuccessModal = true;
    }

    public function closeAllModals()
    {
        $this->showCancelModal = false;
        $this->showUpgradeModal = false;
        $this->showTransferModal = false;
        $this->showSuccessModal = false;
    }

    public function toggleAutoRenew()
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription;

        if ($subscription) {
            $subscription->update(['auto_renew' => !$subscription->auto_renew]);
        }
    }

    private function resetTransferForm()
    {
        $this->bankName = '';
        $this->accountHolder = '';
        $this->transferReference = '';
        $this->transferDate = null;
        $this->userNotes = '';
        $this->proofFile = null;
    }

    public function render()
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription;
        $plan = $subscription?->plan;
        $allPlans = Plan::active()->ordered()->get();
        $subscriptionHistory = $user->subscriptions()->with('plan')->latest()->take(10)->get();
        $pendingPayment = $user->pendingPayment();
        $paymentHistory = $user->paymentTransactions()->with('plan')->latest()->take(10)->get();

        return view('livewire.admin.my-subscription', [
            'user' => $user,
            'subscription' => $subscription,
            'plan' => $plan,
            'allPlans' => $allPlans,
            'subscriptionHistory' => $subscriptionHistory,
            'pendingPayment' => $pendingPayment,
            'paymentHistory' => $paymentHistory,
        ])->layout('layouts.admin');
    }
}
