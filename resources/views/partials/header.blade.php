<header class="bg-white shadow-md">
    <div class="container mx-auto px-4 py-3">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center">
                    <span class="text-2xl font-bold text-primary">Okavango<span class="text-secondary">Book</span></span>
                </a>
            </div>
            
            <!-- Menu de navegação para desktop -->
            <nav class="hidden md:flex space-x-8">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-primary {{ request()->routeIs('home') ? 'font-bold text-primary' : '' }}">Início</a>
                <a href="{{ route('search.results') }}" class="text-gray-700 hover:text-primary {{ request()->routeIs('search.results') && !request()->has('sort') ? 'font-bold text-primary' : '' }}">Hotéis</a>
                <a href="{{ route('search.results', ['sort' => 'price_asc']) }}" class="text-gray-700 hover:text-primary {{ request()->has('sort') ? 'font-bold text-primary' : '' }}">Ofertas</a>
                <a href="{{ route('destinations') }}" class="text-gray-700 hover:text-primary {{ request()->routeIs('destinations') ? 'font-bold text-primary' : '' }}">Destinos</a>
                <a href="{{ route('about.angola') }}" class="text-gray-700 hover:text-primary {{ request()->routeIs('about.angola') ? 'font-bold text-primary' : '' }}">Sobre Angola</a>
                <a href="{{ route('contact') }}" class="text-gray-700 hover:text-primary {{ request()->routeIs('contact') ? 'font-bold text-primary' : '' }}">Contacto</a>
            </nav>
            
            <!-- Botões de autenticação -->
            <div class="hidden md:flex items-center space-x-4">
                @guest
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary">Entrar</a>
                    <a href="{{ route('register') }}" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-blue-700">Registrar</a>
                @else
                    <div class="flex items-center space-x-2">
                        <!-- Links diretos para acesso rápido -->
                        @if(auth()->user()->hasRole('Admin'))
                            <a href="{{ route('admin.dashboard') }}" class="bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700 text-xs flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                </svg>
                                <span>Admin</span>
                            </a>
                        @endif
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-primary text-sm flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Conta</span>
                        </a>
                        
                        <!-- Dropdown com mais opções -->
                        <div class="relative" id="user-dropdown">
                            <button id="dropdown-button" class="flex items-center text-gray-700 hover:text-primary focus:outline-none border border-gray-300 rounded-md px-2 py-1">
                                <span class="mr-1 text-sm">{{ Str::limit(Auth::user()->name, 10) }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="dropdown-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                                <div class="px-4 py-2 text-xs text-gray-500 border-b border-gray-200">
                                    <div class="font-semibold">{{ Auth::user()->name }}</div>
                                    <div class="truncate">{{ Auth::user()->email }}</div>
                                </div>
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        Minha Conta
                                    </div>
                                </a>
                                @if(auth()->user()->hasRole('Admin'))
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            Painel Admin
                                        </div>
                                    </a>
                                    <div class="border-t border-gray-100"></div>
                                    <a href="{{ route('admin.hotels') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 pl-10">Hotéis</a>
                                    <a href="{{ route('admin.users') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 pl-10">Utilizadores</a>
                                    <a href="{{ route('admin.locations') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 pl-10">Localizações</a>
                                @endif
                                <div class="border-t border-gray-100 mt-2"></div>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Terminar Sessão
                                    </div>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>
            
            <!-- Menu mobile (hambúrguer) -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" type="button" class="text-gray-700 hover:text-primary focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Menu mobile -->
    <div id="mobile-menu" class="hidden md:hidden">
        <a href="{{ route('home') }}" class="block py-2 px-4 text-sm hover:bg-gray-100 {{ request()->routeIs('home') ? 'font-bold bg-gray-100' : '' }}">Início</a>
        <a href="{{ route('search.results') }}" class="block py-2 px-4 text-sm hover:bg-gray-100 {{ request()->routeIs('search.results') && !request()->has('sort') ? 'font-bold bg-gray-100' : '' }}">Hotéis</a>
        <a href="{{ route('search.results', ['sort' => 'price_asc']) }}" class="block py-2 px-4 text-sm hover:bg-gray-100 {{ request()->has('sort') ? 'font-bold bg-gray-100' : '' }}">Ofertas</a>
        <a href="{{ route('destinations') }}" class="block py-2 px-4 text-sm hover:bg-gray-100 {{ request()->routeIs('destinations') ? 'font-bold bg-gray-100' : '' }}">Destinos</a>
        <a href="{{ route('about.angola') }}" class="block py-2 px-4 text-sm hover:bg-gray-100 {{ request()->routeIs('about.angola') ? 'font-bold bg-gray-100' : '' }}">Sobre Angola</a>
        <a href="{{ route('contact') }}" class="block py-2 px-4 text-sm hover:bg-gray-100 {{ request()->routeIs('contact') ? 'font-bold bg-gray-100' : '' }}">Contacto</a>
        
        <!-- Links de autenticação -->
        <div class="border-t border-gray-200 pt-2">
            @guest
                <a href="{{ route('login') }}" class="block py-2 px-4 text-sm hover:bg-gray-100">Entrar</a>
                <a href="{{ route('register') }}" class="block py-2 px-4 text-sm hover:bg-gray-100">Registrar</a>
            @else
                <div class="px-4 py-2 text-xs text-gray-500">
                    <div class="font-semibold">{{ Auth::user()->name }}</div>
                    <div class="truncate">{{ Auth::user()->email }}</div>
                </div>
                
                <!-- Dashboard do utilizador -->
                <a href="{{ route('dashboard') }}" class="flex items-center py-2 px-4 text-sm hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Minha Conta
                </a>
                
                <!-- Opções de administrador -->
                @if(auth()->user()->hasRole('Admin'))
                    <div class="border-t border-gray-100 my-1"></div>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center py-2 px-4 text-sm font-medium text-red-600 hover:bg-red-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Painel Admin
                    </a>
                    <a href="{{ route('admin.hotels') }}" class="block py-2 px-4 text-sm hover:bg-gray-100 pl-10">Hotéis</a>
                    <a href="{{ route('admin.users') }}" class="block py-2 px-4 text-sm hover:bg-gray-100 pl-10">Utilizadores</a>
                    <a href="{{ route('admin.locations') }}" class="block py-2 px-4 text-sm hover:bg-gray-100 pl-10">Localizações</a>
                @endif
                
                <!-- Terminar sessão -->
                <div class="border-t border-gray-100 my-1"></div>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();" class="flex items-center py-2 px-4 text-sm text-red-600 hover:bg-red-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Terminar Sessão
                </a>
                <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            @endguest
        </div>
    </div>
</header>

<script>
    // Script para o menu mobile e dropdown do utilizador que funciona mesmo com Livewire
    document.addEventListener('DOMContentLoaded', function() {
        // Menu mobile principal
        const menuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (menuButton && mobileMenu) {
            menuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
        
        // Dropdown de utilizador no desktop
        const dropdownButton = document.getElementById('dropdown-button');
        const dropdownMenu = document.getElementById('dropdown-menu');
        const userDropdown = document.getElementById('user-dropdown');
        
        if (dropdownButton && dropdownMenu) {
            // Abrir/fechar dropdown ao clicar
            dropdownButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropdownMenu.classList.toggle('hidden');
            });
            
            // Fechar dropdown ao clicar fora
            document.addEventListener('click', function(e) {
                if (userDropdown && !userDropdown.contains(e.target)) {
                    dropdownMenu.classList.add('hidden');
                }
            });
            
            // Também mostrar ao fazer hover (opcional)
            userDropdown.addEventListener('mouseenter', function() {
                dropdownMenu.classList.remove('hidden');
            });
            
            userDropdown.addEventListener('mouseleave', function() {
                // Só esconde se não estiver em foco por clique
                if (!dropdownButton.classList.contains('active')) {
                    dropdownMenu.classList.add('hidden');
                }
            });
        }
        
        // Dropdown de províncias no menu mobile
        const provinciasBtn = document.getElementById('provincias-mobile-btn');
        const provinciasDropdown = document.getElementById('provincias-mobile-dropdown');
        
        if (provinciasBtn && provinciasDropdown) {
            provinciasBtn.addEventListener('click', function() {
                provinciasDropdown.classList.toggle('hidden');
                const icon = provinciasBtn.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fa-chevron-down');
                    icon.classList.toggle('fa-chevron-up');
                }
            });
        }
        
        // Garantir que o script seja re-executado após atualizações do Livewire
        document.addEventListener('livewire:load', function () {
            Livewire.hook('message.processed', (message, component) => {
                // Menu mobile principal
                const menuButton = document.getElementById('mobile-menu-button');
                const mobileMenu = document.getElementById('mobile-menu');
                
                if (menuButton && mobileMenu) {
                    menuButton.addEventListener('click', function() {
                        mobileMenu.classList.toggle('hidden');
                    });
                }
                
                // Re-inicializar dropdown do utilizador
                const dropdownButton = document.getElementById('dropdown-button');
                const dropdownMenu = document.getElementById('dropdown-menu');
                
                if (dropdownButton && dropdownMenu) {
                    dropdownButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        dropdownMenu.classList.toggle('hidden');
                    });
                }
                
                // Dropdown de províncias no menu mobile
                const provinciasBtn = document.getElementById('provincias-mobile-btn');
                const provinciasDropdown = document.getElementById('provincias-mobile-dropdown');
                
                if (provinciasBtn && provinciasDropdown) {
                    provinciasBtn.addEventListener('click', function() {
                        provinciasDropdown.classList.toggle('hidden');
                        const icon = provinciasBtn.querySelector('i');
                        if (icon) {
                            icon.classList.toggle('fa-chevron-down');
                            icon.classList.toggle('fa-chevron-up');
                        }
                    });
                }
            });
        });
    });
</script>
