@extends('layouts.app')

@section('title', 'Perguntas Frequentes (FAQ)')
@section('meta_description', 'Respostas às perguntas mais frequentes sobre reservas, pagamentos e conta na KiandaStay.')
@section('robots', 'index, follow')

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 py-12">
    <div class="max-w-3xl mx-auto px-4">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Perguntas Frequentes</h1>
            <p class="text-gray-600 dark:text-gray-400">Não encontra o que procura? <a href="{{ route('contact') }}" class="text-primary dark:text-blue-400 hover:underline">Fale connosco</a>.</p>
        </div>

        <div class="space-y-4" x-data="{ open: 1 }">
            @php
                $faqs = [
                    ['Como faço uma reserva?', 'Pesquise por destino e datas, escolha a propriedade e o tipo de quarto, e siga os passos até confirmar a reserva.'],
                    ['Preciso de conta para reservar?', 'Pode iniciar uma reserva sem conta, mas registar-se permite consultar o histórico, guardar favoritos e criar alertas de preço.'],
                    ['Em que moeda são os preços?', 'Os preços são apresentados em Kwanzas angolanos (AKZ), salvo indicação em contrário.'],
                    ['Posso cancelar uma reserva?', 'As condições de cancelamento dependem de cada propriedade e são apresentadas antes de confirmar a reserva.'],
                    ['Como funcionam os alertas de preço?', 'Defina o preço-alvo para uma propriedade e receberá uma notificação por email quando o valor descer até esse limite.'],
                    ['Como anuncio a minha propriedade?', 'Consulte a nossa página de Planos para conhecer as opções de divulgação para hotéis, resorts e hospedarias.'],
                ];
            @endphp
            @foreach($faqs as $i => $faq)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                    <button type="button" @click="open === {{ $i + 1 }} ? open = null : open = {{ $i + 1 }}"
                            class="w-full flex items-center justify-between px-6 py-4 text-left">
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $faq[0] }}</span>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform" :class="open === {{ $i + 1 }} ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open === {{ $i + 1 }}" x-cloak class="px-6 pb-4 text-gray-600 dark:text-gray-300">
                        {{ $faq[1] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
