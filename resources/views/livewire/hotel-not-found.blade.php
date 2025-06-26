<div class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4 py-16 text-center">
        <div class="bg-white rounded-lg shadow-md p-8 max-w-2xl mx-auto">
            <div class="text-red-500 mb-4">
                <i class="fas fa-exclamation-circle text-6xl"></i>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Hotel não encontrado</h1>
            
            <p class="text-gray-600 mb-6">
                Desculpe, o hotel que você está procurando não foi encontrado ou não está mais disponível em nosso sistema.
            </p>
            
            <div class="flex justify-center space-x-4">
                <a href="{{ route('home') }}" class="bg-primary hover:bg-blue-700 text-white font-bold py-3 px-6 rounded transition duration-300">
                    <i class="fas fa-home mr-2"></i> Voltar para página inicial
                </a>
                
                <a href="{{ route('search.results') }}" class="bg-white text-primary border border-primary font-bold py-3 px-6 rounded hover:bg-gray-100 transition duration-300">
                    <i class="fas fa-search mr-2"></i> Buscar outros hotéis
                </a>
            </div>
        </div>
    </div>
</div>
