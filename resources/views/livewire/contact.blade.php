<div>
    <!-- Hero Section -->
    <div class="relative bg-primary/90 overflow-hidden py-20">
        <div class="absolute inset-0 opacity-20">
            <img src="https://images.unsplash.com/photo-1513415277900-a62401e19be4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=80" 
                 alt="Paisagem de Angola" 
                 class="w-full h-full object-cover"
            >
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 animate-fade-in-down">Entre em Contacto</h1>
                <p class="text-lg md:text-xl max-w-2xl mx-auto opacity-90 animate-fade-in-up">
                    Estamos aqui para ajudar a planejar sua viagem perfeita em Angola. 
                    Pergunte-nos sobre destinos, hotéis ou qualquer dúvida sobre sua próxima aventura.
                </p>
            </div>
        </div>
    </div>

    <!-- Conteúdo Principal -->
    <div class="container mx-auto px-4 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
            <!-- Formulário de Contacto -->
            <div class="bg-white rounded-xl shadow-lg p-8 transform transition duration-500 hover:shadow-2xl opacity-100 animate-fade-in-left">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Envie-nos uma mensagem</h2>
                
                @if($success)
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 animate-fade-in">
                    <p>Sua mensagem foi enviada com sucesso! Entraremos em contacto em breve.</p>
                </div>
                @endif
                
                <form wire:submit.prevent="submitForm" class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nome completo</label>
                        <input 
                            type="text" 
                            id="name" 
                            wire:model="name" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition"
                            placeholder="Seu nome completo"
                        >
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input 
                            type="email" 
                            id="email" 
                            wire:model="email" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition"
                            placeholder="seu.email@exemplo.com"
                        >
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Assunto</label>
                        <input 
                            type="text" 
                            id="subject" 
                            wire:model="subject" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition"
                            placeholder="Assunto da sua mensagem"
                        >
                        @error('subject') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Mensagem</label>
                        <textarea 
                            id="message" 
                            wire:model="message" 
                            rows="5" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition"
                            placeholder="Escreva sua mensagem aqui..."
                        ></textarea>
                        @error('message') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <button 
                            type="submit" 
                            class="w-full px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition transform hover:scale-105 flex items-center justify-center"
                        >
                            <span>Enviar Mensagem</span>
                            <i class="fas fa-paper-plane ml-2" wire:loading.remove wire:target="submitForm"></i>
                            <svg wire:loading wire:target="submitForm" class="animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Informações de Contacto -->
            <div class="space-y-10 opacity-100 animate-fade-in-right" style="animation-delay: 300ms">
                <!-- Mapa -->
                <div class="rounded-xl overflow-hidden shadow-lg h-64 md:h-80">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126096.78421915645!2d13.154970954349378!3d-8.838270001258465!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1a51f15cdc8d2c7d%3A0x850c1c5c5ecc5a92!2sLuanda%2C%20Angola!5e0!3m2!1spt-BR!2sbr!4v1655300139421!5m2!1spt-BR!2sbr" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                </div>
                
                <!-- Detalhes de Contacto -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Informações de Contacto</h3>
                    
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="bg-primary/10 rounded-full p-3 mr-4">
                                <i class="fas fa-map-marker-alt text-primary text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800 mb-1">Endereço</h4>
                                <p class="text-gray-600">Av. 4 de Fevereiro, Luanda, Angola</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="bg-primary/10 rounded-full p-3 mr-4">
                                <i class="fas fa-envelope text-primary text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800 mb-1">Email</h4>
                                <p class="text-gray-600">info@okavangobook.com</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="bg-primary/10 rounded-full p-3 mr-4">
                                <i class="fas fa-phone text-primary text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800 mb-1">Telefone</h4>
                                <p class="text-gray-600">+244 923 456 789</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="bg-primary/10 rounded-full p-3 mr-4">
                                <i class="fas fa-clock text-primary text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800 mb-1">Horário de Atendimento</h4>
                                <p class="text-gray-600">Segunda à Sexta: 8:00 - 18:00</p>
                                <p class="text-gray-600">Sábado: 9:00 - 14:00</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Redes Sociais -->
                <div class="flex justify-center space-x-4">
                    <a href="#" class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition transform hover:scale-110">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-12 h-12 bg-blue-400 text-white rounded-full flex items-center justify-center hover:bg-blue-500 transition transform hover:scale-110">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-12 h-12 bg-pink-600 text-white rounded-full flex items-center justify-center hover:bg-pink-700 transition transform hover:scale-110">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-12 h-12 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition transform hover:scale-110">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- FAQ Section -->
    <div class="bg-gray-50 py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12 opacity-100 animate-fade-in-down">
                <div class="w-20 h-2 bg-primary rounded-full mb-6 mx-auto"></div>
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Perguntas Frequentes</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Encontre respostas para as perguntas mais comuns sobre viagens e hospedagem em Angola.
                </p>
            </div>
            
            <div class="max-w-4xl mx-auto">
                <div class="space-y-6" id="faq-accordion">
                    <!-- Pergunta 1 -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden opacity-100 animate-fade-in-up" style="animation-delay: 100ms">
                        <button 
                            onclick="toggleFaq(1)" 
                            class="flex justify-between items-center w-full px-6 py-4 text-left">
                            <span class="text-lg font-medium text-gray-800">Qual é a melhor época para visitar Angola?</span>
                            <i class="fas fa-chevron-down text-gray-400" id="faq-icon-1"></i>
                        </button>
                        <div class="hidden" id="faq-content-1">
                            <div class="px-6 pb-4 text-gray-600">
                                <p>A melhor época para visitar Angola é durante a estação seca, que ocorre de maio a outubro. 
                                Nesse período, as temperaturas são mais amenas e há menos chuvas, o que facilita o acesso 
                                a muitas atrações turísticas e torna as atividades ao ar livre mais agradáveis.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pergunta 2 -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden opacity-100 animate-fade-in-up" style="animation-delay: 200ms">
                        <button 
                            onclick="toggleFaq(2)" 
                            class="flex justify-between items-center w-full px-6 py-4 text-left">
                            <span class="text-lg font-medium text-gray-800">Preciso de visto para entrar em Angola?</span>
                            <i class="fas fa-chevron-down text-gray-400" id="faq-icon-2"></i>
                        </button>
                        <div class="hidden" id="faq-content-2">
                            <div class="px-6 pb-4 text-gray-600">
                                <p>Sim, a maioria dos visitantes internacionais precisa de visto para entrar em Angola. 
                                É recomendável solicitar o visto com antecedência através da embaixada ou consulado 
                                angolano em seu país. Desde 2018, Angola também oferece visto eletrônico para 
                                cidadãos de alguns países, facilitando o processo.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pergunta 3 -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden opacity-100 animate-fade-in-up" style="animation-delay: 300ms">
                        <button 
                            onclick="toggleFaq(3)" 
                            class="flex justify-between items-center w-full px-6 py-4 text-left">
                            <span class="text-lg font-medium text-gray-800">Qual moeda é utilizada em Angola?</span>
                            <i class="fas fa-chevron-down text-gray-400" id="faq-icon-3"></i>
                        </button>
                        <div class="hidden" id="faq-content-3">
                            <div class="px-6 pb-4 text-gray-600">
                                <p>A moeda oficial de Angola é o Kwanza (AOA). É recomendável ter algum dinheiro em 
                                espécie, pois nem todos os estabelecimentos aceitam cartões de crédito, especialmente 
                                em áreas mais remotas. Você pode trocar dinheiro em bancos, casas de câmbio 
                                autorizadas ou nos hotéis de maior porte.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pergunta 4 -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden opacity-100 animate-fade-in-up" style="animation-delay: 400ms">
                        <button 
                            onclick="toggleFaq(4)" 
                            class="flex justify-between items-center w-full px-6 py-4 text-left">
                            <span class="text-lg font-medium text-gray-800">É seguro viajar por Angola?</span>
                            <i class="fas fa-chevron-down text-gray-400" id="faq-icon-4"></i>
                        </button>
                        <div class="hidden" id="faq-content-4">
                            <div class="px-6 pb-4 text-gray-600">
                                <p>Angola é geralmente segura para turistas, especialmente nas áreas turísticas e nas 
                                grandes cidades. Como em qualquer destino, é aconselhável tomar precauções básicas de 
                                segurança. Recomenda-se viajar com operadoras turísticas locais ao visitar áreas 
                                remotas, pois elas conhecem bem o território e podem oferecer uma experiência mais 
                                segura e enriquecedora.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pergunta 5 -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden opacity-100 animate-fade-in-up" style="animation-delay: 500ms">
                        <button 
                            onclick="toggleFaq(5)" 
                            class="flex justify-between items-center w-full px-6 py-4 text-left">
                            <span class="text-lg font-medium text-gray-800">Como posso me deslocar dentro de Angola?</span>
                            <i class="fas fa-chevron-down text-gray-400" id="faq-icon-5"></i>
                        </button>
                        <div class="hidden" id="faq-content-5">
                            <div class="px-6 pb-4 text-gray-600">
                                <p>Em Angola, você pode se deslocar por voos domésticos, táxis, ônibus ou alugar um carro. 
                                Para distâncias maiores entre cidades, os voos domésticos são a opção mais prática. 
                                Dentro das cidades, táxis e serviços de transporte por aplicativo são comuns. 
                                Se pretende alugar um carro, é recomendável fazê-lo com um motorista local devido 
                                às condições das estradas em algumas áreas.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Newsletter -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto bg-primary rounded-2xl shadow-xl overflow-hidden opacity-100 animate-fade-in-up" style="animation-delay: 100ms">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/3 bg-primary-dark relative hidden md:block">
                        <img 
                            src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                            alt="Viagem" 
                            class="absolute inset-0 w-full h-full object-cover opacity-50"
                        >
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center text-white p-6">
                                <i class="fas fa-envelope-open-text text-4xl mb-4"></i>
                                <h3 class="text-2xl font-bold">Fique por dentro!</h3>
                            </div>
                        </div>
                    </div>
                    <div class="md:w-2/3 p-8 md:p-12">
                        <h3 class="text-2xl font-bold text-white mb-2">Inscreva-se na nossa newsletter</h3>
                        <p class="text-white/80 mb-6">
                            Receba dicas de viagem, ofertas exclusivas e novidades sobre Angola diretamente no seu email.
                        </p>
                        <form class="space-y-4">
                            <div class="flex flex-col sm:flex-row gap-4">
                                <input 
                                    type="email" 
                                    placeholder="Seu email" 
                                    class="flex-grow px-4 py-3 rounded-lg focus:ring-2 focus:ring-white focus:outline-none"
                                >
                                <button type="submit" class="px-6 py-3 bg-white text-primary font-medium rounded-lg hover:bg-gray-100 transition transform hover:scale-105">
                                    Inscrever-se
                                </button>
                            </div>
                            <p class="text-white/70 text-sm">
                                Nunca compartilharemos seu email. Você pode cancelar a inscrição a qualquer momento.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Scripts para animações e acordeon -->
    <script>
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

        // Função para controlar o acordeon de FAQs
        function toggleFaq(id) {
            const content = document.getElementById(`faq-content-${id}`);
            const icon = document.getElementById(`faq-icon-${id}`);
            
            // Fechar todos os outros itens abertos
            const allContents = document.querySelectorAll('[id^="faq-content-"]');
            const allIcons = document.querySelectorAll('[id^="faq-icon-"]');
            
            allContents.forEach((item) => {
                if (item.id !== `faq-content-${id}` && !item.classList.contains('hidden')) {
                    item.classList.add('hidden');
                }
            });
            
            allIcons.forEach((item) => {
                if (item.id !== `faq-icon-${id}`) {
                    item.classList.remove('fa-chevron-up', 'text-primary');
                    item.classList.add('fa-chevron-down', 'text-gray-400');
                }
            });
            
            // Alternar o item clicado
            content.classList.toggle('hidden');
            
            if (content.classList.contains('hidden')) {
                icon.classList.remove('fa-chevron-up', 'text-primary');
                icon.classList.add('fa-chevron-down', 'text-gray-400');
            } else {
                icon.classList.remove('fa-chevron-down', 'text-gray-400');
                icon.classList.add('fa-chevron-up', 'text-primary');
            }
        }
    </script>
</div>
