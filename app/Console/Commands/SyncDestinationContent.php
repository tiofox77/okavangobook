<?php

namespace App\Console\Commands;

use App\Models\Location;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SyncDestinationContent extends Command
{
    protected $signature = 'destinations:sync-content {--only= : Atualiza apenas uma província pelo slug}';

    protected $description = 'Atualiza histórias e fotografias reais das províncias através do Wikimedia Commons';

    private array $destinations = [
        'bengo' => ['Bengo', 'Kissama National Park Angola', 'Entre o rio Kwanza e extensas áreas agrícolas, o Bengo guarda praias tranquilas, lagoas e comunidades onde a vida rural angolana permanece muito presente. Caxito é a principal porta de entrada para descobrir a província.'],
        'benguela' => ['Benguela', '"Banco Nacional, Benguela, Angola"', 'Fundada no século XVII, Benguela cresceu ligada ao comércio atlântico e ao Caminho de Ferro de Benguela. Hoje combina património histórico, gastronomia costeira e praias como a Baía Azul e a Caotinha.'],
        'bie' => ['Bié', '"Cidade do kuito"', 'No coração do planalto central, o Bié reúne terras férteis, nascentes e paisagens de altitude. O Kuito preserva memórias decisivas da história contemporânea de Angola e é um ponto de encontro de culturas do centro do país.'],
        'cabinda' => ['Cabinda', '"Exemplo das árvores da floresta de Mayombe 01"', 'Separada geograficamente do restante território, Cabinda possui uma identidade singular. A floresta do Maiombe, as tradições locais e a costa atlântica fazem da província um destino de natureza e cultura.'],
        'cuando-cubango' => ['Cuando Cubango', 'Okavango River Angola', 'Conhecida como Terras do Fim do Mundo, a província é atravessada pelos rios Cubango e Cuito, que alimentam o delta do Okavango. Savanas, zonas húmidas e vida selvagem dão à região enorme potencial de ecoturismo.'],
        'cuanza-norte' => ['Cuanza Norte', '"Cuanza river near Dondo, Angola"', 'N’dalatando nasceu numa região de clima ameno e vegetação exuberante. O Jardim Botânico, as antigas rotas do café e os rios que descem para o Kwanza contam a história agrícola da província.'],
        'cuanza-sul' => ['Cuanza Sul', '"Beach in Sumbe, Angola"', 'Do litoral do Sumbe às montanhas do interior, o Cuanza Sul oferece praias, grutas e antigas fazendas. A província é também conhecida pelas águas termais e pela diversidade das suas paisagens.'],
        'cunene' => ['Cunene', '"Ruacana.jpg"', 'No sul de Angola, o Cunene é território de fortes tradições agro-pastoris. Ondjiva, as quedas do Ruacaná e as comunidades Cuanhama revelam uma cultura moldada pelo clima semiárido e pela fronteira com a Namíbia.'],
        'huambo' => ['Huambo', 'Huambo Angola city', 'Erguido no planalto central, o Huambo beneficia de clima fresco e solos férteis. A cidade, ligada ao Caminho de Ferro de Benguela, renasceu como centro universitário, agrícola e cultural.'],
        'huila' => ['Huíla', 'Serra da Leba Road Angola', 'A Huíla reúne alguns dos cenários mais emblemáticos de Angola: a Tundavala, a Serra da Leba e o planalto da Humpata. Lubango combina arquitetura histórica, mercados e uma longa tradição de encontro entre povos.'],
        'luanda' => ['Luanda', 'Luanda Angola panorama', 'Fundada em 1576, Luanda cresceu em torno da baía e tornou-se o principal centro político e cultural do país. A Fortaleza de São Miguel, a Marginal e a Ilha de Luanda unem séculos de história à energia contemporânea.'],
        'lunda-norte' => ['Lunda Norte', 'Dundo Angola museum', 'A Lunda Norte é marcada pelos rios, pela floresta e pela tradição dos povos Lunda e Cokwe. O Museu do Dundo conserva uma das mais importantes coleções etnográficas de Angola.'],
        'lunda-sul' => ['Lunda Sul', '"Interior da cidade de saurimo"', 'Saurimo é a capital de uma região de rios e matas ligada à história da cultura Lunda. Para além dos diamantes, a província guarda artesanato, música e paisagens ainda pouco exploradas.'],
        'malanje' => ['Malanje', 'Kalandula Falls Angola', 'Malanje abriga as Quedas de Kalandula, entre as maiores de África, e as formações rochosas de Pungo Andongo. A região foi também um importante centro agrícola e conserva narrativas do antigo Reino do Ndongo.'],
        'moxico' => ['Moxico', 'Zambezi River Angola', 'Maior província histórica de Angola, o Moxico é terra de grandes rios e extensas florestas de miombo. Luena é ponto de partida para conhecer comunidades do leste e paisagens ligadas às nascentes do Zambeze.'],
        'namibe' => ['Namibe', 'Namib Desert Angola', 'No Namibe, o deserto encontra o Atlântico. As dunas, a Lagoa dos Arcos e a rara welwitschia compõem uma paisagem ancestral, enquanto a cidade do Namibe preserva arquitetura e tradições marítimas.'],
        'uige' => ['Uíge', 'coffee plantation Angola', 'O Uíge prosperou historicamente com o café e continua coberto por florestas húmidas do norte. Grutas, rios e aldeias revelam uma província de grande riqueza natural e cultural.'],
        'zaire' => ['Zaire', '"Desfile Provincial do Carnaval em Mbanza Kongo"', 'Mbanza Kongo foi a capital do antigo Reino do Kongo e é hoje Património Mundial da UNESCO. A província estende-se até à foz do rio Congo, reunindo arqueologia, espiritualidade e paisagens atlânticas.'],
    ];

    public function handle(): int
    {
        Storage::disk('public')->makeDirectory('locations/commons');
        $creditsPath = storage_path('app/public/locations/commons/credits.json');
        $credits = is_file($creditsPath)
            ? (json_decode(file_get_contents($creditsPath), true) ?: [])
            : [];

        foreach ($this->destinations as $province => [$name, $query, $description]) {
            if ($this->option('only') && $this->option('only') !== $province) {
                continue;
            }

            usleep(1500000);

            try {
                $image = $this->findImage($query);
            } catch (\Throwable $exception) {
                $this->warn("Falha na pesquisa de {$name}: {$exception->getMessage()}");
                continue;
            }
            if (!$image) {
                $this->warn("Sem fotografia encontrada para {$name}");
                continue;
            }

            try {
                $response = Http::connectTimeout(20)
                    ->timeout(60)
                    ->withHeaders(['User-Agent' => 'KiandaStay/1.0 (destination media; info@kiandastay.vip)'])
                    ->get($image['url']);
            } catch (\Throwable $exception) {
                $this->warn("Falha ao descarregar a fotografia de {$name}");
                continue;
            }

            if (!$response->successful()) {
                $this->warn("Falha ao descarregar a fotografia de {$name}");
                continue;
            }

            $path = "locations/commons/{$province}.jpg";
            Storage::disk('public')->put($path, $response->body());

            Location::where('province', $province)->update([
                'image' => $path,
                'description' => $description,
            ]);

            $credits[$province] = [
                'province' => $name,
                'title' => $image['title'],
                'author' => $image['author'],
                'license' => $image['license'],
                'source' => $image['source'],
            ];

            $this->info("Atualizado: {$name}");
        }

        Storage::disk('public')->put(
            'locations/commons/credits.json',
            json_encode($credits, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );

        return self::SUCCESS;
    }

    private function findImage(string $search): ?array
    {
        $response = Http::connectTimeout(20)
            ->timeout(60)
            ->withHeaders(['User-Agent' => 'KiandaStay/1.0 (destination media; info@kiandastay.vip)'])
            ->get('https://commons.wikimedia.org/w/api.php', [
                'action' => 'query',
                'generator' => 'search',
                'gsrsearch' => $search,
                'gsrnamespace' => 6,
                'gsrlimit' => 8,
                'prop' => 'imageinfo',
                'iiprop' => 'url|mime|extmetadata',
                'iiurlwidth' => 1200,
                'format' => 'json',
                'formatversion' => 2,
            ]);

        foreach ($response->json('query.pages', []) as $page) {
            $info = $page['imageinfo'][0] ?? null;
            $mime = strtolower($info['mime'] ?? '');
            $title = str_replace('File:', '', $page['title'] ?? '');
            if (!$info || (!str_contains($mime, 'jpeg') && !str_contains($mime, 'png'))) {
                continue;
            }
            if (preg_match('/\b(map|locator|flag|coat of arms|provinces?)\b|^AO-/i', $title)) {
                continue;
            }

            $meta = $info['extmetadata'] ?? [];

            return [
                'url' => $info['thumburl'] ?? $info['url'],
                'title' => $title,
                'author' => strip_tags($meta['Artist']['value'] ?? 'Wikimedia Commons contributor'),
                'license' => $meta['LicenseShortName']['value'] ?? 'Wikimedia Commons',
                'source' => $info['descriptionurl'] ?? 'https://commons.wikimedia.org',
            ];
        }

        return null;
    }
}
