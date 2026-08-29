<div id="sidebar" class="sidebar bg-gray-800 text-white h-screen fixed top-0 left-0 z-30 transition-all duration-300 shadow-xl flex flex-col">
    <div class="admin-brand-header px-3 py-2 flex justify-between items-center gap-2 border-b border-gray-700">
        <a href="{{ route('admin.dashboard') }}" class="admin-brand-link flex min-w-0 items-center rounded-xl bg-white p-1.5 shadow-sm" aria-label="KiandaStay Admin">
            <img src="{{ asset('assets/img/branding/kiandastay-logo.png') }}?v=20260813"
                 alt="KiandaStay Admin"
                 width="150" height="102"
                 class="admin-brand-full h-14 w-auto object-contain">
            <img src="{{ asset('assets/img/branding/kiandastay-mark.png') }}?v=20260813"
                 alt=""
                 width="44" height="44"
                 class="admin-brand-mark hidden h-10 w-10 object-contain">
        </a>
        <button id="sidebar-toggle" aria-label="Recolher menu lateral" title="Recolher menu" class="sidebar-toggle-button flex-shrink-0 text-gray-300 hover:text-white transition-colors duration-200 p-1 rounded-md hover:bg-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
        </button>
    </div>
    
    <!-- Links do menu -->
    <nav class="flex-1 overflow-y-auto mt-4 px-2 pb-20">
        <a href="{{ route('admin.dashboard') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.dashboard') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-blue-500 bg-opacity-20 text-blue-400">
                <i class="fas fa-tachometer-alt"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Dashboard</span>
            <span class="tooltip-text">Dashboard</span>
        </a>
        
        <a href="{{ route('admin.notifications') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.notifications') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-rose-500 bg-opacity-20 text-rose-400">
                <i class="fas fa-bell"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Notificações</span>
            <span class="tooltip-text">Notificações</span>
        </a>
        
        @role('Admin')
        <!-- Menu Analytics (com submenu) -->
        <div class="mb-1" x-data="{ open: {{ request()->routeIs('admin.analytics') || request()->routeIs('admin.analytics.visits') ? 'true' : 'false' }} }">
            <a href="#" @click.prevent="open = !open" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.analytics*') ? 'active bg-gray-700' : '' }} tooltip">
                <div class="icon-container bg-indigo-500 bg-opacity-20 text-indigo-400">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <span class="sidebar-link-text transition-opacity duration-300 flex-1">Analytics</span>
                <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200 sidebar-link-text" :class="{ 'rotate-180': open }"></i>
                <span class="tooltip-text">Relatórios e Analytics</span>
            </a>

            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="ml-8 space-y-1">
                <a href="{{ route('admin.analytics') }}" class="menu-item flex items-center py-2 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.analytics') ? 'active bg-gray-700' : '' }} tooltip text-sm">
                    <div class="icon-container bg-indigo-500 bg-opacity-20 text-indigo-400 w-8 h-8">
                        <i class="fas fa-chart-pie text-sm"></i>
                    </div>
                    <span class="sidebar-link-text transition-opacity duration-300 ml-2">Resumo</span>
                    <span class="tooltip-text">Resumo &amp; Relatórios</span>
                </a>

                <a href="{{ route('admin.analytics.visits') }}" class="menu-item flex items-center py-2 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.analytics.visits') ? 'active bg-gray-700' : '' }} tooltip text-sm">
                    <div class="icon-container bg-green-500 bg-opacity-20 text-green-400 w-8 h-8">
                        <i class="fas fa-chart-line text-sm"></i>
                    </div>
                    <span class="sidebar-link-text transition-opacity duration-300 ml-2">Visitas &amp; Tráfego</span>
                    <span class="tooltip-text">Visitas, Dispositivos, Localização</span>
                </a>
            </div>
        </div>
        
        <a href="{{ route('admin.agent-tokens') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.agent-tokens') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-cyan-500 bg-opacity-20 text-cyan-400">
                <i class="fas fa-key"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Tokens API</span>
            <span class="tooltip-text">Tokens da Agent API</span>
        </a>

        <a href="{{ route('admin.articles') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.articles') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-purple-500 bg-opacity-20 text-purple-400">
                <i class="fas fa-newspaper"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Artigos/Blog</span>
            <span class="tooltip-text">Gerir Conteúdo</span>
        </a>
        
        <a href="{{ route('admin.reports.reservations') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.reports.reservations') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-teal-500 bg-opacity-20 text-teal-400">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Relatórios</span>
            <span class="tooltip-text">Relatórios de Reservas</span>
        </a>
        @endrole
        
        <a href="{{ route('admin.hotels') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.hotels') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-amber-500 bg-opacity-20 text-amber-400">
                <i class="fas fa-hotel"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Propriedades</span>
            <span class="tooltip-text">Gerir Propriedades</span>
        </a>
        
        <!-- Menu para Quartos -->
        <a href="{{ route('admin.rooms') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.rooms') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-green-500 bg-opacity-20 text-green-400">
                <i class="fas fa-door-open"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Quartos</span>
            <span class="tooltip-text">Gerir Quartos</span>
        </a>
        
        <!-- Menu para Quartos Individuais -->
        <a href="{{ route('admin.individual-rooms') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.individual-rooms') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-teal-500 bg-opacity-20 text-teal-400">
                <i class="fas fa-door-closed"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Quartos Individuais</span>
            <span class="tooltip-text">Gerir Quartos Individuais</span>
        </a>
        
        <!-- Menu para Comodidades -->
        <a href="{{ route('admin.amenities') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.amenities') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-blue-500 bg-opacity-20 text-blue-400">
                <i class="fas fa-concierge-bell"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Comodidades</span>
            <span class="tooltip-text">Gerir Comodidades</span>
        </a>
        
        <!-- Menu para Restaurante -->
        <a href="{{ route('admin.restaurant') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.restaurant') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-orange-500 bg-opacity-20 text-orange-400">
                <i class="fas fa-utensils"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Restaurante</span>
            <span class="tooltip-text">Gerir Menu do Restaurante</span>
        </a>
        
        <!-- Menu para Instalações de Lazer -->
        <a href="{{ route('admin.leisure') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.leisure') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-cyan-500 bg-opacity-20 text-cyan-400">
                <i class="fas fa-swimming-pool"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Lazer</span>
            <span class="tooltip-text">Gerir Instalações de Lazer</span>
        </a>
        
        @role('Admin')
        <a href="{{ route('admin.locations') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.locations') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-purple-500 bg-opacity-20 text-purple-400">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Localizações</span>
            <span class="tooltip-text">Gerir Localizações</span>
        </a>
        @endrole
        
        <!-- Menu para Reservas -->
        <a href="{{ route('admin.reservations') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.reservations') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-indigo-500 bg-opacity-20 text-indigo-400">
                <i class="fas fa-calendar-check"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Reservas</span>
            <span class="tooltip-text">Gerir Reservas</span>
        </a>
        
        @role('Admin')
        <!-- Menu para Cupons -->
        <a href="{{ route('admin.coupons') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.coupons') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-pink-500 bg-opacity-20 text-pink-400">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Cupons</span>
            <span class="tooltip-text">Gerir Cupons de Desconto</span>
        </a>
        
        <!-- Menu para Newsletter -->
        <a href="{{ route('admin.newsletter') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.newsletter') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-green-500 bg-opacity-20 text-green-400">
                <i class="fas fa-envelope"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Newsletter</span>
            <span class="tooltip-text">Gerir Newsletter</span>
        </a>
        
        <a href="{{ route('admin.newsletter.send') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.newsletter.send') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-blue-500 bg-opacity-20 text-blue-400">
                <i class="fas fa-paper-plane"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Enviar Email</span>
            <span class="tooltip-text">Enviar Newsletter</span>
        </a>
        
        <a href="{{ route('admin.users') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.users') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-pink-500 bg-opacity-20 text-pink-400">
                <i class="fas fa-users"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Utilizadores</span>
            <span class="tooltip-text">Gerir Utilizadores</span>
        </a>
        <!-- Menu para Planos -->
        <a href="{{ route('admin.plans') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.plans') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-purple-500 bg-opacity-20 text-purple-400">
                <i class="fas fa-gem"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Planos</span>
            <span class="tooltip-text">Gerir Planos de Subscrição</span>
        </a>
        
        <!-- Menu para Pagamentos -->
        <a href="{{ route('admin.payments') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.payments') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-green-500 bg-opacity-20 text-green-400">
                <i class="fas fa-credit-card"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Pagamentos</span>
            @php $pendingPaymentsCount = \App\Models\PaymentTransaction::where('status', 'pending')->count(); @endphp
            @if($pendingPaymentsCount > 0)
                <span class="ml-auto bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold sidebar-link-text">{{ $pendingPaymentsCount }}</span>
            @endif
            <span class="tooltip-text">Gerir Pagamentos</span>
        </a>
        @endrole
        
        <div class="border-t border-gray-700 my-4 opacity-50"></div>
        
        @role('Admin')
        <!-- Menu Configurações -->
        <div class="mb-1" x-data="{ open: {{ request()->routeIs('admin.settings') || request()->routeIs('admin.updates') ? 'true' : 'false' }} }">
            <a href="#" @click.prevent="open = !open" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 tooltip">
                <div class="icon-container bg-gray-500 bg-opacity-20 text-gray-400">
                    <i class="fas fa-cog"></i>
                </div>
                <span class="sidebar-link-text transition-opacity duration-300 flex-1">Configurações</span>
                <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                <span class="tooltip-text">Configurações</span>
            </a>
            
            <!-- Submenu Configurações -->
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="ml-8 space-y-1">
                <a href="{{ route('admin.settings') }}" class="menu-item flex items-center py-2 px-3 rounded-lg mb-1 tooltip text-sm">
                    <div class="icon-container bg-blue-500 bg-opacity-20 text-blue-400 w-8 h-8">
                        <i class="fas fa-sliders-h text-sm"></i>
                    </div>
                    <span class="sidebar-link-text transition-opacity duration-300 ml-2">Configurações Gerais</span>
                    <span class="tooltip-text">Configurações Gerais</span>
                </a>
                
                <a href="{{ route('admin.updates') }}" class="menu-item flex items-center py-2 px-3 rounded-lg mb-1 tooltip text-sm">
                    <div class="icon-container bg-green-500 bg-opacity-20 text-green-400 w-8 h-8">
                        <i class="fas fa-download text-sm"></i>
                    </div>
                    <span class="sidebar-link-text transition-opacity duration-300 ml-2">Actualizações</span>
                    <span class="tooltip-text">Actualizações</span>
                </a>
            </div>
        </div>
        @endrole
        
        <div class="border-t border-gray-700 my-4 opacity-50"></div>
        
        <a href="{{ route('admin.profile') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.profile') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-blue-500 bg-opacity-20 text-blue-400">
                <i class="fas fa-user-circle"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Meu Perfil</span>
            <span class="tooltip-text">Meu Perfil</span>
        </a>
        
        <a href="{{ route('admin.my-subscription') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.my-subscription') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-purple-500 bg-opacity-20 text-purple-400">
                <i class="fas fa-gem"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Meu Plano</span>
            <span class="tooltip-text">Meu Plano</span>
        </a>
        
        <a href="{{ route('home') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 tooltip">
            <div class="icon-container bg-gray-500 bg-opacity-20 text-gray-400">
                <i class="fas fa-home"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Ver Site</span>
            <span class="tooltip-text">Ver Site</span>
        </a>
        
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 tooltip">
            <div class="icon-container bg-red-500 bg-opacity-20 text-red-400">
                <i class="fas fa-sign-out-alt"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300 text-red-400">Terminar Sessão</span>
            <span class="tooltip-text">Terminar Sessão</span>
        </a>
        <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </nav>
    
    <!-- Informação do Utilizador -->
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-700 bg-gray-800 bg-opacity-95">
        <a href="{{ route('admin.profile') }}" class="flex items-center space-x-3 hover:opacity-80 transition-opacity">
            <div class="flex-shrink-0 rounded-full bg-gradient-to-br from-blue-500 to-indigo-700 h-10 w-10 flex items-center justify-center shadow-md">
                <span class="text-white font-medium">{{ Auth::user()->name[0] }}</span>
            </div>
            <div class="sidebar-user-info transition-opacity duration-300">
                <p class="font-medium text-white">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
            </div>
        </a>
    </div>
</div>
