<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center">
            <i class="fas fa-credit-card text-green-500 mr-3"></i>
            Gestão de Pagamentos
        </h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Validar transferências bancárias e gerir pagamentos de subscrições</p>
    </div>

    @if(session()->has('message'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700">
            <i class="fas fa-check-circle mr-2"></i>{{ session('message') }}
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <div class="flex items-center">
                <div class="p-2.5 rounded-lg bg-yellow-100 dark:bg-yellow-900/20 text-yellow-600 mr-3">
                    <i class="fas fa-clock text-lg"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['pending'] }}</p>
                    <p class="text-xs text-gray-500">Pendentes</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <div class="flex items-center">
                <div class="p-2.5 rounded-lg bg-green-100 dark:bg-green-900/20 text-green-600 mr-3">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['approved'] }}</p>
                    <p class="text-xs text-gray-500">Aprovados</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <div class="flex items-center">
                <div class="p-2.5 rounded-lg bg-red-100 dark:bg-red-900/20 text-red-600 mr-3">
                    <i class="fas fa-times-circle text-lg"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['rejected'] }}</p>
                    <p class="text-xs text-gray-500">Rejeitados</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <div class="flex items-center">
                <div class="p-2.5 rounded-lg bg-blue-100 dark:bg-blue-900/20 text-blue-600 mr-3">
                    <i class="fas fa-coins text-lg"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500">Receita (AOA)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Pesquisar por referência, nome ou email..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 shadow-sm text-sm">
                </div>
            </div>
            <select wire:model.live="statusFilter" class="rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm py-2.5 shadow-sm">
                <option value="">Todos os estados</option>
                <option value="pending">Pendentes</option>
                <option value="approved">Aprovados</option>
                <option value="rejected">Rejeitados</option>
                <option value="expired">Expirados</option>
            </select>
            <select wire:model.live="dateFilter" class="rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm py-2.5 shadow-sm">
                <option value="">Todo o período</option>
                <option value="today">Hoje</option>
                <option value="week">Última semana</option>
                <option value="month">Último mês</option>
            </select>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr class="text-left text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-4 py-3">Referência</th>
                        <th class="px-4 py-3">Utilizador</th>
                        <th class="px-4 py-3">Plano</th>
                        <th class="px-4 py-3">Valor</th>
                        <th class="px-4 py-3">Método</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Data</th>
                        <th class="px-4 py-3 text-right">Acções</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors {{ $payment->isPending() ? 'bg-yellow-50/50 dark:bg-yellow-900/5' : '' }}">
                            <td class="px-4 py-3">
                                <button wire:click="openDetail({{ $payment->id }})" class="font-mono text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline">
                                    {{ $payment->reference_code }}
                                </button>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold mr-2">
                                        {{ strtoupper(substr($payment->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-white text-xs">{{ $payment->user->name }}</p>
                                        <p class="text-gray-500 text-xs">{{ $payment->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-medium text-gray-800 dark:text-white">{{ $payment->plan->name }}</span>
                                <span class="text-gray-500 text-xs block">{{ $payment->billing_cycle === 'yearly' ? 'Anual' : 'Mensal' }}</span>
                            </td>
                            <td class="px-4 py-3 font-bold text-gray-800 dark:text-white">
                                {{ number_format($payment->amount, 0, ',', '.') }} <span class="text-xs font-normal text-gray-500">{{ $payment->currency }}</span>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">
                                {{ $payment->payment_method_label }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $payment->status_badge }}">
                                    <i class="fas fa-circle text-[5px] mr-1.5"></i>
                                    {{ $payment->status_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500">
                                {{ $payment->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="openDetail({{ $payment->id }})" class="p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700" title="Ver detalhes">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                    @if($payment->isPending())
                                        <button wire:click="openAction({{ $payment->id }}, 'approve')" class="p-1.5 rounded-lg text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20" title="Aprovar">
                                            <i class="fas fa-check text-xs"></i>
                                        </button>
                                        <button wire:click="openAction({{ $payment->id }}, 'reject')" class="p-1.5 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20" title="Rejeitar">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-gray-500">
                                <i class="fas fa-receipt text-3xl mb-2 text-gray-300"></i>
                                <p>Nenhum pagamento encontrado</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payments->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

    <!-- Detail Modal -->
    @if($showDetailModal && $detailPayment)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/60" wire:click="closeDetail"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg z-50 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 text-white flex items-center justify-between">
                    <h3 class="text-lg font-bold">Detalhes do Pagamento</h3>
                    <button wire:click="closeDetail" class="text-white/80 hover:text-white"><i class="fas fa-times"></i></button>
                </div>
                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                    <!-- Reference -->
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Código de Referência</p>
                        <p class="text-xl font-mono font-bold text-gray-800 dark:text-white">{{ $detailPayment->reference_code }}</p>
                    </div>

                    <!-- Info Grid -->
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Utilizador</p>
                            <p class="font-medium text-gray-800 dark:text-white">{{ $detailPayment->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $detailPayment->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Plano</p>
                            <p class="font-medium text-gray-800 dark:text-white">{{ $detailPayment->plan->name }}</p>
                            <p class="text-xs text-gray-500">{{ $detailPayment->billing_cycle === 'yearly' ? 'Anual' : 'Mensal' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Valor</p>
                            <p class="font-bold text-lg text-gray-800 dark:text-white">{{ number_format($detailPayment->amount, 0, ',', '.') }} {{ $detailPayment->currency }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Estado</p>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $detailPayment->status_badge }}">
                                {{ $detailPayment->status_label }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Método</p>
                            <p class="font-medium text-gray-800 dark:text-white">{{ $detailPayment->payment_method_label }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Data do Pedido</p>
                            <p class="font-medium text-gray-800 dark:text-white">{{ $detailPayment->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Transfer Details -->
                    @if($detailPayment->bank_name || $detailPayment->transfer_reference || $detailPayment->account_holder)
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                            <i class="fas fa-university mr-1"></i> Dados da Transferência
                        </h4>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            @if($detailPayment->bank_name)
                            <div>
                                <p class="text-xs text-gray-500 mb-0.5">Banco</p>
                                <p class="font-medium text-gray-800 dark:text-white">{{ $detailPayment->bank_name }}</p>
                            </div>
                            @endif
                            @if($detailPayment->account_holder)
                            <div>
                                <p class="text-xs text-gray-500 mb-0.5">Titular</p>
                                <p class="font-medium text-gray-800 dark:text-white">{{ $detailPayment->account_holder }}</p>
                            </div>
                            @endif
                            @if($detailPayment->transfer_reference)
                            <div>
                                <p class="text-xs text-gray-500 mb-0.5">Ref. Transferência</p>
                                <p class="font-mono font-medium text-gray-800 dark:text-white">{{ $detailPayment->transfer_reference }}</p>
                            </div>
                            @endif
                            @if($detailPayment->transfer_date)
                            <div>
                                <p class="text-xs text-gray-500 mb-0.5">Data Transferência</p>
                                <p class="font-medium text-gray-800 dark:text-white">{{ $detailPayment->transfer_date->format('d/m/Y') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($detailPayment->proof_file)
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                            <i class="fas fa-file-image mr-1"></i> Comprovativo de Transferência
                        </h4>
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-3">
                            @php
                                $ext = pathinfo($detailPayment->proof_file, PATHINFO_EXTENSION);
                                $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png']);
                            @endphp
                            @if($isImage)
                                <a href="{{ asset('storage/' . $detailPayment->proof_file) }}" target="_blank" class="block">
                                    <img src="{{ asset('storage/' . $detailPayment->proof_file) }}" alt="Comprovativo" class="max-h-64 rounded-lg mx-auto border border-gray-200 dark:border-gray-600 hover:opacity-90 transition-opacity">
                                </a>
                            @else
                                <a href="{{ asset('storage/' . $detailPayment->proof_file) }}" target="_blank"
                                    class="flex items-center gap-3 p-3 bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                    <div class="p-2 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-600">
                                        <i class="fas fa-file-pdf text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-white text-sm">Comprovativo PDF</p>
                                        <p class="text-xs text-gray-500">Clique para abrir</p>
                                    </div>
                                    <i class="fas fa-external-link-alt text-gray-400 ml-auto"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($detailPayment->user_notes)
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-comment mr-1"></i> Notas do Utilizador
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3">{{ $detailPayment->user_notes }}</p>
                    </div>
                    @endif

                    @if($detailPayment->admin_notes)
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-user-shield mr-1"></i> Notas do Admin
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 bg-blue-50 dark:bg-blue-900/10 rounded-lg p-3">{{ $detailPayment->admin_notes }}</p>
                    </div>
                    @endif

                    @if($detailPayment->isApproved() && $detailPayment->approvedByUser)
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-check-circle text-green-500 mr-1"></i>
                            Aprovado por <strong>{{ $detailPayment->approvedByUser->name }}</strong> em {{ $detailPayment->approved_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    @endif

                    @if($detailPayment->isRejected())
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <p class="text-xs text-red-600">
                            <i class="fas fa-times-circle mr-1"></i>
                            Rejeitado: <strong>{{ $detailPayment->rejection_reason }}</strong>
                        </p>
                    </div>
                    @endif

                    <!-- Actions for pending -->
                    @if($detailPayment->isPending())
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4 flex gap-3">
                        <button wire:click="openAction({{ $detailPayment->id }}, 'approve')" class="flex-1 py-2.5 px-4 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium text-sm transition-colors">
                            <i class="fas fa-check mr-1"></i> Aprovar
                        </button>
                        <button wire:click="openAction({{ $detailPayment->id }}, 'reject')" class="flex-1 py-2.5 px-4 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium text-sm transition-colors">
                            <i class="fas fa-times mr-1"></i> Rejeitar
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Approve/Reject Modal -->
    @if($showActionModal && $selectedPaymentId)
    @php $actionPayment = \App\Models\PaymentTransaction::with('user', 'plan')->find($selectedPaymentId); @endphp
    @if($actionPayment)
    <div class="fixed inset-0 z-[60] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/60" wire:click="$set('showActionModal', false)"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md z-50 p-6">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 mx-auto rounded-full {{ $actionType === 'approve' ? 'bg-green-100 dark:bg-green-900/20' : 'bg-red-100 dark:bg-red-900/20' }} flex items-center justify-center mb-4">
                        <i class="fas {{ $actionType === 'approve' ? 'fa-check-circle text-green-500' : 'fa-times-circle text-red-500' }} text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">
                        {{ $actionType === 'approve' ? 'Aprovar Pagamento?' : 'Rejeitar Pagamento?' }}
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">
                        <strong>{{ $actionPayment->user->name }}</strong> &mdash; {{ $actionPayment->plan->name }}
                        &mdash; {{ number_format($actionPayment->amount, 0, ',', '.') }} {{ $actionPayment->currency }}
                    </p>
                    <p class="font-mono text-xs text-gray-400 mt-1">{{ $actionPayment->reference_code }}</p>
                </div>

                @if($actionType === 'approve')
                    <div class="bg-green-50 dark:bg-green-900/10 border border-green-200 dark:border-green-800 rounded-xl p-3 mb-4 text-sm text-green-700 dark:text-green-300">
                        <i class="fas fa-info-circle mr-1"></i>
                        Ao aprovar, a subscrição do plano <strong>{{ $actionPayment->plan->name }}</strong> será activada imediatamente.
                    </div>
                @endif

                @if($actionType === 'reject')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Motivo da Rejeição *</label>
                    <textarea wire:model="rejectionReason" rows="2" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-red-500" placeholder="Ex: Transferência não encontrada, valor incorreto..."></textarea>
                    @error('rejectionReason') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                @endif

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notas internas (opcional)</label>
                    <textarea wire:model="adminNotes" rows="2" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm" placeholder="Notas visíveis apenas para admins..."></textarea>
                </div>

                <div class="flex gap-3">
                    <button wire:click="$set('showActionModal', false)" class="flex-1 py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-colors">
                        Cancelar
                    </button>
                    <button wire:click="confirmAction" wire:loading.attr="disabled"
                        class="flex-1 py-2.5 px-4 {{ $actionType === 'approve' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }} text-white rounded-xl font-medium transition-colors">
                        <i class="fas {{ $actionType === 'approve' ? 'fa-check' : 'fa-times' }} mr-1" wire:loading.remove wire:target="confirmAction"></i>
                        <i class="fas fa-spinner fa-spin mr-1" wire:loading wire:target="confirmAction"></i>
                        {{ $actionType === 'approve' ? 'Aprovar' : 'Rejeitar' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endif
</div>
