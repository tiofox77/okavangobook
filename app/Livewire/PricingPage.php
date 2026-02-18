<?php

namespace App\Livewire;

use App\Models\Plan;
use App\Models\PaymentTransaction;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PricingPage extends Component
{
    public string $billingCycle = 'monthly';

    // Step 1: Plan selection confirm
    public bool $showConfirmModal = false;
    public ?int $selectedPlanId = null;
    public ?string $selectedCycle = null;

    // Step 2: Bank transfer form (paid plans)
    public bool $showTransferModal = false;
    public string $bankName = '';
    public string $accountHolder = '';
    public string $transferReference = '';
    public ?string $transferDate = null;
    public string $userNotes = '';

    // Step 3: Success / pending
    public bool $showSuccessModal = false;
    public ?string $paymentRefCode = null;

    public function switchCycle(string $cycle)
    {
        $this->billingCycle = $cycle;
    }

    public function selectPlan(int $planId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Faça login para escolher um plano.');
        }

        $plan = Plan::findOrFail($planId);
        $user = Auth::user();

        // Verificar se já tem este plano activo
        $currentPlan = $user->currentPlan();
        if ($currentPlan && $currentPlan->id === $plan->id) {
            session()->flash('error', 'Já tem este plano activo.');
            return;
        }

        // Verificar se já tem pagamento pendente
        if ($user->hasPendingPayment()) {
            session()->flash('error', 'Já tem um pagamento pendente de validação. Aguarde a aprovação ou contacte o suporte.');
            return;
        }

        $this->selectedPlanId = $planId;
        $this->selectedCycle = $this->billingCycle;
        $this->showConfirmModal = true;
    }

    public function confirmSubscription()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $plan = Plan::findOrFail($this->selectedPlanId);
        $user = Auth::user();

        $this->showConfirmModal = false;

        // Plano gratuito → activar imediatamente
        if ($plan->is_free) {
            $user->subscribeToPlan($plan);

            if (!$user->hasRole('Propriedade') && !$user->hasRole('Admin')) {
                $user->assignRole('Propriedade');
            }

            session()->flash('success', 'Parabéns! O seu plano Gratuito foi activado por 1 ano.');
            return redirect()->route('admin.dashboard');
        }

        // Plano pago → abrir formulário de transferência
        $this->resetTransferForm();
        $this->showTransferModal = true;
    }

    public function submitTransfer()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'bankName' => 'required|string|max:100',
            'accountHolder' => 'required|string|max:150',
            'transferReference' => 'required|string|max:100',
            'transferDate' => 'required|date|before_or_equal:today',
        ], [
            'bankName.required' => 'O nome do banco é obrigatório.',
            'accountHolder.required' => 'O nome do titular é obrigatório.',
            'transferReference.required' => 'A referência da transferência é obrigatória.',
            'transferDate.required' => 'A data da transferência é obrigatória.',
            'transferDate.before_or_equal' => 'A data não pode ser no futuro.',
        ]);

        $plan = Plan::findOrFail($this->selectedPlanId);
        $user = Auth::user();

        $payment = $user->createPaymentRequest($plan, $this->selectedCycle, [
            'payment_method' => 'bank_transfer',
            'bank_name' => $this->bankName,
            'account_holder' => $this->accountHolder,
            'transfer_reference' => $this->transferReference,
            'transfer_date' => $this->transferDate,
            'user_notes' => $this->userNotes ?: null,
        ]);

        $this->showTransferModal = false;
        $this->paymentRefCode = $payment->reference_code;
        $this->showSuccessModal = true;
    }

    public function closeAllModals()
    {
        $this->showConfirmModal = false;
        $this->showTransferModal = false;
        $this->showSuccessModal = false;
        $this->selectedPlanId = null;
        $this->paymentRefCode = null;
    }

    private function resetTransferForm()
    {
        $this->bankName = '';
        $this->accountHolder = '';
        $this->transferReference = '';
        $this->transferDate = null;
        $this->userNotes = '';
    }

    public function render()
    {
        $plans = Plan::active()->ordered()->get();
        $currentPlan = Auth::check() ? Auth::user()->currentPlan() : null;
        $activeSubscription = Auth::check() ? Auth::user()->activeSubscription : null;
        $pendingPayment = Auth::check() ? Auth::user()->pendingPayment() : null;

        return view('livewire.pricing-page', [
            'plans' => $plans,
            'currentPlan' => $currentPlan,
            'activeSubscription' => $activeSubscription,
            'pendingPayment' => $pendingPayment,
        ])->layout('layouts.app');
    }
}
