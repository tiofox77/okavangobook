<footer class="bg-gray-800 dark:bg-gray-950 text-white py-8 mt-auto transition-colors duration-300">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Coluna 1: Logo e Descrição -->
            <div>
                <div class="text-2xl font-bold mb-4">Kianda<span class="text-secondary">Stay</span></div>
                <p class="text-gray-300 mb-4">
                    Encontre as melhores acomodações em toda Angola com os melhores preços garantidos.
                </p>
                @php
                    $socials = array_filter([
                        'facebook-f' => \App\Models\Setting::get('social_facebook', ''),
                        'instagram'  => \App\Models\Setting::get('social_instagram', ''),
                        'twitter'    => \App\Models\Setting::get('social_twitter', ''),
                        'youtube'    => \App\Models\Setting::get('social_youtube', ''),
                    ]);
                @endphp
                @if(count($socials))
                    <div class="flex space-x-4">
                        @foreach($socials as $icon => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ ucfirst(str_replace('-f', '', $icon)) }}" class="text-gray-300 hover:text-white transition-colors">
                                <i class="fab fa-{{ $icon }}"></i>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- Coluna 2: Links Úteis -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Links Úteis</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('about.angola') }}" class="text-gray-300 hover:text-white">Sobre Nós</a></li>
                    <li><a href="{{ route('destinations') }}" class="text-gray-300 hover:text-white">Destinos Populares</a></li>
                    <li><a href="{{ route('search.results', ['sort' => 'price_asc']) }}" class="text-gray-300 hover:text-white">Ofertas Especiais</a></li>
                    <li><a href="{{ route('articles') }}" class="text-gray-300 hover:text-white">Blog de Viagens</a></li>
                    <li><a href="{{ route('about.angola') }}" class="text-gray-300 hover:text-white">Guia de Angola</a></li>
                </ul>
            </div>
            
            <!-- Coluna 3: Suporte -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Suporte</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('faq') }}" class="text-gray-300 hover:text-white">FAQ</a></li>
                    <li><a href="{{ route('privacy') }}" class="text-gray-300 hover:text-white">Política de Privacidade</a></li>
                    <li><a href="{{ route('terms') }}" class="text-gray-300 hover:text-white">Termos e Condições</a></li>
                    <li><a href="{{ route('contact') }}" class="text-gray-300 hover:text-white">Contacte-nos</a></li>
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
                    info@kiandastay.vip
                </div>
            </div>
        </div>
        
        <hr class="border-gray-700 my-6">
        
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="text-gray-400 mb-4 md:mb-0">
                &copy; {{ date('Y') }} KiandaStay. Todos os direitos reservados.
            </div>
            <div class="flex space-x-3">
                <span class="bg-white text-blue-800 text-xs font-bold px-3 py-1.5 rounded shadow-sm">VISA</span>
                <span class="bg-white text-red-600 text-xs font-bold px-3 py-1.5 rounded shadow-sm">Mastercard</span>
                <span class="bg-white text-green-700 text-xs font-bold px-3 py-1.5 rounded shadow-sm">Multicaixa</span>
            </div>
        </div>
    </div>
</footer>
