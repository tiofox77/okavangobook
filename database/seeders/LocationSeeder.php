<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desativar chaves estrangeiras temporariamente para evitar erros
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Limpar apenas as localizações que não têm relacionamentos
        $locationsToDelete = Location::whereDoesntHave('hotels')->whereDoesntHave('searches')->get();
        foreach ($locationsToDelete as $location) {
            $location->delete();
        }
        
        // Reativar chaves estrangeiras
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Lista completa das 18 províncias de Angola
        $locations = [
            // 1. LUANDA - Capital e província mais populosa
            [
                'name' => 'Luanda',
                'province' => 'luanda',
                'description' => 'A capital e maior cidade de Angola, Luanda é uma cidade cosmopolita com praias deslumbrantes, mercados movimentados e uma rica história colonial portuguesa. Não deixe de visitar a Fortaleza de São Miguel, o Museu Nacional de Antropologia e a Ilha de Luanda.',
                'image' => 'https://images.unsplash.com/photo-1612461310865-573f6a56e6f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'latitude' => -8.8368,
                'longitude' => 13.2343,
                'is_featured' => true,
                'hotels_count' => 150
            ],
            
            // 2. BENGUELA
            [
                'name' => 'Benguela',
                'province' => 'benguela',
                'description' => 'Conhecida pelas suas praias deslumbrantes e águas claras, Benguela é uma das mais antigas cidades de Angola e um importante centro turístico. A cidade tem uma rica herança cultural, com monumentos históricos e um ambiente relaxante.',
                'image' => 'https://images.unsplash.com/photo-1580431791954-1276d9762617?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'latitude' => -12.5763,
                'longitude' => 13.4055,
                'is_featured' => true,
                'hotels_count' => 80
            ],
            [
                'name' => 'Lobito',
                'province' => 'benguela',
                'description' => 'Cidade portuária localizada na província de Benguela, Lobito é conhecida por suas belas praias, arquitetura colonial e pelo seu importante porto. A restinga de Lobito é uma faixa de terra que se estende pelo oceano, criando uma bela baía natural.',
                'image' => 'https://images.unsplash.com/photo-1585351650024-8e333a11eae3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'latitude' => -12.3644,
                'longitude' => 13.5361,
                'is_featured' => false,
                'hotels_count' => 45
            ],
            
            // 3. HUAMBO
            [
                'name' => 'Huambo',
                'province' => 'huambo',
                'description' => 'Antiga Nova Lisboa, Huambo é uma cidade localizada no planalto central de Angola. Com um clima agradável, a cidade oferece belas paisagens naturais e uma arquitetura que mistura o colonial com o moderno.',
                'image' => 'https://images.unsplash.com/photo-1591461712364-3007921f32a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'latitude' => -12.7667,
                'longitude' => 15.7333,
                'is_featured' => false,
                'hotels_count' => 40
            ],
            
            // 4. HUÍLA
            [
                'name' => 'Lubango',
                'province' => 'huila',
                'description' => 'Situada a mais de 1.700 metros de altitude, Lubango oferece um clima ameno e paisagens montanhosas impressionantes. A cidade é conhecida pelo monumento Cristo Rei, similar ao do Rio de Janeiro, e pela serra da Leba, com suas estradas sinuosas e vistas espetaculares.',
                'image' => 'https://images.unsplash.com/photo-1562711988-bff1cb3f63e1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'latitude' => -14.9167,
                'longitude' => 13.5,
                'is_featured' => true,
                'hotels_count' => 50
            ],
            
            // 5. NAMIBE
            [
                'name' => 'Namibe',
                'province' => 'namibe',
                'description' => 'Uma cidade costeira no sudoeste de Angola, conhecida pelas suas praias virgens e pelo deserto do Namibe, o mais antigo do mundo. A região oferece paisagens únicas onde o deserto encontra o oceano.',
                'image' => 'https://images.unsplash.com/photo-1583422409516-2895a77efded?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'latitude' => -15.1961,
                'longitude' => 12.1522,
                'is_featured' => false,
                'hotels_count' => 35
            ],
            
            // 6. MALANJE
            [
                'name' => 'Malanje',
                'province' => 'malanje',
                'description' => 'Famosa pelas Quedas de Kalandula, umas das maiores quedas d’água de África, e pelas peculiares formações rochosas de Pungo Andongo. A região oferece uma natureza exuberante e é um destino perfeito para os amantes de ecoturismo.',
                'image' => 'https://images.unsplash.com/photo-1574236170880-faf57c856220?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'latitude' => -9.5402,
                'longitude' => 16.3534,
                'is_featured' => false,
                'hotels_count' => 30
            ],
            
            // 7. CABINDA
            [
                'name' => 'Cabinda',
                'province' => 'cabinda',
                'description' => 'Um enclave separado do resto de Angola, Cabinda é rica em petróleo e recursos naturais. A região oferece belas praias, florestas tropicais e uma cultura única influenciada pelos países vizinhos.',
                'image' => 'https://images.unsplash.com/photo-1517144960348-b480a4595cc2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'latitude' => -5.5667,
                'longitude' => 12.2,
                'is_featured' => false,
                'hotels_count' => 25
            ],
            
            // 8. ZAIRE
            [
                'name' => 'Soyo',
                'province' => 'zaire',
                'description' => 'Localizada na foz do rio Congo, Soyo é uma importante cidade portuária e um centro de produção de petróleo. A cidade oferece belas paisagens onde o rio encontra o oceano e uma rica diversidade cultural.',
                'image' => 'https://images.unsplash.com/photo-1594064424123-5ef1eb9426dc?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'latitude' => -6.1333,
                'longitude' => 12.3667,
                'is_featured' => false,
                'hotels_count' => 20
            ],
            
            // 9. CUANZA SUL
            [
                'name' => 'Sumbe',
                'province' => 'cuanza-sul',
                'description' => 'Cidade costeira com belas praias e paisagens naturais. Sumbe é conhecida pela sua tranquilidade e pela produção de sal. A região oferece boas opções para quem busca relaxamento à beira-mar.',
                'image' => 'https://images.unsplash.com/photo-1602111466106-cde1fab7a2b5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'latitude' => -11.2061,
                'longitude' => 13.8428,
                'is_featured' => false,
                'hotels_count' => 15
            ],
            
            // 10. CUANZA NORTE
            [
                'name' => "N'dalatando",
                'province' => 'cuanza-norte',
                'description' => "Capital da província do Cuanza Norte, N'dalatando é uma cidade cercada por montanhas e com clima agradável. A região é conhecida pela produção de café e por suas paisagens verdejantes que combinam agricultura e natureza.",
                'image' => 'https://images.unsplash.com/photo-1523171067499-348fb67f97e5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'latitude' => -9.2971,
                'longitude' => 14.9125,
                'is_featured' => false,
                'hotels_count' => 10
            ],
            
            // 11. UÍGE
            [
                'name' => 'Uíge',
                'province' => 'uige',
                'description' => 'Localizada no norte de Angola, a província do Uíge é conhecida por suas plantações de café e pela densa floresta tropical. A região apresenta uma rica biodiversidade e cachoeiras impressionantes entre as montanhas verdejantes.',
                'image' => 'https://images.unsplash.com/photo-1566240822818-ac6ba6fb2a65?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'latitude' => -7.6087,
                'longitude' => 15.0613,
                'is_featured' => false,
                'hotels_count' => 12
            ],
            
            // 12. LUNDA NORTE
            [
                'name' => 'Dundo',
                'province' => 'lunda-norte',
                'description' => 'Conhecida pela extração de diamantes, a província da Lunda Norte tem no Dundo sua capital administrativa. A região é cortada por rios importantes e oferece uma interessante mistura de tradições culturais e modernidade devido à indústria de mineração.',
                'image' => 'https://images.unsplash.com/photo-1609780447631-05b93e5a88ea?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'latitude' => -7.3833,
                'longitude' => 20.8333,
                'is_featured' => false,
                'hotels_count' => 8
            ],
            
            // 13. LUNDA SUL
            [
                'name' => 'Saurimo',
                'province' => 'lunda-sul',
                'description' => 'Saurimo, capital da Lunda Sul, é outro importante centro de mineração de diamantes em Angola. A cidade e arredores oferecem belas paisagens naturais, com rios, lagos e uma rica biodiversidade ainda preservada em algumas áreas.',
                'image' => 'https://images.unsplash.com/photo-1509099381441-ea3c0cf98b94?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'latitude' => -9.6608,
                'longitude' => 20.3934,
                'is_featured' => false,
                'hotels_count' => 7
            ],
            
            // 14. BIÉ
            [
                'name' => 'Kuito',
                'province' => 'bie',
                'description' => 'Localizada no planalto central de Angola, a província do Bié tem como capital o Kuito. A região é conhecida por suas terras férteis, produção agrícola e belezas naturais como cachoeiras e formações rochosas impressionantes.',
                'image' => 'https://images.unsplash.com/photo-1510797215324-95aa89f43c33?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'latitude' => -12.3793,
                'longitude' => 16.9376,
                'is_featured' => false,
                'hotels_count' => 6
            ],
            
            // 15. MOXICO
            [
                'name' => 'Luena',
                'province' => 'moxico',
                'description' => 'Luena é a capital da província do Moxico, a maior em extensão territorial de Angola. A região é banhada pelo rio Zambeze e oferece paisagens deslumbrantes com savanas, florestas e importantes reservas de fauna e flora.',
                'image' => 'https://images.unsplash.com/photo-1523741543316-beb7fc7023d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'latitude' => -11.7833,
                'longitude' => 19.9167,
                'is_featured' => false,
                'hotels_count' => 5
            ],
            
            // 16. CUNENE
            [
                'name' => 'Ondjiva',
                'province' => 'cunene',
                'description' => 'Situada no extremo sul de Angola, a província do Cunene faz fronteira com a Namíbia. A região é habitada principalmente pelo povo Cuanhama e é conhecida por suas tradições culturais únicas, além da paisagem árida e semidesértica.',
                'image' => 'https://images.unsplash.com/photo-1562305138-65be897645af?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'latitude' => -17.0667,
                'longitude' => 15.7333,
                'is_featured' => false,
                'hotels_count' => 4
            ],
            
            // 17. CUANDO CUBANGO
            [
                'name' => 'Menongue',
                'province' => 'cuando-cubango',
                'description' => 'A província do Cuando Cubango é conhecida como as \'Terras do Fim do Mundo\' devido à sua localização remota no sudeste de Angola. Menongue, sua capital, é ponto de partida para explorar os rios Cubango e Cuito, além das vastas planícies e savanas com rica fauna selvagem.',
                'image' => 'https://images.unsplash.com/photo-1565348271942-ab9e44c98aad?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'latitude' => -14.6576,
                'longitude' => 17.6818,
                'is_featured' => false,
                'hotels_count' => 3
            ],
            
            // 18. BENGO
            [
                'name' => 'Caxito',
                'province' => 'bengo',
                'description' => 'Próxima à capital Luanda, a província do Bengo é conhecida por suas fazendas e pela produção agrícola. A região possui belas paisagens fluviais, incluindo o rio Kwanza, e oferece opções de ecoturismo para os visitantes que buscam experiências na natureza.',
                'image' => 'https://images.unsplash.com/photo-1631049087602-45c8b333fbe9?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'latitude' => -8.5783,
                'longitude' => 13.6644,
                'is_featured' => false,
                'hotels_count' => 10
            ],
        ];

        foreach ($locations as $locationData) {
            // Gerar o slug a partir do nome
            $slug = Str::slug($locationData['name']);
            
            // Verificar se já existe uma localização com este slug
            $existingLocation = Location::where('slug', $slug)->first();
            
            if ($existingLocation) {
                // Se já existe, atualizar os dados
                $existingLocation->update([
                    'name' => $locationData['name'],
                    'province' => $locationData['province'],
                    'description' => $locationData['description'],
                    'image' => $locationData['image'],
                    'latitude' => $locationData['latitude'],
                    'longitude' => $locationData['longitude'],
                    'is_featured' => $locationData['is_featured'],
                    'hotels_count' => $locationData['hotels_count']
                ]);
            } else {
                // Se não existe, criar nova localização
                $locationData['slug'] = $slug;
                Location::create($locationData);
            }
        }
    }
}
