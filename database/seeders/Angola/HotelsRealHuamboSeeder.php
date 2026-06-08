<?php

namespace Database\Seeders\Angola;

use App\Models\Location;
use App\Models\User;

/**
 * Hotéis REAIS de Huambo
 * Fonte: hoteisangola.com — sem duplicar os já criados no HotelsHuamboSeeder
 */
class HotelsRealHuamboSeeder
{
    use HotelSeederTrait;

    public function run(User $admin): void
    {
        $loc = Location::where('name', 'Huambo')->first();
        if (!$loc) return;

        $hotels = [
            [
                'name' => 'Hospedaria Engrácia & Filhos Huambo',
                'property_type' => 'hospedaria',
                'description' => 'Uma das hospedarias mais antigas e queridas do Huambo, a Hospedaria Engrácia & Filhos é um verdadeiro símbolo de hospitalidade familiar na cidade. Gerida pela mesma família há décadas, combina quartos confortáveis e acolhedores, refeições caseiras com pratos tradicionais do planalto central e uma atmosfera que faz os hóspedes sentirem-se em casa. No bairro Santa Teresa, com acesso fácil ao centro histórico.',
                'address' => 'Bairro Santa Teresa, Rua Silva Carvalho 106, Huambo',
                'stars' => 2, 'rating' => 4.5, 'reviews_count' => 134, 'min_price' => 18000, 'is_featured' => false,
                'latitude' => -12.7710, 'longitude' => 15.7390,
                'phone' => '+244 241 224 500', 'email' => 'engracia@hospedaria.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=1200&q=80',
                    'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?w=1200&q=80',
                    'https://images.unsplash.com/photo-1568084680786-a84f91d1153c?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Refeições caseiras incluídas', 'Ambiente familiar', 'Estacionamento gratuito', 'Tours cidade'],
            ],
            [
                'name' => 'Hotel Roma Ritz Huambo',
                'property_type' => 'hotel',
                'description' => 'O Hotel Roma Ritz é um dos hotéis mais completos e sofisticados do Huambo, situado numa das principais avenidas da cidade. Com quartos e suítes de design contemporâneo, restaurante com cozinha italiana e angolana, bar, ginásio e business center. Uma escolha de eleição para executivos e viajantes que exigem padrões elevados de qualidade e serviço.',
                'address' => 'Avenida do Chiúma, Huambo',
                'stars' => 4, 'rating' => 4.5, 'reviews_count' => 198, 'min_price' => 65000, 'is_featured' => true,
                'latitude' => -12.7680, 'longitude' => 15.7360,
                'phone' => '+244 241 226 000', 'email' => 'romaritz@romaritzhuambo.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200&q=80',
                    'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=1200&q=80',
                    'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=1200&q=80',
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                    'https://images.unsplash.com/photo-1583037189850-1921ae7c6c22?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante italiano & angolano', 'Bar', 'Ginásio', 'Business center', 'Salas de reunião', 'Estacionamento', 'Serviço de quarto 24h'],
            ],
            [
                'name' => 'Complexo Turístico Paraíso da Chiva Huambo',
                'property_type' => 'resort',
                'description' => 'O Complexo Turístico Paraíso da Chiva é um dos destinos de lazer mais populares do Huambo, combinando alojamento de qualidade com um parque de lazer e entretenimento. Bungalows familiares com cozinha equipada, piscinas temáticas, restaurante e bar, campos desportivos e espaços de festas. Ideal para famílias com crianças e grupos que buscam diversão e conforto.',
                'address' => 'Bairro da Chiva, Huambo',
                'stars' => 3, 'rating' => 4.4, 'reviews_count' => 267, 'min_price' => 45000, 'is_featured' => true,
                'latitude' => -12.7750, 'longitude' => 15.7420,
                'phone' => '+244 241 228 500', 'email' => 'paraiso@chiva.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=1200&q=80',
                    'https://images.unsplash.com/photo-1568084680786-a84f91d1153c?w=1200&q=80',
                    'https://images.unsplash.com/photo-1541123437800-1bb1317badc2?w=1200&q=80',
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Bungalows familiares', 'Piscinas temáticas', 'Parque de lazer', 'Restaurante', 'Bar', 'Campos desportivos', 'Espaço de festas', 'Estacionamento amplo'],
            ],
            [
                'name' => 'IU Hotel Huambo',
                'property_type' => 'hotel',
                'description' => 'Hotel de design moderno e minimalista em Huambo, o IU destaca-se pela sua abordagem contemporânea ao alojamento. Quartos funcionais e elegantes com tudo o que é necessário, restaurante com ementa simples mas de qualidade, bar e espaço de co-working. Popular entre jovens executivos e nómadas digitais que visitam o Huambo.',
                'address' => 'Bairro Académico, Huambo',
                'stars' => 3, 'rating' => 4.3, 'reviews_count' => 145, 'min_price' => 48000, 'is_featured' => false,
                'latitude' => -12.7720, 'longitude' => 15.7370,
                'phone' => '+244 241 229 000', 'email' => 'huambo@iuhotel.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=1200&q=80',
                    'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=1200&q=80',
                    'https://images.unsplash.com/photo-1631049035182-249067d7618e?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante', 'Bar', 'Co-working', 'Estacionamento', 'Bicicletas gratuitas'],
            ],
            [
                'name' => 'Aparthotel Tropicana Huambo',
                'property_type' => 'hotel',
                'description' => 'Aparthotel confortável no centro do Huambo, com apartamentos T1 e T2 totalmente equipados. Muito procurado por profissionais em missão prolongada e por famílias que preferem a independência de uma cozinha equipada. Piscina exterior, ginásio e serviços de hotel completos. Boa relação qualidade-preço no mercado hoteleiro do Huambo.',
                'address' => 'Rua da Independência, Huambo',
                'stars' => 3, 'rating' => 4.2, 'reviews_count' => 112, 'min_price' => 40000, 'is_featured' => false,
                'latitude' => -12.7730, 'longitude' => 15.7380,
                'phone' => '+244 241 230 000', 'email' => 'tropicana@aparthoteltropicana.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1540541338287-41700207dee6?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1540541338287-41700207dee6?w=1200&q=80',
                    'https://images.unsplash.com/photo-1568084680786-a84f91d1153c?w=1200&q=80',
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Apartamentos equipados', 'Piscina exterior', 'Ginásio', 'Lavandaria', 'Estacionamento'],
            ],
            [
                'name' => 'Catito Hotel Bailundo Huambo',
                'property_type' => 'hotel',
                'description' => 'Situado no histórico Bailundo, capital do reino homónimo e sede de um dos mais importantes sobados do planalto central, o Catito Hotel Bailundo é um estabelecimento de três estrelas com quartos confortáveis, restaurante regional e ambiente autêntico. Ideal para explorar o património cultural da região e as suas tradições dos povos Ovimbundu.',
                'address' => 'Bailundo, Huambo',
                'stars' => 3, 'rating' => 4.1, 'reviews_count' => 76, 'min_price' => 30000, 'is_featured' => false,
                'latitude' => -12.3500, 'longitude' => 15.7900,
                'phone' => '+244 241 245 000', 'email' => 'catito@catitohotel.ao',
                'thumbnail' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=800&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=1200&q=80',
                    'https://images.unsplash.com/photo-1565348271942-ab9e44c98aad?w=1200&q=80',
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                ],
                'amenities' => ['WiFi gratuito', 'Restaurante regional Ovimbundu', 'Bar', 'Tours culturais Bailundo', 'Estacionamento gratuito'],
            ],
        ];

        foreach ($hotels as $data) {
            $this->createHotel($data, $admin, $loc);
        }
    }
}
