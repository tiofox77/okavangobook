<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center">
            <i class="fas fa-user-circle text-blue-500 mr-3"></i>
            Meu Perfil
        </h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Gerir informações da sua conta</p>
    </div>

    <!-- Profile Header Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center gap-6">
            <div class="flex-shrink-0">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ $user->name }}</h2>
                <p class="text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach($user->roles as $role)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $role->name === 'Admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                            <i class="fas {{ $role->name === 'Admin' ? 'fa-shield-alt' : 'fa-building' }} mr-1"></i>
                            {{ $role->name }}
                        </span>
                    @endforeach
                    @if($plan)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            <i class="fas fa-gem mr-1"></i> Plano {{ $plan->name }}
                        </span>
                    @endif
                </div>
            </div>
            @if($subscription)
                <div class="flex-shrink-0 text-right">
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-xl p-4 border border-indigo-100 dark:border-indigo-800">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Plano Actual</p>
                        <p class="text-lg font-bold text-indigo-700 dark:text-indigo-400">{{ $plan->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ $subscription->daysRemaining() }} dias restantes
                        </p>
                        <a href="{{ route('admin.my-subscription') }}" class="inline-flex items-center text-xs text-indigo-600 hover:text-indigo-800 mt-1 font-medium">
                            Gerir Plano <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            @else
                <div class="flex-shrink-0">
                    <a href="{{ route('pricing') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg text-sm font-medium hover:from-indigo-700 hover:to-purple-700 transition-all shadow-md">
                        <i class="fas fa-gem mr-2"></i> Escolher Plano
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex">
                <button wire:click="changeTab('profile')" class="px-6 py-4 text-sm font-medium transition-colors {{ $activeTab === 'profile' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400' }}">
                    <i class="fas fa-user mr-2"></i> Dados Pessoais
                </button>
                <button wire:click="changeTab('password')" class="px-6 py-4 text-sm font-medium transition-colors {{ $activeTab === 'password' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400' }}">
                    <i class="fas fa-lock mr-2"></i> Alterar Senha
                </button>
                <button wire:click="changeTab('activity')" class="px-6 py-4 text-sm font-medium transition-colors {{ $activeTab === 'activity' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400' }}">
                    <i class="fas fa-history mr-2"></i> Actividade
                </button>
            </nav>
        </div>

        <div class="p-6">
            @if($activeTab === 'profile')
                <!-- Profile Form -->
                @if(session()->has('profile_success'))
                    <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('profile_success') }}
                    </div>
                @endif

                <form wire:submit="updateProfile" class="space-y-5 max-w-2xl">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                <i class="fas fa-user text-gray-400 mr-1"></i> Nome Completo
                            </label>
                            <input type="text" wire:model="name" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm" placeholder="Seu nome">
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                <i class="fas fa-envelope text-gray-400 mr-1"></i> Email
                            </label>
                            <input type="email" wire:model="email" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm" placeholder="seu@email.com">
                            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="max-w-sm">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            <i class="fas fa-phone text-gray-400 mr-1"></i> Telefone
                        </label>
                        <input type="text" wire:model="phone" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm" placeholder="+244 9XX XXX XXX">
                        @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Membro desde</span>
                                <p class="font-medium text-gray-800 dark:text-white">{{ $user->created_at->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Último acesso</span>
                                <p class="font-medium text-gray-800 dark:text-white">{{ $user->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md transition-all disabled:opacity-50">
                            <i class="fas fa-save mr-2" wire:loading.remove wire:target="updateProfile"></i>
                            <i class="fas fa-spinner fa-spin mr-2" wire:loading wire:target="updateProfile"></i>
                            Guardar Alterações
                        </button>
                    </div>
                </form>

            @elseif($activeTab === 'password')
                <!-- Password Form -->
                @if(session()->has('password_success'))
                    <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('password_success') }}
                    </div>
                @endif

                <form wire:submit="updatePassword" class="space-y-5 max-w-lg">
                    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4 mb-4">
                        <div class="flex items-start">
                            <i class="fas fa-shield-alt text-amber-500 mt-0.5 mr-3"></i>
                            <div class="text-sm text-amber-700 dark:text-amber-300">
                                <p class="font-medium">Segurança da Conta</p>
                                <p class="mt-1 text-amber-600 dark:text-amber-400">Use uma senha forte com pelo menos 8 caracteres, incluindo letras maiúsculas, minúsculas e números.</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            <i class="fas fa-lock text-gray-400 mr-1"></i> Senha Actual
                        </label>
                        <input type="password" wire:model="current_password" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 shadow-sm" placeholder="••••••••">
                        @error('current_password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            <i class="fas fa-key text-gray-400 mr-1"></i> Nova Senha
                        </label>
                        <input type="password" wire:model="new_password" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 shadow-sm" placeholder="••••••••">
                        @error('new_password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            <i class="fas fa-key text-gray-400 mr-1"></i> Confirmar Nova Senha
                        </label>
                        <input type="password" wire:model="new_password_confirmation" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 shadow-sm" placeholder="••••••••">
                    </div>

                    <div class="pt-2">
                        <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md transition-all disabled:opacity-50">
                            <i class="fas fa-lock mr-2" wire:loading.remove wire:target="updatePassword"></i>
                            <i class="fas fa-spinner fa-spin mr-2" wire:loading wire:target="updatePassword"></i>
                            Alterar Senha
                        </button>
                    </div>
                </form>

            @elseif($activeTab === 'activity')
                <!-- Activity / Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-100 dark:border-blue-800">
                        <div class="flex items-center">
                            <div class="p-2.5 rounded-lg bg-blue-100 dark:bg-blue-800 text-blue-600 mr-3">
                                <i class="fas fa-hotel text-lg"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $user->managedHotels()->count() }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Propriedades</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 border border-green-100 dark:border-green-800">
                        <div class="flex items-center">
                            <div class="p-2.5 rounded-lg bg-green-100 dark:bg-green-800 text-green-600 mr-3">
                                <i class="fas fa-calendar-check text-lg"></i>
                            </div>
                            <div>
                                @php
                                    $hotelIds = $user->managedHotels()->pluck('id');
                                    $totalReservations = \App\Models\Reservation::whereIn('hotel_id', $hotelIds)->count();
                                @endphp
                                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalReservations }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Reservas Totais</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-amber-50 dark:bg-amber-900/20 rounded-xl p-4 border border-amber-100 dark:border-amber-800">
                        <div class="flex items-center">
                            <div class="p-2.5 rounded-lg bg-amber-100 dark:bg-amber-800 text-amber-600 mr-3">
                                <i class="fas fa-star text-lg"></i>
                            </div>
                            <div>
                                @php
                                    $totalReviews = \App\Models\Review::whereIn('hotel_id', $hotelIds)->count();
                                @endphp
                                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalReviews }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Avaliações</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Notifications -->
                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-3">
                    <i class="fas fa-bell mr-1"></i> Notificações Recentes
                </h3>
                @php
                    $recentNotifications = $user->notifications()->latest()->take(10)->get();
                @endphp
                @if($recentNotifications->count() > 0)
                    <div class="space-y-2">
                        @foreach($recentNotifications as $notif)
                            <div class="flex items-start p-3 rounded-lg {{ $notif->is_read ? 'bg-gray-50 dark:bg-gray-700/30' : 'bg-blue-50 dark:bg-blue-900/10' }}">
                                <div class="flex-shrink-0 {{ $notif->badge_color }} rounded-full p-1.5 mt-0.5">
                                    <svg class="h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">{!! $notif->icon_svg !!}</svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm {{ $notif->is_read ? 'text-gray-600 dark:text-gray-400' : 'font-medium text-gray-800 dark:text-white' }}">{{ $notif->title }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $notif->time_ago }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-bell-slash text-3xl mb-2 text-gray-300"></i>
                        <p class="text-sm">Sem notificações recentes</p>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
