<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Título keyword-first (melhor para SEO): "Hotéis em Luanda… | KiandaStay" --}}
    <title>@yield('title', 'Hotéis em Angola: compare preços e reserve nas 18 províncias') | {{ \App\Models\Setting::get('app_name', config('app.name', 'KiandaStay')) }}</title>

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
    <link rel="icon" href="{{ asset('favicon.ico') }}?v=20260813" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon-32.png') }}?v=20260813">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon-16.png') }}?v=20260813">
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/img/icon.svg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/pwa/icon-180.png') }}?v=20260813">
    <link rel="mask-icon" href="{{ asset('assets/img/icon.svg') }}" color="#134e91">

    <!-- PWA -->
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}?v=20260813">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="KiandaStay">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="KiandaStay">
    <meta name="msapplication-TileColor" content="#134e91">
    <meta name="msapplication-TileImage" content="{{ asset('assets/img/pwa/icon-144.png') }}">

    {{-- Dark mode: overrides globais ANTES do Tailwind — a especificidade
         (.dark .bg-white) já vence as classes claras, e as variantes dark:
         explícitas do build v3 (carregado depois) mantêm prioridade. --}}
    <link href="{{ asset('assets/css/dark-overrides.css') }}?v=20260830" rel="stylesheet">

    <!-- Tailwind CSS v3 (build completo do projeto: npm run build:css) -->
    <link href="{{ asset('assets/css/tailwind.min.css') }}?v=20260830c" rel="stylesheet">

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

        /* iOS Safari (iPhone): inputs de data têm largura intrínseca própria e
           ignoram width:100%, transbordando o cartão (distorção no iPhone).
           appearance:none + min-width:0 devolve o controlo do tamanho ao CSS. */
        input[type="date"],
        input[type="datetime-local"],
        input[type="time"],
        input[type="month"] {
            -webkit-appearance: none;
            appearance: none;
            display: block;
            width: 100%;
            min-width: 0;
            max-width: 100%;
            min-height: 2.75rem;
            background-color: transparent;
        }
        /* iOS centra o texto da data por omissão — alinhar à esquerda como os outros campos */
        input[type="date"]::-webkit-date-and-time-value,
        input[type="datetime-local"]::-webkit-date-and-time-value {
            text-align: left;
        }

        /* (o antigo bloco "Compat Tailwind v2" foi removido: o CSS agora é um
           build completo do Tailwind v3 e gera nativamente as classes
           arbitrárias — h-[70vh], min-h-[44px], grid-cols-[…], etc.) */
    </style>
    
    @livewireStyles
    
    <!-- Scripts adicionais -->
    @stack('styles')
</head>
<body class="bg-gray-100 dark:bg-gray-900 dark:text-gray-100 min-h-screen flex flex-col overflow-x-hidden transition-colors duration-300">
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

    {{-- Ilhas React (páginas que as usam fazem @push('islands') com @vite) --}}
    @stack('islands')
    
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
    
    <!-- Geolocalização partilhada: o pedido de permissão parte sempre de um gesto do utilizador -->
    <script>
        window.KiandaLocation = {
            setState(state, message = '') {
                document.querySelectorAll('[data-location-button]').forEach((button) => {
                    button.disabled = state === 'loading';
                    button.classList.toggle('opacity-70', state === 'loading');
                    const label = button.querySelector('[data-location-label]');
                    if (label) {
                        label.textContent = state === 'loading'
                            ? 'A obter localização...'
                            : state === 'success'
                                ? 'Localização ativada'
                                : 'Usar a minha localização';
                    }
                });

                document.querySelectorAll('[data-location-status]').forEach((status) => {
                    status.textContent = message;
                    status.hidden = !message;
                });
            },

            request(componentId, deniedMethod = null) {
                if (!window.isSecureContext && !['localhost', '127.0.0.1'].includes(location.hostname)) {
                    this.setState('error', 'A localização só pode ser ativada numa ligação HTTPS segura.');
                    return;
                }

                if (!('geolocation' in navigator)) {
                    this.setState('error', 'Este dispositivo não disponibiliza geolocalização.');
                    return;
                }

                this.setState('loading', 'Confirme a permissão de localização no seu telemóvel.');
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        window.Livewire?.find(componentId)?.call(
                            'setUserLocation',
                            position.coords.latitude,
                            position.coords.longitude
                        );
                        this.setState('success', 'Hotéis próximos atualizados com a sua localização.');
                    },
                    (error) => {
                        const messages = {
                            1: 'Permissão negada. Ative Localização nas definições do navegador ou da aplicação.',
                            2: 'Não foi possível determinar a sua localização. Confirme se o GPS está ligado.',
                            3: 'O GPS demorou demasiado. Tente novamente num local com melhor sinal.',
                        };
                        this.setState('error', messages[error.code] || 'Não foi possível obter a sua localização.');
                        if (deniedMethod) {
                            window.Livewire?.find(componentId)?.call(deniedMethod);
                        }
                    },
                    { enableHighAccuracy: true, timeout: 15000, maximumAge: 120000 }
                );
            },

            async useIfAlreadyGranted(componentId, deniedMethod = null) {
                if (!navigator.permissions?.query) return;
                try {
                    const permission = await navigator.permissions.query({ name: 'geolocation' });
                    if (permission.state === 'granted') this.request(componentId, deniedMethod);
                } catch (_) {
                    // Safari não implementa completamente a Permissions API.
                }
            },
        };
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
