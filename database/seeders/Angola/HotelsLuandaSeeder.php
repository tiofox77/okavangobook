<?php

namespace Database\Seeders\Angola;

use App\Models\Hotel;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Str;

class HotelsLuandaSeeder
{
    use HotelSeederTrait;

    public function run(User $admin): void
    {
        $loc = Location::where('name', 'Luanda')->first();
        if (!$loc) return;

        $hotels = [
            [
                'name' => 'EPIC SANA Luanda Hotel',
                'property_type' => 'hotel',
                'description' => 'O hotel de referência da capital angolana, situado na Avenida 4 de Fevereiro com vista directa para a Baía de Luanda. Inaugurado em 2012, combina arquitectura contemporânea com serviço cinco estrelas. Com 293 quartos, dois restaurantes premiados, piscina panorâmica no rooftop, Spa SANA e o maior salão de eventos de Angola, é a escolha de eleição de viajantes de negócios e turismo de luxo.',
                'address' => 'Avenida 4 de Fevereiro, Luanda',
                'stars' => 5, 'rating' => 4.8, 'reviews_count' => 1243, 'min_price' => 180000, 'is_featured' => true,
                'latitude' => -8.8147, 'longitude' => 13.2302,
                'phone' => '+244 222 697 000', 'email' => 'reservas@epicsanaluanda.ao', 'website' => 'https://www.epicsana.com',
                'thumbnail' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200&q=80',
                    'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=1200&q=80',
                    'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=1200&q=80',
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                    'https://images.unsplash.com/photo-1519690889869-e705e59f72e1?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Piscina rooftop', 'Spa SANA', 'Academia', '2 Restaurantes', 'Bar', 'Salão de eventos 1500 lugares', 'Business center', 'Transfer aeroporto', 'Concierge 24h', 'Valet parking', 'Serviço de quarto 24h'],
            ],
            [
                'name' => 'Hotel Presidente Luanda',
                'property_type' => 'hotel',
                'description' => 'Ícone da hotelaria angolana, erguendo-se no centro de Luanda com vista privilegiada para a baía. Reconhecido pela hospitalidade clássica e pela localização estratégica próxima do Palácio Presidencial e dos ministérios. Restaurante internacional, piscina exterior e completo centro de negócios.',
                'address' => 'Rua Major Kanhangulo, Centro, Luanda',
                'stars' => 5, 'rating' => 4.6, 'reviews_count' => 876, 'min_price' => 150000, 'is_featured' => true,
                'latitude' => -8.8200, 'longitude' => 13.2350,
                'phone' => '+244 222 332 772', 'email' => 'info@hotelpresidente.ao', 'website' => null,
                'thumbnail' => 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=1200&q=80',
                    'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1200&q=80',
                    'https://images.unsplash.com/photo-1468824357306-a439d58ccb1c?w=1200&q=80',
                    'https://images.unsplash.com/photo-1583037189850-1921ae7c6c22?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Piscina exterior', 'Restaurante', 'Bar', 'Business center', 'Ginásio', 'Transfer aeroporto', 'Estacionamento'],
            ],
            [
                'name' => 'Skyna Hotel Luanda',
                'property_type' => 'hotel',
                'description' => 'Hotel de design contemporâneo nas Ingombotas. Quartos elegantes com decoração moderna, restaurante internacional, bar trendy e cobertura com vistas sobre a cidade. As suas instalações de conferências são altamente conceituadas na comunidade empresarial luandense.',
                'address' => 'Rua Amilcar Cabral, Ingombotas, Luanda',
                'stars' => 4, 'rating' => 4.5, 'reviews_count' => 654, 'min_price' => 95000, 'is_featured' => true,
                'latitude' => -8.8250, 'longitude' => 13.2300,
                'phone' => '+244 222 694 000', 'email' => 'reservations@skynahotel.ao', 'website' => null,
                'thumbnail' => 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=1200&q=80',
                    'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200&q=80',
                    'https://images.unsplash.com/photo-1574362848149-11496d93a7c7?w=1200&q=80',
                    'https://images.unsplash.com/photo-1540541338287-41700207dee6?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante', 'Bar rooftop', 'Salas de conferência', 'Ginásio', 'Estacionamento', 'Serviço de quarto'],
            ],
            [
                'name' => 'Hotel Tropic Luanda',
                'property_type' => 'hotel',
                'description' => 'Distingue-se pelo ambiente tropical no coração urbano de Luanda. Jardins exuberantes, piscina rodeada de vegetação e restaurante especializado em frutos do mar frescos do Atlântico. Localizado no Miramar, zona residencial tranquila e bem servida de transportes.',
                'address' => 'Rua Fernando Pessoa, Miramar, Luanda',
                'stars' => 4, 'rating' => 4.4, 'reviews_count' => 315, 'min_price' => 85000, 'is_featured' => false,
                'latitude' => -8.8400, 'longitude' => 13.2500,
                'phone' => '+244 222 448 500', 'email' => 'tropic@hoteltropic.ao', 'website' => null,
                'thumbnail' => 'https://images.unsplash.com/photo-1468824357306-a439d58ccb1c?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1468824357306-a439d58ccb1c?w=1200&q=80',
                    'https://images.unsplash.com/photo-1591088398332-8a7791972843?w=1200&q=80',
                    'https://images.unsplash.com/photo-1541123437800-1bb1317badc2?w=1200&q=80',
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Piscina tropical', 'Restaurante frutos do mar', 'Bar', 'Jardim tropical', 'Serviço de quarto'],
            ],
            [
                'name' => 'Hotel de Convenções de Talatona',
                'property_type' => 'hotel',
                'description' => 'O maior complexo hoteleiro e de convenções do Sul de Luanda, no moderno bairro de Talatona. Com mais de 200 quartos, auditório para 1.500 pessoas, salas de reunião, 3 restaurantes, piscina olímpica e spa. Destino preferido para grandes eventos corporativos e conferências internacionais.',
                'address' => 'Via Expressa Sul, Talatona, Luanda Sul',
                'stars' => 5, 'rating' => 4.7, 'reviews_count' => 892, 'min_price' => 160000, 'is_featured' => true,
                'latitude' => -8.9200, 'longitude' => 13.1900,
                'phone' => '+244 222 710 000', 'email' => 'reservas@talatona.ao', 'website' => null,
                'thumbnail' => 'https://images.unsplash.com/photo-1596386461350-326ccb383e9f?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1596386461350-326ccb383e9f?w=1200&q=80',
                    'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=1200&q=80',
                    'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=1200&q=80',
                    'https://images.unsplash.com/photo-1583037189850-1921ae7c6c22?w=1200&q=80',
                    'https://images.unsplash.com/photo-1519690889869-e705e59f72e1?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Piscina olímpica', 'Spa & wellness', 'Academia', 'Auditório 1500 lugares', 'Salas de reunião', '3 Restaurantes', 'Business center', 'Transfer', 'Estacionamento amplo'],
            ],
            [
                'name' => 'Baía Palace Hotel Luanda',
                'property_type' => 'hotel',
                'description' => 'Ergue-se na Marginal de Luanda com vistas panorâmicas para a baía. Arquitectura art déco renovada com todas as comodidades modernas. Quartos e suítes com varanda virada ao mar, restaurante panorâmico no último piso e bar na piscina infinita.',
                'address' => 'Avenida 4 de Fevereiro, Marginal, Luanda',
                'stars' => 5, 'rating' => 4.7, 'reviews_count' => 1056, 'min_price' => 175000, 'is_featured' => true,
                'latitude' => -8.8160, 'longitude' => 13.2280,
                'phone' => '+244 222 310 000', 'email' => 'reservas@baiapalace.ao', 'website' => null,
                'thumbnail' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&q=80',
                    'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1200&q=80',
                    'https://images.unsplash.com/photo-1468824357306-a439d58ccb1c?w=1200&q=80',
                    'https://images.unsplash.com/photo-1574362848149-11496d93a7c7?w=1200&q=80',
                    'https://images.unsplash.com/photo-1596436889106-be35e843f974?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Piscina infinita', 'Restaurante panorâmico', 'Bar', 'Spa', 'Academia', 'Vista para a baía', 'Concierge 24h', 'Transfer', 'Estacionamento'],
            ],
            [
                'name' => 'Mussulo Bay Resort & Spa',
                'property_type' => 'resort',
                'description' => 'Refúgio de luxo na paradisíaca Ilha do Mussulo com vistas deslumbrantes para o Atlântico. Piscinas infinitas, acesso privado à praia, spa de referência internacional e dois restaurantes gourmet. Perfeito para lua de mel, fuga de fim-de-semana e eventos corporativos exclusivos.',
                'address' => 'Ilha do Mussulo, Baía de Luanda',
                'stars' => 5, 'rating' => 4.9, 'reviews_count' => 432, 'min_price' => 200000, 'is_featured' => true,
                'latitude' => -8.9352, 'longitude' => 13.1843,
                'phone' => '+244 923 456 789', 'email' => 'reservas@mussulobay.ao', 'website' => null,
                'thumbnail' => 'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?w=1200&q=80',
                    'https://images.unsplash.com/photo-1540541338287-41700207dee6?w=1200&q=80',
                    'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200&q=80',
                    'https://images.unsplash.com/photo-1519690889869-e705e59f72e1?w=1200&q=80',
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                ],
                'amenities' => ['Praia privativa', 'Piscina infinita', 'Spa', 'Restaurante gourmet', 'Bar na piscina', 'Desportos aquáticos', 'Transfer de barco', 'Kids club', 'WiFi gratuito', 'Estacionamento'],
            ],
            [
                'name' => 'Victoria Garden Hotel Luanda',
                'property_type' => 'hotel',
                'description' => 'Hotel boutique de alto padrão no Miramar com jardins meticulosamente cuidados que criam uma atmosfera de refúgio. Apenas 45 quartos para atendimento personalizado. Restaurante com esplanada no jardim serve fusão de cozinha angolana e mediterrânica.',
                'address' => 'Rua Frederico Engels, Miramar, Luanda',
                'stars' => 4, 'rating' => 4.6, 'reviews_count' => 267, 'min_price' => 110000, 'is_featured' => false,
                'latitude' => -8.8380, 'longitude' => 13.2480,
                'phone' => '+244 222 440 200', 'email' => 'victoria@victoriagarden.ao', 'website' => null,
                'thumbnail' => 'https://images.unsplash.com/photo-1541123437800-1bb1317badc2?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1541123437800-1bb1317badc2?w=1200&q=80',
                    'https://images.unsplash.com/photo-1631049035182-249067d7618e?w=1200&q=80',
                    'https://images.unsplash.com/photo-1468824357306-a439d58ccb1c?w=1200&q=80',
                    'https://images.unsplash.com/photo-1574362848149-11496d93a7c7?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Jardim privativo', 'Piscina aquecida', 'Spa', 'Ginásio', 'Restaurante', 'Bar', 'Estacionamento'],
            ],
            [
                'name' => 'Thomson Luanda Hotel',
                'property_type' => 'hotel',
                'description' => 'Hotel moderno na zona do Alvalade, um dos bairros mais dinâmicos de Luanda. Com quartos executivos bem equipados, restaurante com cozinha angolana e internacional, bar, piscina e salas de reunião. Muito procurado por executivos e profissionais expatriados que residem e trabalham em Luanda.',
                'address' => 'Bairro Alvalade, Luanda',
                'stars' => 4, 'rating' => 4.3, 'reviews_count' => 389, 'min_price' => 88000, 'is_featured' => false,
                'latitude' => -8.8350, 'longitude' => 13.2450,
                'phone' => '+244 222 447 100', 'email' => 'thomson@thomson.ao', 'website' => null,
                'thumbnail' => 'https://images.unsplash.com/photo-1445019980597-93fa8acb246c?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1445019980597-93fa8acb246c?w=1200&q=80',
                    'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=1200&q=80',
                    'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=1200&q=80',
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Piscina', 'Restaurante', 'Bar', 'Salas de reunião', 'Ginásio', 'Estacionamento', 'Serviço de quarto'],
            ],
            [
                'name' => 'Luanda Guest House Miramar',
                'property_type' => 'hospedaria',
                'description' => 'Guest house acolhedora no Miramar com decoração de inspiração angolana e atmosfera familiar. Pequeno-almoço caseiro incluído. Ideal para viajantes que procuram autenticidade e conforto sem excessos, com acesso fácil ao centro e à praia da Ilha.',
                'address' => 'Rua das Acácias, Miramar, Luanda',
                'stars' => 3, 'rating' => 4.5, 'reviews_count' => 143, 'min_price' => 35000, 'is_featured' => false,
                'latitude' => -8.8430, 'longitude' => 13.2520,
                'phone' => '+244 923 112 334', 'email' => 'luandaguesthouse@gmail.com', 'website' => null,
                'thumbnail' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80',
                    'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?w=1200&q=80',
                    'https://images.unsplash.com/photo-1568084680786-a84f91d1153c?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Pequeno-almoço incluído', 'Jardim', 'Estacionamento gratuito', 'Cozinha partilhada', 'Tours locais'],
            ],
            [
                'name' => 'Royal Luanda Hotel',
                'property_type' => 'hotel',
                'description' => 'O Royal Luanda Hotel é uma opção de qualidade na zona do Maianga, com quartos espaçosos, restaurante angolano com pratos típicos e bar com selecção de bebidas nacionais e importadas. Dispõe de piscina, ginásio e estacionamento coberto. Ideal para visitas de negócios e turismo familiar.',
                'address' => 'Bairro Maianga, Luanda',
                'stars' => 4, 'rating' => 4.2, 'reviews_count' => 211, 'min_price' => 72000, 'is_featured' => false,
                'latitude' => -8.8300, 'longitude' => 13.2400,
                'phone' => '+244 222 510 500', 'email' => 'royal@royalluanda.ao', 'website' => null,
                'thumbnail' => 'https://images.unsplash.com/photo-1549294413-26f195200c16?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1549294413-26f195200c16?w=1200&q=80',
                    'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=1200&q=80',
                    'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Piscina', 'Restaurante angolano', 'Bar', 'Ginásio', 'Estacionamento coberto', 'Lavandaria'],
            ],
        ];

        foreach ($hotels as $data) {
            $this->createHotel($data, $admin, $loc);
        }
    }
}
