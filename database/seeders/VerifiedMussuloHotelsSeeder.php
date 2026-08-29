<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Location;
use Illuminate\Database\Seeder;

class VerifiedMussuloHotelsSeeder extends Seeder
{
    public function run(): void
    {
        $location = Location::updateOrCreate(
            ['slug' => 'mussulo'],
            [
                'name' => 'Mussulo',
                'province' => 'luanda',
                'description' => 'Península costeira a sul de Luanda, conhecida pelas praias, águas calmas e resorts.',
                'image' => 'locations/commons/luanda.jpg',
                'latitude' => -8.9312,
                'longitude' => 13.08432,
                'is_featured' => true,
            ]
        );

        $hotels = [
            [
                'name' => 'NAW Beach Resort Mussulo',
                'slug' => 'naw-beach-resort-mussulo',
                'description' => 'Resort à beira-mar no Mussulo, com quartos duplos, restaurante, piscina exterior, jardim, praia privativa e atividades aquáticas.',
                'address' => 'Península do Mussulo, Luanda, Angola',
                'latitude' => -8.8748876,
                'longitude' => 13.1460942,
                'thumbnail' => 'hotels/verified-mussulo/naw-beach-resort.jpg',
                'website' => 'https://www.hoteisangola.com/en/alojamento/hotels/mussulo/naw-beach-resort-mussulo.html',
                'amenities' => ['Restaurante', 'Piscina', 'Jardim', 'Praia privativa', 'Desportos aquáticos', 'Ar Condicionado'],
            ],
            [
                'name' => 'Flamingo Bay Resort',
                'slug' => 'flamingo-bay-resort-mussulo',
                'description' => 'Resort no Mussulo com alojamentos orientados para a baía e a costa, piscina exterior, duas praias, restaurante e parque infantil.',
                'address' => 'Ponte das Palmeirinhas, Mussulo, Belas, Luanda, Angola',
                'latitude' => -9.027525,
                'longitude' => 13.010023,
                'thumbnail' => 'hotels/verified-mussulo/flamingo-bay-resort.jpg',
                'website' => 'https://flamingobay.co.ao/pt/',
                'phone' => '+244 936 203 806',
                'amenities' => ['Restaurante', 'Piscina', 'Praia privativa', 'Parque infantil', 'Canoagem', 'Estacionamento'],
            ],
            [
                'name' => 'Dallys Resort',
                'slug' => 'dallys-resort-mussulo',
                'description' => 'Empreendimento turístico na Ilha do Mussulo com alojamento, restaurante, piscina exterior, jardim e acesso à praia.',
                'address' => 'Ilha do Mussulo, Luanda, Angola',
                'latitude' => -8.891063,
                'longitude' => 13.133229,
                'thumbnail' => 'hotels/verified-mussulo/dallys-resort.jpg',
                'website' => 'https://www.hoteisangola.com/en/alojamento/hotels/mussulo/dallys-resort-mussulo.html',
                'amenities' => ['Restaurante', 'Piscina', 'Jardim', 'Praia', 'Bar', 'Pequeno-almoço'],
            ],
            [
                'name' => 'Roça das Mangueiras',
                'slug' => 'roca-das-mangueiras-mussulo',
                'description' => 'Aldeamento turístico no Mussulo vocacionado para descanso, lazer e estadias junto à praia, com restaurante, jardim e piscina.',
                'address' => 'Mussulo, Luanda, Angola',
                'latitude' => -8.889362,
                'longitude' => 13.139153,
                'thumbnail' => 'hotels/verified-mussulo/roca-das-mangueiras.jpg',
                'website' => 'https://www.hoteisangola.com/en/alojamento/hotels/mussulo/roca-das-mangueiras.html',
                'amenities' => ['Restaurante', 'Piscina', 'Jardim', 'Praia privativa', 'Bar', 'Pequeno-almoço'],
            ],
            [
                'name' => 'Resort Madeirense',
                'slug' => 'resort-madeirense-mussulo',
                'description' => 'Resort no Mussulo com quartos e casas para famílias e grupos, piscina exterior, restaurante, jardim e acesso à praia.',
                'address' => 'Mussulo, Belas, Luanda, Angola',
                'latitude' => -8.886418,
                'longitude' => 13.1405375,
                'thumbnail' => 'hotels/verified-mussulo/resort-madeirense.jpg',
                'website' => 'https://madeirenseangola.com/',
                'amenities' => ['Restaurante', 'Piscina', 'Jardim', 'Praia', 'Bar', 'Pequeno-almoço'],
            ],
            [
                'name' => 'Mussulito Resort',
                'slug' => 'mussulito-resort-mussulo',
                'description' => 'Resort situado no Canal do Sai-Sai, no Mussulo, com jardim, restaurante, piscina, praia e bar.',
                'address' => 'Ilha do Mussulo, Canal do Sai-Sai, Zanga, Luanda, Angola',
                'latitude' => -8.952910,
                'longitude' => 13.062428,
                'thumbnail' => 'locations/commons/luanda.jpg',
                'website' => 'https://visiteluanda.com/directorio/alojamento/belas/mussulito-resort.html',
                'amenities' => ['Restaurante', 'Piscina', 'Jardim', 'Praia', 'Bar'],
            ],
            [
                'name' => 'Netu’s Village Resort',
                'slug' => 'netus-village-resort-mussulo',
                'description' => 'Resort na contra-costa do Mussulo com bungalows, moradias, piscina, bar, restaurante e praia privativa.',
                'address' => 'Zona do Macocô, Mussulo, Luanda, Angola',
                'thumbnail' => 'hotels/verified-mussulo/netus-village.jpg',
                'website' => 'https://www.netusvillage.ao/home',
                'phone' => '+244 930 263 665',
                'email' => 'reservas@netusvillage.ao',
                'amenities' => ['Restaurante', 'Piscina', 'Praia privativa', 'Bar', 'Pequeno-almoço', 'Moradias familiares'],
            ],
            [
                'name' => 'Macoco Resort',
                'slug' => 'macoco-resort-mussulo',
                'description' => 'Pequeno complexo turístico no Macoco, composto por cinco bungalows próximos da praia, com piscina, bar, restaurante por encomenda e atividades de lazer.',
                'address' => 'Ilha do Mussulo, Macoco, próximo do Museu da Escravatura, Luanda, Angola',
                'thumbnail' => 'hotels/verified-mussulo/macoco-resort.jpg',
                'website' => 'https://macoco-resort.com/public/',
                'phone' => '+244 939 248 880',
                'email' => 'reserva@macoco-resort.com',
                'check_in_time' => '08:00:00',
                'check_out_time' => '16:00:00',
                'amenities' => ['Ar Condicionado', 'Piscina', 'Bar', 'Restaurante', 'Kayak', 'Karaoke', 'Minibar'],
            ],
        ];

        foreach ($hotels as $data) {
            Hotel::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge([
                    'property_type' => 'resort',
                    'location_id' => $location->id,
                    'stars' => 0,
                    'rating' => 0,
                    'reviews_count' => 0,
                    'min_price' => null,
                    'images' => [$data['thumbnail']],
                    'is_featured' => false,
                    'is_active' => true,
                ], $data)
            );
        }

        $location->update([
            'hotels_count' => Hotel::where('location_id', $location->id)->where('is_active', true)->count(),
        ]);
    }
}
