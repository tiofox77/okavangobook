<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center">
            <i class="fas fa-gem text-purple-500 mr-3"></i>
            Meu Plano
        </h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Gerir a sua subscrição e ver planos disponíveis</p>
    </div>

    @if(session()->has('message'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700">
            <i class="fas fa-check-circle mr-2"></i>{{ session('message') }}
        </div>
    @endif
    @if(session()->has('error'))
        <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    <!-- Pending Payment Banner -->
    @if($pendingPayment)
        <div class="mb-6 bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800 rounded-2xl p-5">
            <div class="flex items-start gap-4">
                <div class="p-3 rounded-xl bg-amber-100 dark:bg-amber-900/30 text-amber-600 flex-shrink-0">
                    <i class="fas fa-hourglass-half text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-amber-800 dark:text-amber-300">Pagamento Pendente de Validação</p>
                    <p class="text-sm text-amber-700 dark:text-amber-400 mt-1">
                        Plano <strong>{{ $pendingPayment->plan->name }}</strong> &mdash;
                        {{ number_format($pendingPayment->amount, 0, ',', '.') }} {{ $pendingPayment->currency }}
                        ({{ $pendingPayment->billing_cycle === 'yearly' ? 'Anual' : 'Mensal' }})
                    </p>
                    <div class="flex flex-wrap gap-4 mt-2 text-xs text-amber-600 dark:text-amber-500">
                        <span><i class="fas fa-hashtag mr-1"></i>Ref: <strong class="font-mono">{{ $pendingPayment->reference_code }}</strong></span>
                        <span><i class="fas fa-university mr-1"></i>{{ $pendingPayment->bank_name }}</span>
                        <span><i class="fas fa-calendar mr-1"></i>{{ $pendingPayment->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <p class="text-xs text-amber-600 dark:text-amber-500 mt-1">
                        <i class="fas fa-clock mr-1"></i>A equipa irá validar em até 24h úteis.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Current Plan Card -->
    @if($subscription && $plan)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-700 p-6 text-white">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <p class="text-indigo-200 text-sm uppercase tracking-wider font-medium">Plano Actual</p>
                        <h2 class="text-3xl font-bold mt-1">{{ $plan->name }}</h2>
                        <p class="text-indigo-200 mt-1">{{ $plan->description }}</p>
                    </div>
                    <div class="text-right">
                        @if($plan->is_free)
                            <p class="text-3xl font-bold">Grátis</p>
                            <p class="text-indigo-200 text-sm">por 1 ano</p>
                        @elseif($subscription->billing_cycle === 'yearly')
                            <p class="text-3xl font-bold">{{ number_format($plan->price_yearly, 0, ',', '.') }} <span class="text-lg">AOA</span></p>
                            <p class="text-indigo-200 text-sm">por ano</p>
                        @else
                            <p class="text-3xl font-bold">{{ number_format($plan->price_monthly, 0, ',', '.') }} <span class="text-lg">AOA</span></p>
                            <p class="text-indigo-200 text-sm">por mês</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Status -->
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Estado</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $subscription->status_badge }}">
                            <i class="fas fa-circle text-[6px] mr-2"></i>
                            {{ $subscription->status_label }}
                        </span>
                    </div>
                    <!-- Dates -->
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Início</p>
                        <p class="font-semibold text-gray-800 dark:text-white">{{ $subscription->starts_at->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Expira em</p>
                        <p class="font-semibold text-gray-800 dark:text-white">{{ $subscription->ends_at->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $subscription->daysRemaining() }} dias restantes</p>
                    </div>
                    <!-- Renewal -->
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Renovação Automática</p>
                        <button wire:click="toggleAutoRenew" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $subscription->auto_renew ? 'bg-green-500' : 'bg-gray-300' }}">
                            <span class="inline-block h-4 w-4 rounded-full bg-white transition-transform {{ $subscription->auto_renew ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                        <p class="text-xs text-gray-500 mt-1">{{ $subscription->auto_renew ? 'Activa' : 'Desactivada' }}</p>
                    </div>
                </div>

                <!-- Progress Bar -->
                @php
                    $totalDays = $subscription->starts_at->diffInDays($subscription->ends_at);
                    $usedDays = $subscription->starts_at->diffInDays(now());
                    $progress = $totalDays > 0 ? min(100, ($usedDays / $totalDays) * 100) : 0;
                @endphp
                <div class="mt-6">
                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                        <span>{{ $subscription->starts_at->format('d/m/Y') }}</span>
                        <span>{{ round($progress) }}% utilizado</span>
                        <span>{{ $subscription->ends_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-2 rounded-full transition-all" style="width: {{ $progress }}%"></div>
                    </div>
                </div>

                <!-- Limits Usage -->
                <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                    @php
                        $hotelsUsed = $user->managedHotels()->count();
                        $maxHotels = $plan->max_hotels >= 999 ? '∞' : $plan->max_hotels;
                        $hotelsPercent = $plan->max_hotels >= 999 ? 10 : min(100, ($hotelsUsed / max(1, $plan->max_hotels)) * 100);
                    @endphp
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-3">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Propriedades</p>
                        <p class="text-lg font-bold text-gray-800 dark:text-white">{{ $hotelsUsed }} <span class="text-sm font-normal text-gray-500">/ {{ $maxHotels }}</span></p>
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-1.5 mt-1">
                            <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $hotelsPercent }}%"></div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-3">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Fotos/Hotel</p>
                        <p class="text-lg font-bold text-gray-800 dark:text-white">{{ $plan->max_images_per_hotel >= 999 ? '∞' : $plan->max_images_per_hotel }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-3">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Tipos Quarto/Hotel</p>
                        <p class="text-lg font-bold text-gray-800 dark:text-white">{{ $plan->max_room_types_per_hotel >= 999 ? '∞' : $plan->max_room_types_per_hotel }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-3">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Ciclo</p>
                        <p class="text-lg font-bold text-gray-800 dark:text-white capitalize">{{ $subscription->billing_cycle === 'trial' ? 'Gratuito' : ($subscription->billing_cycle === 'yearly' ? 'Anual' : 'Mensal') }}</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('pricing') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium text-sm transition-colors shadow-sm">
                        <i class="fas fa-arrow-up mr-2"></i> Fazer Upgrade
                    </a>
                    @if($subscription->isActive() && !$subscription->isCancelled())
                        <button wire:click="$set('showCancelModal', true)" class="inline-flex items-center px-4 py-2 bg-white hover:bg-red-50 text-red-600 border border-red-200 rounded-lg font-medium text-sm transition-colors">
                            <i class="fas fa-times mr-2"></i> Cancelar Plano
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @else
        <!-- No Plan -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center mb-6">
            <div class="w-20 h-20 mx-auto rounded-2xl bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 flex items-center justify-center mb-4">
                <i class="fas fa-gem text-3xl text-indigo-500"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Sem Plano Activo</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md mx-auto">
                Escolha um plano para desbloquear todas as funcionalidades e começar a gerir as suas propriedades.
            </p>
            <a href="{{ route('pricing') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl font-semibold shadow-lg transition-all transform hover:scale-105">
                <i class="fas fa-gem mr-2"></i> Ver Planos Disponíveis
            </a>
        </div>
    @endif

    <!-- Features of Current Plan -->
    @if($plan)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">
                <i class="fas fa-list-check text-indigo-500 mr-2"></i> Funcionalidades do Plano {{ $plan->name }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($plan->features_list as $feature)
                    <div class="flex items-center p-2.5 rounded-lg {{ $feature['included'] ? 'bg-green-50 dark:bg-green-900/10' : 'bg-gray-50 dark:bg-gray-700/30' }}">
                        @if($feature['included'])
                            <i class="fas fa-check-circle text-green-500 mr-2.5"></i>
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $feature['text'] }}</span>
                        @else
                            <i class="fas fa-times-circle text-gray-300 dark:text-gray-600 mr-2.5"></i>
                            <span class="text-sm text-gray-400 dark:text-gray-500 line-through">{{ $feature['text'] }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Available Plans for Upgrade -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">
            <i class="fas fa-arrow-up text-blue-500 mr-2"></i> Planos Disponíveis
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($allPlans as $availablePlan)
                <div class="rounded-xl border-2 p-4 transition-all hover:shadow-md
                    {{ $plan && $plan->id === $availablePlan->id ? 'border-indigo-500 bg-indigo-50/50 dark:bg-indigo-900/10' : 'border-gray-100 dark:border-gray-700' }}
                    {{ $availablePlan->is_popular ? 'ring-2 ring-indigo-200 dark:ring-indigo-800' : '' }}">

                    @if($availablePlan->is_popular)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700 mb-2">
                            <i class="fas fa-fire mr-1"></i> Popular
                        </span>
                    @endif

                    <h4 class="font-bold text-gray-800 dark:text-white">{{ $availablePlan->name }}</h4>
                    <div class="mt-1">
                        @if($availablePlan->is_free)
                            <span class="text-xl font-bold text-green-600">Grátis</span>
                        @else
                            <span class="text-xl font-bold text-gray-800 dark:text-white">{{ number_format($availablePlan->price_monthly, 0, ',', '.') }}</span>
                            <span class="text-xs text-gray-500"> AOA/mês</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-1 mb-3">{{ $availablePlan->max_hotels >= 999 ? 'Ilimitadas' : $availablePlan->max_hotels }} propriedades</p>

                    @if($plan && $plan->id === $availablePlan->id)
                        <span class="inline-flex items-center text-xs text-indigo-600 font-medium">
                            <i class="fas fa-check mr-1"></i> Plano Actual
                        </span>
                    @else
                        <button wire:click="openUpgradeModal({{ $availablePlan->id }})" class="w-full py-2 px-3 rounded-lg text-xs font-medium transition-colors
                            {{ $plan && $availablePlan->price_monthly > $plan->price_monthly ? 'bg-indigo-600 hover:bg-indigo-700 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                            {{ $plan && $availablePlan->price_monthly > $plan->price_monthly ? 'Fazer Upgrade' : 'Mudar Plano' }}
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Subscription History -->
    @if($subscriptionHistory->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">
                <i class="fas fa-history text-gray-500 mr-2"></i> Histórico de Subscrições
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-gray-200 dark:border-gray-700">
                            <th class="pb-3 pr-4">Plano</th>
                            <th class="pb-3 pr-4">Ciclo</th>
                            <th class="pb-3 pr-4">Valor</th>
                            <th class="pb-3 pr-4">Estado</th>
                            <th class="pb-3 pr-4">Início</th>
                            <th class="pb-3">Fim</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($subscriptionHistory as $sub)
                            <tr>
                                <td class="py-3 pr-4 font-medium text-gray-800 dark:text-white">{{ $sub->plan?->name ?? 'N/A' }}</td>
                                <td class="py-3 pr-4 text-gray-600 dark:text-gray-400 capitalize">
                                    {{ $sub->billing_cycle === 'trial' ? 'Gratuito' : ($sub->billing_cycle === 'yearly' ? 'Anual' : 'Mensal') }}
                                </td>
                                <td class="py-3 pr-4 text-gray-600 dark:text-gray-400">
                                    {{ $sub->amount_paid > 0 ? number_format($sub->amount_paid, 0, ',', '.') . ' AOA' : 'Grátis' }}
                                </td>
                                <td class="py-3 pr-4">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $sub->status_badge }}">
                                        {{ $sub->status_label }}
                                    </span>
                                </td>
                                <td class="py-3 pr-4 text-gray-600 dark:text-gray-400">{{ $sub->starts_at->format('d/m/Y') }}</td>
                                <td class="py-3 text-gray-600 dark:text-gray-400">{{ $sub->ends_at->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Payment History -->
    @if($paymentHistory->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mt-6">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">
                <i class="fas fa-credit-card text-green-500 mr-2"></i> Histórico de Pagamentos
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-gray-200 dark:border-gray-700">
                            <th class="pb-3 pr-4">Referência</th>
                            <th class="pb-3 pr-4">Plano</th>
                            <th class="pb-3 pr-4">Valor</th>
                            <th class="pb-3 pr-4">Método</th>
                            <th class="pb-3 pr-4">Estado</th>
                            <th class="pb-3">Data</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($paymentHistory as $pay)
                            <tr>
                                <td class="py-3 pr-4 font-mono text-xs font-bold text-gray-800 dark:text-white">{{ $pay->reference_code }}</td>
                                <td class="py-3 pr-4 font-medium text-gray-800 dark:text-white">{{ $pay->plan?->name ?? 'N/A' }}</td>
                                <td class="py-3 pr-4 font-bold text-gray-800 dark:text-white">{{ number_format($pay->amount, 0, ',', '.') }} AOA</td>
                                <td class="py-3 pr-4 text-gray-600 dark:text-gray-400 text-xs">{{ $pay->payment_method_label }}</td>
                                <td class="py-3 pr-4">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $pay->status_badge }}">
                                        {{ $pay->status_label }}
                                    </span>
                                </td>
                                <td class="py-3 text-gray-600 dark:text-gray-400 text-xs">{{ $pay->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- ═══════════════════ MODALS ═══════════════════ -->

    <!-- Cancel Modal -->
    @if($showCancelModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/60" wire:click="closeAllModals"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md z-50 p-6">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 mx-auto rounded-full bg-red-100 dark:bg-red-900/20 flex items-center justify-center mb-4">
                        <i class="fas fa-exclamation-triangle text-2xl text-red-500"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">Cancelar Subscrição?</h3>
                    <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">
                        O seu plano permanecerá activo até <strong>{{ $subscription?->ends_at?->format('d/m/Y') }}</strong>. Após essa data, perderá acesso às funcionalidades premium.
                    </p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Motivo (opcional)</label>
                    <textarea wire:model="cancellationReason" rows="3" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500" placeholder="Diga-nos porque está a cancelar..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button wire:click="closeAllModals" class="flex-1 py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-colors">
                        Manter Plano
                    </button>
                    <button wire:click="cancelSubscription" class="flex-1 py-2.5 px-4 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition-colors">
                        Confirmar Cancelamento
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Upgrade Modal (choose cycle) -->
    @if($showUpgradeModal && $upgradePlanId)
        @php $upgradePlan = \App\Models\Plan::find($upgradePlanId); @endphp
        @if($upgradePlan)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/60" wire:click="closeAllModals"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md z-50 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-6 text-center text-white">
                        <h3 class="text-xl font-bold">Mudar para {{ $upgradePlan->name }}</h3>
                        <p class="text-indigo-200 text-sm mt-1">{{ $upgradePlan->description }}</p>
                    </div>
                    <div class="p-6">
                        @if(!$upgradePlan->is_free)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ciclo de Facturação</label>
                            <div class="grid grid-cols-2 gap-3">
                                <button wire:click="$set('upgradeCycle', 'monthly')"
                                    class="p-3 rounded-xl border-2 text-center transition-all {{ $upgradeCycle === 'monthly' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-600' }}">
                                    <p class="font-bold text-gray-800 dark:text-white">{{ number_format($upgradePlan->price_monthly, 0, ',', '.') }} AOA</p>
                                    <p class="text-xs text-gray-500">Mensal</p>
                                </button>
                                <button wire:click="$set('upgradeCycle', 'yearly')"
                                    class="p-3 rounded-xl border-2 text-center transition-all {{ $upgradeCycle === 'yearly' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-600' }}">
                                    <p class="font-bold text-gray-800 dark:text-white">{{ number_format($upgradePlan->price_yearly, 0, ',', '.') }} AOA</p>
                                    <p class="text-xs text-gray-500">Anual</p>
                                    @if($upgradePlan->yearly_savings_percent > 0)
                                        <span class="inline-flex text-xs text-green-600 font-medium mt-1">-{{ $upgradePlan->yearly_savings_percent }}%</span>
                                    @endif
                                </button>
                            </div>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-900/10 border border-blue-200 dark:border-blue-800 rounded-xl p-3 mb-4 text-sm text-blue-700 dark:text-blue-300">
                            <i class="fas fa-info-circle mr-1"></i>
                            Após confirmar, será direccionado para submeter os dados da transferência bancária.
                        </div>
                        @endif
                        <div class="flex gap-3">
                            <button wire:click="closeAllModals" class="flex-1 py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-colors">
                                Cancelar
                            </button>
                            <button wire:click="confirmUpgrade" class="flex-1 py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition-colors">
                                <i class="fas fa-arrow-right mr-1"></i> {{ $upgradePlan->is_free ? 'Activar' : 'Continuar' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif

    <!-- Bank Transfer Form Modal -->
    @if($showTransferModal && $upgradePlanId)
        @php $transferPlan = \App\Models\Plan::find($upgradePlanId); @endphp
        @if($transferPlan)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 py-6">
                <div class="fixed inset-0 bg-black/60" wire:click="closeAllModals"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg z-50 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold">Pagamento por Transferência</h3>
                                <p class="text-blue-200 text-sm">{{ $transferPlan->name }} &mdash; {{ $upgradeCycle === 'yearly' ? 'Anual' : 'Mensal' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold">
                                    {{ number_format($upgradeCycle === 'yearly' ? $transferPlan->price_yearly : $transferPlan->price_monthly, 0, ',', '.') }}
                                </p>
                                <p class="text-blue-200 text-xs">AOA</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 max-h-[70vh] overflow-y-auto">
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mb-5">
                            <h4 class="text-sm font-bold text-blue-800 dark:text-blue-300 mb-3">
                                <i class="fas fa-university mr-1"></i> Dados Bancários para Transferência
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-blue-600 dark:text-blue-400">Banco:</span>
                                    <span class="font-bold text-blue-900 dark:text-white">{{ \App\Models\Setting::get('bank_name', 'BAI - Banco Angolano de Investimentos') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-blue-600 dark:text-blue-400">Titular:</span>
                                    <span class="font-bold text-blue-900 dark:text-white">{{ \App\Models\Setting::get('bank_holder', \App\Models\Setting::get('app_name', 'KiandaStay') . ' Lda') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-blue-600 dark:text-blue-400">IBAN:</span>
                                    <span class="font-mono font-bold text-blue-900 dark:text-white">{{ \App\Models\Setting::get('bank_iban', 'AO06.0040.0000.0000.0000.0000.0') }}</span>
                                </div>
                                <div class="flex justify-between border-t border-blue-200 dark:border-blue-700 pt-2 mt-2">
                                    <span class="text-blue-600 dark:text-blue-400 font-medium">Valor:</span>
                                    <span class="font-extrabold text-blue-900 dark:text-white text-lg">
                                        {{ number_format($upgradeCycle === 'yearly' ? $transferPlan->price_yearly : $transferPlan->price_monthly, 0, ',', '.') }} AOA
                                    </span>
                                </div>
                            </div>
                        </div>

                        <form wire:submit="submitTransfer" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Banco de Origem *</label>
                                <input type="text" wire:model="bankName" placeholder="Ex: BAI, BFA, BIC, BMA..."
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 shadow-sm text-sm">
                                @error('bankName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome do Titular *</label>
                                <input type="text" wire:model="accountHolder" placeholder="Nome completo do titular da conta"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 shadow-sm text-sm">
                                @error('accountHolder') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ref. da Transferência *</label>
                                    <input type="text" wire:model="transferReference" placeholder="Nº do comprovativo"
                                        class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 shadow-sm text-sm">
                                    @error('transferReference') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data da Transferência *</label>
                                    <input type="date" wire:model="transferDate" max="{{ date('Y-m-d') }}"
                                        class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 shadow-sm text-sm">
                                    @error('transferDate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Comprovativo de Transferência *</label>
                                <div class="relative">
                                    <input type="file" wire:model="proofFile" accept=".jpg,.jpeg,.png,.pdf"
                                        class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/30 dark:file:text-blue-300 border border-gray-300 dark:border-gray-600 rounded-xl cursor-pointer bg-white dark:bg-gray-700">
                                    <div wire:loading wire:target="proofFile" class="absolute right-3 top-1/2 -translate-y-1/2">
                                        <i class="fas fa-spinner fa-spin text-blue-500"></i>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">JPG, PNG ou PDF (máx. 5MB)</p>
                                @if($proofFile && !$errors->has('proofFile'))
                                    <p class="text-xs text-green-600 mt-1"><i class="fas fa-check-circle mr-1"></i>{{ $proofFile->getClientOriginalName() }}</p>
                                @endif
                                @error('proofFile') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notas adicionais (opcional)</label>
                                <textarea wire:model="userNotes" rows="2" placeholder="Informação adicional..."
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 shadow-sm text-sm"></textarea>
                            </div>
                            <div class="flex gap-3 pt-2">
                                <button type="button" wire:click="closeAllModals" class="flex-1 py-3 px-4 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl font-semibold transition-colors">
                                    Cancelar
                                </button>
                                <button type="submit" wire:loading.attr="disabled"
                                    class="flex-1 py-3 px-4 text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-xl font-semibold shadow-md transition-all">
                                    <span wire:loading.remove wire:target="submitTransfer"><i class="fas fa-paper-plane mr-1"></i> Enviar</span>
                                    <span wire:loading wire:target="submitTransfer"><i class="fas fa-spinner fa-spin mr-1"></i> Enviando...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif

    <!-- Success Modal -->
    @if($showSuccessModal && $paymentRefCode)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/60"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md z-50 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-8 text-center text-white">
                    <div class="w-20 h-20 mx-auto rounded-full bg-white/20 flex items-center justify-center mb-4">
                        <i class="fas fa-check-circle text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold">Pagamento Enviado!</h3>
                </div>
                <div class="p-6 text-center">
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        O comprovativo foi enviado com sucesso. Validação em até <strong>24h úteis</strong>.
                    </p>
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 mb-5">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Código de Referência</p>
                        <p class="text-xl font-mono font-bold text-gray-800 dark:text-white">{{ $paymentRefCode }}</p>
                    </div>
                    <button wire:click="closeAllModals"
                        class="w-full py-3 px-4 text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-xl font-semibold shadow-md transition-all">
                        <i class="fas fa-check mr-1"></i> Entendido
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
