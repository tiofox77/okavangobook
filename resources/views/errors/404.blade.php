@extends('layouts.app')

@section('title', 'Página não encontrada')
@section('robots', 'noindex, follow')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center bg-gray-50 dark:bg-gray-900 px-4 py-16">
    <div class="text-center max-w-lg">
        <div class="text-primary dark:text-blue-400 mb-6">
            <i class="fas fa-map-signs text-7xl"></i>
        </div>
        <h1 class="text-6xl font-extrabold text-gray-900 dark:text-white mb-3">404</h1>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-3">Página não encontrada</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-8">
            A página que procura não existe ou foi movida. Vamos ajudá-lo a encontrar a acomodação perfeita em Angola.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                <i class="fas fa-home mr-2"></i> Voltar ao início
            </a>
            <a href="{{ route('search.results') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white dark:bg-gray-800 text-primary dark:text-blue-400 border border-primary dark:border-gray-600 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition font-semibold">
                <i class="fas fa-search mr-2"></i> Pesquisar hotéis
            </a>
        </div>
    </div>
</div>
@endsection
