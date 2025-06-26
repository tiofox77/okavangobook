@extends('layouts.app')

@section('title', 'Detalhes do Hotel')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="bg-white rounded-lg shadow-lg p-8 text-center">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Detalhes do Hotel (ID: {{ $id }})</h1>
        <p class="text-gray-600 mb-8">Esta página está em desenvolvimento. Na versão completa, aqui serão exibidas informações detalhadas sobre o hotel, fotos, preços e opções de reserva.</p>
        
        <div class="flex justify-center space-x-4">
            <a href="{{ route('home') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                <i class="fas fa-home mr-2"></i> Página inicial
            </a>
            <a href="{{ route('search.results') }}" class="bg-primary hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i> Voltar para resultados
            </a>
        </div>
    </div>
</div>
@endsection
