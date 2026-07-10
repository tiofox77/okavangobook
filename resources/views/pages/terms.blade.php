@extends('layouts.app')

@section('title', 'Termos e Condições')
@section('meta_description', 'Termos e condições de utilização da plataforma KiandaStay.')
@section('robots', 'index, follow')

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 py-12">
    <div class="max-w-3xl mx-auto px-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-8 md:p-12">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Termos e Condições</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">Última atualização: {{ date('d/m/Y') }}</p>

            <div class="prose max-w-none text-gray-700 dark:text-gray-300 space-y-6">
                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">1. Aceitação dos termos</h2>
                    <p>Ao utilizar a {{ \App\Models\Setting::get('app_name', 'KiandaStay') }}, aceita estes termos e condições na íntegra. Se não concordar, não deverá utilizar a plataforma.</p>
                </section>
                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">2. Utilização da plataforma</h2>
                    <p>A plataforma permite pesquisar, comparar e reservar acomodações em Angola. Compromete-se a fornecer informação verdadeira e a não utilizar o serviço para fins ilícitos.</p>
                </section>
                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">3. Reservas e pagamentos</h2>
                    <p>As reservas estão sujeitas à disponibilidade e às condições de cada propriedade, incluindo políticas de cancelamento. Os preços são apresentados em Kwanzas (AKZ), salvo indicação em contrário.</p>
                </section>
                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">4. Responsabilidade</h2>
                    <p>A KiandaStay atua como intermediária entre hóspedes e propriedades. Não somos responsáveis por serviços prestados diretamente pelas propriedades, embora trabalhemos para garantir a melhor experiência.</p>
                </section>
                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">5. Propriedade intelectual</h2>
                    <p>Todo o conteúdo da plataforma (marca, textos, imagens e código) é propriedade da KiandaStay ou dos respetivos titulares e não pode ser reproduzido sem autorização.</p>
                </section>
                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">6. Alterações</h2>
                    <p>Podemos atualizar estes termos periodicamente. A utilização continuada da plataforma após alterações implica a aceitação das mesmas.</p>
                </section>
                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">7. Contacto</h2>
                    <p>Para esclarecimentos, use a nossa <a href="{{ route('contact') }}" class="text-primary dark:text-blue-400 hover:underline">página de contacto</a>.</p>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
