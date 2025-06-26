<div class="bg-gray-100 min-h-screen">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">Olá, {{ $user->name }}</h1>
            <p class="mt-1 text-sm text-gray-500">Bem-vindo ao seu painel de utilizador do OkavangoBook.</p>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="text-2xl mb-4">O Que Deseja Fazer?</div>
                    
                    <!-- Cards de ações rápidas -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Pesquisar Hotéis -->
                        <div class="bg-blue-50 p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-blue-700">Pesquisar Hotéis</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">Encontre hotéis por localização, preço ou comodidades</p>
                            <a href="{{ route('search.results') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors text-sm">Pesquisar Agora</a>
                        </div>
                        
                        <!-- Ver Destinos -->
                        <div class="bg-green-50 p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-green-700">Explorar Destinos</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                </svg>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">Descubra os melhores destinos em Angola para as suas férias</p>
                            <a href="{{ route('destinations') }}" class="inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors text-sm">Ver Destinos</a>
                        </div>
                        
                        <!-- Editar Perfil -->
                        <div class="bg-purple-50 p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-purple-700">Editar Perfil</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">Actualize as suas informações pessoais e preferências</p>
                            <a href="#" class="inline-block px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition-colors text-sm">Editar Perfil</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Secção de reservas (a implementar quando tiver o modelo de reservas) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">As Minhas Reservas</h2>
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-800">Ver todas →</a>
                    </div>
                    
                    <!-- Placeholder para reservas -->
                    <div class="text-center py-10 text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="mb-2">Ainda não tem reservas</p>
                        <p class="text-sm">Encontre o hotel perfeito para a sua próxima viagem</p>
                        <a href="{{ route('search.results') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors text-sm">Pesquisar Hotéis</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
