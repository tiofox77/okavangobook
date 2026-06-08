<?php

namespace Database\Seeders\Angola;

use App\Models\Location;
use App\Models\User;

class HotelsLubangoSeeder
{
    use HotelSeederTrait;

    public function run(User $admin): void
    {
        $loc = Location::where('name', 'Lubango')->first();
        if (!$loc) return;

        $hotels = [
            [
                'name' => 'Serra da Leba Mountain Resort',
                'property_type' => 'resort',
                'description' => 'Empoleirado nas majestosas montanhas da Huíla, o Serra da Leba Mountain Resort oferece uma experiência única com vistas panorâmicas de cortar a respiração. Inspire-se com o ar puro da serra, desfrute de trilhas exclusivas, e relaxe no spa alpino com tratamentos inspirados na flora local. Arquitectura que harmoniza com a paisagem e gastronomia que celebra os sabores regionais.',
                'address' => 'Serra da Leba, Via Lubango-Namibe',
                'stars' => 5, 'rating' => 4.9, 'reviews_count' => 203, 'min_price' => 95000, 'is_featured' => true,
                'latitude' => -14.9358, 'longitude' => 13.4825,
                'phone' => '+244 261 234 567', 'email' => 'reservas@serradaleba.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&q=80',
                    'https://images.unsplash.com/photo-1519904981063-b0cf448d479e?w=1200&q=80',
                    'https://images.unsplash.com/photo-1551632811-561732d1e306?w=1200&q=80',
                    'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200&q=80',
                    'https://images.unsplash.com/photo-1519690889869-e705e59f72e1?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Piscina aquecida coberta', 'Spa alpino & sauna', 'Restaurante panorâmico', 'Wine bar', 'Trilhas guiadas', 'Observatório de estrelas', 'Fogueira ao ar livre', 'Passeios 4x4', 'Biblioteca'],
            ],
            [
                'name' => 'Hotel Lubango',
                'property_type' => 'hotel',
                'description' => 'O Hotel Lubango é o estabelecimento mais tradicional da capital da Huíla, com localização central e excelente reputação. Quartos amplos com ar condicionado e vista para a cidade, restaurante com gastronomia regional, bar, piscina e serviços completos. A apenas minutos do Cristo Rei e da Serra da Leba.',
                'address' => 'Avenida Dr. António Agostinho Neto, Lubango',
                'stars' => 4, 'rating' => 4.3, 'reviews_count' => 287, 'min_price' => 55000, 'is_featured' => true,
                'latitude' => -14.9167, 'longitude' => 13.5000,
                'phone' => '+244 261 220 000', 'email' => 'reservas@hotellubango.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=1200&q=80',
                    'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=1200&q=80',
                    'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=1200&q=80',
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Piscina', 'Restaurante regional', 'Bar', 'Ginásio', 'Estacionamento', 'Transfer Cristo Rei'],
            ],
            [
                'name' => 'Hotel Império Lubango',
                'property_type' => 'hotel',
                'description' => 'Hotel de charme no centro de Lubango, com arquitectura colonial renovada e decoração que homenageia a cultura local da Huíla. Quartos confortáveis, restaurante com cozinha regional premiada, bar e terraço com vista para a cidade. Muito procurado por turistas que visitam o Cristo Rei e a Serra da Leba.',
                'address' => 'Rua Pinheiro Chagas, Centro, Lubango',
                'stars' => 4, 'rating' => 4.4, 'reviews_count' => 198, 'min_price' => 52000, 'is_featured' => false,
                'latitude' => -14.9200, 'longitude' => 13.5020,
                'phone' => '+244 261 225 000', 'email' => 'imperio@hotelimp erio.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=1200&q=80',
                    'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1200&q=80',
                    'https://images.unsplash.com/photo-1583037189850-1921ae7c6c22?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante regional premiado', 'Bar', 'Terraço panorâmico', 'Estacionamento', 'Tours regionais'],
            ],
            [
                'name' => 'Hotel Cristo Rei Lubango',
                'property_type' => 'hotel',
                'description' => 'Situado próximo do icónico monumento do Cristo Rei, este hotel oferece vistas únicas sobre Lubango e o Vale da Huíla. Quartos com varanda, piscina com vista para o vale, restaurante e serviços de qualidade. Um ponto de partida ideal para explorar as maravilhas naturais da Huíla, incluindo a Serra da Leba.',
                'address' => 'Alto do Cristo Rei, Lubango',
                'stars' => 4, 'rating' => 4.6, 'reviews_count' => 324, 'min_price' => 60000, 'is_featured' => true,
                'latitude' => -14.9100, 'longitude' => 13.4950,
                'phone' => '+244 261 230 000', 'email' => 'cristorei@hotelcristorei.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1551632811-561732d1e306?w=1200&q=80',
                    'https://images.unsplash.com/photo-1519904981063-b0cf448d479e?w=1200&q=80',
                    'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=1200&q=80',
                    'https://images.unsplash.com/photo-1541123437800-1bb1317badc2?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Piscina com vista para o vale', 'Restaurante', 'Bar', 'Tours Serra da Leba', 'Estacionamento', 'Serviço de quarto'],
            ],
            [
                'name' => 'Pousada da Serra Lubango',
                'property_type' => 'hospedaria',
                'description' => 'Pousada acolhedora a 1.700 metros de altitude, rodeada de natureza exuberante. Quartos simples mas confortáveis, pequeno-almoço caseiro com produtos locais, lareira na sala comum e jardim com flores da serra. A proprietária organiza passeios guiados pela Serra da Leba e arredores.',
                'address' => 'Bairro da Cela, Lubango',
                'stars' => 3, 'rating' => 4.7, 'reviews_count' => 112, 'min_price' => 28000, 'is_featured' => false,
                'latitude' => -14.9250, 'longitude' => 13.4900,
                'phone' => '+244 912 345 678', 'email' => 'pousadaserra@gmail.com',
                'thumbnail' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80',
                    'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=1200&q=80',
                    'https://images.unsplash.com/photo-1568084680786-a84f91d1153c?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Pequeno-almoço caseiro incluído', 'Jardim', 'Lareira', 'Passeios guiados', 'Estacionamento gratuito'],
            ],
            [
                'name' => 'Hotel Palanca Negra Lubango',
                'property_type' => 'hotel',
                'description' => 'Nomeado em homenagem ao antílope símbolo de Angola, o Hotel Palanca Negra é um estabelecimento de três estrelas no centro de Lubango. Quartos limpos e confortáveis, restaurante com pratos típicos angolanos, bar e serviços de apoio ao turista. Bom ponto de partida para excursões ao planalto da Huíla.',
                'address' => 'Avenida da Serra da Leba, Lubango',
                'stars' => 3, 'rating' => 4.1, 'reviews_count' => 167, 'min_price' => 32000, 'is_featured' => false,
                'latitude' => -14.9180, 'longitude' => 13.5050,
                'phone' => '+244 261 221 500', 'email' => 'palanca@hotelpalanca.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=1200&q=80',
                    'https://images.unsplash.com/photo-1631049035182-249067d7618e?w=1200&q=80',
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante angolano', 'Bar', 'Excursões organizadas', 'Estacionamento'],
            ],
        ];

        foreach ($hotels as $data) {
            $this->createHotel($data, $admin, $loc);
        }
    }
}
