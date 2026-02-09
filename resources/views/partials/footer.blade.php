<footer class="bg-gray-800 text-white py-8 mt-auto">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Coluna 1: Logo e Descrição -->
            <div>
                <div class="text-2xl font-bold mb-4">Okavango<span class="text-secondary">Book</span></div>
                <p class="text-gray-300 mb-4">
                    Encontre as melhores acomodações em toda Angola com os melhores preços garantidos.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-300 hover:text-white">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-white">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-white">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-white">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
            
            <!-- Coluna 2: Links Úteis -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Links Úteis</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-300 hover:text-white">Sobre Nós</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white">Destinos Populares</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white">Ofertas Especiais</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white">Blog de Viagens</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white">Guia de Angola</a></li>
                </ul>
            </div>
            
            <!-- Coluna 3: Suporte -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Suporte</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-300 hover:text-white">FAQ</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white">Política de Privacidade</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white">Termos e Condições</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white">Contacte-nos</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white">Carreiras</a></li>
                </ul>
            </div>
            
            <!-- Coluna 4: Newsletter -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Newsletter</h3>
                <p class="text-gray-300 mb-4 text-sm">Receba ofertas exclusivas e novidades diretamente no seu email.</p>
                @livewire('newsletter-subscribe')
            </div>
        </div>
        
        <!-- Informações de Contacto -->
        <div class="mt-8 pt-8 border-t border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-gray-300 text-sm">
                <div class="flex items-center">
                    <i class="fas fa-map-marker-alt mr-2 text-blue-400"></i>
                    Luanda, Angola
                </div>
                <div class="flex items-center">
                    <i class="fas fa-phone mr-2 text-blue-400"></i>
                    +244 123 456 789
                </div>
                <div class="flex items-center">
                    <i class="fas fa-envelope mr-2 text-blue-400"></i>
                    info@okavangobook.com
                </div>
            </div>
        </div>
        
        <hr class="border-gray-700 my-6">
        
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="text-gray-400 mb-4 md:mb-0">
                &copy; {{ date('Y') }} OkavangoBook. Todos os direitos reservados.
            </div>
            <div class="flex space-x-4">
                <img src="https://via.placeholder.com/50x30" alt="Visa" class="h-8">
                <img src="https://via.placeholder.com/50x30" alt="Mastercard" class="h-8">
                <img src="https://via.placeholder.com/50x30" alt="Multicaixa" class="h-8">
            </div>
        </div>
    </div>
</footer>
