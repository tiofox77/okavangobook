<?php

namespace Database\Seeders\Angola;

use App\Models\Location;
use App\Models\User;

class HotelsBenguelaSeeder
{
    use HotelSeederTrait;

    public function run(User $admin): void
    {
        // ── BENGUELA (cidade) ──────────────────────────────────────────
        $benguela = Location::where('name', 'Benguela')->first();
        if ($benguela) {
            $hotels = [
                [
                    'name' => 'Hotel Terminus Benguela',
                    'property_type' => 'hotel',
                    'description' => 'Um dos hotéis mais emblemáticos de Benguela, o Terminus ocupa um edifício histórico renovado no coração da cidade. Com vista para o Oceano Atlântico, restaurante de referência com especialidades de peixe e marisco, bar animado e piscina exterior. A sua localização central facilita o acesso à praia, ao mercado e ao centro histórico colonial.',
                    'address' => 'Avenida da República, Benguela',
                    'stars' => 4, 'rating' => 4.5, 'reviews_count' => 432, 'min_price' => 65000, 'is_featured' => true,
                    'latitude' => -12.5780, 'longitude' => 13.4070,
                    'phone' => '+244 272 234 500', 'email' => 'terminus@terminus.ao',
                    'thumbnail' => 'https://images.unsplash.com/photo-1602391833977-358a52198938?w=800&q=80',
                    'images' => [
                        'https://images.unsplash.com/photo-1602391833977-358a52198938?w=1200&q=80',
                        'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1200&q=80',
                        'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                        'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=1200&q=80',
                    ],
                    'amenities' => ['WiFi gratuito', 'Piscina', 'Restaurante frutos do mar', 'Bar', 'Vista para o mar', 'Estacionamento', 'Serviço de quarto'],
                ],
                [
                    'name' => 'Praia Morena Eco Resort',
                    'property_type' => 'resort',
                    'description' => 'Pioneiro em turismo sustentável em Angola, na costa da Baía Farta. Construído com materiais ecológicos e energia solar, oferece luxo consciente sem comprometer o conforto. Piscinas naturais, trilhas ecológicas, restaurante orgânico farm-to-table e programas de conservação marinha fazem deste resort um destino único.',
                    'address' => 'Baía Farta, Benguela',
                    'stars' => 5, 'rating' => 4.8, 'reviews_count' => 287, 'min_price' => 120000, 'is_featured' => true,
                    'latitude' => -12.5428, 'longitude' => 13.3918,
                    'phone' => '+244 272 345 678', 'email' => 'info@praiamorenaresort.ao',
                    'thumbnail' => 'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?w=800&q=80',
                    'images' => [
                        'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?w=1200&q=80',
                        'https://images.unsplash.com/photo-1540541338287-41700207dee6?w=1200&q=80',
                        'https://images.unsplash.com/photo-1568084680786-a84f91d1153c?w=1200&q=80',
                        'https://images.unsplash.com/photo-1596436889106-be35e843f974?w=1200&q=80',
                        'https://images.unsplash.com/photo-1541123437800-1bb1317badc2?w=1200&q=80',
                    ],
                    'amenities' => ['Praia privativa', 'Piscinas naturais', 'Spa ecológico', 'Restaurante orgânico', 'Yoga & meditação', 'Observação de golfinhos', 'Aulas de surf', 'WiFi gratuito', 'Estacionamento solar'],
                ],
                [
                    'name' => 'Hotel Baía Azul Benguela',
                    'property_type' => 'hotel',
                    'description' => 'Hotel moderno com arquitectura contemporânea em frente à Praia Morena. Quartos com vista para o Atlântico, piscina com acesso direto à praia, restaurante de gastronomia atlântica e bar sunset. Popular entre famílias e casais que buscam conforto e proximidade ao mar.',
                    'address' => 'Praia Morena, Benguela',
                    'stars' => 4, 'rating' => 4.4, 'reviews_count' => 298, 'min_price' => 72000, 'is_featured' => false,
                    'latitude' => -12.5850, 'longitude' => 13.4150,
                    'phone' => '+244 272 287 000', 'email' => 'baiaazul@baiaazul.ao',
                    'thumbnail' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&q=80',
                    'images' => [
                        'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&q=80',
                        'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=1200&q=80',
                        'https://images.unsplash.com/photo-1468824357306-a439d58ccb1c?w=1200&q=80',
                        'https://images.unsplash.com/photo-1574362848149-11496d93a7c7?w=1200&q=80',
                    ],
                    'amenities' => ['WiFi gratuito', 'Piscina acesso à praia', 'Restaurante', 'Bar sunset', 'Vista para o mar', 'Estacionamento'],
                ],
                [
                    'name' => 'Hotel Mombaka Benguela',
                    'property_type' => 'hotel',
                    'description' => 'Referência de hospitalidade em Benguela com décadas de história. Amplas instalações com piscina vista para o mar, restaurante clássico angolano, bar, salão de festas e estacionamento amplo. A sua equipa experiente garante um serviço caloroso e eficiente.',
                    'address' => 'Avenida 10 de Fevereiro, Benguela',
                    'stars' => 3, 'rating' => 4.2, 'reviews_count' => 187, 'min_price' => 42000, 'is_featured' => false,
                    'latitude' => -12.5800, 'longitude' => 13.4100,
                    'phone' => '+244 272 220 100', 'email' => 'mombaka@mombaka.ao',
                    'thumbnail' => 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=800&q=80',
                    'images' => [
                        'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=1200&q=80',
                        'https://images.unsplash.com/photo-1591088398332-8a7791972843?w=1200&q=80',
                        'https://images.unsplash.com/photo-1583037189850-1921ae7c6c22?w=1200&q=80',
                    ],
                    'amenities' => ['WiFi gratuito', 'Piscina', 'Restaurante angolano', 'Bar', 'Salão de festas', 'Estacionamento'],
                ],
                [
                    'name' => 'Kambumbe Lodge Benguela',
                    'property_type' => 'resort',
                    'description' => 'Lodge de luxo na costa de Benguela, entre dunas e mar. Bangalôs independentes com varanda privativa, piscina natural, restaurante com especialidades locais e serviços de turismo activo. Uma experiência única que combina aventura, natureza e conforto na costa angolana.',
                    'address' => 'Baía Azul, Benguela',
                    'stars' => 4, 'rating' => 4.7, 'reviews_count' => 156, 'min_price' => 95000, 'is_featured' => true,
                    'latitude' => -12.5200, 'longitude' => 13.3700,
                    'phone' => '+244 926 345 678', 'email' => 'kambumbe@kambumbe.ao',
                    'thumbnail' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800&q=80',
                    'images' => [
                        'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=1200&q=80',
                        'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?w=1200&q=80',
                        'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200&q=80',
                        'https://images.unsplash.com/photo-1541123437800-1bb1317badc2?w=1200&q=80',
                    ],
                    'amenities' => ['Bangalôs privados', 'Praia privativa', 'Piscina natural', 'Restaurante local', 'Passeios de barco', 'Pesca desportiva', 'WiFi gratuito'],
                ],
                [
                    'name' => 'Marisol Guest House Benguela',
                    'property_type' => 'hospedaria',
                    'description' => 'A poucos passos da praia, a Marisol é uma hospedaria familiar que combina simplicidade com conforto. Vista para o mar, quartos arejados e ambiente descontraído. Ideal para surfistas, mochileiros e famílias. Dona Maria é conhecida pela hospitalidade calorosa e pelo delicioso peixe grelhado servido no terraço.',
                    'address' => 'Avenida Marginal, Praia Morena, Benguela',
                    'stars' => 2, 'rating' => 4.4, 'reviews_count' => 128, 'min_price' => 18000, 'is_featured' => false,
                    'latitude' => -12.6083, 'longitude' => 13.4056,
                    'phone' => '+244 272 456 789', 'email' => 'marisolguest@hotmail.com',
                    'thumbnail' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&q=80',
                    'images' => [
                        'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=1200&q=80',
                        'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?w=1200&q=80',
                        'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200&q=80',
                    ],
                    'amenities' => ['WiFi gratuito', 'Vista para o mar', 'Terraço', 'Cozinha partilhada', 'Acesso direto à praia', 'Armazenamento de pranchas de surf'],
                ],
            ];
            foreach ($hotels as $data) {
                $this->createHotel($data, $admin, $benguela);
            }
        }

        // ── LOBITO ────────────────────────────────────────────────────
        $lobito = Location::where('name', 'Lobito')->first();
        if ($lobito) {
            $hotels = [
                [
                    'name' => 'Hotel Restinga Lobito',
                    'property_type' => 'hotel',
                    'description' => 'Situado na famosa Restinga de Lobito, uma estreita faixa de terra entre o Oceano Atlântico e a Baía de Lobito, o Hotel Restinga oferece vistas únicas sobre as águas. Quartos com varanda, restaurante de frutos do mar, bar e acesso direto à praia. Um dos destinos mais apreciados da costa angolana.',
                    'address' => 'Restinga, Lobito',
                    'stars' => 4, 'rating' => 4.5, 'reviews_count' => 312, 'min_price' => 60000, 'is_featured' => true,
                    'latitude' => -12.3644, 'longitude' => 13.5361,
                    'phone' => '+244 272 522 000', 'email' => 'restinga@hotelrestinga.ao',
                    'thumbnail' => 'https://images.unsplash.com/photo-1445019980597-93fa8acb246c?w=800&q=80',
                    'images' => [
                        'https://images.unsplash.com/photo-1445019980597-93fa8acb246c?w=1200&q=80',
                        'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&q=80',
                        'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1200&q=80',
                        'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80',
                    ],
                    'amenities' => ['WiFi gratuito', 'Praia privativa', 'Restaurante frutos do mar', 'Bar', 'Vista para o mar', 'Estacionamento'],
                ],
                [
                    'name' => 'Hotel Porto de Lobito',
                    'property_type' => 'hotel',
                    'description' => 'Hotel comercial bem localizado no centro de Lobito, a poucos minutos do porto e da estação ferroviária. Quartos funcionais e bem equipados, restaurante com cozinha variada, bar e salas de reunião. Ideal para viajantes de negócios ligados à actividade portuária e comercial de Lobito.',
                    'address' => 'Avenida da Independência, Lobito',
                    'stars' => 3, 'rating' => 4.0, 'reviews_count' => 198, 'min_price' => 38000, 'is_featured' => false,
                    'latitude' => -12.3700, 'longitude' => 13.5400,
                    'phone' => '+244 272 510 300', 'email' => 'porto@hotelporto.ao',
                    'thumbnail' => 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800&q=80',
                    'images' => [
                        'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=1200&q=80',
                        'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=1200&q=80',
                        'https://images.unsplash.com/photo-1583037189850-1921ae7c6c22?w=1200&q=80',
                    ],
                    'amenities' => ['WiFi gratuito', 'Restaurante', 'Bar', 'Salas de reunião', 'Lavandaria', 'Estacionamento'],
                ],
                [
                    'name' => 'Costa do Sol Resort Lobito',
                    'property_type' => 'resort',
                    'description' => 'Resort de charme na costa norte de Lobito, com acesso direto a uma praia de areia dourada e água cristalina. Bangalôs com varanda, piscina, restaurante de cozinha atlântica e actividades aquáticas. Um refúgio perfeito a apenas 30 minutos de Benguela.',
                    'address' => 'Praia do Lobito Norte, Lobito',
                    'stars' => 4, 'rating' => 4.6, 'reviews_count' => 234, 'min_price' => 80000, 'is_featured' => true,
                    'latitude' => -12.3300, 'longitude' => 13.5200,
                    'phone' => '+244 926 512 890', 'email' => 'costadosol@costadosol.ao',
                    'thumbnail' => 'https://images.unsplash.com/photo-1602391833977-358a52198938?w=800&q=80',
                    'images' => [
                        'https://images.unsplash.com/photo-1602391833977-358a52198938?w=1200&q=80',
                        'https://images.unsplash.com/photo-1540541338287-41700207dee6?w=1200&q=80',
                        'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200&q=80',
                        'https://images.unsplash.com/photo-1596436889106-be35e843f974?w=1200&q=80',
                    ],
                    'amenities' => ['Praia privativa', 'Piscina', 'Restaurante', 'Bar', 'Desportos aquáticos', 'Mergulho', 'WiFi gratuito', 'Estacionamento'],
                ],
            ];
            foreach ($hotels as $data) {
                $this->createHotel($data, $admin, $lobito);
            }
        }
    }
}
