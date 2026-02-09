<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Configurações do Sistema</h1>
        <p class="mt-2 text-gray-600">Gerir todas as configurações da aplicação</p>
    </div>

    <!-- Tab Navigation -->
    <div class="mb-6">
        <nav class="flex space-x-8 border-b border-gray-200">
            <button wire:click="setActiveTab('general')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm transition-colors
                           {{ $activeTab === 'general' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <i class="fas fa-cog mr-2"></i>Configurações Gerais
            </button>
            <button wire:click="setActiveTab('contact')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm transition-colors
                           {{ $activeTab === 'contact' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <i class="fas fa-address-book mr-2"></i>Informações de Contacto
            </button>
            <button wire:click="setActiveTab('maintenance')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm transition-colors
                           {{ $activeTab === 'maintenance' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <i class="fas fa-tools mr-2"></i>Manutenção
            </button>
            <button wire:click="setActiveTab('requirements')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm transition-colors
                           {{ $activeTab === 'requirements' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <i class="fas fa-check-circle mr-2"></i>Requisitos do Sistema
            </button>
        </nav>
    </div>

    <!-- General Settings Tab -->
    @if($activeTab === 'general')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Application Settings -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        Informações da Aplicação
                    </h3>
                    
                    <form wire:submit.prevent="saveGeneralSettings" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nome da Aplicação</label>
                            <input type="text" wire:model.live="appName" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('appName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                            <textarea wire:model.live="appDescription" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            @error('appDescription') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Palavras-chave</label>
                            <input type="text" wire:model.live="appKeywords" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="hotéis, reservas, turismo">
                            @error('appKeywords') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Moeda</label>
                                <select wire:model.live="appCurrency" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="KZ">Kwanza (KZ)</option>
                                    <option value="USD">Dólar (USD)</option>
                                    <option value="EUR">Euro (EUR)</option>
                                </select>
                                @error('appCurrency') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Idioma</label>
                                <select wire:model.live="appLanguage" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="pt">Português</option>
                                    <option value="en">English</option>
                                    <option value="fr">Français</option>
                                </select>
                                @error('appLanguage') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fuso Horário</label>
                            <select wire:model.live="appTimezone" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="Africa/Luanda">África/Luanda</option>
                                <option value="UTC">UTC</option>
                                <option value="Europe/Lisbon">Europa/Lisboa</option>
                            </select>
                            @error('appTimezone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" 
                                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-save mr-2"></i>Salvar Configurações
                        </button>
                    </form>
                </div>
            </div>

            <!-- File Uploads -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-images text-green-500 mr-2"></i>
                        Imagens da Aplicação
                    </h3>
                    
                    <div class="space-y-6">
                        <!-- Logo Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Logo da Aplicação</label>
                            
                            <!-- Imagem atual -->
                            @if($currentAppLogo && !$appLogo)
                                <div class="mb-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <img src="{{ Storage::url($currentAppLogo) }}" alt="Logo Atual" class="h-16 w-auto rounded shadow-sm">
                                            <div>
                                                <p class="text-xs text-gray-600 font-medium">Imagem atual</p>
                                                <p class="text-xs text-gray-400">{{ basename($currentAppLogo) }}</p>
                                            </div>
                                        </div>
                                        <button type="button" wire:click="removeAppLogo" 
                                                wire:confirm="Tem certeza que deseja remover o logo?"
                                                class="text-red-600 hover:text-red-800 px-3 py-1 rounded hover:bg-red-50 transition-colors">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Upload de nova imagem -->
                            <div class="flex items-center space-x-4">
                                <input type="file" wire:model="appLogo" accept="image/*" 
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            @error('appLogo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            
                            <!-- Preview da nova imagem -->
                            @if($appLogo)
                                <div class="mt-3 p-3 bg-green-50 rounded-lg border border-green-200">
                                    <p class="text-xs text-green-700 font-medium mb-2"><i class="fas fa-info-circle mr-1"></i> Nova imagem (pré-visualização)</p>
                                    <img src="{{ $appLogo->temporaryUrl() }}" alt="Preview" class="h-16 w-auto rounded shadow-sm">
                                </div>
                            @endif
                        </div>

                        <!-- Favicon Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                            
                            <!-- Imagem atual -->
                            @if($currentAppFavicon && !$appFavicon)
                                <div class="mb-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <img src="{{ Storage::url($currentAppFavicon) }}" alt="Favicon Atual" class="h-8 w-8 rounded shadow-sm">
                                            <div>
                                                <p class="text-xs text-gray-600 font-medium">Imagem atual</p>
                                                <p class="text-xs text-gray-400">{{ basename($currentAppFavicon) }}</p>
                                            </div>
                                        </div>
                                        <button type="button" wire:click="removeAppFavicon" 
                                                wire:confirm="Tem certeza que deseja remover o favicon?"
                                                class="text-red-600 hover:text-red-800 px-3 py-1 rounded hover:bg-red-50 transition-colors">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Upload de nova imagem -->
                            <div class="flex items-center space-x-4">
                                <input type="file" wire:model="appFavicon" accept="image/*" 
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            @error('appFavicon') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            
                            <!-- Preview da nova imagem -->
                            @if($appFavicon)
                                <div class="mt-3 p-3 bg-green-50 rounded-lg border border-green-200">
                                    <p class="text-xs text-green-700 font-medium mb-2"><i class="fas fa-info-circle mr-1"></i> Nova imagem (pré-visualização)</p>
                                    <img src="{{ $appFavicon->temporaryUrl() }}" alt="Preview" class="h-8 w-8 rounded shadow-sm">
                                </div>
                            @endif
                        </div>

                        <!-- Hero Background Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Imagem de Fundo Hero</label>
                            
                            <!-- Imagem atual -->
                            @if($currentHeroBackground && !$heroBackground)
                                <div class="mb-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <img src="{{ Storage::url($currentHeroBackground) }}" alt="Fundo Hero Atual" class="h-24 w-auto rounded shadow-sm">
                                            <div>
                                                <p class="text-xs text-gray-600 font-medium">Imagem atual</p>
                                                <p class="text-xs text-gray-400">{{ basename($currentHeroBackground) }}</p>
                                            </div>
                                        </div>
                                        <button type="button" wire:click="removeHeroBackground" 
                                                wire:confirm="Tem certeza que deseja remover a imagem de fundo?"
                                                class="text-red-600 hover:text-red-800 px-3 py-1 rounded hover:bg-red-50 transition-colors">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Upload de nova imagem -->
                            <div class="flex items-center space-x-4">
                                <input type="file" wire:model="heroBackground" accept="image/*" 
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            @error('heroBackground') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            
                            <!-- Preview da nova imagem -->
                            @if($heroBackground)
                                <div class="mt-3 p-3 bg-green-50 rounded-lg border border-green-200">
                                    <p class="text-xs text-green-700 font-medium mb-2"><i class="fas fa-info-circle mr-1"></i> Nova imagem (pré-visualização)</p>
                                    <img src="{{ $heroBackground->temporaryUrl() }}" alt="Preview" class="h-24 w-auto rounded shadow-sm">
                                </div>
                            @endif
                        </div>

                        <!-- Offers Background Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Imagem de Fundo Ofertas Especiais</label>
                            
                            <!-- Imagem atual -->
                            @if($currentOffersBackground && !$offersBackground)
                                <div class="mb-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <img src="{{ Storage::url($currentOffersBackground) }}" alt="Fundo Ofertas Atual" class="h-24 w-auto rounded shadow-sm">
                                            <div>
                                                <p class="text-xs text-gray-600 font-medium">Imagem atual</p>
                                                <p class="text-xs text-gray-400">{{ basename($currentOffersBackground) }}</p>
                                            </div>
                                        </div>
                                        <button type="button" wire:click="removeOffersBackground" 
                                                wire:confirm="Tem certeza que deseja remover a imagem de fundo de ofertas?"
                                                class="text-red-600 hover:text-red-800 px-3 py-1 rounded hover:bg-red-50 transition-colors">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Upload de nova imagem -->
                            <div class="flex items-center space-x-4">
                                <input type="file" wire:model="offersBackground" accept="image/*" 
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            @error('offersBackground') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            
                            <!-- Preview da nova imagem -->
                            @if($offersBackground)
                                <div class="mt-3 p-3 bg-green-50 rounded-lg border border-green-200">
                                    <p class="text-xs text-green-700 font-medium mb-2"><i class="fas fa-info-circle mr-1"></i> Nova imagem (pré-visualização)</p>
                                    <img src="{{ $offersBackground->temporaryUrl() }}" alt="Preview" class="h-24 w-auto rounded shadow-sm">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Contact Settings Tab -->
    @if($activeTab === 'contact')
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-address-book text-blue-500 mr-2"></i>
                    Informações de Contacto
                </h3>
                
                <form wire:submit.prevent="saveContactSettings" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email de Contacto</label>
                            <input type="email" wire:model.live="contactEmail" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('contactEmail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
                            <input type="text" wire:model.live="contactPhone" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('contactPhone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Endereço</label>
                        <textarea wire:model.live="contactAddress" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        @error('contactAddress') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <h4 class="text-md font-semibold text-gray-900 mt-6 mb-3">Redes Sociais</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Facebook</label>
                            <input type="text" wire:model.live="socialFacebook" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="@username ou URL">
                            @error('socialFacebook') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Instagram</label>
                            <input type="text" wire:model.live="socialInstagram" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="@username ou URL">
                            @error('socialInstagram') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Twitter</label>
                            <input type="text" wire:model.live="socialTwitter" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="@username ou URL">
                            @error('socialTwitter') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <button type="submit" 
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-save mr-2"></i>Salvar Informações de Contacto
                    </button>
                </form>
            </div>
        </div>
    @endif

    <!-- Maintenance Tab -->
    @if($activeTab === 'maintenance')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Maintenance Settings -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-tools text-yellow-500 mr-2"></i>
                        Configurações de Manutenção
                    </h3>
                    
                    <form wire:submit.prevent="saveMaintenanceSettings" class="space-y-4">
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Modo de Manutenção</h4>
                                <p class="text-sm text-gray-500">Desativar temporariamente o site para os usuários</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="maintenanceMode" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Modo Debug</h4>
                                <p class="text-sm text-gray-500">Exibir informações detalhadas de erro</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="debugMode" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <button type="submit" 
                                class="w-full bg-yellow-600 text-white py-2 px-4 rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-save mr-2"></i>Salvar Configurações de Manutenção
                        </button>
                    </form>
                </div>
            </div>

            <!-- Cache Management -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-broom text-green-500 mr-2"></i>
                        Gestão de Cache
                    </h3>
                    
                    <div class="space-y-4">
                        <p class="text-sm text-gray-600">
                            Limpar o cache pode resolver problemas de configuração e melhorar o desempenho.
                        </p>
                        
                        <button wire:click="showConfirmation('clearCache', 'Tem certeza que deseja limpar todo o cache do sistema?')" 
                                class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-broom mr-2"></i>Limpar Cache
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- System Requirements Tab -->
    @if($activeTab === 'requirements')
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Requisitos do Sistema
                    </h3>
                    <button wire:click="checkSystemRequirements" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                            wire:loading.attr="disabled">
                        <i class="fas fa-sync-alt mr-2" wire:loading.class="fa-spin"></i>
                        Verificar Requisitos
                    </button>
                </div>

                @if($isCheckingRequirements)
                    <div class="flex items-center justify-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <span class="ml-2 text-gray-600">Verificando requisitos...</span>
                    </div>
                @endif

                @if(!empty($systemRequirements))
                    <!-- Requirements Summary -->
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $requirementsStatus['passed'] }}</div>
                            <div class="text-sm text-green-600">Aprovados</div>
                        </div>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-yellow-600">{{ $requirementsStatus['warnings'] }}</div>
                            <div class="text-sm text-yellow-600">Avisos</div>
                        </div>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-red-600">{{ $requirementsStatus['failed'] }}</div>
                            <div class="text-sm text-red-600">Falharam</div>
                        </div>
                    </div>

                    <!-- Requirements List -->
                    <div class="space-y-3">
                        @foreach($systemRequirements as $requirement)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $requirement['name'] }}</h4>
                                    <p class="text-sm text-gray-500">{{ $requirement['required'] }}</p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600">{{ $requirement['current'] }}</span>
                                    @if($requirement['status'] === 'passed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>OK
                                        </span>
                                    @elseif($requirement['status'] === 'warning')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Aviso
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i>Erro
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Confirmation Modal -->
    @if($showConfirmModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mt-2">Confirmação</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">{{ $confirmMessage }}</p>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button wire:click="executeConfirmedAction" 
                                class="px-4 py-2 bg-yellow-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-300 mr-2 mb-2">
                            Confirmar
                        </button>
                        <button wire:click="cancelConfirmation" 
                                class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
</div>

<script>
    // Auto-hide flash messages
    setTimeout(() => {
        const alerts = document.querySelectorAll('[role="alert"]');
        alerts.forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);
</script>
