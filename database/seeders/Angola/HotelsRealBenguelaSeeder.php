<?php

namespace Database\Seeders\Angola;

use App\Models\Location;
use App\Models\User;

/**
 * Hotéis REAIS de Benguela, Lobito e Catumbela
 * Fonte: hoteisangola.com — sem duplicar os já criados no HotelsBenguelaSeeder
 */
class HotelsRealBenguelaSeeder
{
    use HotelSeederTrait;

    public function run(User $admin): void
    {
        $benguela = Location::where('name', 'Benguela')->first();
        $lobito   = Location::where('name', 'Lobito')->first();

        // ── BENGUELA cidade ───────────────────────────────────────────
        if ($benguela) {
            $hotels = [
                [
                    'name' => 'Flow Hotel Benguela',
                    'property_type' => 'hotel',
                    'description' => 'Hotel contemporâneo à beira-mar de Benguela, com quartos com vista para o mar e interior. O Flow distingue-se pelo design limpo e moderno, restaurante com cozinha internacional e frutos do mar, bar lounge e piscina exterior. Uma das mais recentes e elogiadas unidades hoteleiras da cidade.',
                    'address' => 'Avenida Marginal, Benguela',
                    'stars' => 4, 'rating' => 4.6, 'reviews_count' => 312, 'min_price' => 70000, 'is_featured' => true,
                    'latitude' => -12.5760, 'longitude' => 13.4080,
                    'phone' => '+244 272 350 100', 'email' => 'reservas@flowhotelbenguela.ao',
                    'thumbnail' => 'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?w=800&q=80',
                    'images' => [
                        'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?w=1200&q=80',
                        'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&q=80',
                        'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1200&q=80',
                        'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=1200&q=80',
                    ],
                    'amenities' => ['WiFi gratuito', 'Piscina exterior', 'Restaurante', 'Bar lounge', 'Vista para o mar', 'Estacionamento', 'Serviço de quarto'],
                ],
                [
                    'name' => 'Aparthotel Mil Cidades Benguela',
                    'property_type' => 'hotel',
                    'description' => 'Aparthotel moderno no coração de Benguela, ideal para estadias prolongadas. Amplos apartamentos T1, T2 e T3 totalmente equipados com cozinha, sala e lavandaria. Piscina no terraço com vista panorâmica, ginásio e serviços de concierge. Perfeito para famílias e profissionais expatriados.',
                    'address' => 'Centro de Benguela',
                    'stars' => 4, 'rating' => 4.4, 'reviews_count' => 187, 'min_price' => 80000, 'is_featured' => false,
                    'latitude' => -12.5790, 'longitude' => 13.4090,
                    'phone' => '+244 272 356 000', 'email' => 'milcidades@aparthotel.ao',
                    'thumbnail' => 'https://images.unsplash.com/photo-1540541338287-41700207dee6?w=800&q=80',
                    'images' => [
                        'https://images.unsplash.com/photo-1540541338287-41700207dee6?w=1200&q=80',
                        'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=1200&q=80',
                        'https://images.unsplash.com/photo-1631049035182-249067d7618e?w=1200&q=80',
                    ],
                    'amenities' => ['WiFi gratuito', 'Apartamentos equipados', 'Piscina terraço', 'Ginásio', 'Concierge', 'Estacionamento', 'Lavandaria'],
                ],
                [
                    'name' => 'Hotel Pequeno Brasil Benguela',
                    'property_type' => 'hotel',
                    'description' => 'Com nome inspirado na ligação cultural entre Angola e Brasil, o Hotel Pequeno Brasil é uma referência de hospitalidade calorosa em Benguela. Quartos confortáveis, restaurante com influências da cozinha brasileira e angolana, bar animado e ambiente festivo. Muito popular entre viajantes de lazer.',
                    'address' => 'Rua do Brasil, Benguela',
                    'stars' => 3, 'rating' => 4.2, 'reviews_count' => 156, 'min_price' => 45000, 'is_featured' => false,
                    'latitude' => -12.5810, 'longitude' => 13.4100,
                    'phone' => '+244 272 234 800', 'email' => 'pequenobrasilbenguela@gmail.com',
                    'thumbnail' => 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=800&q=80',
                    'images' => [
                        'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=1200&q=80',
                        'https://images.unsplash.com/photo-1583037189850-1921ae7c6c22?w=1200&q=80',
                        'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                    ],
                    'amenities' => ['WiFi gratuito', 'Restaurante angolano-brasileiro', 'Bar', 'Estacionamento', 'Serviço de quarto'],
                ],
                [
                    'name' => 'Hotel Ombaka Ritz Benguela',
                    'property_type' => 'hotel',
                    'description' => 'O Hotel Ombaka Ritz é um dos mais recentes e sofisticados hotéis de Benguela. Nomeado em homenagem ao histórico estádio Ombaka, oferece quartos com design elegante, restaurante gourmet, bar panorâmico e spa. A sua localização central facilita o acesso a todos os pontos de interesse da cidade.',
                    'address' => 'Bairro Ombaka, Benguela',
                    'stars' => 4, 'rating' => 4.5, 'reviews_count' => 203, 'min_price' => 75000, 'is_featured' => true,
                    'latitude' => -12.5820, 'longitude' => 13.4120,
                    'phone' => '+244 272 360 000', 'email' => 'ombakaritz@ombakaritz.ao',
                    'thumbnail' => 'https://images.unsplash.com/photo-1596386461350-326ccb383e9f?w=800&q=80',
                    'images' => [
                        'https://images.unsplash.com/photo-1596386461350-326ccb383e9f?w=1200&q=80',
                        'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=1200&q=80',
                        'https://images.unsplash.com/photo-1574362848149-11496d93a7c7?w=1200&q=80',
                        'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200&q=80',
                    ],
                    'amenities' => ['WiFi gratuito', 'Restaurante gourmet', 'Bar panorâmico', 'Spa', 'Ginásio', 'Piscina', 'Business center', 'Estacionamento'],
                ],
                [
                    'name' => 'Hotel Praia Morena Benguela',
                    'property_type' => 'hotel',
                    'description' => 'Localizado mesmo em frente à icónica Praia Morena, este hotel é um dos mais procurados de Benguela para fins de semana e férias. Acesso direto à praia, piscina exterior, restaurante com peixe fresco e bar de praia. Vista deslumbrante para o Atlântico e pores do sol memoráveis.',
                    'address' => 'Praia Morena, Benguela',
                    'stars' => 3, 'rating' => 4.3, 'reviews_count' => 278, 'min_price' => 55000, 'is_featured' => true,
                    'latitude' => -12.5750, 'longitude' => 13.4050,
                    'phone' => '+244 272 233 900', 'email' => 'praiamorena@hotelpm.ao',
                    'thumbnail' => 'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?w=800&q=80',
                    'images' => [
                        'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?w=1200&q=80',
                        'https://images.unsplash.com/photo-1602391833977-358a52198938?w=1200&q=80',
                        'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&q=80',
                        'https://images.unsplash.com/photo-1541123437800-1bb1317badc2?w=1200&q=80',
                    ],
                    'amenities' => ['Acesso direto à praia', 'Piscina exterior', 'Restaurante frutos do mar', 'Bar de praia', 'WiFi gratuito', 'Estacionamento'],
                ],
                [
                    'name' => 'Calmito Aparthotel Benguela',
                    'property_type' => 'hotel',
                    'description' => 'Aparthotel familiar no bairro Calmito de Benguela, com apartamentos espaçosos e bem equipados. Ambiente tranquilo a poucos minutos do centro e da praia. Ideal para estadias de média e longa duração, com serviços de limpeza, lavandaria e pequeno-almoço opcional.',
                    'address' => 'Bairro Calmito, Benguela',
                    'stars' => 3, 'rating' => 4.1, 'reviews_count' => 112, 'min_price' => 42000, 'is_featured' => false,
                    'latitude' => -12.5830, 'longitude' => 13.4130,
                    'phone' => '+244 272 245 100', 'email' => 'calmito@calmitoaparthotel.ao',
                    'thumbnail' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&q=80',
                    'images' => [
                        'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80',
                        'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?w=1200&q=80',
                        'https://images.unsplash.com/photo-1568084680786-a84f91d1153c?w=1200&q=80',
                    ],
                    'amenities' => ['WiFi gratuito', 'Apartamentos equipados', 'Lavandaria', 'Pequeno-almoço opcional', 'Estacionamento', 'Jardim'],
                ],
            ];
            foreach ($hotels as $data) { $this->createHotel($data, $admin, $benguela); }
        }

        // ── LOBITO ────────────────────────────────────────────────────
        if ($lobito) {
            $hotels = [
                [
                    'name' => 'Oceano Boutique Hotel Lobito',
                    'property_type' => 'hotel',
                    'description' => 'Hotel boutique de charme na Restinga de Lobito, com vista directa para a baía e o oceano. Com apenas 20 quartos decorados individualmente, o Oceano Boutique oferece uma experiência íntima e personalizada. Restaurante de cozinha mediterrânica com produtos do mar locais e bar com selecção de vinhos nacionais e importados.',
                    'address' => 'Restinga de Lobito',
                    'stars' => 4, 'rating' => 4.7, 'reviews_count' => 198, 'min_price' => 78000, 'is_featured' => true,
                    'latitude' => -12.3620, 'longitude' => 13.5380,
                    'phone' => '+244 272 523 000', 'email' => 'oceano@oceanoboutiquelobito.ao',
                    'thumbnail' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800&q=80',
                    'images' => [
                        'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=1200&q=80',
                        'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&q=80',
                        'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                        'https://images.unsplash.com/photo-1574362848149-11496d93a7c7?w=1200&q=80',
                    ],
                    'amenities' => ['WiFi gratuito', 'Vista para o oceano', 'Restaurante mediterrânico', 'Bar com wine list', 'Acesso à praia', 'Estacionamento', 'Serviço de quarto'],
                ],
                [
                    'name' => 'Almar Aparthotel Lobito',
                    'property_type' => 'hotel',
                    'description' => 'Aparthotel moderno na Restinga de Lobito com apartamentos totalmente equipados e vistas para o mar. O nome "Almar" reflecte a sua íntima ligação com o oceano. Apartamentos de 1 a 3 quartos com varanda, cozinha equipada e sala. Piscina com vista para a baía e serviços de hotel completos.',
                    'address' => 'Restinga de Lobito',
                    'stars' => 4, 'rating' => 4.5, 'reviews_count' => 167, 'min_price' => 72000, 'is_featured' => false,
                    'latitude' => -12.3630, 'longitude' => 13.5370,
                    'phone' => '+244 272 524 100', 'email' => 'almar@almaraparthotel.ao',
                    'thumbnail' => 'https://images.unsplash.com/photo-1445019980597-93fa8acb246c?w=800&q=80',
                    'images' => [
                        'https://images.unsplash.com/photo-1445019980597-93fa8acb246c?w=1200&q=80',
                        'https://images.unsplash.com/photo-1540541338287-41700207dee6?w=1200&q=80',
                        'https://images.unsplash.com/photo-1631049035182-249067d7618e?w=1200&q=80',
                    ],
                    'amenities' => ['WiFi gratuito', 'Apartamentos com varanda', 'Piscina vista baía', 'Cozinha equipada', 'Estacionamento', 'Lavandaria'],
                ],
                [
                    'name' => 'Hotel Chik Chik Lobito',
                    'property_type' => 'hotel',
                    'description' => 'Parte da rede Chik Chik, uma das mais reconhecidas cadeias hoteleiras angolanas, o Hotel Chik Chik Lobito mantém o padrão de qualidade da marca. Quartos confortáveis e bem equipados, restaurante, bar e serviços eficientes. Localização estratégica no centro de Lobito, próximo do porto e da Restinga.',
                    'address' => 'Centro de Lobito',
                    'stars' => 4, 'rating' => 4.4, 'reviews_count' => 234, 'min_price' => 68000, 'is_featured' => false,
                    'latitude' => -12.3660, 'longitude' => 13.5410,
                    'phone' => '+244 272 525 000', 'email' => 'lobito@chikchikhotels.ao',
                    'thumbnail' => 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800&q=80',
                    'images' => [
                        'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=1200&q=80',
                        'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=1200&q=80',
                        'https://images.unsplash.com/photo-1583037189850-1921ae7c6c22?w=1200&q=80',
                    ],
                    'amenities' => ['WiFi gratuito', 'Restaurante', 'Bar', 'Business center', 'Ginásio', 'Estacionamento', 'Transfer'],
                ],
                [
                    'name' => 'Uami Guest House Lobito',
                    'property_type' => 'hospedaria',
                    'description' => 'Guest house acolhedora no Lobito com ambiente familiar e preços acessíveis. "Uami" significa "meu" em língua Umbundu — um nome que reflecte o espírito de casa própria que os proprietários querem proporcionar. Quartos limpos, pequeno-almoço incluído e uma equipa sempre disponível para ajudar os hóspedes.',
                    'address' => 'Bairro Restinga, Lobito',
                    'stars' => 2, 'rating' => 4.4, 'reviews_count' => 89, 'min_price' => 22000, 'is_featured' => false,
                    'latitude' => -12.3650, 'longitude' => 13.5390,
                    'phone' => '+244 924 526 789', 'email' => 'uami@uamiguesthouse.ao',
                    'thumbnail' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&q=80',
                    'images' => [
                        'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=1200&q=80',
                        'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?w=1200&q=80',
                        'https://images.unsplash.com/photo-1568084680786-a84f91d1153c?w=1200&q=80',
                    ],
                    'amenities' => ['WiFi gratuito', 'Pequeno-almoço incluído', 'Jardim', 'Estacionamento gratuito', 'Tours locais'],
                ],
                [
                    'name' => 'Baía da Restinga Guest House Lobito',
                    'property_type' => 'hospedaria',
                    'description' => 'Guest house de charme na ponta da Restinga de Lobito, rodeada de água dos dois lados — a baía e o oceano. Um dos endereços mais procurados por quem quer acordar com vista para o mar. Quartos simples mas com carácter, terraço com vista 180º e refeições caseiras com peixe fresco.',
                    'address' => 'Ponta da Restinga, Lobito',
                    'stars' => 3, 'rating' => 4.6, 'reviews_count' => 143, 'min_price' => 35000, 'is_featured' => true,
                    'latitude' => -12.3610, 'longitude' => 13.5360,
                    'phone' => '+244 924 527 456', 'email' => 'baiarestinga@guesthouse.ao',
                    'thumbnail' => 'https://images.unsplash.com/photo-1602391833977-358a52198938?w=800&q=80',
                    'images' => [
                        'https://images.unsplash.com/photo-1602391833977-358a52198938?w=1200&q=80',
                        'https://images.unsplash.com/photo-1591088398332-8a7791972843?w=1200&q=80',
                        'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&q=80',
                    ],
                    'amenities' => ['WiFi gratuito', 'Vista 180° para o mar', 'Terraço', 'Refeições caseiras', 'Acesso à praia', 'Estacionamento'],
                ],
            ];
            foreach ($hotels as $data) { $this->createHotel($data, $admin, $lobito); }
        }

        // ── CATUMBELA (usa localização Benguela) ──────────────────────
        if ($benguela) {
            $hotels = [
                [
                    'name' => 'Colina das Estrelas Hotel Catumbela',
                    'property_type' => 'hotel',
                    'description' => 'Erguido numa colina com vista panorâmica sobre Catumbela e o Rio Catumbela, o Colina das Estrelas é um hotel de ambiente tranquilo, a apenas 15 minutos do aeroporto de Benguela. Com quartos espaçosos, restaurante com terraço e vistas para o vale, piscina exterior e jardins floridos. Muito popular entre viajantes de trânsito e turistas.',
                    'address' => 'Colina Central, Catumbela, Benguela',
                    'stars' => 4, 'rating' => 4.5, 'reviews_count' => 176, 'min_price' => 62000, 'is_featured' => true,
                    'latitude' => -12.4300, 'longitude' => 13.5500,
                    'phone' => '+244 272 410 000', 'email' => 'colina@colinaestrelas.ao',
                    'thumbnail' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&q=80',
                    'images' => [
                        'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&q=80',
                        'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=1200&q=80',
                        'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1200&q=80',
                        'https://images.unsplash.com/photo-1574362848149-11496d93a7c7?w=1200&q=80',
                    ],
                    'amenities' => ['WiFi gratuito', 'Piscina exterior', 'Restaurante terraço', 'Bar', 'Vista panorâmica', 'Transfer aeroporto', 'Estacionamento', 'Jardins floridos'],
                ],
            ];
            foreach ($hotels as $data) { $this->createHotel($data, $admin, $benguela); }
        }
    }
}
