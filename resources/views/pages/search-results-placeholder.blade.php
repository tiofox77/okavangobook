@extends('layouts.app')

@section('title', 'Resultados da Pesquisa')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="bg-white rounded-lg shadow-lg p-8 text-center">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Resultados da Pesquisa</h1>
        <p class="text-gray-600 mb-8">Esta página está em desenvolvimento. Na versão completa, aqui serão exibidos os resultados da sua busca por hotéis em Angola.</p>
        
        <div class="flex justify-center">
            <a href="{{ route('home') }}" class="bg-primary hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i> Voltar para a página inicial
            </a>
        </div>
    </div>
</div>
@endsection
