<footer class="bg-gray-800 dark:bg-gray-950 text-white py-8 mt-auto transition-colors duration-300">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Coluna 1: Logo e Descrição -->
            <div>
                <a href="{{ route('home') }}" class="inline-flex rounded-xl bg-white p-2 mb-4" aria-label="KiandaStay — página inicial">
                    <img src="{{ asset('assets/img/branding/kiandastay-logo.png') }}?v=20260813"
                         alt="KiandaStay"
                         width="144" height="98"
                         class="h-20 w-auto object-contain"
                         loading="lazy" decoding="async">
                </a>
                <p class="text-gray-300 mb-4">
                    {{ __('Encontre as melhores acomodações em toda Angola com os melhores preços garantidos.') }}
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
                <h3 class="text-lg font-semibold mb-4">{{ __('Links Úteis') }}</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('about.angola') }}" class="text-gray-300 hover:text-white">{{ __('Sobre Nós') }}</a></li>
                    <li><a href="{{ route('destinations') }}" class="text-gray-300 hover:text-white">{{ __('Destinos Populares') }}</a></li>
                    <li><a href="{{ route('search.results', ['sort' => 'price_asc']) }}" class="text-gray-300 hover:text-white">{{ __('Ofertas Especiais') }}</a></li>
                    <li><a href="{{ route('articles') }}" class="text-gray-300 hover:text-white">{{ __('Blog de Viagens') }}</a></li>
                    <li><a href="{{ route('about.angola') }}" class="text-gray-300 hover:text-white">{{ __('Guia de Angola') }}</a></li>
                </ul>
            </div>
            
            <!-- Coluna 3: Suporte -->
            <div>
                <h3 class="text-lg font-semibold mb-4">{{ __('Suporte') }}</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('faq') }}" class="text-gray-300 hover:text-white">{{ __('FAQ') }}</a></li>
                    <li><a href="{{ route('privacy') }}" class="text-gray-300 hover:text-white">{{ __('Política de Privacidade') }}</a></li>
                    <li><a href="{{ route('terms') }}" class="text-gray-300 hover:text-white">{{ __('Termos e Condições') }}</a></li>
                    <li><a href="{{ route('contact') }}" class="text-gray-300 hover:text-white">{{ __('Contacte-nos') }}</a></li>
                </ul>
            </div>
            
            <!-- Coluna 4: Newsletter -->
            <div>
                <h3 class="text-lg font-semibold mb-4">{{ __('Newsletter') }}</h3>
                <p class="text-gray-300 mb-4 text-sm">{{ __('Receba ofertas exclusivas e novidades diretamente no seu email.') }}</p>
                @livewire('newsletter-subscribe')
            </div>
        </div>
        
        <!-- Hotéis por Província (links internos p/ SEO: "hotéis em {província}") -->
        @php
            $footerProvinces = \Illuminate\Support\Facades\Cache::remember('footer_provinces_hotels', 21600, function () {
                return \App\Models\Hotel::where('hotels.is_active', true)
                    ->join('locations', 'hotels.location_id', '=', 'locations.id')
                    ->selectRaw('locations.province, COUNT(*) as total')
                    ->groupBy('locations.province')
                    ->orderByDesc('total')
                    ->limit(10)
                    ->pluck('total', 'province');
            });
        @endphp
        @if($footerProvinces->isNotEmpty())
        <div class="mt-8 pt-8 border-t border-gray-700">
            <h3 class="text-lg font-semibold mb-4">{{ __('Hotéis por Província') }}</h3>
            <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm">
                @foreach($footerProvinces as $footerProvince => $footerTotal)
                    <a href="{{ route('location.details', \Illuminate\Support\Str::slug($footerProvince)) }}"
                       class="text-gray-300 hover:text-white">
                        Hotéis em {{ \App\Models\Location::provinceName(\Illuminate\Support\Str::slug($footerProvince)) }}
                        <span class="text-gray-500">({{ $footerTotal }})</span>
                    </a>
                @endforeach
                <a href="{{ route('destinations') }}" class="text-blue-400 hover:text-blue-300 font-medium">{{ __('Ver todas as províncias') }} →</a>
            </div>
        </div>
        @endif

        <!-- Informações de Contacto -->
        <div class="mt-8 pt-8 border-t border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-gray-300 text-sm">
                <div class="flex items-center">
                    <i class="fas fa-map-marker-alt mr-2 text-blue-400"></i>
                    Luanda, Angola
                </div>
                <div class="flex items-center">
                    <i class="fas fa-phone mr-2 text-blue-400"></i>
                    <a href="tel:{{ '+' . preg_replace('/\D+/', '', \App\Models\Setting::get('contact_phone', '+244 942 705 533')) }}" class="hover:text-white transition-colors">{{ \App\Models\Setting::get('contact_phone', '+244 942 705 533') }}</a>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-envelope mr-2 text-blue-400"></i>
                    <a href="mailto:{{ \App\Models\Setting::get('contact_email', 'geral@kiandastay.vip') }}" class="hover:text-white transition-colors">{{ \App\Models\Setting::get('contact_email', 'geral@kiandastay.vip') }}</a>
                </div>
            </div>
        </div>
        
        <hr class="border-gray-700 my-6">
        
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="text-gray-400 mb-4 md:mb-0">
                &copy; {{ date('Y') }} KiandaStay. {{ __('Todos os direitos reservados.') }}
            </div>
            <div class="flex space-x-3">
                <span class="bg-white text-blue-800 text-xs font-bold px-3 py-1.5 rounded shadow-sm">VISA</span>
                <span class="bg-white text-red-600 text-xs font-bold px-3 py-1.5 rounded shadow-sm">Mastercard</span>
                <span class="bg-white text-green-700 text-xs font-bold px-3 py-1.5 rounded shadow-sm">Multicaixa</span>
            </div>
        </div>
    </div>
</footer>
