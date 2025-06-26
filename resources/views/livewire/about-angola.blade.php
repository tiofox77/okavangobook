<div>
    <!-- Hero Section com vídeo/imagem paralax -->
    <div class="relative h-screen overflow-hidden">
        <div class="absolute inset-0 bg-black">
            <!-- Vídeo de background ou imagem com efeito paralax -->
            <div class="absolute inset-0 opacity-60">
                <img src="https://images.unsplash.com/photo-1521747116042-5a810fda9664?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=80" 
                     alt="Paisagem de Angola" 
                     class="w-full h-full object-cover object-center">
            </div>
        </div>
        
        <div class="absolute inset-0 flex items-center justify-center z-10">
            <div class="text-center text-white px-4 opacity-100 transform transition-all duration-1000 ease-out">
                <h1 class="text-5xl md:text-7xl font-bold mb-6 drop-shadow-lg animate-fade-in-down">ANGOLA</h1>
                <p class="text-xl md:text-2xl max-w-3xl mx-auto mb-10 opacity-90 animate-fade-in-up">Uma terra de beleza incomparável, cultura rica e pessoas acolhedoras</p>
                <div class="flex flex-wrap justify-center gap-4 animate-fade-in">
                    <a href="#exploracao" class="px-8 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition transform hover:scale-105 shadow-lg">
                        Explorar Angola
                    </a>
                    <a href="#cultura" class="px-8 py-3 bg-white/20 backdrop-blur-sm text-white rounded-lg hover:bg-white/30 transition transform hover:scale-105">
                        Nossa Cultura
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Scroll indicator -->
        <div class="absolute bottom-10 left-0 right-0 flex justify-center animate-bounce">
            <a href="#intro" class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-white/30 transition">
                <i class="fas fa-chevron-down text-xl"></i>
            </a>
        </div>
    </div>
    
    <!-- Introdução -->
    <section id="intro" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center gap-16">
                <div class="md:w-1/2 opacity-100 transform transition-all duration-1000 ease-out animate-fade-in-right">
                    <img src="https://images.unsplash.com/photo-1609198092458-38a293c7ac4b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Mapa de Angola" 
                         class="rounded-xl shadow-2xl transform -rotate-3 hover:rotate-0 transition-all duration-500"
                         onerror="this.onerror=null; this.src='{{ asset('images/placeholder-image.jpg') }}'"
                    >
                </div>
                <div class="md:w-1/2 opacity-100 transform transition-all duration-1000 ease-out animate-fade-in-left">
                    <div class="w-20 h-2 bg-primary rounded-full mb-6"></div>
                    <h2 class="text-4xl font-bold text-gray-800 mb-6">Uma Nação Deslumbrante</h2>
                    <p class="text-gray-600 text-lg mb-6">
                        Angola, oficialmente República de Angola, é um país na costa oeste da África Austral. 
                        É o sétimo maior país da África e está repleto de recursos naturais, paisagens deslumbrantes
                        e uma rica diversidade cultural.
                    </p>
                    <p class="text-gray-600 text-lg mb-8">
                        Com mais de 1.600 km de costa atlântica, florestas tropicais, savanas extensas e montanhas 
                        imponentes, Angola oferece um destino turístico ainda a ser descoberto, com potencial incomparável
                        para aventuras e experiências inesquecíveis.
                    </p>
                    <div class="grid grid-cols-2 gap-6 text-center">
                        <div class="bg-blue-50 rounded-lg p-4 transform transition hover:scale-105">
                            <p class="text-4xl font-bold text-primary">1.246.700</p>
                            <p class="text-gray-600">km² de área</p>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-4 transform transition hover:scale-105">
                            <p class="text-4xl font-bold text-primary">18</p>
                            <p class="text-gray-600">províncias</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Destinos imperdíveis - Slider -->
    <section id="exploracao" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16"
                 x-data="{show: false}"
                 x-intersect="show = true"
                 :class="{'opacity-0 -translate-y-10': !show, 'opacity-100 translate-y-0': show}"
                 class="transition-all duration-1000 ease-out">
                <div class="w-20 h-2 bg-primary rounded-full mb-6 mx-auto"></div>
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Destinos Imperdíveis</h2>
                <p class="text-gray-600 text-lg max-w-3xl mx-auto">
                    De cascatas majestosas a desertos áridos, Angola oferece paisagens deslumbrantes 
                    e experiências únicas para todos os tipos de viajantes.
                </p>
            </div>
            
            <!-- Slider/Carousel -->
            <div class="relative" x-data="{activeSlide: 0, totalSlides: 5}" x-init="">
                <div class="overflow-hidden relative">
                    <div class="flex transition-transform duration-500 ease-out" 
                         style="transform: translateX(0%)">
                        
                        <!-- Slide 1 -->
                        <div class="min-w-full">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                                <div class="rounded-2xl overflow-hidden shadow-xl h-96">
                                    <img src="https://images.unsplash.com/photo-1550951298-5c7b95a66da7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                         alt="Quedas de Kalandula" 
                                         class="w-full h-full object-cover hover:scale-110 transition-transform duration-700"
                                    >
                                </div>
                                <div class="p-6">
                                    <span class="inline-block px-3 py-1 bg-primary/10 text-primary text-sm font-semibold rounded-full mb-4">
                                        PROVÍNCIA DE MALANJE
                                    </span>
                                    <h3 class="text-3xl font-bold text-gray-800 mb-4">Quedas de Kalandula</h3>
                                    <p class="text-gray-600 mb-6">
                                        Com mais de 100 metros de altura e 400 metros de largura, as Quedas de Kalandula 
                                        são uma das maiores cachoeiras da África. Este espetáculo natural oferece 
                                        vistas de tirar o fôlego e oportunidades para fotografia impressionante.
                                    </p>
                                    <a href="{{ route('search.results', ['location' => 'malanje']) }}" class="inline-block px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition">
                                        Explorar Malanje <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Slide 2 -->
                        <div class="min-w-full">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                                <div class="rounded-2xl overflow-hidden shadow-xl h-96">
                                    <img src="https://images.unsplash.com/photo-1578508393012-597e509a5ea5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                         alt="Deserto do Namibe" 
                                         class="w-full h-full object-cover hover:scale-110 transition-transform duration-700"
                                    >
                                </div>
                                <div class="p-6">
                                    <span class="inline-block px-3 py-1 bg-primary/10 text-primary text-sm font-semibold rounded-full mb-4">
                                        PROVÍNCIA DO NAMIBE
                                    </span>
                                    <h3 class="text-3xl font-bold text-gray-800 mb-4">Deserto do Namibe</h3>
                                    <p class="text-gray-600 mb-6">
                                        Um dos desertos mais antigos do mundo, o Namibe oferece paisagens surreais 
                                        e a famosa planta Welwitschia mirabilis, que pode viver mais de 1.000 anos. 
                                        Aventure-se nas dunas de areia e descubra um cenário de outro mundo.
                                    </p>
                                    <a href="{{ route('search.results', ['location' => 'namibe']) }}" class="inline-block px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition">
                                        Explorar Namibe <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Slide 3 -->
                        <div class="min-w-full">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                                <div class="rounded-2xl overflow-hidden shadow-xl h-96">
                                    <img src="https://images.unsplash.com/photo-1520645521318-f03a712f0e67?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                         alt="Ilha de Luanda" 
                                         class="w-full h-full object-cover hover:scale-110 transition-transform duration-700"
                                    >
                                </div>
                                <div class="p-6">
                                    <span class="inline-block px-3 py-1 bg-primary/10 text-primary text-sm font-semibold rounded-full mb-4">
                                        PROVÍNCIA DE LUANDA
                                    </span>
                                    <h3 class="text-3xl font-bold text-gray-800 mb-4">Ilha de Luanda</h3>
                                    <p class="text-gray-600 mb-6">
                                        Esta península estreita que protege a Baía de Luanda é um popular 
                                        destino turístico com suas praias, restaurantes e vida noturna. 
                                        Desfrute da culinária local e vistas deslumbrantes do oceano Atlântico.
                                    </p>
                                    <a href="{{ route('search.results', ['location' => 'luanda']) }}" class="inline-block px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition">
                                        Explorar Luanda <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Slide 4 -->
                        <div class="min-w-full">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                                <div class="rounded-2xl overflow-hidden shadow-xl h-96">
                                    <img src="https://images.unsplash.com/photo-1513415277900-a62401e19be4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                         alt="Serra da Leba" 
                                         class="w-full h-full object-cover hover:scale-110 transition-transform duration-700"
                                    >
                                </div>
                                <div class="p-6">
                                    <span class="inline-block px-3 py-1 bg-primary/10 text-primary text-sm font-semibold rounded-full mb-4">
                                        PROVÍNCIA DA HUÍLA
                                    </span>
                                    <h3 class="text-3xl font-bold text-gray-800 mb-4">Serra da Leba</h3>
                                    <p class="text-gray-600 mb-6">
                                        Famosa por sua estrada sinuosa em forma de serpente, a Serra da Leba 
                                        oferece vistas panorâmicas espetaculares. A estrada é uma maravilha 
                                        da engenharia e um dos pontos mais fotografados de Angola.
                                    </p>
                                    <a href="{{ route('search.results', ['location' => 'lubango']) }}" class="inline-block px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition">
                                        Explorar Huíla <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Slide 5 -->
                        <div class="min-w-full">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                                <div class="rounded-2xl overflow-hidden shadow-xl h-96">
                                    <img src="https://images.unsplash.com/photo-1535402803947-a950d5f7ae4b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                         alt="Parque Nacional do Quiçama" 
                                         class="w-full h-full object-cover hover:scale-110 transition-transform duration-700"
                                    >
                                </div>
                                <div class="p-6">
                                    <span class="inline-block px-3 py-1 bg-primary/10 text-primary text-sm font-semibold rounded-full mb-4">
                                        PROVÍNCIA DE LUANDA
                                    </span>
                                    <h3 class="text-3xl font-bold text-gray-800 mb-4">Parque Nacional do Quiçama</h3>
                                    <p class="text-gray-600 mb-6">
                                        A apenas uma hora de Luanda, este parque nacional oferece safáris 
                                        para observar elefantes, antílopes, zebras e uma variedade de aves. 
                                        Uma oportunidade incrível para experienciar a vida selvagem africana.
                                    </p>
                                    <a href="{{ route('search.results', ['location' => 'luanda']) }}" class="inline-block px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition">
                                        Explorar Quiçama <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Controles do slider -->
                <div class="flex justify-center mt-8 space-x-4">
                    <button class="w-12 h-12 flex items-center justify-center bg-white rounded-full shadow-lg hover:bg-gray-100" 
                            onclick="changeSlide('prev')">
                        <i class="fas fa-chevron-left text-primary"></i>
                    </button>
                    
                    <div class="flex space-x-2 items-center">
                        <button class="w-3 h-3 rounded-full transition-all duration-300 bg-primary scale-125" onclick="goToSlide(0)"></button>
                        <button class="w-3 h-3 rounded-full transition-all duration-300 bg-gray-300" onclick="goToSlide(1)"></button>
                        <button class="w-3 h-3 rounded-full transition-all duration-300 bg-gray-300" onclick="goToSlide(2)"></button>
                        <button class="w-3 h-3 rounded-full transition-all duration-300 bg-gray-300" onclick="goToSlide(3)"></button>
                        <button class="w-3 h-3 rounded-full transition-all duration-300 bg-gray-300" onclick="goToSlide(4)"></button>
                    </div>
                    
                    <button class="w-12 h-12 flex items-center justify-center bg-white rounded-full shadow-lg hover:bg-gray-100" 
                            onclick="changeSlide('next')">
                        <i class="fas fa-chevron-right text-primary"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Seção de Cultura -->
    <section id="cultura" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16"
                 x-data="{show: false}"
                 x-intersect="show = true"
                 :class="{'opacity-0 -translate-y-10': !show, 'opacity-100 translate-y-0': show}"
                 class="transition-all duration-1000 ease-out">
                <div class="w-20 h-2 bg-primary rounded-full mb-6 mx-auto"></div>
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Cultura Vibrante</h2>
                <p class="text-gray-600 text-lg max-w-3xl mx-auto">
                    Angola possui uma rica tapeçaria cultural, com mais de 90 grupos étnicos e tradições 
                    que remontam a séculos, criando uma identidade nacional única e diversa.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-gray-50 rounded-xl overflow-hidden shadow-lg group hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 opacity-100 animate-fade-in-up" style="animation-delay: 100ms">
                    <div class="h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                             alt="Música Angolana" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                        >
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">Música e Dança</h3>
                        <p class="text-gray-600 mb-4">
                            Do semba ao kuduro, a música angolana é reconhecida mundialmente. 
                            A dança é parte integral da cultura, com o kizomba e a semba sendo 
                            estilos populares que representam a expressão cultural do país.
                        </p>
                    </div>
                </div>
                
                <!-- Card 2 -->
                <div class="bg-gray-50 rounded-xl overflow-hidden shadow-lg group hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 opacity-100 animate-fade-in-up" style="animation-delay: 300ms">
                    <div class="h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                             alt="Culinária Angolana" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                        >
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">Gastronomia</h3>
                        <p class="text-gray-600 mb-4">
                            A cozinha angolana é uma fusão de influências portuguesas e africanas. 
                            Pratos como o calulu, mufete de peixe e a moamba de galinha são 
                            especialidades que não pode deixar de experimentar.
                        </p>
                    </div>
                </div>
                
                <!-- Card 3 -->
                <div class="bg-gray-50 rounded-xl overflow-hidden shadow-lg group hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 opacity-100 animate-fade-in-up" style="animation-delay: 500ms">
                    <div class="h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1551913902-c92207136625?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                             alt="Artesanato Angolano" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                        >
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">Artesanato</h3>
                        <p class="text-gray-600 mb-4">
                            O artesanato angolano é rico em simbolismo e tradição. As máscaras cokwe, 
                            esculturas em madeira, cestaria e tecidos estampados são exemplos da 
                            rica expressão artística do povo angolano.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Locais Populares -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16"
                 x-data="{show: false}"
                 x-intersect="show = true"
                 :class="{'opacity-0 -translate-y-10': !show, 'opacity-100 translate-y-0': show}"
                 class="transition-all duration-1000 ease-out">
                <div class="w-20 h-2 bg-primary rounded-full mb-6 mx-auto"></div>
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Destinos Populares</h2>
                <p class="text-gray-600 text-lg max-w-3xl mx-auto">
                    Descubra os destinos mais procurados e encontre o hotel perfeito para sua próxima aventura em Angola.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($popularLocations as $location)
                <div class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 opacity-100 animate-fade-in-up"
                     style="animation-delay: {{ $loop->index * 150 }}ms">
                    <a href="{{ route('search.results', ['location' => $location->slug]) }}" class="block">
                        <div class="h-60 overflow-hidden relative">
                            <img 
                                src="{{ filter_var($location->image, FILTER_VALIDATE_URL) ? $location->image : asset('storage/locations/' . $location->image) }}" 
                                alt="{{ $location->name }}" 
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                onerror="this.src='{{ asset('images/placeholder-location.jpg') }}'"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <h3 class="text-xl font-bold text-white mb-1">{{ $location->name }}</h3>
                                <div class="flex items-center text-white/80 text-sm">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    <span>{{ ucfirst($location->province) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-5">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <i class="fas fa-hotel text-primary mr-2"></i>
                                    <span class="text-gray-700">{{ $location->hotels_count }} hotéis</span>
                                </div>
                                <span class="text-sm font-medium text-primary group-hover:text-primary-dark transition flex items-center">
                                    <span>Ver hotéis</span>
                                    <i class="fas fa-arrow-right ml-2 group-hover:ml-3 transition-all"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('destinations') }}" class="inline-block px-8 py-4 bg-primary text-white rounded-lg hover:bg-primary-dark transition transform hover:scale-105 shadow-lg">
                    Ver Todos os Destinos <i class="fas fa-globe-africa ml-2"></i>
                </a>
            </div>
        </div>
    </section>
    
    <!-- Call to Action -->
    <section class="py-20 bg-primary">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center text-white">
                <h2 class="text-4xl font-bold mb-6">Pronto para explorar Angola?</h2>
                <p class="text-lg opacity-90 mb-8">
                    Reserve seu hotel agora e embarque em uma jornada inesquecível 
                    pela terra dos contrastes e da beleza natural.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('search.results') }}" class="px-8 py-4 bg-white text-primary font-medium rounded-lg hover:bg-gray-100 transition transform hover:scale-105 shadow-lg">
                        Encontrar Hotéis <i class="fas fa-search ml-2"></i>
                    </a>
                    <a href="{{ route('contact') }}" class="px-8 py-4 bg-transparent border-2 border-white text-white font-medium rounded-lg hover:bg-white/10 transition transform hover:scale-105">
                        Fale Conosco <i class="fas fa-envelope ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Script para o carrossel -->
    <script>
        let activeSlide = 0;
        const totalSlides = 5;
        const slider = document.querySelector('.flex.transition-transform');

        function updateSlider() {
            slider.style.transform = `translateX(-${activeSlide * 100}%)`;
            
            // Atualizar os indicadores
            const indicators = document.querySelectorAll('.flex.space-x-2.items-center button');
            indicators.forEach((indicator, index) => {
                if (index === activeSlide) {
                    indicator.classList.add('bg-primary', 'scale-125');
                    indicator.classList.remove('bg-gray-300');
                } else {
                    indicator.classList.add('bg-gray-300');
                    indicator.classList.remove('bg-primary', 'scale-125');
                }
            });
        }

        function changeSlide(direction) {
            if (direction === 'prev') {
                activeSlide = Math.max(0, activeSlide - 1);
            } else {
                activeSlide = Math.min(totalSlides - 1, activeSlide + 1);
            }
            updateSlider();
        }

        function goToSlide(index) {
            activeSlide = index;
            updateSlider();
        }

        // Definir classes de animação
        if (!document.querySelector('style#animation-styles')) {
            const styleEl = document.createElement('style');
            styleEl.id = 'animation-styles';
            styleEl.textContent = `
                @keyframes fadeInDown {
                    from { opacity: 0; transform: translateY(-20px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                
                @keyframes fadeInUp {
                    from { opacity: 0; transform: translateY(20px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                
                @keyframes fadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }

                @keyframes fadeInLeft {
                    from { opacity: 0; transform: translateX(-20px); }
                    to { opacity: 1; transform: translateX(0); }
                }

                @keyframes fadeInRight {
                    from { opacity: 0; transform: translateX(20px); }
                    to { opacity: 1; transform: translateX(0); }
                }
                
                .animate-fade-in-down {
                    animation: fadeInDown 1s ease forwards;
                }
                
                .animate-fade-in-up {
                    animation: fadeInUp 1s ease forwards;
                }
                
                .animate-fade-in {
                    animation: fadeIn 1s ease forwards;
                }

                .animate-fade-in-left {
                    animation: fadeInLeft 1s ease forwards;
                }

                .animate-fade-in-right {
                    animation: fadeInRight 1s ease forwards;
                }
            `;
            document.head.appendChild(styleEl);
        }
    </script>
</div>
