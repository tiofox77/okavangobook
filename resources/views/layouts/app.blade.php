<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Okavango Book') }} - @yield('title', 'Encontre as melhores acomodações em Angola')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Tailwind CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
    </style>
    
    @livewireStyles
    
    <!-- Scripts adicionais -->
    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
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
    
    <!-- Scripts adicionais -->
    @stack('scripts')
</body>
</html>
