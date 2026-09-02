<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

/**
 * Capitais das três províncias criadas na divisão político-administrativa
 * que entrou em vigor a 1 de janeiro de 2025: Cuando (Mavinga), Moxico
 * Leste (Cazombo) e Ícolo e Bengo (Catete).
 *
 * Idempotente: usa updateOrCreate pelo slug, por isso pode correr as vezes
 * que forem precisas sem duplicar.
 *
 *   php artisan db:seed --class=NovasProvincias2025Seeder
 */
class NovasProvincias2025Seeder extends Seeder
{
    public function run(): void
    {
        $capitais = [
            [
                'slug' => 'mavinga',
                'name' => 'Mavinga',
                'province' => 'cuando',
                'capital' => 'Mavinga',
                'latitude' => -16.000000,
                'longitude' => 20.000000,
                'description' => 'Mavinga é a capital da província do Cuando, criada em 2025 a partir do antigo Cuando Cubango. Ponto de entrada do Parque Nacional de Mavinga, integra o corredor de conservação transfronteiriço KAZA — o maior de África — partilhado com a Zâmbia, o Botswana, o Zimbabué e a Namíbia. Savanas abertas, matas de miombo e o rio Cuando fazem desta uma das regiões com maior potencial de ecoturismo e observação de vida selvagem em Angola.',
            ],
            [
                'slug' => 'cazombo',
                'name' => 'Cazombo',
                'province' => 'moxico-leste',
                'capital' => 'Cazombo',
                'latitude' => -11.900000,
                'longitude' => 22.900000,
                'description' => 'Cazombo é a capital da província do Moxico Leste, criada em 2025 a partir do Moxico. Situada junto à fronteira com a Zâmbia, na região do Alto Zambeze, é servida por aeroporto próprio e rodeada por matas de miombo e planícies que se alagam na época das chuvas. O alto curso do rio Zambeze e a proximidade das nascentes fazem dela uma base para quem procura a Angola mais remota e preservada.',
            ],
            [
                'slug' => 'catete',
                'name' => 'Catete',
                'province' => 'icolo-e-bengo',
                'capital' => 'Catete',
                'latitude' => -9.107722,
                'longitude' => 13.688694,
                'description' => 'Catete é a capital da província de Ícolo e Bengo, criada em 2025 a partir de Luanda. A cerca de 60 km da capital do país, é conhecida como terra natal de António Agostinho Neto e acolhe o museu que lhe é dedicado. A província reúne o interior agrícola a leste de Luanda, as margens do rio Kwanza e praias ainda pouco movimentadas — e é onde fica o novo Aeroporto Internacional Dr. António Agostinho Neto, inaugurado em 2025.',
            ],
        ];

        foreach ($capitais as $dados) {
            $slug = $dados['slug'];
            unset($dados['slug']);

            Location::updateOrCreate(
                ['slug' => $slug],
                $dados + ['is_active' => true, 'is_featured' => false]
            );

            $this->command?->info("  {$dados['name']} — província {$dados['province']}");
        }
    }
}
