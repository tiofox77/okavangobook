<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Price;
use App\Models\RoomType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class VerifiedResortRoomTypesSeeder extends Seeder
{
    /**
     * Tipologias confirmadas em fontes publicas. Quando a fonte nao publica
     * a distribuicao do inventario, rooms_count=1 representa apenas o minimo
     * comprovado (a existencia da tipologia), nunca uma estimativa.
     */
    public function run(): void
    {
        $catalogues = $this->catalogues();

        foreach ($catalogues as $catalogue) {
            foreach ((array) $catalogue['slugs'] as $slug) {
                $hotel = Hotel::query()->where('slug', $slug)->first();

                if (! $hotel) {
                    $this->command?->warn("Resort nao encontrado: {$slug}");
                    continue;
                }

                foreach ($catalogue['rooms'] as $position => $room) {
                    $publishedPrice = $room['published_price'] ?? null;
                    $basePrice = $publishedPrice
                        ?? $room['base_price']
                        ?? $hotel->min_price
                        ?? $catalogue['fallback_price']
                        ?? 1;

                    $roomType = RoomType::query()->updateOrCreate(
                        [
                            'hotel_id' => $hotel->id,
                            'name' => $room['name'],
                        ],
                        [
                            'description' => $room['description'] ?? 'Tipologia confirmada em fonte publica; disponibilidade sujeita a confirmacao do alojamento.',
                            'capacity' => $room['capacity'],
                            'adult_capacity' => $room['adult_capacity'] ?? $room['capacity'],
                            'children_capacity' => $room['children_capacity'] ?? 0,
                            'beds' => $room['beds'] ?? 1,
                            'bed_type' => $room['bed_type'] ?? null,
                            'size' => $room['size'] ?? null,
                            'amenities' => $room['amenities'] ?? [],
                            'images' => $room['images'] ?? [],
                            'is_available' => true,
                            'base_price' => $basePrice,
                            'rooms_count' => $room['rooms_count'] ?? 1,
                            'is_featured' => $room['is_featured'] ?? ($position === 0),
                            'position' => $position,
                            'source_url' => $catalogue['source'],
                        ]
                    );

                    if ($publishedPrice !== null) {
                        $this->storePublishedPrice($hotel, $roomType, $catalogue, $room, (float) $publishedPrice);
                    }
                }
            }
        }
    }

    private function storePublishedPrice(
        Hotel $hotel,
        RoomType $roomType,
        array $catalogue,
        array $room,
        float $publishedPrice
    ): void {
        $today = Carbon::today();

        Price::query()->updateOrCreate(
            [
                'room_type_id' => $roomType->id,
                'provider' => $catalogue['provider'] ?? 'Fonte publica',
                'link' => $catalogue['source'],
            ],
            [
                'hotel_id' => $hotel->id,
                'price' => $publishedPrice,
                'currency' => 'AKZ',
                'original_price' => $room['original_price'] ?? null,
                'discount_percentage' => $room['discount_percentage'] ?? null,
                'check_in' => $today,
                'check_out' => $today->copy()->addDay(),
                'breakfast_included' => $room['breakfast_included'] ?? false,
                'free_cancellation' => false,
                'pay_at_hotel' => false,
                'cancellation_policy' => 'Tarifa publica de referencia. Confirmar disponibilidade e condicoes diretamente com o alojamento.',
                'taxes_fees' => [],
                'last_updated' => now(),
                'is_available' => true,
            ]
        );
    }

    private function catalogues(): array
    {
        $hotelRoomAmenities = ['Ar condicionado', 'Casa de banho privativa'];
        $mussuloAmenities = ['Ar condicionado', 'Casa de banho privativa', 'Pequeno-almoco'];

        return [
            [
                'slugs' => 'baia-das-pipas-lodge-namibe',
                'source' => 'https://baialodge.webnode.pt/sobre-nos-about-us/',
                'provider' => 'Baia\'s Lodge',
                'rooms' => [
                    [
                        'name' => 'Casa equipada',
                        'description' => 'Casa independente equipada para estadias na Baia das Pipas. A fonte oficial confirma um total de 10 casas.',
                        'capacity' => 2,
                        'rooms_count' => 10,
                        'beds' => 1,
                        'bed_type' => 'Casal ou Twin',
                        'amenities' => ['Casa de banho privativa', 'Cozinha'],
                    ],
                ],
            ],
            [
                'slugs' => 'complexo-turistico-paraiso-da-chiva-huambo',
                'source' => 'https://pt.linkedin.com/posts/hoteisangola_huambo-turismo-hoteisangola-activity-7329437730856177664-6JQr',
                'provider' => 'HoteisAngola',
                'fallback_price' => 8560,
                'rooms' => [
                    ['name' => 'Quarto Single', 'capacity' => 1, 'beds' => 1, 'bed_type' => 'Individual', 'amenities' => $hotelRoomAmenities],
                    ['name' => 'Quarto Twin', 'capacity' => 2, 'beds' => 2, 'bed_type' => 'Twin', 'amenities' => $hotelRoomAmenities],
                    ['name' => 'Quarto Duplo', 'capacity' => 2, 'beds' => 1, 'bed_type' => 'Casal', 'amenities' => $hotelRoomAmenities],
                    ['name' => 'Suite', 'capacity' => 2, 'beds' => 1, 'bed_type' => 'Casal', 'amenities' => $hotelRoomAmenities],
                    ['name' => 'Casa T1', 'capacity' => 2, 'beds' => 1, 'bed_type' => 'Casal', 'amenities' => ['Cozinha', 'Casa de banho privativa']],
                    ['name' => 'Casa T2', 'capacity' => 4, 'beds' => 2, 'bed_type' => 'Casal ou Twin', 'amenities' => ['Cozinha', 'Casa de banho privativa']],
                ],
            ],
            [
                'slugs' => 'dallys-resort-mussulo',
                'source' => 'https://www.hoteisangola.com/alojamento/hotels/mussulo/dallys-resort-mussulo.html',
                'provider' => 'HoteisAngola',
                'rooms' => [
                    ['name' => 'Quarto Single', 'capacity' => 1, 'beds' => 1, 'bed_type' => 'Individual', 'published_price' => 63000, 'breakfast_included' => true, 'amenities' => $mussuloAmenities],
                    ['name' => 'Quarto Duplo Standard', 'capacity' => 2, 'beds' => 1, 'bed_type' => 'Casal', 'published_price' => 72000, 'breakfast_included' => true, 'amenities' => $mussuloAmenities],
                    ['name' => 'Quarto Duplo Superior', 'capacity' => 2, 'beds' => 1, 'bed_type' => 'King', 'published_price' => 85500, 'breakfast_included' => true, 'amenities' => $mussuloAmenities],
                    ['name' => 'Quarto Familiar', 'description' => 'Quarto para 2 adultos e 2 criancas ate 12 anos.', 'capacity' => 4, 'adult_capacity' => 2, 'children_capacity' => 2, 'beds' => 2, 'bed_type' => 'Twin', 'published_price' => 108000, 'breakfast_included' => true, 'amenities' => $mussuloAmenities],
                    ['name' => 'Quarto Master', 'capacity' => 2, 'beds' => 1, 'bed_type' => 'Casal', 'published_price' => 135000, 'breakfast_included' => true, 'amenities' => $mussuloAmenities],
                ],
            ],
            [
                'slugs' => 'flamingo-bay-resort-mussulo',
                'source' => 'https://www.hoteisangola.com/alojamento/hotels/mussulo/flamingo-bay-mussulo.html',
                'provider' => 'HoteisAngola',
                'rooms' => [
                    ['name' => 'Bungalow', 'capacity' => 2, 'beds' => 1, 'bed_type' => 'Casal', 'published_price' => 75000, 'breakfast_included' => true, 'amenities' => $mussuloAmenities],
                    ['name' => 'Suite Casal', 'capacity' => 2, 'beds' => 1, 'bed_type' => 'Casal', 'published_price' => 82500, 'breakfast_included' => true, 'amenities' => $mussuloAmenities],
                    ['name' => 'Suite Premium', 'description' => 'Suite para 2 adultos e 1 crianca.', 'capacity' => 3, 'adult_capacity' => 2, 'children_capacity' => 1, 'beds' => 1, 'bed_type' => 'Casal', 'published_price' => 110000, 'breakfast_included' => true, 'amenities' => $mussuloAmenities],
                    ['name' => 'Suite Familiar', 'description' => 'Suite para 2 adultos e 2 criancas ate 16 anos.', 'capacity' => 4, 'adult_capacity' => 2, 'children_capacity' => 2, 'beds' => 2, 'published_price' => 165000, 'breakfast_included' => true, 'amenities' => $mussuloAmenities],
                    ['name' => 'Suite T2', 'description' => 'Suite T2 para 2 adultos e ate 3 criancas.', 'capacity' => 5, 'adult_capacity' => 2, 'children_capacity' => 3, 'beds' => 2, 'published_price' => 195500, 'breakfast_included' => true, 'amenities' => $mussuloAmenities],
                    ['name' => 'Suite T2 Duplex Vista Mar', 'description' => 'Suite duplex T2 com vista mar.', 'capacity' => 5, 'adult_capacity' => 2, 'children_capacity' => 3, 'beds' => 2, 'published_price' => 195500, 'breakfast_included' => true, 'amenities' => $mussuloAmenities],
                    ['name' => 'Suite T3', 'capacity' => 6, 'beds' => 3, 'published_price' => 247500, 'breakfast_included' => true, 'amenities' => $mussuloAmenities],
                ],
            ],
            [
                'slugs' => 'kimbo-do-soba-lubango',
                'source' => 'https://www.hoteisangola.com/alojamento/hotels/lubango/kimbo-soba.html',
                'provider' => 'HoteisAngola',
                'rooms' => [
                    ['name' => 'Bungalow Single', 'capacity' => 1, 'beds' => 1, 'bed_type' => 'Individual', 'published_price' => 60000, 'breakfast_included' => true, 'amenities' => $hotelRoomAmenities],
                    ['name' => 'Bungalow Duplo', 'capacity' => 2, 'beds' => 1, 'bed_type' => 'Casal', 'published_price' => 70000, 'breakfast_included' => true, 'amenities' => $hotelRoomAmenities],
                    ['name' => 'Bungalow Twin', 'capacity' => 2, 'beds' => 2, 'bed_type' => 'Twin', 'published_price' => 70000, 'breakfast_included' => true, 'amenities' => $hotelRoomAmenities],
                    ['name' => 'Master Suite', 'capacity' => 2, 'beds' => 1, 'bed_type' => 'Casal', 'published_price' => 110000, 'breakfast_included' => true, 'amenities' => $hotelRoomAmenities],
                    ['name' => 'Casa T2', 'capacity' => 5, 'beds' => 2, 'published_price' => 190000, 'breakfast_included' => true, 'amenities' => ['Ar condicionado', 'Smart TV', 'Casa de banho privativa']],
                    ['name' => 'Casa T3', 'capacity' => 8, 'beds' => 3, 'published_price' => 260000, 'breakfast_included' => true, 'amenities' => ['Ar condicionado', 'Smart TV', 'Casa de banho privativa']],
                    ['name' => 'Casa T5', 'capacity' => 14, 'beds' => 5, 'published_price' => 410000, 'breakfast_included' => true, 'amenities' => ['Ar condicionado', 'Smart TV', 'Suites privativas']],
                    ['name' => 'Quarto Casino', 'capacity' => 1, 'beds' => 1, 'bed_type' => 'Individual', 'published_price' => 90000, 'breakfast_included' => true, 'amenities' => $hotelRoomAmenities],
                ],
            ],
            [
                'slugs' => 'kinwica-resort-hotel-soyo',
                'source' => 'https://www.hoteisangola.com/alojamento/hotels/soyo/kinwica-resort-hotel-soyo-zaire-angola.html',
                'provider' => 'HoteisAngola',
                'rooms' => [
                    ['name' => 'Quarto Single', 'capacity' => 1, 'beds' => 1, 'bed_type' => 'Individual', 'published_price' => 52965, 'breakfast_included' => true, 'amenities' => $hotelRoomAmenities],
                    ['name' => 'Quarto Duplo Casal', 'capacity' => 2, 'beds' => 1, 'bed_type' => 'Casal', 'published_price' => 57965, 'breakfast_included' => true, 'amenities' => $hotelRoomAmenities],
                    ['name' => 'Quarto Duplo Twin', 'capacity' => 2, 'beds' => 2, 'bed_type' => 'Twin', 'published_price' => 74900, 'breakfast_included' => true, 'amenities' => $hotelRoomAmenities],
                    ['name' => 'Quarto Familiar', 'capacity' => 4, 'beds' => 2, 'published_price' => 87740, 'breakfast_included' => true, 'amenities' => $hotelRoomAmenities],
                    ['name' => 'Quarto Triplo 2 camas', 'capacity' => 3, 'beds' => 2, 'published_price' => 81320, 'breakfast_included' => true, 'amenities' => $hotelRoomAmenities],
                    ['name' => 'Quarto Triplo 3 camas', 'capacity' => 3, 'beds' => 3, 'published_price' => 83460, 'breakfast_included' => true, 'amenities' => $hotelRoomAmenities],
                ],
            ],
            [
                'slugs' => 'macoco-resort-mussulo',
                'source' => 'https://www.macoco-resort.com/public/',
                'provider' => 'Macoco Resort',
                'rooms' => [
                    ['name' => 'Bungalow T1', 'description' => 'Bungalow T1 para 2 a 3 hospedes.', 'capacity' => 3, 'adult_capacity' => 2, 'children_capacity' => 1, 'beds' => 1, 'bed_type' => 'Casal', 'published_price' => 45000, 'amenities' => ['Ar condicionado', 'Frigorifico', 'Casa de banho privativa']],
                    ['name' => 'Bungalow T2', 'description' => 'Bungalow T2 com capacidade maxima de 6 hospedes.', 'capacity' => 6, 'beds' => 2, 'published_price' => 100000, 'amenities' => ['Ar condicionado', 'Frigorifico', 'Casa de banho privativa']],
                ],
            ],
            [
                'slugs' => 'mariquita-beach-resort-namibe',
                'source' => 'https://www.praiadamariquita.com/servi%C3%A7os/hotelaria-e-turismo/',
                'provider' => 'Praia da Mariquita',
                'rooms' => [
                    ['name' => 'Bungalow Vista Mar Superior', 'capacity' => 4, 'adult_capacity' => 2, 'children_capacity' => 2, 'beds' => 2, 'amenities' => ['Ar condicionado', 'Sala', 'Sofa-cama', 'Casa de banho privativa']],
                    ['name' => 'Bungalow Vista Mar Standard', 'capacity' => 4, 'adult_capacity' => 2, 'children_capacity' => 2, 'beds' => 2, 'amenities' => ['Ar condicionado', 'Sala', 'Sofa-cama', 'Casa de banho privativa']],
                    ['name' => 'Quarto Double/Twin Vista Mar', 'capacity' => 2, 'beds' => 1, 'bed_type' => 'Casal ou Twin', 'amenities' => $hotelRoomAmenities],
                    ['name' => 'Quarto Double/Twin Vista Montanha', 'capacity' => 2, 'beds' => 1, 'bed_type' => 'Casal ou Twin', 'amenities' => $hotelRoomAmenities],
                    ['name' => 'Bungalow Familiar Vista Montanha', 'capacity' => 6, 'adult_capacity' => 2, 'children_capacity' => 4, 'beds' => 3, 'amenities' => ['Ar condicionado', 'Sala', 'Sofa-cama', 'Casa de banho privativa']],
                ],
            ],
            [
                'slugs' => 'mussulito-resort-mussulo',
                'source' => 'https://www.hoteisangola.com/destaques/promocoes-especiais/cacimbo-mussulito-resort.html',
                'provider' => 'HoteisAngola',
                'fallback_price' => 15000,
                'rooms' => [
                    ['name' => 'Suite', 'capacity' => 2, 'rooms_count' => 5, 'beds' => 1, 'bed_type' => 'Casal', 'amenities' => ['Casa de banho privativa']],
                    ['name' => 'Casa T1', 'capacity' => 2, 'rooms_count' => 3, 'beds' => 1, 'bed_type' => 'Casal', 'amenities' => ['Sala', 'Casa de banho privativa']],
                    ['name' => 'Casa T2', 'capacity' => 4, 'rooms_count' => 2, 'beds' => 2, 'amenities' => ['Sala', 'Casa de banho privativa']],
                    ['name' => 'Casa T3', 'capacity' => 6, 'rooms_count' => 2, 'beds' => 3, 'amenities' => ['Sala', 'Casa de banho privativa']],
                ],
            ],
            [
                'slugs' => 'naw-beach-resort-mussulo',
                'source' => 'https://www.hoteisangola.com/alojamento/hotels/mussulo/naw-beach-resort-mussulo.html',
                'provider' => 'HoteisAngola',
                'rooms' => [
                    ['name' => 'Quarto Duplo', 'capacity' => 2, 'rooms_count' => 13, 'beds' => 1, 'bed_type' => 'Casal', 'published_price' => 95000, 'breakfast_included' => true, 'amenities' => ['Ar condicionado', 'WC', 'Piscina privada']],
                ],
            ],
            [
                'slugs' => 'netus-village-resort-mussulo',
                'source' => 'https://www.netusvillage.ao/alojamento',
                'provider' => 'Netu\'s Village',
                'fallback_price' => 76000,
                'rooms' => [
                    ['name' => 'Bungalow T0', 'description' => 'Estudio T0 para 2 adultos e 1 crianca.', 'capacity' => 3, 'adult_capacity' => 2, 'children_capacity' => 1, 'rooms_count' => 13, 'beds' => 1, 'bed_type' => 'Casal', 'amenities' => ['Kitchenette', 'Casa de banho privativa']],
                    ['name' => 'Moradia T2', 'description' => 'Moradia familiar T2; o resort indica capacidade ate 6 hospedes nas moradias.', 'capacity' => 6, 'adult_capacity' => 4, 'children_capacity' => 2, 'rooms_count' => 10, 'beds' => 2, 'amenities' => ['Cozinha', 'Sala', 'Casa de banho privativa']],
                    ['name' => 'Moradia T3', 'capacity' => 6, 'rooms_count' => 1, 'beds' => 3, 'amenities' => ['Cozinha', 'Sala', 'Casa de banho privativa']],
                ],
            ],
            [
                'slugs' => 'praia-do-soba-eco-lodge-namibe',
                'source' => 'https://praiadosobanamibe.com/bungalows/',
                'provider' => 'Praia do Soba',
                'rooms' => [
                    ['name' => 'Bungalow Familiar n.o 1', 'capacity' => 4, 'rooms_count' => 1, 'beds' => 3, 'bed_type' => 'Casal e solteiro', 'amenities' => ['Televisao', 'Casa de banho privativa', 'Vista mar']],
                    ['name' => 'Bungalow Familiar n.o 2', 'capacity' => 5, 'rooms_count' => 1, 'beds' => 4, 'bed_type' => 'Casal e solteiro', 'amenities' => ['Televisao', 'Casa de banho privativa', 'Vista mar']],
                    ['name' => 'Bungalow Familiar n.o 3', 'capacity' => 7, 'rooms_count' => 1, 'beds' => 5, 'bed_type' => 'Casal e solteiro', 'amenities' => ['Sala', 'Televisao', 'Casa de banho privativa', 'Vista mar']],
                    ['name' => 'Bungalow Familiar n.o 4', 'capacity' => 7, 'rooms_count' => 1, 'beds' => 5, 'bed_type' => 'Casal e solteiro', 'amenities' => ['Sala', 'Televisao', 'Casa de banho privativa']],
                    ['name' => 'Bungalow n.o 6', 'capacity' => 2, 'rooms_count' => 1, 'beds' => 2, 'bed_type' => 'Casal e solteiro', 'amenities' => ['Televisao', 'Casa de banho privativa']],
                ],
            ],
            [
                'slugs' => ['praia-morena-eco-resort', 'praia-morena-eco-resort-b6cf'],
                'source' => 'https://www.hoteisangola.com/alojamento/hotels/benguela-municipio/hotel-praia-morena.html',
                'provider' => 'HoteisAngola',
                'rooms' => [
                    ['name' => 'Quarto Duplo', 'capacity' => 2, 'beds' => 1, 'bed_type' => 'Casal', 'published_price' => 50000, 'breakfast_included' => true, 'amenities' => $hotelRoomAmenities],
                    ['name' => 'Quarto Twin', 'capacity' => 2, 'beds' => 2, 'bed_type' => 'Twin', 'published_price' => 50000, 'breakfast_included' => true, 'amenities' => $hotelRoomAmenities],
                    ['name' => 'Quarto Duplo Superior Vista Piscina', 'capacity' => 2, 'beds' => 1, 'bed_type' => 'Casal', 'published_price' => 60000, 'breakfast_included' => true, 'amenities' => ['Ar condicionado', 'Vista piscina', 'Casa de banho privativa']],
                    ['name' => 'Quarto Twin Superior Vista Piscina', 'capacity' => 2, 'beds' => 2, 'bed_type' => 'Twin', 'published_price' => 60000, 'breakfast_included' => true, 'amenities' => ['Ar condicionado', 'Vista piscina', 'Casa de banho privativa']],
                ],
            ],
            [
                'slugs' => 'pululukwa-resort-lubango',
                'source' => 'https://www.hoteisangola.com/alojamento/hotels/lubango/pululukwa-resort.html',
                'provider' => 'HoteisAngola',
                'rooms' => [
                    ['name' => 'Quarto Duplo VIP', 'capacity' => 2, 'beds' => 1, 'bed_type' => 'Casal ou Twin', 'published_price' => 116900, 'breakfast_included' => true, 'amenities' => $hotelRoomAmenities],
                    ['name' => 'Quarto Familiar', 'description' => 'Quarto familiar para casal e 2 criancas ate 10 anos.', 'capacity' => 4, 'adult_capacity' => 2, 'children_capacity' => 2, 'beds' => 2, 'size' => 40, 'published_price' => 131280, 'breakfast_included' => true, 'amenities' => $hotelRoomAmenities],
                    ['name' => 'Quarto Duplo', 'capacity' => 2, 'beds' => 1, 'bed_type' => 'Casal', 'published_price' => 101440, 'breakfast_included' => true, 'amenities' => $hotelRoomAmenities],
                    ['name' => 'Quarto Twin', 'capacity' => 2, 'beds' => 2, 'bed_type' => 'Twin', 'published_price' => 101440, 'breakfast_included' => true, 'amenities' => $hotelRoomAmenities],
                ],
            ],
            [
                'slugs' => 'resort-madeirense-mussulo',
                'source' => 'https://www.hoteisangola.com/alojamento/hotels/mussulo/resort-madeirense-mussulo.html',
                'provider' => 'HoteisAngola',
                'rooms' => [
                    ['name' => 'Suite Single', 'capacity' => 1, 'beds' => 1, 'bed_type' => 'Individual', 'published_price' => 48000, 'original_price' => 60000, 'discount_percentage' => 20, 'breakfast_included' => true, 'amenities' => $mussuloAmenities],
                    ['name' => 'Suite', 'capacity' => 2, 'beds' => 1, 'bed_type' => 'Casal', 'published_price' => 72000, 'original_price' => 90000, 'discount_percentage' => 20, 'breakfast_included' => true, 'amenities' => $mussuloAmenities],
                    ['name' => 'Moradia T2', 'capacity' => 4, 'beds' => 2, 'published_price' => 144000, 'original_price' => 180000, 'discount_percentage' => 20, 'breakfast_included' => true, 'amenities' => ['Ar condicionado', 'Sala', 'Casa de banho privativa', 'Pequeno-almoco']],
                    ['name' => 'Moradia T3', 'capacity' => 6, 'beds' => 3, 'published_price' => 216000, 'original_price' => 270000, 'discount_percentage' => 20, 'breakfast_included' => true, 'amenities' => ['Ar condicionado', 'Sala', 'Casa de banho privativa', 'Pequeno-almoco']],
                ],
            ],
            [
                'slugs' => 'roca-das-mangueiras-mussulo',
                'source' => 'https://www.hoteisangola.com/alojamento/hotels/mussulo/roca-das-mangueiras.html',
                'provider' => 'HoteisAngola',
                'rooms' => [
                    ['name' => 'Quarto Single', 'capacity' => 1, 'beds' => 1, 'bed_type' => 'Individual', 'published_price' => 85000, 'breakfast_included' => true, 'amenities' => $mussuloAmenities],
                    ['name' => 'Quarto Duplo', 'capacity' => 2, 'beds' => 1, 'bed_type' => 'King', 'published_price' => 97000, 'breakfast_included' => true, 'amenities' => $mussuloAmenities],
                    ['name' => 'Vila T1', 'capacity' => 2, 'beds' => 1, 'published_price' => 114000, 'breakfast_included' => true, 'amenities' => $mussuloAmenities],
                    ['name' => 'Vila T2', 'capacity' => 4, 'beds' => 2, 'published_price' => 245000, 'breakfast_included' => true, 'amenities' => $mussuloAmenities],
                    ['name' => 'Vila T3', 'capacity' => 6, 'beds' => 3, 'published_price' => 375000, 'breakfast_included' => true, 'amenities' => $mussuloAmenities],
                    ['name' => 'Quarto Twin', 'capacity' => 2, 'beds' => 2, 'bed_type' => 'Twin', 'published_price' => 97000, 'breakfast_included' => true, 'amenities' => $mussuloAmenities],
                ],
            ],
        ];
    }
}
