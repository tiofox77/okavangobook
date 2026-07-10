@extends('layouts.app')

@section('title', 'Política de Privacidade')
@section('meta_description', 'Saiba como a KiandaStay recolhe, utiliza e protege os seus dados pessoais.')
@section('robots', 'index, follow')

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 py-12">
    <div class="max-w-3xl mx-auto px-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-8 md:p-12">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Política de Privacidade</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">Última atualização: {{ date('d/m/Y') }}</p>

            <div class="prose max-w-none text-gray-700 dark:text-gray-300 space-y-6">
                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">1. Introdução</h2>
                    <p>A {{ \App\Models\Setting::get('app_name', 'KiandaStay') }} valoriza a sua privacidade. Esta política explica que dados recolhemos, como os utilizamos e quais os seus direitos ao usar a nossa plataforma de reservas de acomodações em Angola.</p>
                </section>
                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">2. Dados que recolhemos</h2>
                    <ul class="list-disc pl-6 space-y-1">
                        <li>Dados de conta: nome, email e palavra-passe (encriptada).</li>
                        <li>Dados de reserva: datas, número de hóspedes e propriedade escolhida.</li>
                        <li>Preferências de pesquisa e histórico, para melhorar as recomendações.</li>
                        <li>Dados técnicos: endereço IP, tipo de dispositivo e navegador.</li>
                    </ul>
                </section>
                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">3. Como utilizamos os dados</h2>
                    <p>Utilizamos os seus dados para processar reservas, personalizar recomendações, enviar comunicações que autorizou (newsletter, alertas de preço) e melhorar a plataforma.</p>
                </section>
                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">4. Partilha de dados</h2>
                    <p>Não vendemos os seus dados. Podemos partilhar informação estritamente necessária com as propriedades para concretizar a sua reserva.</p>
                </section>
                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">5. Os seus direitos</h2>
                    <p>Pode aceder, corrigir ou eliminar os seus dados, bem como cancelar subscrições a qualquer momento. Para exercer estes direitos, contacte-nos.</p>
                </section>
                <section>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">6. Contacto</h2>
                    <p>Para questões sobre esta política, use a nossa <a href="{{ route('contact') }}" class="text-primary dark:text-blue-400 hover:underline">página de contacto</a>.</p>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
