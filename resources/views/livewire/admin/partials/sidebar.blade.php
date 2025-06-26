<div id="sidebar" class="sidebar bg-gray-800 text-white h-screen overflow-y-auto fixed top-0 left-0 z-30 transition-all duration-300 shadow-xl">
    <div class="p-4 flex justify-between items-center border-b border-gray-700">
        <div class="flex items-center space-x-2">
            <div class="flex-shrink-0 w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center rotate-12 transform transition-all duration-300 hover:rotate-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
            </div>
            <h1 class="text-xl font-bold sidebar-title transition-opacity duration-300">Okavango Admin</h1>
        </div>
        <button id="sidebar-toggle" class="text-gray-300 hover:text-white transition-colors duration-200 p-1 rounded-md hover:bg-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
        </button>
    </div>
    
    <!-- Links do menu -->
    <nav class="mt-4 px-2">
        <a href="{{ route('admin.dashboard') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.dashboard') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-blue-500 bg-opacity-20 text-blue-400">
                <i class="fas fa-tachometer-alt"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Dashboard</span>
            <span class="tooltip-text">Dashboard</span>
        </a>
        
        <a href="{{ route('admin.hotels') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.hotels') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-amber-500 bg-opacity-20 text-amber-400">
                <i class="fas fa-hotel"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Hotéis</span>
            <span class="tooltip-text">Gerir Hotéis</span>
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
        
        <a href="{{ route('admin.locations') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.locations') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-purple-500 bg-opacity-20 text-purple-400">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Localizações</span>
            <span class="tooltip-text">Gerir Localizações</span>
        </a>
        
        <!-- Menu para Reservas -->
        <a href="{{ route('admin.reservations') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.reservations') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-indigo-500 bg-opacity-20 text-indigo-400">
                <i class="fas fa-calendar-check"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Reservas</span>
            <span class="tooltip-text">Gerir Reservas</span>
        </a>
        
        <a href="{{ route('admin.users') }}" class="menu-item flex items-center py-3 px-3 rounded-lg mb-1 {{ request()->routeIs('admin.users') ? 'active bg-gray-700' : '' }} tooltip">
            <div class="icon-container bg-pink-500 bg-opacity-20 text-pink-400">
                <i class="fas fa-users"></i>
            </div>
            <span class="sidebar-link-text transition-opacity duration-300">Utilizadores</span>
            <span class="tooltip-text">Gerir Utilizadores</span>
        </a>
        
        <div class="border-t border-gray-700 my-4 opacity-50"></div>
        
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
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0 rounded-full bg-gradient-to-br from-red-500 to-red-700 h-10 w-10 flex items-center justify-center shadow-md">
                <span class="text-white font-medium">{{ Auth::user()->name[0] }}</span>
            </div>
            <div class="sidebar-user-info transition-opacity duration-300">
                <p class="font-medium text-white">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>
</div>