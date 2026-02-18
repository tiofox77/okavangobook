<div>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center">
                    <i class="fas fa-gem text-purple-500 mr-3"></i>
                    Gestão de Planos
                </h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Gerir pacotes de subscrição da plataforma</p>
            </div>
            <button wire:click="openModal" class="inline-flex items-center px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-md transition-all duration-200">
                <i class="fas fa-plus mr-2"></i> Novo Plano
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
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

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600">
                    <i class="fas fa-layer-group text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Planos</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['total_plans'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Planos Activos</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['active_plans'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Subscrições Activas</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['total_subscriptions'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-600">
                    <i class="fas fa-coins text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Receita Mensal</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($stats['monthly_revenue'], 0, ',', '.') }} <span class="text-sm font-normal">AOA</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="mb-4">
        <div class="relative w-full md:w-96">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Pesquisar planos..." class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        </div>
    </div>

    <!-- Plans Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        @foreach($plans as $plan)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border-2 {{ $plan->is_popular ? 'border-indigo-500 ring-2 ring-indigo-200 dark:ring-indigo-800' : 'border-gray-100 dark:border-gray-700' }} overflow-hidden relative">
                @if($plan->is_popular)
                    <div class="absolute top-0 right-0 bg-indigo-500 text-white px-3 py-1 rounded-bl-lg text-xs font-bold uppercase">
                        <i class="fas fa-fire mr-1"></i>Popular
                    </div>
                @endif

                <!-- Plan Header -->
                <div class="p-6 text-center border-b border-gray-100 dark:border-gray-700 bg-gradient-to-br
                    {{ $plan->badge_color === 'gray' ? 'from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-750' : '' }}
                    {{ $plan->badge_color === 'blue' ? 'from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20' : '' }}
                    {{ $plan->badge_color === 'indigo' ? 'from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20' : '' }}
                    {{ $plan->badge_color === 'amber' ? 'from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20' : '' }}
                ">
                    <div class="mb-3">
                        @if($plan->icon === 'gift')
                            <i class="fas fa-gift text-3xl text-gray-500"></i>
                        @elseif($plan->icon === 'rocket')
                            <i class="fas fa-rocket text-3xl text-blue-500"></i>
                        @elseif($plan->icon === 'star')
                            <i class="fas fa-star text-3xl text-indigo-500"></i>
                        @elseif($plan->icon === 'crown')
                            <i class="fas fa-crown text-3xl text-amber-500"></i>
                        @else
                            <i class="fas fa-gem text-3xl text-purple-500"></i>
                        @endif
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">{{ $plan->name }}</h3>
                    <div class="mt-2">
                        @if($plan->is_free)
                            <span class="text-3xl font-extrabold text-green-600">Grátis</span>
                            <p class="text-sm text-gray-500 mt-1">por 1 ano</p>
                        @else
                            <span class="text-3xl font-extrabold text-gray-800 dark:text-white">{{ number_format($plan->price_monthly, 0, ',', '.') }}</span>
                            <span class="text-sm text-gray-500"> AOA/mês</span>
                        @endif
                    </div>
                    <div class="mt-2 flex items-center justify-center gap-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $plan->is_active ? 'Activo' : 'Inactivo' }}
                        </span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $plan->activeSubscriptions->count() }} subs
                        </span>
                    </div>
                </div>

                <!-- Features -->
                <div class="p-4 space-y-2 text-sm">
                    <div class="flex items-center text-gray-600 dark:text-gray-300">
                        <i class="fas fa-building w-5 text-center mr-2 text-purple-500"></i>
                        <span>{{ $plan->max_hotels >= 999 ? 'Ilimitadas' : $plan->max_hotels }} propriedade{{ $plan->max_hotels != 1 ? 's' : '' }}</span>
                    </div>
                    <div class="flex items-center text-gray-600 dark:text-gray-300">
                        <i class="fas fa-bed w-5 text-center mr-2 text-blue-500"></i>
                        <span>{{ $plan->max_room_types_per_hotel >= 999 ? 'Ilimitados' : $plan->max_room_types_per_hotel }} tipos de quarto</span>
                    </div>
                    <div class="flex items-center text-gray-600 dark:text-gray-300">
                        <i class="fas fa-images w-5 text-center mr-2 text-teal-500"></i>
                        <span>{{ $plan->max_images_per_hotel >= 999 ? 'Ilimitadas' : $plan->max_images_per_hotel }} fotos/hotel</span>
                    </div>
                    <div class="flex items-center {{ $plan->featured_listing ? 'text-green-600' : 'text-gray-400' }}">
                        <i class="fas {{ $plan->featured_listing ? 'fa-check' : 'fa-times' }} w-5 text-center mr-2"></i>
                        <span>Listagem destaque</span>
                    </div>
                    <div class="flex items-center {{ $plan->advanced_analytics ? 'text-green-600' : 'text-gray-400' }}">
                        <i class="fas {{ $plan->advanced_analytics ? 'fa-check' : 'fa-times' }} w-5 text-center mr-2"></i>
                        <span>Analytics avançados</span>
                    </div>
                    <div class="flex items-center {{ $plan->priority_support ? 'text-green-600' : 'text-gray-400' }}">
                        <i class="fas {{ $plan->priority_support ? 'fa-check' : 'fa-times' }} w-5 text-center mr-2"></i>
                        <span>Suporte prioritário</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="p-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between gap-2">
                    <button wire:click="openModal({{ $plan->id }})" class="flex-1 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 text-sm font-medium transition-colors">
                        <i class="fas fa-edit mr-1"></i> Editar
                    </button>
                    <button wire:click="viewSubscriptions({{ $plan->id }})" class="flex-1 px-3 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/40 text-sm font-medium transition-colors">
                        <i class="fas fa-users mr-1"></i> Subs
                    </button>
                    <button wire:click="toggleActive({{ $plan->id }})" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $plan->is_active ? 'bg-yellow-50 text-yellow-600 hover:bg-yellow-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }}" title="{{ $plan->is_active ? 'Desactivar' : 'Activar' }}">
                        <i class="fas {{ $plan->is_active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $plans->links() }}
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/60 transition-opacity" wire:click="closeModal"></div>

            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto z-50">
                <!-- Modal Header -->
                <div class="sticky top-0 bg-white dark:bg-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between z-10">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                        <i class="fas fa-gem text-purple-500 mr-2"></i>
                        {{ $editingPlanId ? 'Editar Plano' : 'Novo Plano' }}
                    </h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <form wire:submit="save" class="p-6 space-y-6">
                    <!-- Basic Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome *</label>
                            <input type="text" wire:model.live="name" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500" placeholder="Ex: Profissional">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Slug *</label>
                            <input type="text" wire:model="slug" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500" placeholder="profissional">
                            @error('slug') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
                        <textarea wire:model="description" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500" placeholder="Breve descrição do plano..."></textarea>
                    </div>

                    <!-- Visual -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cor do Badge</label>
                            <select wire:model="badge_color" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
                                <option value="gray">Cinzento</option>
                                <option value="blue">Azul</option>
                                <option value="indigo">Indigo</option>
                                <option value="purple">Roxo</option>
                                <option value="amber">Dourado</option>
                                <option value="green">Verde</option>
                                <option value="red">Vermelho</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ícone</label>
                            <select wire:model="icon" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
                                <option value="gift">🎁 Presente</option>
                                <option value="rocket">🚀 Foguete</option>
                                <option value="star">⭐ Estrela</option>
                                <option value="crown">👑 Coroa</option>
                                <option value="gem">💎 Diamante</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ordem</label>
                            <input type="number" wire:model="sort_order" min="0" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                        <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wider">
                            <i class="fas fa-tag mr-1"></i> Preços (AOA)
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Mensal</label>
                                <input type="number" wire:model="price_monthly" min="0" step="100" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500" {{ $is_free ? 'disabled' : '' }}>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Anual</label>
                                <input type="number" wire:model="price_yearly" min="0" step="100" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500" {{ $is_free ? 'disabled' : '' }}>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Dias de Teste</label>
                                <input type="number" wire:model="trial_days" min="0" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
                            </div>
                        </div>
                    </div>

                    <!-- Limits -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                        <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wider">
                            <i class="fas fa-sliders-h mr-1"></i> Limites
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Max Hotéis</label>
                                <input type="number" wire:model="max_hotels" min="1" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
                                <p class="text-xs text-gray-400 mt-1">999 = ilimitado</p>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Tipos Quarto/Hotel</label>
                                <input type="number" wire:model="max_room_types_per_hotel" min="1" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Fotos/Hotel</label>
                                <input type="number" wire:model="max_images_per_hotel" min="1" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Fotos/Quarto</label>
                                <input type="number" wire:model="max_images_per_room" min="1" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
                            </div>
                        </div>
                    </div>

                    <!-- Features Toggles -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                        <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wider">
                            <i class="fas fa-puzzle-piece mr-1"></i> Funcionalidades
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach([
                                'featured_listing' => 'Listagem em Destaque',
                                'priority_search' => 'Prioridade na Pesquisa',
                                'advanced_analytics' => 'Analytics Avançados',
                                'review_responses' => 'Responder Avaliações',
                                'restaurant_management' => 'Gestão de Restaurante',
                                'leisure_management' => 'Gestão de Lazer',
                                'promotions' => 'Promoções e Cupões',
                                'export_reports' => 'Exportar Relatórios',
                                'priority_support' => 'Suporte Prioritário',
                                'custom_branding' => 'Marca Personalizada',
                                'api_access' => 'Acesso à API',
                            ] as $field => $label)
                                <label class="flex items-center space-x-2 p-2 rounded-lg hover:bg-white dark:hover:bg-gray-700 transition-colors cursor-pointer">
                                    <input type="checkbox" wire:model="{{ $field }}" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Status Flags -->
                    <div class="flex flex-wrap gap-6">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Activo</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" wire:model="is_popular" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Popular</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" wire:model="is_free" class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Gratuito</span>
                        </label>
                    </div>

                    <!-- Submit -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" wire:click="closeModal" class="px-5 py-2.5 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 font-medium transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium shadow-md transition-colors">
                            <i class="fas fa-save mr-2"></i>
                            {{ $editingPlanId ? 'Actualizar' : 'Criar Plano' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Subscriptions Modal -->
    @if($showSubscriptionsModal && $viewingPlanId)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/60" wire:click="closeSubscriptionsModal"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[80vh] overflow-y-auto z-50">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800 dark:text-white">
                        <i class="fas fa-users text-blue-500 mr-2"></i> Subscrições do Plano
                    </h2>
                    <button wire:click="closeSubscriptionsModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-6">
                    @if($viewingPlanSubscriptions && $viewingPlanSubscriptions->count() > 0)
                        <div class="space-y-3">
                            @foreach($viewingPlanSubscriptions as $sub)
                                <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 font-bold text-sm mr-3">
                                            {{ strtoupper(substr($sub->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $sub->user->name ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500">{{ $sub->user->email ?? '' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $sub->status_badge }}">
                                            {{ $sub->status_label }}
                                        </span>
                                        <p class="text-xs text-gray-500 mt-1">Expira: {{ $sub->ends_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                            <p>Nenhuma subscrição para este plano.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
