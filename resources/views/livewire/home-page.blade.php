<div class="relative bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 dark:text-white transition-all duration-500 ease-in-out">
   
    <!-- Hero Section simplificada com imagem de fundo garantida -->
    <div class="relative h-screen overflow-hidden">
        <!-- Imagem de fundo garantida e simples -->
        <img src="https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=80" 
            alt="Angola Landscape" 
            class="absolute inset-0 w-full h-full object-cover">
        
        <!-- Overlay simples para melhorar a legibilidade do texto -->
        <div class="absolute inset-0 bg-black/40 z-10"></div>
        
        <!-- Conteúdo Hero -->
        <div class="container mx-auto px-4 h-full flex flex-col justify-center items-center relative z-30">
            <div class="animate-fade-in-down">
                <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 text-center tracking-tight leading-tight">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-white to-blue-100">Explore Angola</span>
                    <span class="block mt-2 text-3xl md:text-5xl">Como Nunca Antes</span>
                </h1>
                <p class="text-xl text-white/90 mb-10 text-center max-w-3xl mx-auto font-light">
                    Descubra experiências únicas em 2025 com nossa tecnologia de reservas inteligente
                </p>
            </div>
            
            <!-- Card de busca com efeito de vidro (glassmorphism) -->
            <div class="w-full max-w-5xl animate-fade-in-up backdrop-blur-xl bg-white/10 dark:bg-black/20 rounded-2xl p-6 border border-white/20 shadow-2xl">
                @livewire('search-form')
                
                <!-- Estatísticas animadas abaixo do formulário -->
                <div class="flex flex-wrap justify-center mt-6 gap-8 text-center text-white">
                    <div class="stat-item">
                        <span class="block text-2xl font-bold">2500+</span>
                        <span class="text-sm opacity-80">Hotéis</span>
                    </div>
                    <div class="stat-item">
                        <span class="block text-2xl font-bold">18</span>
                        <span class="text-sm opacity-80">Províncias</span>
                    </div>
                    <div class="stat-item">
                        <span class="block text-2xl font-bold">180k+</span>
                        <span class="text-sm opacity-80">Usuários</span>
                    </div>
                </div>
            </div>
            
            <!-- Scroll indicator -->
            <div class="absolute bottom-8 left-0 right-0 flex justify-center animate-bounce">
                <a href="#destinations" class="text-white opacity-80 hover:opacity-100 transition-opacity">
                    <i class="fas fa-chevron-down text-2xl"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Destinos Populares com design imersivo 2025 -->
    <section id="destinations" class="py-24 relative overflow-hidden bg-white dark:bg-gray-900">
        <!-- Fundo com imagem real de Angola -->
        <div class="absolute inset-0 z-0">
            <!-- Imagem de paisagem angolana mais visível -->
            <img src="https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
                 alt="Angola Landscape Background" 
                 class="w-full h-full object-cover opacity-20 dark:opacity-20 scale-110 animate-ken-burns"
                 loading="eager"
                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1506370622353-6c8946e3a195?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80';">
            
            <!-- Overlay para melhor contraste e legibilidade -->
            <div class="absolute inset-0 bg-gradient-to-br from-white/95 via-white/90 to-blue-50/95 dark:from-gray-900/98 dark:via-gray-900/95 dark:to-gray-900/98"></div>
            
            <!-- Elementos decorativos com cores da bandeira de Angola - mais visíveis -->
            <div class="absolute top-20 left-20 w-80 h-80 bg-red-600 rounded-full mix-blend-multiply dark:mix-blend-overlay blur-3xl opacity-30 animate-float"></div>
            <div class="absolute bottom-20 right-40 w-96 h-96 bg-black rounded-full mix-blend-multiply dark:mix-blend-overlay blur-3xl opacity-30 animate-float-delayed"></div>
            <div class="absolute top-1/2 right-1/4 w-60 h-60 bg-yellow-400 rounded-full mix-blend-multiply dark:mix-blend-overlay blur-3xl opacity-25 animate-float-delayed"></div>
        </div>
        
        <!-- Estilo para as animações -->
        <style>
            .bg-dots-pattern {
                background-image: radial-gradient(circle, #4A6CF7 1px, transparent 1px);
                background-size: 20px 20px;
            }
            @keyframes float {
                0% { transform: translateY(0) scale(1); }
                50% { transform: translateY(-20px) scale(1.05); }
                100% { transform: translateY(0) scale(1); }
            }
            @keyframes float-delayed {
                0% { transform: translateY(0) scale(1); }
                50% { transform: translateY(20px) scale(1.05); }
                100% { transform: translateY(0) scale(1); }
            }
            @keyframes ken-burns {
                0% { transform: scale(1); }
                50% { transform: scale(1.1); }
                100% { transform: scale(1); }
            }
            .animate-float {
                animation: float 15s infinite ease-in-out;
            }
            .animate-float-delayed {
                animation: float-delayed 18s infinite ease-in-out;
            }
            .animate-ken-burns {
                animation: ken-burns 30s infinite ease-in-out;
            }
        </style>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-16">
                <span class="inline-block px-5 py-2 rounded-full bg-primary/10 text-primary dark:bg-primary/20 text-sm font-medium mb-6 animate-pulse shadow-sm border border-primary/20 dark:border-primary/30">
                    <i class="fas fa-map-marker-alt mr-2"></i>Explore Angola
                </span>
                <h2 class="text-4xl md:text-5xl font-extrabold mb-6 text-gray-800 dark:text-white">
                    Províncias de Angola
                    <div class="w-24 h-1.5 bg-gradient-to-r from-primary to-blue-500 mx-auto mt-5 rounded-full"></div>
                </h2>
                <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto text-lg font-normal leading-relaxed">
                    Descubra a beleza e cultura das 18 províncias de Angola em 2025
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $colorClasses = [
                        'bg-gradient-to-r from-blue-600 to-blue-800 text-white',
                        'bg-gradient-to-r from-yellow-500 to-yellow-600 text-gray-900',
                        'bg-gradient-to-r from-blue-500 to-blue-700 text-white',
                        'bg-gradient-to-r from-yellow-400 to-yellow-500 text-gray-900',
                        'bg-gradient-to-r from-blue-700 to-blue-900 text-white',
                        'bg-gradient-to-r from-yellow-600 to-yellow-700 text-white'
                    ];
                @endphp
                @forelse($popularDestinations as $index => $destination)
                @php
                    $colorIndex = $index % count($colorClasses);
                    $buttonClass = $colorClasses[$colorIndex];
                @endphp
                <div class="group relative bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 hover:translate-y-[-8px] border border-blue-100/30 dark:border-blue-900/20">
                    <!-- Badge de província -->
                    <div class="absolute top-4 left-4 z-20 bg-white/90 dark:bg-black/70 py-1 px-3 rounded-full shadow-lg backdrop-blur-sm">
                        <div class="flex items-center">
                            <div class="w-2 h-2 rounded-full bg-primary animate-pulse mr-2"></div>
                            <span class="text-xs font-semibold text-primary dark:text-blue-400">Província</span>
                        </div>
                    </div>
                    
                    <div class="relative overflow-hidden h-72">
                        <!-- Usando o ImageHelper com lazy loading e fallback melhorado -->
                        <img 
                            src="{{ \App\Helpers\ImageHelper::getValidImage($destination['image'], 'location') }}" 
                            alt="{{ $destination['name'] }}" 
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                            loading="lazy"
                            onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1562182384-08115de5ee97?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';"
                        >
                        <!-- Gradiente avançado para melhor legibilidade -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
                        
                        <!-- Título flutuante no hover -->
                        <div class="absolute inset-x-0 bottom-0 p-5 translate-y-full group-hover:translate-y-0 transition-transform duration-500">
                            <h3 class="text-2xl font-bold text-white mb-2 drop-shadow-md">{{ $destination['name'] }}</h3>
                            <p class="text-white/90 line-clamp-2 text-sm max-w-md">{{ $destination['description'] }}</p>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <!-- Nome da província sempre visível -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-primary mr-2"></i>
                                <h3 class="text-xl font-bold text-gray-800 dark:text-white">{{ $destination['name'] }}</h3>
                            </div>
                            <div class="flex items-center px-2 py-1 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <i class="fas fa-hotel text-primary mr-2"></i>
                                <span class="text-sm font-medium text-primary dark:text-blue-300">{{ $destination['hotels_count'] }}</span>
                            </div>
                        </div>
                        
                        <!-- Botão de explorar estilizado -->
                        <a href="/search?location={{ urlencode($destination['name']) }}" 
                           class="mt-2 w-full py-3 flex items-center justify-center {{ $buttonClass }} font-semibold hover:shadow-lg hover:shadow-primary/40 transition-all duration-300 transform group-hover:scale-[1.02] hover:brightness-110 rounded-xl">
                            <span>Explorar {{ $destination['name'] }}</span>
                            <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-12 bg-white dark:bg-gray-800 rounded-2xl shadow">
                    <i class="fas fa-map-signs text-5xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Nenhum destino encontrado</h3>
                    <p class="text-gray-500 dark:text-gray-400">No momento não há destinos populares disponíveis.</p>
                </div>
                @endforelse
            </div>
            
            <div class="text-center mt-16">
                <a href="/search" class="inline-flex items-center px-8 py-4 rounded-full bg-gradient-to-r from-blue-600 to-blue-800 text-white font-semibold hover:shadow-lg hover:shadow-blue-500/40 transition-all duration-300 transform hover:scale-105 hover:brightness-110">
                    <span>Ver Todos os Destinos</span>
                    <i class="fas fa-compass ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Por que escolher o Okavango Book (versão moderna 2025) -->
    <section class="py-28 relative overflow-hidden">
        <!-- Background com efeito de gradiente e formas geométricas -->
        <div class="absolute inset-0 bg-gradient-to-tr from-gray-50 to-blue-50 dark:from-gray-900 dark:to-blue-900 opacity-50"></div>
        <div class="absolute inset-0 overflow-hidden">
            <div class="blur-3xl opacity-30 absolute -top-40 -left-40 w-80 h-80 bg-primary rounded-full"></div>
            <div class="blur-3xl opacity-20 absolute -bottom-60 -right-60 w-96 h-96 bg-blue-400 rounded-full"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-20">
                <span class="inline-block px-4 py-1 rounded-full bg-primary/10 text-primary dark:bg-primary/20 text-sm font-medium mb-4">Tecnologia de 2025</span>
                <h2 class="text-4xl md:text-5xl font-extrabold mb-6">Por que escolher o <span class="text-primary">Okavango Book</span></h2>
                <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">Nosso sistema utiliza tecnologia de ponta com inteligência artificial para proporcionar a melhor experiência de reserva</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Card 1 - Comparação de Preços -->
                <div class="group bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 relative overflow-hidden">
                    <!-- Indicador de gradiente no topo -->
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary to-blue-400"></div>
                    
                    <div class="w-16 h-16 mb-6 flex items-center justify-center bg-primary/10 dark:bg-primary/20 rounded-2xl text-primary group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-search-dollar text-2xl"></i>
                    </div>
                    
                    <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Comparação Inteligente</h3>
                    <p class="text-gray-600 dark:text-gray-300">Nossa IA analisa e compara preços em tempo real para garantir as melhores ofertas para sua viagem.</p>
                </div>
                
                <!-- Card 2 - Avaliações Verificadas -->
                <div class="group bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-green-400 to-primary"></div>
                    
                    <div class="w-16 h-16 mb-6 flex items-center justify-center bg-green-500/10 dark:bg-green-500/20 rounded-2xl text-green-500 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-shield-check text-2xl"></i>
                    </div>
                    
                    <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Avaliações Autenticadas</h3>
                    <p class="text-gray-600 dark:text-gray-300">Sistema de verificação biométrica garante que todas as avaliações sejam de hóspedes reais.</p>
                </div>
                
                <!-- Card 3 - Especialistas em Angola -->
                <div class="group bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-purple-400 to-pink-400"></div>
                    
                    <div class="w-16 h-16 mb-6 flex items-center justify-center bg-purple-500/10 dark:bg-purple-500/20 rounded-2xl text-purple-500 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-globe-africa text-2xl"></i>
                    </div>
                    
                    <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Conhecimento Local</h3>
                    <p class="text-gray-600 dark:text-gray-300">Nossa equipe de especialistas locais atualiza constantemente informações sobre cada região de Angola.</p>
                </div>
                
                <!-- Card 4 - Reservas Seguras -->
                <div class="group bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 to-orange-400"></div>
                    
                    <div class="w-16 h-16 mb-6 flex items-center justify-center bg-amber-500/10 dark:bg-amber-500/20 rounded-2xl text-amber-500 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-fingerprint text-2xl"></i>
                    </div>
                    
                    <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Segurança Avançada</h3>
                    <p class="text-gray-600 dark:text-gray-300">Tecnologia blockchain e autenticação em vários fatores para proteção total dos seus dados.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Ofertas Especiais - Design Imersivo 2025 -->
    <section class="py-24 relative overflow-hidden">
        <!-- Background imersivo para ofertas especiais -->
        <div class="absolute inset-0 z-0">
            <!-- Imagem de fundo que representa Angola do Unsplash -->
            <img src="https://images.unsplash.com/photo-1501785888041-af3ef285b470?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=80" 
                 alt="Angola Landscape" 
                 class="w-full h-full object-cover scale-105 animate-slow-zoom"
                 loading="lazy">
            
            <!-- Overlay de gradiente -->
            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/90 via-secondary/80 to-amber-700/90 dark:from-amber-900/90 dark:via-secondary/80 dark:to-amber-950/90 mix-blend-multiply"></div>
            
            <!-- Padrão de linhas diagonais -->
            <div class="absolute inset-0 opacity-10">
                <div class="diagonal-pattern"></div>
            </div>
            
            <!-- Elementos decorativos flutuantes -->
            <div class="absolute top-40 left-10 w-60 h-60 rounded-full border-4 border-white/10 opacity-30 animate-spin-slow"></div>
            <div class="absolute bottom-20 right-20 w-80 h-80 rounded-full border-2 border-white/10 opacity-20 animate-spin-slow-reverse"></div>
        </div>
        
        <!-- Estilo para os elementos decorativos -->
        <style>
            .diagonal-pattern {
                background-image: repeating-linear-gradient(45deg, rgba(255,255,255,0.1), rgba(255,255,255,0.1) 2px, transparent 2px, transparent 8px);
                height: 100%;
                width: 100%;
            }
            @keyframes spin-slow {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            @keyframes spin-slow-reverse {
                from { transform: rotate(360deg); }
                to { transform: rotate(0deg); }
            }
            .animate-spin-slow {
                animation: spin-slow 60s linear infinite;
            }
            .animate-spin-slow-reverse {
                animation: spin-slow-reverse 45s linear infinite;
            }
        </style>
        <div class="container mx-auto px-4 relative z-10">
            <!-- Cabeçalho da seção com badge moderna -->
            <div class="text-center mb-16 relative">
                <div class="inline-block px-4 py-1 bg-primary/10 dark:bg-primary/20 rounded-full text-primary dark:text-blue-300 font-medium text-sm mb-3">
                    <span class="mr-2">💎</span>Promoções Exclusivas<span class="ml-2">💎</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-primary to-blue-600 dark:from-blue-400 dark:to-blue-300">Ofertas Especiais</h2>
                <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">Descubra nossas melhores ofertas e promoções para sua próxima aventura em Angola.</p>
                <div class="absolute w-20 h-1 bg-primary rounded-full left-1/2 transform -translate-x-1/2 bottom-[-1rem]"></div>
            </div>
            
            @php
                $offerColorClasses = [
                    'bg-gradient-to-r from-blue-600 to-blue-800 text-white',
                    'bg-gradient-to-r from-yellow-500 to-yellow-600 text-gray-900',
                    'bg-gradient-to-r from-green-600 to-green-800 text-white',
                    'bg-gradient-to-r from-red-500 to-red-700 text-white',
                    'bg-gradient-to-r from-purple-600 to-purple-800 text-white',
                    'bg-gradient-to-r from-amber-500 to-amber-600 text-gray-900'
                ];
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($specialOffers as $index => $offer)
                @php
                    $colorIndex = $index % count($offerColorClasses);
                    $offerButtonClass = $offerColorClasses[$colorIndex];
                @endphp
                <div class="group relative bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100 dark:border-gray-700 hover:border-primary/30 dark:hover:border-blue-400/30">
                    <!-- Efeito de brilho ao passar o mouse -->
                    <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                    
                    <div class="relative overflow-hidden h-64 bg-white">
                        <!-- Badge de desconto -->
                        <div class="absolute top-4 right-4 z-20">
                            <div class="relative">
                                <div class="relative bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-lg shadow-lg transform rotate-3 group-hover:rotate-0 transition-all duration-300">
                                    <div class="flex items-center">
                                        <span class="text-base font-bold">-{{ $offer['discount_percentage'] }}%</span>
                                        <span class="ml-1 text-xs font-semibold">OFF</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Imagem da oferta -->
                        <div class="relative h-full w-full overflow-hidden">
                            <img 
                                src="{{ \App\Helpers\ImageHelper::getValidImage($offer['image'], 'hotel') }}" 
                                alt="{{ $offer['name'] }}" 
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                loading="lazy"
                                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1540541338287-41700207dee6?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';"
                                style="min-height: 100%; min-width: 100%; object-position: center;"
                            >
                            
                            <!-- Badge de tempo limitado -->
                            <div class="absolute bottom-4 left-4 z-10">
                                <div class="flex items-center bg-white text-gray-800 text-xs px-3 py-1.5 rounded-full shadow-md border border-gray-100">
                                    <i class="far fa-clock mr-1.5 text-yellow-500"></i>
                                    <span class="font-medium">Oferta por tempo limitado</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6 relative">
                        <!-- Localização do hotel -->
                        <div class="flex items-center mb-3 text-sm font-medium text-primary dark:text-blue-300">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span class="truncate">{{ $offer['location'] }}</span>
                        </div>
                        
                        <!-- Título da oferta -->
                        <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mb-4 leading-tight group-hover:text-primary dark:group-hover:text-blue-300 transition-colors duration-300">
                            {{ $offer['name'] }}
                        </h3>
                        
                        <!-- Preço e economia -->
                        <div class="mb-5">
                            <div class="flex items-baseline mb-1">
                                <span class="text-3xl font-extrabold text-gray-900 dark:text-white">AKZ {{ number_format($offer['discount_price'], 0, ',', '.') }}</span>
                                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400 line-through">AKZ {{ number_format($offer['original_price'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                    <i class="fas fa-wallet mr-1"></i>
                                    Economize {{ $offer['discount_percentage'] }}% (AKZ {{ number_format($offer['original_price'] - $offer['discount_price'], 0, ',', '.') }})
                                </span>
                            </div>
                        </div>
                        
                        <!-- Botão de ação com efeito de brilho -->
                        <div class="relative group/button mt-4">
                            <div class="absolute -inset-0.5 rounded-xl opacity-75 group-hover/button:opacity-100 blur transition duration-500 group-hover/button:duration-200 animate-tilt {{ str_contains($offerButtonClass, 'text-white') ? 'from-blue-500 to-blue-700' : 'from-yellow-400 to-yellow-600' }}"></div>
                            <a href="{{ route('hotel.details', ['id' => $offer['id']]) }}" 
                               class="relative w-full py-3 px-6 flex items-center justify-between {{ $offerButtonClass }} rounded-xl font-bold hover:shadow-xl hover:shadow-primary/30 transition-all duration-300 transform group-hover/button:-translate-y-0.5">
                                <span>Reservar Agora</span>
                                <i class="fas fa-arrow-right ml-2 transition-transform group-hover/button:translate-x-1 group-hover/button:scale-110"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-20 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 dark:bg-blue-900/30 text-primary dark:text-blue-300 mb-6">
                        <i class="fas fa-tag text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Nenhuma oferta disponível no momento</h3>
                    <p class="text-gray-600 dark:text-gray-300 max-w-md mx-auto mb-6">Nossas ofertas especiais estão temporariamente esgotadas. Volte em breve para não perder as próximas promoções!</p>
                    <a href="{{ route('search.results') }}" class="inline-flex items-center px-6 py-3 rounded-full bg-gradient-to-r from-primary to-blue-600 text-white font-medium hover:shadow-lg hover:shadow-primary/20 transition-all duration-300">
                        <span>Explorar Hotéis</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Newsletter & CTA - Design 2025 -->
    <section class="py-28 md:py-36 relative overflow-hidden bg-gradient-to-br from-blue-900 to-primary">
        <!-- Background dinâmico -->
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1547036967-23d11aacaee0?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80')] bg-cover bg-center animate-float"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/95 via-primary/90 to-blue-900/95"></div>
            
            <!-- Elementos decorativos -->
            <div class="absolute inset-0 overflow-hidden opacity-20">
                <div class="absolute -top-32 -left-32 w-64 h-64 bg-white rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-yellow-400 rounded-full blur-3xl opacity-30"></div>
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl h-1 bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>
            </div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <!-- Cabeçalho -->
                <div class="mb-14" data-aos="fade-up">
                    <span class="inline-flex items-center px-5 py-2 rounded-full bg-white/10 backdrop-blur-sm text-yellow-300 text-sm font-semibold tracking-wider mb-6 border border-yellow-300/30">
                        <i class="fas fa-gem mr-2 text-yellow-300"></i>
                        CONTEÚDO EXCLUSIVO
                    </span>
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 text-white leading-tight">
                        Desperte o viajante em si com ofertas especiais
                    </h2>
                    <p class="text-xl text-blue-100 max-w-2xl mx-auto leading-relaxed">
                        Seja o primeiro a receber ofertas exclusivas, descontos especiais e dicas de viagem personalizadas diretamente na sua caixa de entrada.
                    </p>
                </div>
                
                <!-- Formulário -->
                <div class="max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                    <form class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-grow relative">
                            <div class="absolute inset-0 bg-white/10 backdrop-blur-sm rounded-xl -z-10"></div>
                            <input 
                                type="email" 
                                placeholder="Digite seu melhor e-mail" 
                                class="w-full bg-transparent border-2 border-white/20 text-white placeholder-blue-200/70 px-6 py-5 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-300 focus:border-transparent transition-all duration-300"
                                required
                            >
                        </div>
                        <button 
                            type="submit" 
                            class="group relative overflow-hidden bg-yellow-400 hover:bg-yellow-300 text-gray-900 font-bold px-8 py-5 rounded-xl transition-all duration-300 hover:shadow-lg hover:shadow-yellow-400/30 transform hover:-translate-y-0.5"
                        >
                            <span class="relative z-10 flex items-center justify-center">
                                <span class="mr-3">Quero me inscrever</span>
                                <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                            </span>
                            <div class="absolute inset-0 bg-gradient-to-r from-yellow-400 to-yellow-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </button>
                    </form>
                    
                    <p class="text-sm text-blue-200/80 mt-4">
                        <i class="fas fa-lock mr-1"></i> Seus dados estão protegidos. Nunca compartilharemos seu e-mail com terceiros.
                    </p>
                </div>
                
                <!-- Vantagens -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-16 text-left" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-white/5 backdrop-blur-sm p-6 rounded-xl border border-white/10 hover:bg-white/10 transition-all duration-300 group">
                        <div class="w-12 h-12 bg-yellow-400/10 rounded-lg flex items-center justify-center mb-4 text-yellow-300 text-xl group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-gift"></i>
                        </div>
                        <h4 class="text-white text-lg font-semibold mb-2">Ofertas Exclusivas</h4>
                        <p class="text-blue-100/80 text-sm">Acesso antecipado a promoções especiais e pacotes com desconto.</p>
                    </div>
                    
                    <div class="bg-white/5 backdrop-blur-sm p-6 rounded-xl border border-white/10 hover:bg-white/10 transition-all duration-300 group">
                        <div class="w-12 h-12 bg-yellow-400/10 rounded-lg flex items-center justify-center mb-4 text-yellow-300 text-xl group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <h4 class="text-white text-lg font-semibold mb-2">Guias de Viagem</h4>
                        <p class="text-blue-100/80 text-sm">Dicas e roteiros exclusivos para explorar Angola como um local.</p>
                    </div>
                    
                    <div class="bg-white/5 backdrop-blur-sm p-6 rounded-xl border border-white/10 hover:bg-white/10 transition-all duration-300 group">
                        <div class="w-12 h-12 bg-yellow-400/10 rounded-lg flex items-center justify-center mb-4 text-yellow-300 text-xl group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h4 class="text-white text-lg font-semibold mb-2">Alertas de Preço</h4>
                        <p class="text-blue-100/80 text-sm">Seja notificado quando os preços caírem para seus destinos favoritos.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    

</div>
