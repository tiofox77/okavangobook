<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Artigos iniciais do blog — guias de viagem por Angola.
 *
 * Conteúdo escrito com base na divisão político-administrativa em vigor
 * desde 1 de janeiro de 2025 (21 províncias) e em informação verificada
 * sobre vistos, aeroportos e atrações.
 *
 * Idempotente (updateOrCreate pelo slug):
 *   php artisan db:seed --class=ArtigosBlogSeeder
 */
class ArtigosBlogSeeder extends Seeder
{
    public function run(): void
    {
        $autor = User::role('Admin')->first() ?? User::first();

        foreach ($this->artigos() as $i => $dados) {
            $slug = $dados['slug'];
            unset($dados['slug']);

            Article::updateOrCreate(['slug' => $slug], $dados + [
                'author_id' => $autor?->id,
                'is_published' => true,
                'published_at' => now()->subDays(count($this->artigos()) - $i),
                'views' => 0,
            ]);

            $this->command?->info("  {$dados['title']}");
        }
    }

    private function artigos(): array
    {
        return [
            [
                'slug' => 'guia-completo-angola-21-provincias',
                'title' => 'Angola em 21 províncias: o guia para escolher o seu destino',
                'category' => 'guia',
                'read_time' => 8,
                'excerpt' => 'Desde janeiro de 2025 Angola tem 21 províncias. Percorremos todas — do deserto do Namibe às quedas de Kalandula — para o ajudar a decidir para onde ir.',
                'tags' => ['angola', 'províncias', 'roteiro'],
                'locations' => ['luanda', 'benguela', 'huila', 'namibe', 'malanje'],
                'content' => <<<'HTML'
<p>A 1 de janeiro de 2025 entrou em vigor uma nova divisão político-administrativa: Angola passou de 18 para <strong>21 províncias</strong>. Nasceram o Cuando (desmembrado do antigo Cuando Cubango, que passou a chamar-se Cubango), o Moxico Leste e Ícolo e Bengo. Se está a planear viagem, este guia ajuda-o a escolher.</p>

<h2>Para quem quer praia</h2>
<p><strong>Benguela</strong> é a escolha óbvia: a Baía Azul, a Baía Farta e a Caotinha oferecem águas calmas e uma cidade com centro histórico caminhável. No <strong>Namibe</strong>, o deserto encontra o Atlântico — praias quase desertas, dunas e as formações da Lagoa dos Arcos. Mais perto da capital, a península do <strong>Mussulo</strong>, em Luanda, resolve um fim de semana sem grandes deslocações.</p>

<h2>Para quem quer natureza</h2>
<p><strong>Malanje</strong> concentra dois cartões de visita: as Quedas de Kalandula, com cerca de 105 metros de altura, e as Pedras Negras de Pungo Andongo. O Parque Nacional da Cangandala protege a palanca-negra-gigante, símbolo nacional que só existe em Angola.</p>
<p>Na <strong>Huíla</strong>, a Fenda da Tundavala abre-se a mais de 2.000 metros de altitude, a poucos quilómetros do Lubango, e a Serra da Leba é provavelmente a estrada mais fotografada do país.</p>

<h2>Para quem procura o menos visitado</h2>
<p>O <strong>Cuando</strong> e o <strong>Moxico Leste</strong>, criados em 2025, são as províncias mais remotas. O Cuando integra o corredor de conservação KAZA — o maior de África, partilhado com a Zâmbia, o Botswana, o Zimbabué e a Namíbia. O Moxico Leste, com capital em Cazombo, fica na região do Alto Zambeze. São destinos para quem viaja preparado e com tempo.</p>

<h2>Para quem viaja por história</h2>
<p><strong>Mbanza Kongo</strong>, no Zaire, foi capital do antigo Reino do Kongo e é Património Mundial da UNESCO. Em <strong>Luanda</strong>, a Fortaleza de São Miguel (1575) e o Mausoléu de Agostinho Neto contam séculos numa tarde. Em <strong>Ícolo e Bengo</strong>, Catete é a terra natal do primeiro presidente angolano.</p>

<h2>Como chegar</h2>
<p>Desde 2025, os voos internacionais chegam ao novo <strong>Aeroporto Internacional Dr. António Agostinho Neto</strong>, a cerca de 40 km a sudeste de Luanda, com capacidade para 15 milhões de passageiros por ano. Cidadãos de muitos países entram sem visto para turismo — confirme sempre as condições em vigor antes de viajar.</p>
HTML,
            ],
            [
                'slug' => 'melhores-praias-de-angola',
                'title' => 'As melhores praias de Angola — e quando visitar cada uma',
                'category' => 'destino',
                'read_time' => 6,
                'excerpt' => 'Da Baía Azul ao Mussulo, passando pelas praias desertas do Namibe: onde ficam, o que esperar e a melhor altura do ano para ir.',
                'tags' => ['praias', 'benguela', 'namibe', 'mussulo'],
                'locations' => ['benguela', 'namibe', 'luanda'],
                'content' => <<<'HTML'
<p>Angola tem mais de 1.600 km de costa atlântica e uma variedade de praias que vai das águas mornas do norte às correntes frias do sul, onde o deserto chega ao mar.</p>

<h2>Baía Azul, Benguela</h2>
<p>Águas transparentes e calmas, protegidas por uma enseada, a cerca de 20 km de Benguela. É a praia de referência do país para quem quer nadar sem ondulação forte. Nas proximidades, a Baía Farta e a Caotinha completam o dia.</p>

<h2>Mussulo, Luanda</h2>
<p>Uma língua de areia a sul de Luanda, acessível de barco. Do lado da baía as águas são quase paradas — ideal para famílias e desportos náuticos; do lado oceânico, há ondulação. É a fuga de fim de semana clássica de quem vive na capital.</p>

<h2>Praias do Namibe</h2>
<p>No sul, a paisagem muda por completo: dunas que terminam no Atlântico, falésias e praias onde é possível não cruzar ninguém durante horas. As águas são frias (corrente de Benguela) e o vento é constante — leve um corta-vento mesmo em pleno verão.</p>

<h2>Quando ir</h2>
<p>A estação seca, de <strong>maio a setembro</strong>, é a mais confortável: menos humidade, céu limpo e temperaturas amenas. De outubro a abril há calor e chuvas — mais intensas no norte. No Namibe chove muito pouco todo o ano, mas as manhãs podem ser de nevoeiro.</p>

<h2>Antes de ir</h2>
<p>Fora das cidades há poucos serviços: leve água, protetor solar e dinheiro em numerário. Confirme sempre o estado das estradas na época das chuvas e, se for de carro, prefira viajar de dia.</p>
HTML,
            ],
            [
                'slug' => 'quedas-de-kalandula-guia-pratico',
                'title' => 'Quedas de Kalandula: guia prático para a visita',
                'category' => 'destino',
                'read_time' => 5,
                'excerpt' => 'Entre as maiores quedas de água de África, ficam em Malanje e podem ser visitadas num fim de semana a partir de Luanda. Veja como chegar e onde ficar.',
                'tags' => ['kalandula', 'malanje', 'cascatas'],
                'locations' => ['malanje'],
                'content' => <<<'HTML'
<p>Com cerca de 105 metros de altura e centenas de metros de largura na época das chuvas, as Quedas de Kalandula estão entre as maiores de África. Ficam na província de Malanje, a nordeste de Luanda.</p>

<h2>Como chegar</h2>
<p>São cerca de 360 km de Luanda até à cidade de Malanje por estrada asfaltada, e mais aproximadamente 80 km até às quedas. Conte com um dia de viagem tranquilo, ou parta cedo e chegue a tempo da luz da tarde, a melhor para fotografia.</p>

<h2>Quando ir</h2>
<p>Na <strong>época das chuvas</strong> (outubro a abril) o caudal é impressionante e a cortina de água atinge a largura máxima — mas o acesso ao miradouro inferior pode estar escorregadio. Na <strong>época seca</strong> o caudal é menor, porém vê-se melhor a estrutura rochosa e o acesso é mais fácil.</p>

<h2>O que combinar na mesma viagem</h2>
<ul>
<li><strong>Pedras Negras de Pungo Andongo</strong> — formações rochosas gigantes ligadas às lendas da rainha Nzinga, a caminho de Malanje.</li>
<li><strong>Parque Nacional da Cangandala</strong> — o refúgio da palanca-negra-gigante, espécie endémica de Angola.</li>
</ul>

<h2>Dicas</h2>
<p>Leve calçado com boa aderência, repelente e água. Há vendedores locais junto ao miradouro, mas poucos serviços — planeie as refeições. E reserve alojamento em Malanje com antecedência nos fins de semana prolongados.</p>
HTML,
            ],
            [
                'slug' => 'primeira-viagem-a-angola-dicas',
                'title' => 'Primeira viagem a Angola: 10 coisas que convém saber',
                'category' => 'dica',
                'read_time' => 7,
                'excerpt' => 'Vistos, moeda, transportes, saúde e etiqueta local. O essencial para quem visita Angola pela primeira vez, sem surpresas à chegada.',
                'tags' => ['dicas', 'primeira viagem', 'vistos'],
                'locations' => ['luanda'],
                'content' => <<<'HTML'
<p>Angola é um destino que recompensa quem chega preparado. Reunimos o essencial para uma primeira visita correr bem.</p>

<h2>1. Vistos</h2>
<p>Cidadãos de vários países podem entrar sem visto para turismo. As condições e a duração permitida mudam com alguma frequência — confirme junto da representação diplomática antes de comprar bilhete.</p>

<h2>2. Chegada</h2>
<p>Os voos internacionais operam no novo Aeroporto Internacional Dr. António Agostinho Neto, aberto em 2025, a cerca de 40 km do centro de Luanda. Combine o transporte com antecedência, sobretudo se chegar de noite.</p>

<h2>3. Moeda</h2>
<p>A moeda é o <strong>kwanza (AKZ)</strong>. Fora dos hotéis e centros comerciais das grandes cidades, o numerário continua a ser rei — leve dinheiro trocado quando sair de Luanda.</p>

<h2>4. Melhor altura para ir</h2>
<p>A estação seca, de maio a setembro, é a mais confortável em quase todo o país.</p>

<h2>5. Distâncias</h2>
<p>Angola é grande — tem mais de 1,2 milhões de km². Voos internos poupam dias inteiros de estrada entre províncias distantes.</p>

<h2>6. Saúde</h2>
<p>Confirme as vacinas recomendadas com antecedência e leve repelente. Beba água engarrafada fora dos grandes hotéis.</p>

<h2>7. Língua</h2>
<p>O português é a língua oficial e é falado em todo o país. Umbundu, kimbundu e kikongo são muito presentes no dia a dia.</p>

<h2>8. Ligação à internet</h2>
<p>Comprar um cartão SIM local é simples e barato, e resolve navegação e comunicação fora do hotel.</p>

<h2>9. Etiqueta</h2>
<p>Cumprimentar antes de pedir é importante. Peça sempre autorização antes de fotografar pessoas — e evite fotografar instalações oficiais.</p>

<h2>10. Reservar alojamento</h2>
<p>Nas províncias fora de Luanda a oferta é limitada e esgota em fins de semana prolongados. Reserve com antecedência e confirme diretamente com a propriedade se viajar em época alta.</p>
HTML,
            ],
            [
                'slug' => 'lubango-tundavala-serra-da-leba',
                'title' => 'Lubango, Tundavala e Serra da Leba: o planalto da Huíla',
                'category' => 'destino',
                'read_time' => 6,
                'excerpt' => 'A 2.000 metros de altitude, a Huíla oferece o clima mais ameno de Angola e duas das paisagens mais impressionantes do país.',
                'tags' => ['huila', 'lubango', 'tundavala', 'montanha'],
                'locations' => ['huila'],
                'content' => <<<'HTML'
<p>O Lubango, capital da Huíla, fica no planalto sul, a mais de 1.700 metros de altitude. O clima ameno todo o ano e a proximidade de dois miradouros extraordinários fazem da província um dos destinos mais completos de Angola.</p>

<h2>Fenda da Tundavala</h2>
<p>A cerca de 18 km do Lubango, a fenda abre-se a mais de 2.000 metros de altitude, com uma queda a pique de centenas de metros sobre a planície. Vá de manhã cedo: a partir do meio da tarde é frequente formar-se nevoeiro que tapa completamente a vista.</p>

<h2>Serra da Leba</h2>
<p>A estrada que liga o planalto à planície do Namibe desce em curvas apertadas ao longo da encosta — a imagem mais reproduzida do país. Há um miradouro no topo para apreciar o traçado sem parar na via.</p>

<h2>Cristo Rei</h2>
<p>A estátua sobre a cidade oferece a melhor vista panorâmica do Lubango e é um bom primeiro ponto para se orientar.</p>

<h2>Quando ir e o que levar</h2>
<p>A altitude faz as noites serem frescas mesmo no verão — leve um casaco. A estação seca (maio a setembro) garante céu limpo, que é o que interessa nos miradouros.</p>

<h2>Onde ficar</h2>
<p>O Lubango tem a maior oferta hoteleira do sul do país e serve de base para toda a região. Compare preços e disponibilidade antes de reservar, sobretudo na época das festas da Nossa Senhora do Monte.</p>
HTML,
            ],
            [
                'slug' => 'mbanza-kongo-patrimonio-unesco',
                'title' => 'Mbanza Kongo: a capital de um reino, Património da UNESCO',
                'category' => 'destino',
                'read_time' => 5,
                'excerpt' => 'No Zaire, a antiga capital do Reino do Kongo guarda vestígios da primeira catedral a sul do Sara e uma história com séculos.',
                'tags' => ['zaire', 'mbanza kongo', 'unesco', 'história'],
                'locations' => ['zaire'],
                'content' => <<<'HTML'
<p>Mbanza Kongo, no norte de Angola, foi a capital política e espiritual do Reino do Kongo, um dos estados africanos mais poderosos do seu tempo. Em 2017 tornou-se Património Mundial da UNESCO.</p>

<h2>O que ver</h2>
<p>As ruínas da <strong>Kulumbimbi</strong>, a antiga catedral erguida no século XVI, são o símbolo da cidade — considerada a primeira igreja construída a sul do Sara. O Museu dos Reis do Kongo reúne peças que ajudam a contextualizar a história do reino e do território.</p>

<h2>Como chegar</h2>
<p>Mbanza Kongo é servida por aeroporto, com ligações a Luanda — a forma mais prática de chegar. Por estrada, a viagem desde a capital é longa e deve ser planeada com paragens.</p>

<h2>Combinar com</h2>
<p>A província do Zaire tem ainda a foz do rio Congo e uma costa atlântica pouco visitada, boa para quem quer juntar história e praia sem multidões.</p>

<h2>Dica</h2>
<p>Contrate um guia local: grande parte do valor do sítio está no que não se vê à primeira vista, e a interpretação faz toda a diferença.</p>
HTML,
            ],
        ];
    }
}
