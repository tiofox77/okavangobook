<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ \App\Models\Setting::get('app_name', config('app.name', 'KiandaStay')) }} - Painel Administrativo - @yield('title', 'Dashboard')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Tailwind CSS local -->
    <link href="{{ asset('assets/css/tailwind.min.css') }}" rel="stylesheet">

    <!-- Dark mode: overrides globais (antes) + variantes dark: explícitas (depois) -->
    <link href="{{ asset('assets/css/dark-overrides.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/dark-mode.css') }}" rel="stylesheet">

    <!-- Font Awesome via CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts - Inter e Poppins (mais modernas) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Animate.css local -->
    <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}">
    
    <!-- Chart.js local -->
    <script src="{{ asset('assets/js/chart.min.js') }}"></script>
    
    <!-- Estilos personalizados para o painel admin -->
    <style>
        :root {
            --primary: #134e91;
            --primary-dark: #0d3a6b;
            --secondary: #f59e0b;
            --admin-primary: #dc2626;
            --admin-dark: #b91c1c;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            scroll-behavior: smooth;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
        }
        
        /* Cores específicas para o painel admin */
        .text-admin-primary {
            color: var(--admin-primary);
        }
        
        .bg-admin-primary {
            background-color: var(--admin-primary);
        }
        
        .border-admin-primary {
            border-color: var(--admin-primary);
        }
        
        .hover\:bg-admin-primary:hover {
            background-color: var(--admin-dark);
        }
        
        /* Sidebar styling */
        .sidebar {
            min-width: 260px;
            width: 260px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .sidebar-collapsed {
            min-width: 70px;
            width: 70px;
        }
        
        .content-expanded {
            margin-left: 70px;
        }
        
        .icon-container {
            width: 24px;
            height: 24px;
            text-align: center;
            margin-right: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        
        /* Menu items hover effect */
        .menu-item {
            position: relative;
            transition: all 0.2s ease-in-out;
            border-left: 3px solid transparent;
        }
        
        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: var(--admin-primary);
        }
        
        .menu-item.active {
            background-color: rgba(220, 38, 38, 0.1);
            border-left-color: var(--admin-primary);
            font-weight: 500;
        }

        /* Tooltip styling */
        .tooltip {
            position: relative;
        }
        
        .tooltip .tooltip-text {
            visibility: hidden;
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 6px 10px;
            position: absolute;
            z-index: 100;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            margin-left: 10px;
            opacity: 0;
            transition: opacity 0.3s ease, transform 0.3s ease;
            font-size: 13px;
            white-space: nowrap;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .tooltip .tooltip-text::after {
            content: "";
            position: absolute;
            top: 50%;
            right: 100%;
            margin-top: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: transparent rgba(0, 0, 0, 0.8) transparent transparent;
        }
        
        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
            transform: translateY(-50%) translateX(5px);
        }
        
        /* Cards com hover e animações */
        .admin-card {
            transition: all 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .admin-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        /* Botões com efeitos */
        .btn {
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        /* Animações para elementos que aparecem */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Spinner animado para carregamentos */
        .spinner {
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 3px solid #fff;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Notificação com badge */
        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background-color: var(--danger);
            color: white;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
        
        /* Estilização para tabelas */
        .table-hover tr {
            transition: all 0.2s ease-in-out;
        }
        
        .table-hover tr:hover {
            background-color: rgba(243, 244, 246, 0.8);
            transform: translateY(-2px);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
    </style>
    
    @livewireStyles
    
    <!-- Scripts adicionais -->
    @stack('styles')
</head>
<body class="bg-gray-100 h-screen overflow-hidden" x-data="{ 
    showNotifications: false,
    darkMode: localStorage.getItem('darkMode') === 'true',
    toggleDarkMode() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('darkMode', this.darkMode);
    }
}" :class="{ 'dark bg-gray-900': darkMode }">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('livewire.admin.partials.sidebar')
        
        <!-- Conteúdo Principal -->
        <div id="content-wrapper" class="flex-1 overflow-x-hidden overflow-y-auto ml-64 transition-all duration-300">
            <!-- Barra superior -->
            <header class="bg-white dark:bg-gray-800 shadow-sm">
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center">
                        <button id="mobile-menu-button" class="mr-4 text-gray-600 dark:text-gray-300 hover:text-admin-primary lg:hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white animate__animated animate__fadeIn">@yield('header-title', 'Dashboard')</h2>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <button @click="toggleDarkMode()" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200 ease-in-out">
                            <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                            </svg>
                            <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-300" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        
                        @livewire('admin.admin-notifications')
                        
                        <span class="border-r border-gray-300 dark:border-gray-600 h-6"></span>
                        
                        <div class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            <span id="current-date" class=""></span>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Conteúdo principal -->
            <main class="p-6 dark:bg-gray-900">
                <!-- Alertas e notificações -->
                <div id="alert-container" class="mb-4">
                    @if (session()->has('success'))
                        <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 mb-4 rounded-md animate__animated animate__fadeIn" role="alert">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <p>{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if (session()->has('error'))
                        <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 mb-4 rounded-md animate__animated animate__fadeIn" role="alert">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <p>{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Conteúdo da página -->
                <div class="fade-in">
                    {{ $slot }}
                </div>
            </main>
            
            <!-- Footer -->
            <footer class="bg-white dark:bg-gray-800 p-6 shadow-inner">
                <div class="container mx-auto">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="mb-4 md:mb-0">
                            <p class="text-gray-600 dark:text-gray-300 text-sm">&copy; {{ date('Y') }} OkavangoBook - Desenvolvido com <span class="text-red-500">❤</span> por OkavangoTeam</p>
                        </div>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-500 hover:text-admin-primary dark:text-gray-400 dark:hover:text-gray-300 transition-colors duration-200">
                                <span class="sr-only">Documentação</span>
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 12h6m-6-4h6m2 8H7a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-500 hover:text-admin-primary dark:text-gray-400 dark:hover:text-gray-300 transition-colors duration-200">
                                <span class="sr-only">Suporte</span>
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </a>
                            <a href="#" class="text-gray-500 hover:text-admin-primary dark:text-gray-400 dark:hover:text-gray-300 transition-colors duration-200">
                                <span class="sr-only">Configurações</span>
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
    @livewireScripts
    
    <!-- Scripts para o painel admin -->
    <script>
        // Inicialização dos componentes de UI após o carregamento do DOM
        document.addEventListener('DOMContentLoaded', function() {
            // Atualizar data atual
            function updateCurrentDate() {
                const now = new Date();
                const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
                const dateElement = document.getElementById('current-date');
                if (dateElement) {
                    dateElement.textContent = now.toLocaleDateString('pt-AO', options);
                }
            }
            
            // Atualizar a data e configurar o intervalo
            updateCurrentDate();
            setInterval(updateCurrentDate, 60000);
            
            // Elementos da UI
            const sidebar = document.getElementById('sidebar');
            const contentWrapper = document.getElementById('content-wrapper');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            
            if (sidebar && contentWrapper) {
                // Verificar se a sidebar estava colapsada na sessão anterior
                const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                
                if (sidebarCollapsed) {
                    sidebar.classList.add('sidebar-collapsed');
                    contentWrapper.classList.add('content-expanded');
                    contentWrapper.classList.remove('ml-64');
                }
                
                // Toggle da sidebar em desktop
                if (sidebarToggle) {
                    sidebarToggle.addEventListener('click', function() {
                        sidebar.classList.toggle('sidebar-collapsed');
                        
                        if (sidebar.classList.contains('sidebar-collapsed')) {
                            contentWrapper.classList.add('content-expanded');
                            contentWrapper.classList.remove('ml-64');
                            localStorage.setItem('sidebarCollapsed', 'true');
                        } else {
                            contentWrapper.classList.remove('content-expanded');
                            contentWrapper.classList.add('ml-64');
                            localStorage.setItem('sidebarCollapsed', 'false');
                        }
                    });
                }
                
                // Toggle do menu em dispositivos móveis
                if (mobileMenuButton) {
                    mobileMenuButton.addEventListener('click', function() {
                        if (sidebar.classList.contains('hidden')) {
                            sidebar.classList.remove('hidden');
                            sidebar.classList.add('absolute', 'z-50', 'h-screen');
                        } else {
                            sidebar.classList.add('hidden');
                            sidebar.classList.remove('absolute', 'z-50');
                        }
                    });
                }
                
                // Detectar tamanho da tela e ajustar sidebar automaticamente
                function handleResize() {
                    if (window.innerWidth < 768) {
                        sidebar.classList.add('hidden');
                    } else {
                        sidebar.classList.remove('hidden');
                    }
                }
                
                window.addEventListener('resize', handleResize);
                handleResize(); // Executar na inicialização
            }
            
            // Configuração de animações para alertas
            const alerts = document.querySelectorAll('#alert-container > div');
            alerts.forEach(alert => {
                // Auto fechar alertas após 5 segundos
                setTimeout(() => {
                    if (alert) {
                        alert.classList.add('animate__fadeOut');
                        setTimeout(() => {
                            if (alert && alert.parentNode) {
                                alert.parentNode.removeChild(alert);
                            }
                        }, 500);
                    }
                }, 5000);
            });
            
            // Menu dropdown das configurações é gerido por Alpine.js no sidebar
        });
    </script>
    
    <!-- Scripts adicionais -->
    @stack('scripts')
</body>
</html>
