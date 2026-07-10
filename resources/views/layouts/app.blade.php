<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ \App\Models\Setting::get('app_name', config('app.name', 'KiandaStay')) }} - @yield('title', 'Encontre as melhores acomodações em Angola')</title>

    {{-- SEO: meta description, Open Graph, Twitter, canonical, dados estruturados --}}
    @include('partials.seo')

    <!-- Aplicar modo escuro antes do render para evitar flash (FOUC) -->
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
        function toggleDarkMode() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', isDark);
        }
    </script>

    <!-- Favicon & ícones -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon-16.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/img/icon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-touch-icon.png') }}">

    <!-- PWA -->
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="KiandaStay">
    <meta name="mobile-web-app-capable" content="yes">

    <!-- Tailwind CSS local -->
    <link href="{{ asset('assets/css/tailwind.min.css') }}" rel="stylesheet">

    <!-- Dark mode: overrides globais (antes) + variantes dark: explícitas (depois) -->
    <link href="{{ asset('assets/css/dark-overrides.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/dark-mode.css') }}" rel="stylesheet">

    <!-- Font Awesome via CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Toastr CSS local -->
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}">
    
    <!-- Google Fonts - Roboto e Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Estilos personalizados -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif;
        }
        
        .text-primary {
            color: #134e91;
        }
        
        .bg-primary {
            background-color: #134e91;
        }
        
        .btn-primary {
            background-color: #134e91;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #0d3a6b;
        }
        
        .text-secondary {
            color: #f59e0b;
        }

        .bg-secondary {
            background-color: #f59e0b;
        }

        [x-cloak] { display: none !important; }
    </style>
    
    @livewireStyles
    
    <!-- Scripts adicionais -->
    @stack('styles')
</head>
<body class="bg-gray-100 dark:bg-gray-900 dark:text-gray-100 min-h-screen flex flex-col transition-colors duration-300">
    <!-- Header -->
    @include('partials.header')
    
    <!-- Conteúdo principal -->
    <main class="flex-grow">
        @if (isset($slot))
            {{ $slot }}
        @else
            @yield('content')
        @endif
    </main>
    
    <!-- Footer -->
    @include('partials.footer')
    
    <!-- jQuery via CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    @livewireScripts
    
    <!-- Scripts para garantir compatibilidade com Livewire v3 -->
    <script>
        // Livewire v3 usa livewire:init em vez de livewire:load
        document.addEventListener('livewire:init', () => {
            // Verificar se o Livewire está carregado corretamente
            console.log('Livewire v3 carregado com sucesso');
            
            // Regra importante: Com Livewire v3, usamos dispatch em vez de emit para eventos
        });
    </script>
    
    <!-- jQuery local -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    
    <!-- Toastr JS local -->
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    
    <!-- Configuração do Toastr -->
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        
        // Listener Livewire para mostrar notificações
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('show-toast', (event) => {
                const data = event[0] || event;
                const type = data.type || 'info';
                const message = data.message || 'Notificação';
                
                if (type === 'success') {
                    toastr.success(message);
                } else if (type === 'error') {
                    toastr.error(message);
                } else if (type === 'warning') {
                    toastr.warning(message);
                } else {
                    toastr.info(message);
                }
            });
        });
    </script>
    
    <!-- Scripts adicionais -->
    @stack('scripts')

    <!-- Registo do Service Worker (PWA) -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch((err) => {
                    console.warn('Falha ao registar o Service Worker:', err);
                });
            });
        }
    </script>
</body>
</html>
