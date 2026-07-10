@extends('layouts.app')

@section('title', 'Erro no servidor')
@section('robots', 'noindex, nofollow')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center bg-gray-50 dark:bg-gray-900 px-4 py-16">
    <div class="text-center max-w-lg">
        <div class="text-amber-500 mb-6">
            <i class="fas fa-triangle-exclamation text-7xl"></i>
        </div>
        <h1 class="text-6xl font-extrabold text-gray-900 dark:text-white mb-3">500</h1>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-3">Algo correu mal</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-8">
            Ocorreu um erro no nosso servidor. Já estamos a tratar disso — por favor, tente novamente dentro de momentos.
        </p>
        <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-blue-700 transition font-semibold">
            <i class="fas fa-home mr-2"></i> Voltar ao início
        </a>
    </div>
</div>
@endsection
