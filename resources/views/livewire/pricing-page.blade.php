@section('title', 'Planos e preços — Anuncie a sua propriedade')
@section('meta_description', 'Conheça os planos KiandaStay para divulgar o seu hotel, resort ou hospedaria em Angola. Escolha o plano ideal para o seu negócio.')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-800 text-white">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-72 h-72 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-purple-300 rounded-full blur-3xl"></div>
        </div>
        <div class="relative container mx-auto px-4 py-16 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4">
                Escolha o Plano Ideal para o Seu Negócio
            </h1>
            <p class="text-lg md:text-xl text-purple-100 max-w-2xl mx-auto mb-8">
                Comece gratuitamente e faça upgrade quando precisar. Planos flexíveis para proprietários de todos os tamanhos.
            </p>

            <!-- Billing Toggle -->
            <div class="inline-flex items-center bg-white/10 backdrop-blur-sm rounded-full p-1.5">
                <button wire:click="switchCycle('monthly')"
                    class="px-6 py-2.5 rounded-full text-sm font-semibold transition-all duration-300
                    {{ $billingCycle === 'monthly' ? 'bg-white text-indigo-700 shadow-lg' : 'text-white hover:text-indigo-200' }}">
                    Mensal
                </button>
                <button wire:click="switchCycle('yearly')"
                    class="px-6 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 flex items-center
                    {{ $billingCycle === 'yearly' ? 'bg-white text-indigo-700 shadow-lg' : 'text-white hover:text-indigo-200' }}">
                    Anual
                    <span class="ml-2 bg-green-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">-17%</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <div class="container mx-auto px-4 mt-6">
        @if(session()->has('success'))
            <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 flex items-center">
                <i class="fas fa-check-circle text-xl mr-3"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session()->has('error'))
            <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 flex items-center">
                <i class="fas fa-exclamation-circle text-xl mr-3"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <!-- Pending Payment Banner -->
    @if($pendingPayment)
        <div class="container mx-auto px-4 mt-6">
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-amber-100 text-amber-600 mr-4 flex-shrink-0">
                            <i class="fas fa-hourglass-half text-xl"></i>
                        </div>
                        <div>
                            <p class="font-bold text-amber-800">Pagamento Pendente de Validação</p>
                            <p class="text-sm text-amber-700 mt-1">
                                Plano <strong>{{ $pendingPayment->plan->name }}</strong> &mdash;
                                {{ number_format($pendingPayment->amount, 0, ',', '.') }} {{ $pendingPayment->currency }}
                            </p>
                            <p class="text-xs text-amber-600 mt-1">
                                <i class="fas fa-hashtag mr-1"></i>Referência: <strong class="font-mono">{{ $pendingPayment->reference_code }}</strong>
                                &mdash; Enviado em {{ $pendingPayment->created_at->format('d/m/Y H:i') }}
                            </p>
                            <p class="text-xs text-amber-600 mt-0.5">
                                <i class="fas fa-clock mr-1"></i>A nossa equipa irá validar o pagamento em até 24h úteis.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Current Plan Banner -->
    @if($currentPlan && $activeSubscription)
        <div class="container mx-auto px-4 mt-6">
            <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 flex flex-col md:flex-row items-center justify-between">
                <div class="flex items-center mb-3 md:mb-0">
                    <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 mr-4">
                        <i class="fas fa-crown text-xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-indigo-800">Plano Actual: {{ $currentPlan->name }}</p>
                        <p class="text-sm text-indigo-600">
                            @if($activeSubscription->isTrial())
                                Período gratuito &mdash; expira em {{ $activeSubscription->ends_at->format('d/m/Y') }}
                                ({{ $activeSubscription->daysRemaining() }} dias restantes)
                            @else
                                Activo até {{ $activeSubscription->ends_at->format('d/m/Y') }}
                                ({{ $activeSubscription->daysRemaining() }} dias restantes)
                            @endif
                        </p>
                    </div>
                </div>
                <a href="{{ route('admin.my-subscription') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors text-sm">
                    <i class="fas fa-gem mr-1"></i> Gerir Plano
                </a>
            </div>
        </div>
    @endif

    <!-- Plans Grid -->
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 lg:gap-8 max-w-7xl mx-auto">
            @foreach($plans as $plan)
                <div class="relative flex flex-col bg-white rounded-2xl shadow-lg border-2 transition-all duration-300 hover:shadow-xl hover:-translate-y-1
                    {{ $plan->is_popular ? 'border-indigo-500 ring-4 ring-indigo-100' : 'border-gray-100' }}
                    {{ $currentPlan && $currentPlan->id === $plan->id ? 'ring-4 ring-green-200 border-green-400' : '' }}">

                    @if($plan->is_popular && (!$currentPlan || $currentPlan->id !== $plan->id))
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                            <span class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-1.5 rounded-full text-xs font-bold uppercase shadow-lg">
                                <i class="fas fa-fire mr-1"></i> Mais Popular
                            </span>
                        </div>
                    @endif

                    @if($currentPlan && $currentPlan->id === $plan->id)
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                            <span class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-1.5 rounded-full text-xs font-bold uppercase shadow-lg">
                                <i class="fas fa-check mr-1"></i> Plano Actual
                            </span>
                        </div>
                    @endif

                    <!-- Plan Header -->
                    <div class="p-6 pt-8 text-center border-b border-gray-100">
                        <div class="mb-4">
                            @php
                                $iconMap = ['gift' => ['bg-gray-100','text-gray-500'], 'rocket' => ['bg-blue-100','text-blue-500'], 'star' => ['bg-indigo-100','text-indigo-500'], 'crown' => ['bg-amber-100','text-amber-500']];
                                $ic = $iconMap[$plan->icon] ?? ['bg-gray-100','text-gray-500'];
                            @endphp
                            <div class="w-14 h-14 mx-auto rounded-2xl {{ $ic[0] }} flex items-center justify-center">
                                <i class="fas fa-{{ $plan->icon }} text-2xl {{ $ic[1] }}"></i>
                            </div>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $plan->name }}</h3>
                        <p class="text-sm text-gray-500 mb-4 min-h-[40px]">{{ $plan->description }}</p>

                        <div class="mb-2">
                            @if($plan->is_free)
                                <div class="text-4xl font-extrabold text-green-600">Grátis</div>
                                <p class="text-sm text-gray-500 mt-1">durante 1 ano completo</p>
                            @else
                                @if($billingCycle === 'monthly')
                                    <div class="flex items-baseline justify-center">
                                        <span class="text-4xl font-extrabold text-gray-900">{{ number_format($plan->price_monthly, 0, ',', '.') }}</span>
                                        <span class="text-gray-500 ml-1">AOA/mês</span>
                                    </div>
                                @else
                                    <div class="flex items-baseline justify-center">
                                        <span class="text-4xl font-extrabold text-gray-900">{{ number_format($plan->price_yearly / 12, 0, ',', '.') }}</span>
                                        <span class="text-gray-500 ml-1">AOA/mês</span>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ number_format($plan->price_yearly, 0, ',', '.') }} AOA/ano
                                        @if($plan->yearly_savings > 0)
                                            <span class="text-green-600 font-semibold ml-1">
                                                (poupa {{ number_format($plan->yearly_savings, 0, ',', '.') }} AOA)
                                            </span>
                                        @endif
                                    </p>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Features / Benefits List -->
                    <div class="p-6 flex-1">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Benefícios incluídos:</p>
                        <ul class="space-y-2.5">
                            @foreach($plan->features_list as $feature)
                                <li class="flex items-start text-sm">
                                    @if($feature['included'])
                                        <div class="flex-shrink-0 w-5 h-5 rounded-full bg-green-100 flex items-center justify-center mt-0.5 mr-2.5">
                                            <i class="fas fa-check text-green-600 text-[10px]"></i>
                                        </div>
                                        <span class="text-gray-700">{{ $feature['text'] }}</span>
                                    @else
                                        <div class="flex-shrink-0 w-5 h-5 rounded-full bg-gray-100 flex items-center justify-center mt-0.5 mr-2.5">
                                            <i class="fas fa-times text-gray-400 text-[10px]"></i>
                                        </div>
                                        <span class="text-gray-400 line-through">{{ $feature['text'] }}</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- CTA Button -->
                    <div class="p-6 pt-0">
                        @if($currentPlan && $currentPlan->id === $plan->id)
                            <button disabled class="w-full py-3 px-6 rounded-xl font-semibold text-green-700 bg-green-100 cursor-not-allowed">
                                <i class="fas fa-check mr-2"></i> Plano Actual
                            </button>
                        @elseif($plan->is_free)
                            <button wire:click="selectPlan({{ $plan->id }})"
                                class="w-full py-3 px-6 rounded-xl font-semibold text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-[1.02]">
                                <i class="fas fa-gift mr-2"></i> Começar Grátis
                            </button>
                        @elseif($plan->is_popular)
                            <button wire:click="selectPlan({{ $plan->id }})"
                                class="w-full py-3 px-6 rounded-xl font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-[1.02]">
                                <i class="fas fa-rocket mr-2"></i> Escolher Plano
                            </button>
                        @else
                            <button wire:click="selectPlan({{ $plan->id }})"
                                class="w-full py-3 px-6 rounded-xl font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 border-2 border-gray-200 hover:border-gray-300 transition-all duration-300">
                                Escolher Plano
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Comparison Table -->
    <div class="container mx-auto px-4 pb-12 max-w-6xl">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-8">Comparação Detalhada de Planos</h2>
        <div class="overflow-x-auto bg-white rounded-2xl shadow-lg border border-gray-100">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-gray-700 font-bold">Funcionalidade</th>
                        @foreach($plans as $plan)
                            <th class="px-4 py-4 text-center {{ $plan->is_popular ? 'bg-indigo-50' : '' }}">
                                <span class="font-bold text-gray-800">{{ $plan->name }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="px-6 py-3 text-gray-700 font-medium"><i class="fas fa-hotel text-blue-500 mr-2 w-5 text-center"></i>Propriedades</td>
                        @foreach($plans as $plan)
                            <td class="px-4 py-3 text-center font-semibold {{ $plan->is_popular ? 'bg-indigo-50/50' : '' }}">{{ $plan->max_hotels >= 999 ? 'Ilimitadas' : $plan->max_hotels }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="px-6 py-3 text-gray-700 font-medium"><i class="fas fa-door-open text-green-500 mr-2 w-5 text-center"></i>Tipos de quarto / hotel</td>
                        @foreach($plans as $plan)
                            <td class="px-4 py-3 text-center font-semibold {{ $plan->is_popular ? 'bg-indigo-50/50' : '' }}">{{ $plan->max_room_types_per_hotel >= 999 ? 'Ilimitados' : $plan->max_room_types_per_hotel }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="px-6 py-3 text-gray-700 font-medium"><i class="fas fa-camera text-purple-500 mr-2 w-5 text-center"></i>Fotos / hotel</td>
                        @foreach($plans as $plan)
                            <td class="px-4 py-3 text-center font-semibold {{ $plan->is_popular ? 'bg-indigo-50/50' : '' }}">{{ $plan->max_images_per_hotel >= 999 ? 'Ilimitadas' : $plan->max_images_per_hotel }}</td>
                        @endforeach
                    </tr>
                    @foreach([
                        ['featured_listing', 'Listagem em destaque', 'fa-star', 'text-amber-500'],
                        ['priority_search', 'Prioridade na pesquisa', 'fa-search', 'text-indigo-500'],
                        ['review_responses', 'Responder avaliações', 'fa-reply', 'text-cyan-500'],
                        ['advanced_analytics', 'Analytics avançados', 'fa-chart-bar', 'text-blue-500'],
                        ['restaurant_management', 'Gestão de restaurante', 'fa-utensils', 'text-orange-500'],
                        ['leisure_management', 'Gestão de lazer', 'fa-swimming-pool', 'text-teal-500'],
                        ['promotions', 'Promoções e cupões', 'fa-tags', 'text-pink-500'],
                        ['export_reports', 'Exportar relatórios', 'fa-file-export', 'text-gray-500'],
                        ['priority_support', 'Suporte prioritário', 'fa-headset', 'text-green-500'],
                        ['custom_branding', 'Marca personalizada', 'fa-palette', 'text-red-500'],
                        ['api_access', 'Acesso à API', 'fa-code', 'text-gray-600'],
                    ] as $feat)
                    <tr>
                        <td class="px-6 py-3 text-gray-700 font-medium"><i class="fas {{ $feat[2] }} {{ $feat[3] }} mr-2 w-5 text-center"></i>{{ $feat[1] }}</td>
                        @foreach($plans as $plan)
                            <td class="px-4 py-3 text-center {{ $plan->is_popular ? 'bg-indigo-50/50' : '' }}">
                                @if($plan->{$feat[0]})
                                    <i class="fas fa-check-circle text-green-500"></i>
                                @else
                                    <i class="fas fa-times-circle text-gray-300"></i>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Payment Methods Section -->
    <div class="container mx-auto px-4 pb-12 max-w-4xl">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-8">Métodos de Pagamento</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6 text-center hover:shadow-md transition-shadow">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-blue-100 flex items-center justify-center mb-4">
                    <i class="fas fa-university text-2xl text-blue-600"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Transferência Bancária</h3>
                <p class="text-sm text-gray-500">Faça transferência para a nossa conta e envie o comprovativo. Validação em até 24h.</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-6 text-center hover:shadow-md transition-shadow">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-green-100 flex items-center justify-center mb-4">
                    <i class="fas fa-mobile-alt text-2xl text-green-600"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Multicaixa Express</h3>
                <p class="text-sm text-gray-500">Pague directamente pelo seu telemóvel através do Multicaixa Express. (Em breve)</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-6 text-center hover:shadow-md transition-shadow">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-purple-100 flex items-center justify-center mb-4">
                    <i class="fas fa-barcode text-2xl text-purple-600"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Referência de Pagamento</h3>
                <p class="text-sm text-gray-500">Gere uma referência e pague em qualquer caixa automática ou banco. (Em breve)</p>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="container mx-auto px-4 pb-16 max-w-4xl" x-data="{ openFaq: null }">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-10">Perguntas Frequentes</h2>
        <div class="space-y-3">
            @foreach([
                ['q' => 'Posso mudar de plano a qualquer momento?', 'a' => 'Sim! Pode fazer upgrade do seu plano a qualquer momento. Basta escolher o novo plano, realizar a transferência e aguardar a validação do admin.'],
                ['q' => 'O plano gratuito tem alguma limitação?', 'a' => 'O plano gratuito permite 1 propriedade com até 3 tipos de quarto e 5 fotos. É ideal para começar e testar a plataforma durante 1 ano completo.'],
                ['q' => 'Como funciona o pagamento por transferência?', 'a' => 'Após escolher o plano, receberá os dados bancários para transferência. Depois de transferir, preencha o formulário com os dados da transferência (banco, referência, data). A nossa equipa valida em até 24 horas úteis e o plano é activado automaticamente.'],
                ['q' => 'Posso cancelar a minha subscrição?', 'a' => 'Sim, pode cancelar a qualquer momento no painel de gestão. O seu plano permanecerá activo até ao final do período de facturação pago.'],
                ['q' => 'O que acontece quando a subscrição expira?', 'a' => 'Quando a subscrição expira, o acesso às funcionalidades premium é desactivado automaticamente. As suas propriedades continuam visíveis, mas não poderá fazer alterações até renovar.'],
                ['q' => 'Existe suporte técnico?', 'a' => 'Todos os planos incluem suporte por email. Os planos Profissional e Empresarial incluem suporte prioritário com tempos de resposta mais rápidos.'],
            ] as $i => $faq)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <button @click="openFaq = openFaq === {{ $i }} ? null : {{ $i }}"
                        class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors">
                        <span class="font-semibold text-gray-800">{{ $faq['q'] }}</span>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"
                           :class="{ 'rotate-180': openFaq === {{ $i }} }"></i>
                    </button>
                    <div x-show="openFaq === {{ $i }}" x-collapse>
                        <div class="px-5 pb-5 text-gray-600">{{ $faq['a'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- ═══════════════════ MODALS ═══════════════════ -->

    <!-- Step 1: Confirm Plan Selection -->
    @if($showConfirmModal && $selectedPlanId)
        @php $selectedPlan = \App\Models\Plan::find($selectedPlanId); @endphp
        @if($selectedPlan)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeAllModals"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md z-50 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-8 text-center text-white">
                        <div class="w-16 h-16 mx-auto rounded-2xl bg-white/20 flex items-center justify-center mb-4">
                            <i class="fas fa-{{ $selectedPlan->icon }} text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold">{{ $selectedPlan->name }}</h3>
                        <div class="mt-2">
                            @if($selectedPlan->is_free)
                                <span class="text-3xl font-extrabold">Grátis</span>
                                <span class="text-purple-200"> / 1 ano</span>
                            @elseif($selectedCycle === 'yearly')
                                <span class="text-3xl font-extrabold">{{ number_format($selectedPlan->price_yearly, 0, ',', '.') }}</span>
                                <span class="text-purple-200"> AOA/ano</span>
                            @else
                                <span class="text-3xl font-extrabold">{{ number_format($selectedPlan->price_monthly, 0, ',', '.') }}</span>
                                <span class="text-purple-200"> AOA/mês</span>
                            @endif
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Key Benefits -->
                        <div class="mb-5">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">O que está incluído:</p>
                            <div class="space-y-1.5">
                                @foreach(collect($selectedPlan->features_list)->where('included', true)->take(6) as $feat)
                                    <div class="flex items-center text-sm text-gray-700">
                                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>{{ $feat['text'] }}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-4 mb-5">
                            <div class="space-y-1.5 text-sm text-gray-600">
                                <div class="flex justify-between">
                                    <span>Plano</span>
                                    <span class="font-medium text-gray-800">{{ $selectedPlan->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Ciclo</span>
                                    <span class="font-medium text-gray-800">
                                        {{ $selectedPlan->is_free ? '1 Ano (gratuito)' : ($selectedCycle === 'yearly' ? 'Anual' : 'Mensal') }}
                                    </span>
                                </div>
                                <div class="flex justify-between border-t border-gray-200 pt-1.5 mt-1.5">
                                    <span class="font-bold text-gray-800">Total a pagar</span>
                                    <span class="font-bold text-indigo-600 text-lg">
                                        @if($selectedPlan->is_free)
                                            0 AOA
                                        @elseif($selectedCycle === 'yearly')
                                            {{ number_format($selectedPlan->price_yearly, 0, ',', '.') }} AOA
                                        @else
                                            {{ number_format($selectedPlan->price_monthly, 0, ',', '.') }} AOA
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if(!$selectedPlan->is_free)
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 mb-5 text-sm text-blue-700">
                                <i class="fas fa-info-circle mr-1"></i>
                                Após confirmar, será direccionado para submeter os dados da transferência bancária.
                            </div>
                        @endif

                        <div class="flex gap-3">
                            <button wire:click="closeAllModals"
                                class="flex-1 py-3 px-4 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl font-semibold transition-colors">
                                Cancelar
                            </button>
                            <button wire:click="confirmSubscription" wire:loading.attr="disabled"
                                class="flex-1 py-3 px-4 text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-xl font-semibold shadow-md transition-all">
                                <span wire:loading.remove wire:target="confirmSubscription">
                                    @if($selectedPlan->is_free)
                                        <i class="fas fa-check mr-1"></i> Activar Grátis
                                    @else
                                        <i class="fas fa-arrow-right mr-1"></i> Continuar
                                    @endif
                                </span>
                                <span wire:loading wire:target="confirmSubscription">
                                    <i class="fas fa-spinner fa-spin mr-1"></i> Processando...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif

    <!-- Step 2: Bank Transfer Form -->
    @if($showTransferModal && $selectedPlanId)
        @php $transferPlan = \App\Models\Plan::find($selectedPlanId); @endphp
        @if($transferPlan)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 py-6">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeAllModals"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg z-50 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold">Pagamento por Transferência</h3>
                                <p class="text-blue-200 text-sm">{{ $transferPlan->name }} &mdash; {{ $selectedCycle === 'yearly' ? 'Anual' : 'Mensal' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold">
                                    {{ number_format($selectedCycle === 'yearly' ? $transferPlan->price_yearly : $transferPlan->price_monthly, 0, ',', '.') }}
                                </p>
                                <p class="text-blue-200 text-xs">AOA</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 max-h-[70vh] overflow-y-auto">
                        <!-- Bank Details to Transfer To -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4 mb-5">
                            <h4 class="text-sm font-bold text-blue-800 mb-3">
                                <i class="fas fa-university mr-1"></i> Dados Bancários para Transferência
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-blue-600">Banco:</span>
                                    <span class="font-bold text-blue-900">{{ \App\Models\Setting::get('bank_name', 'BAI - Banco Angolano de Investimentos') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-blue-600">Titular:</span>
                                    <span class="font-bold text-blue-900">{{ \App\Models\Setting::get('bank_holder', \App\Models\Setting::get('app_name', 'KiandaStay') . ' Lda') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-blue-600">IBAN:</span>
                                    <span class="font-mono font-bold text-blue-900">{{ \App\Models\Setting::get('bank_iban', 'AO06.0040.0000.0000.0000.0000.0') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-blue-600">Nº Conta:</span>
                                    <span class="font-mono font-bold text-blue-900">{{ \App\Models\Setting::get('bank_account', '0000.0000.0000.0000.0') }}</span>
                                </div>
                                <div class="flex justify-between border-t border-blue-200 pt-2 mt-2">
                                    <span class="text-blue-600 font-medium">Valor a transferir:</span>
                                    <span class="font-extrabold text-blue-900 text-lg">
                                        {{ number_format($selectedCycle === 'yearly' ? $transferPlan->price_yearly : $transferPlan->price_monthly, 0, ',', '.') }} AOA
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 mb-5 text-sm text-amber-700">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Após realizar a transferência, preencha os dados abaixo. A validação será feita em até <strong>24 horas úteis</strong>.
                        </div>

                        <!-- Transfer Form -->
                        <form wire:submit="submitTransfer" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Banco de Origem *</label>
                                <input type="text" wire:model="bankName" placeholder="Ex: BAI, BFA, BIC, BMA..."
                                    class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm text-sm">
                                @error('bankName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nome do Titular *</label>
                                <input type="text" wire:model="accountHolder" placeholder="Nome completo do titular da conta"
                                    class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm text-sm">
                                @error('accountHolder') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Ref. da Transferência *</label>
                                    <input type="text" wire:model="transferReference" placeholder="Nº do comprovativo"
                                        class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm text-sm">
                                    @error('transferReference') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Data da Transferência *</label>
                                    <input type="date" wire:model="transferDate" max="{{ date('Y-m-d') }}"
                                        class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm text-sm">
                                    @error('transferDate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Comprovativo de Transferência *</label>
                                <div class="relative">
                                    <input type="file" wire:model="proofFile" accept=".jpg,.jpeg,.png,.pdf"
                                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-xl cursor-pointer">
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
                                <label class="block text-sm font-medium text-gray-700 mb-1">Notas adicionais (opcional)</label>
                                <textarea wire:model="userNotes" rows="2" placeholder="Informação adicional sobre a transferência..."
                                    class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm text-sm"></textarea>
                            </div>

                            <div class="flex gap-3 pt-2">
                                <button type="button" wire:click="closeAllModals"
                                    class="flex-1 py-3 px-4 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl font-semibold transition-colors">
                                    Cancelar
                                </button>
                                <button type="submit" wire:loading.attr="disabled"
                                    class="flex-1 py-3 px-4 text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-xl font-semibold shadow-md transition-all">
                                    <span wire:loading.remove wire:target="submitTransfer">
                                        <i class="fas fa-paper-plane mr-1"></i> Enviar Comprovativo
                                    </span>
                                    <span wire:loading wire:target="submitTransfer">
                                        <i class="fas fa-spinner fa-spin mr-1"></i> Enviando...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif

    <!-- Step 3: Success Modal -->
    @if($showSuccessModal && $paymentRefCode)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md z-50 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-8 text-center text-white">
                    <div class="w-20 h-20 mx-auto rounded-full bg-white/20 flex items-center justify-center mb-4">
                        <i class="fas fa-check-circle text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold">Pagamento Enviado!</h3>
                </div>
                <div class="p-6 text-center">
                    <p class="text-gray-600 mb-4">
                        O seu comprovativo de transferência foi enviado com sucesso. A nossa equipa irá validar em até <strong>24 horas úteis</strong>.
                    </p>
                    <div class="bg-gray-50 rounded-xl p-4 mb-5">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Código de Referência</p>
                        <p class="text-xl font-mono font-bold text-gray-800">{{ $paymentRefCode }}</p>
                        <p class="text-xs text-gray-500 mt-1">Guarde este código para acompanhar o estado</p>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 mb-5 text-sm text-blue-700 text-left">
                        <p class="font-medium mb-1"><i class="fas fa-info-circle mr-1"></i> Próximos passos:</p>
                        <ol class="list-decimal list-inside space-y-1 text-blue-600">
                            <li>A equipa valida a transferência bancária</li>
                            <li>Receberá uma notificação de confirmação</li>
                            <li>O plano é activado automaticamente</li>
                        </ol>
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
