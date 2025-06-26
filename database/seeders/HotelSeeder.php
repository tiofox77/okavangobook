<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desativar os hotéis existentes em vez de removê-los, para preservar as referências
        // Não podemos usar truncate devido às chaves estrangeiras
        Hotel::query()->update(['is_active' => false]);
        
        // Remover os hotéis existentes que não têm relacionamentos
        // Se for seguro remover (sem relacionamentos), removemos
        $hotelsToDelete = Hotel::whereDoesntHave('roomTypes')->get();
        foreach ($hotelsToDelete as $hotel) {
            $hotel->delete();
        }
        
        // Vamos obter as localizações que já foram cadastradas para associar os hotéis
        $luanda = Location::where('name', 'Luanda')->first();
        $benguela = Location::where('name', 'Benguela')->first();
        $lubango = Location::where('name', 'Lubango')->first();
        $namibe = Location::where('name', 'Namibe')->first();
        $huambo = Location::where('name', 'Huambo')->first();
        $malanje = Location::where('name', 'Malanje')->first() ?? Location::create([
            'name' => 'Malanje',
            'province' => 'malanje',
            'description' => 'Província conhecida pelas Quedas de Kalandula e as Pedras Negras de Pungo Andongo.',
            'slug' => 'malanje',
        ]);
        
        // Arrays de dados para geração aleatória
        $amenities = [
            'Wi-Fi Grátis', 'Piscina', 'Spa', 'Academia', 'Restaurante', 'Bar', 'Estacionamento',
            'Serviço de Quarto 24h', 'Business Center', 'Transfer Aeroporto', 'Salas de Conferência',
            'Ar Condicionado', 'Cofre', 'Lavanderia', 'TV a Cabo', 'Minibar', 'Varanda',
            'Vista para o Mar', 'Vista para a Cidade', 'Café da Manhã Incluso', 'Almoço Incluso',
            'Janta Inclusa', 'Clube Infantil', 'Acesso à Praia', 'Jardim', 'Terraço',
            'Espaço para Eventos', 'Acessível para Cadeirantes', 'Pet Friendly'
        ];
        
        // Coletando imagens de hotéis confiáveis do Unsplash, testadas e verificadas
        $hotelImages = [
            // Imagens para miniaturas - moderna
            'thumbnails' => [
                'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Hotel Exterior 1
                'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Hotel Exterior 2
                'https://images.unsplash.com/photo-1564501049412-61c2a3083791?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Hotel Exterior 3
                'https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Hotel Exterior 4
                'https://images.unsplash.com/photo-1445019980597-93fa8acb246c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Hotel Exterior 5
                'https://images.unsplash.com/photo-1596386461350-326ccb383e9f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Hotel Exterior 6
                'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Hotel Entrance
                'https://images.unsplash.com/photo-1618773928121-c32242e63f39?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Hotel Room 1
                'https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Hotel Room 2
                'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'  // Hotel Pool
            ],
            // Imagens de quartos
            'rooms' => [
                'https://images.unsplash.com/photo-1618773928121-c32242e63f39?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Luxury Room
                'https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Standard Room
                'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Deluxe Room
                'https://images.unsplash.com/photo-1611892440504-42a792e24d32?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Suite
                'https://images.unsplash.com/photo-1631049035182-249067d7618e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Twin Room
                'https://images.unsplash.com/photo-1591088398332-8a7791972843?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Single Room
                'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Bathroom
                'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Bed Detail
                'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // View from Room
                'https://images.unsplash.com/photo-1595576508898-0ad5c879a061?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'  // Room Workspace
            ],
            // Imagens de áreas comuns
            'common_areas' => [
                'https://images.unsplash.com/photo-1583037189850-1921ae7c6c22?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Lobby
                'https://images.unsplash.com/photo-1566665797739-1674de7a421a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Restaurant
                'https://images.unsplash.com/photo-1574362848149-11496d93a7c7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Bar
                'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Pool
                'https://images.unsplash.com/photo-1468824357306-a439d58ccb1c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Infinity Pool
                'https://images.unsplash.com/photo-1596436889106-be35e843f974?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Gym
                'https://images.unsplash.com/photo-1519690889869-e705e59f72e1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Spa
                'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Conference Room
                'https://images.unsplash.com/photo-1541123437800-1bb1317badc2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Garden
                'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'  // Event Space
            ],
            // Imagens exteriores/fachadas
            'exterior' => [
                'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Modern Hotel
                'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // High-rise Hotel
                'https://images.unsplash.com/photo-1564501049412-61c2a3083791?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Resort Hotel
                'https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Luxury Hotel
                'https://images.unsplash.com/photo-1445019980597-93fa8acb246c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Beachfront Hotel
                'https://images.unsplash.com/photo-1596386461350-326ccb383e9f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Boutique Hotel
                'https://images.unsplash.com/photo-1573052905904-34ad8c27f0cc?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Colonial Style
                'https://images.unsplash.com/photo-1519449556851-5720b33024e7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Resort View
                'https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', // Hotel at Night
                'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'  // Hotel Entrance
            ]
        ];
        
        // Nomes para geração de hotéis
        $hotelNames = [
            'Luanda' => [
                'EPIC SANA Luanda', 'Hotel Presidente', 'Skyna Hotel', 'Hotel Continental', 'Hotel Alvalade',
                'Hotel Tropic', 'Hotel de Convenções de Talatona', 'Hotel Royal Luanda', 'Thomson Luanda',
                'Diamante Luanda', 'Baía Palace', 'Hotel Palmeiras', 'Royal Plaza', 'Victoria Garden',
                'Luanda Bay Hotel'
            ],
            'Benguela' => [
                'Hotel Praia Morena', 'Hotel Terminus', 'Hotel Mombaka', 'Benguela Hotel', 'Baía Azul',
                'Costa do Sol', 'Hotel Horizonte', 'Kambumbe Lodge', 'Hotel Atlântico', 'Hotel Kalunga',
                'Hotel Carvalho', 'Pousada das Acácias', 'Hotel Baía'
            ],
            'Lubango' => [
                'Hotel Serra da Chela', 'Hotel Lubango', 'Hotel Império', 'Hotel Estrelícia', 'Hotel Casper',
                'Hotel Cristo Rei', 'Hotel Palanca Negra', 'Victoria Garden', 'Hotel Avenida'
            ],
            'Namibe' => [
                'Hotel Namibe', 'Hotel Infitur', 'Hotel Moçâmedes', 'Hotel Flamingo', 'Hotel Bahia',
                'Hotel Horizonte', 'Desert Star'
            ],
            'Huambo' => [
                'Ekuikui Hotel', 'Hotel Huambo', 'Hotel Central', 'Hotel Imperial', 'Pousada do Planalto',
                'Hotel Horizonte', 'Hotel Planalto'
            ],
            'Malanje' => [
                'Hotel Malanje', 'Hotel Quedas de Kalandula', 'Pousada Pedras Negras', 'Hotel Pungo Andongo',
                'Hotel Palanca Negra', 'Rio Kwanza Lodge'
            ]
        ];
        
        // Gerar 50 hotéis distribuídos pelas províncias
        $hotels = [];
        $locations = [
            'Luanda' => $luanda,
            'Benguela' => $benguela,
            'Lubango' => $lubango,
            'Namibe' => $namibe,
            'Huambo' => $huambo,
            'Malanje' => $malanje
        ];
        
        // Quantidade aproximada de hotéis por província
        $hotelsDistribution = [
            'Luanda' => 15,    // 15 hotéis em Luanda
            'Benguela' => 10,  // 10 hotéis em Benguela
            'Lubango' => 8,    // 8 hotéis em Lubango
            'Namibe' => 6,     // 6 hotéis em Namibe
            'Huambo' => 6,     // 6 hotéis em Huambo
            'Malanje' => 5     // 5 hotéis em Malanje
        ];
        
        // Descrições genéricas para compor hotéis
        $descriptionParts = [
            'intro' => [
                'Localizado no coração de',
                'Situado na zona central de',
                'Estrategicamente posicionado em',
                'No centro histórico de',
                'Na parte mais exclusiva de',
                'À beira-mar em',
                'Com vista panorâmica para',
                'A poucos minutos do centro de'
            ],
            'description' => [
                'este hotel oferece uma combinação perfeita de conforto e sofisticação',
                'o hotel proporciona uma experiência única de hospedagem com serviço personalizado',
                'este estabelecimento oferece acomodações modernas e confortáveis',
                'o hotel é conhecido por seu serviço de excelência e instalações premium',
                'este empreendimento combina a cultura local com conforto internacional',
                'o hotel se destaca pela gastronomia refinada e hospitalidade excepcional'
            ],
            'features' => [
                'Os quartos são espaçosos e elegantemente decorados',
                'As acomodações contam com varanda privativa e vista deslumbrante',
                'Todos os apartamentos são equipados com ar-condicionado e internet de alta velocidade',
                'Os quartos combinam o estilo clássico angolano com toques modernos',
                'Cada suíte oferece uma experiência única com decoração personalizada'
            ],
            'amenities' => [
                'O hotel possui piscina, spa e centro fitness de última geração',
                'Entre as comodidades estão restaurante, bar e salas para eventos',
                'As instalações incluem business center, restaurantes temáticos e academia',
                'Para relaxar, os hóspedes podem desfrutar do spa, piscina e área de lazer',
                'O complexo conta com restaurantes de gastronomia internacional e local'
            ],
            'location' => [
                'Ideal para viajantes a negócios e turistas que buscam conforto e conveniência',
                'Perfeito para quem deseja explorar os principais pontos turísticos da região',
                'Excelente opção tanto para viagens de negócios quanto para lazer',
                'Localização privilegiada próxima a pontos turísticos e áreas comerciais',
                'Acesso fácil aos principais atrativos da cidade e região'
            ]
        ];
        
        // Endereços genéricos por província
        $addresses = [
            'Luanda' => ['Rua da Missão', 'Avenida 4 de Fevereiro', 'Largo do Kinaxixi', 'Avenida Lenin', 'Rua Amílcar Cabral', 'Bairro Talatona', 'Avenida Ho Chi Minh'],
            'Benguela' => ['Avenida 10 de Fevereiro', 'Rua Dr. António Agostinho Neto', 'Praia Morena', 'Avenida da Praia', 'Bairro Cassange'],
            'Lubango' => ['Avenida Dr. António Agostinho Neto', 'Rua Pinheiro Chagas', 'Avenida da Serra da Leba', 'Bairro Comercial'],
            'Namibe' => ['Avenida do Mar', 'Rua do Namibe', 'Avenida Eduardo Mondlane', 'Bairro da Praia'],
            'Huambo' => ['Avenida da República', 'Rua Dr. António Agostinho Neto', 'Largo Kizomba', 'Avenida Norton de Matos'],
            'Malanje' => ['Avenida Comandante Dangereux', 'Rua das Quedas', 'Avenida 4 de Abril', 'Bairro das Pedras']
        ];
        
        // Gerar hotéis por província
        foreach ($hotelsDistribution as $province => $count) {
            $location = $locations[$province];
            $provinceNames = $hotelNames[$province];
            
            // Usar nomes da lista ou gerar um nome quando a lista acabar
            for ($i = 0; $i < $count; $i++) {
                $name = isset($provinceNames[$i]) ? $provinceNames[$i] : $province . ' Hotel ' . ($i + 1);
                
                // Gerar descrição completa aleatória
                $description = $descriptionParts['intro'][array_rand($descriptionParts['intro'])] . ' ' . $province . ', ' .
                                $descriptionParts['description'][array_rand($descriptionParts['description'])] . '. ' .
                                $descriptionParts['features'][array_rand($descriptionParts['features'])] . '. ' .
                                $descriptionParts['amenities'][array_rand($descriptionParts['amenities'])] . '. ' .
                                $descriptionParts['location'][array_rand($descriptionParts['location'])] . '.';
                
                // Escolher endereço aleatório
                $address = $addresses[$province][array_rand($addresses[$province])] . ', ' . rand(100, 999) . ', ' . $province;
                
                // Escolher ammenities aleatórios (entre 5 e 12)
                $hotelAmenities = $amenities;
                shuffle($hotelAmenities);
                $hotelAmenities = array_slice($hotelAmenities, 0, rand(5, 12));
                
                // Escolher imagens aleatórias
                $thumbnail = $hotelImages['thumbnails'][array_rand($hotelImages['thumbnails'])];
                
                // Escolher 3-5 imagens para o hotel
                $imageCount = rand(3, 5);
                $images = [];
                $images[] = $hotelImages['rooms'][array_rand($hotelImages['rooms'])];
                $images[] = $hotelImages['common_areas'][array_rand($hotelImages['common_areas'])];
                $images[] = $hotelImages['exterior'][array_rand($hotelImages['exterior'])];
                
                // Adicionar mais imagens aleatórias se necessário
                if ($imageCount > 3) {
                    $allImages = array_merge(
                        $hotelImages['rooms'],
                        $hotelImages['common_areas'],
                        $hotelImages['exterior']
                    );
                    shuffle($allImages);
                    $images = array_merge($images, array_slice($allImages, 0, $imageCount - 3));
                }
                
                // Dados de coordenadas aproximadas por província
                $coordinates = [
                    'Luanda' => ['-8.8', '13.2'], 
                    'Benguela' => ['-12.5', '13.4'], 
                    'Lubango' => ['-14.9', '13.5'], 
                    'Namibe' => ['-15.2', '12.1'], 
                    'Huambo' => ['-12.7', '15.7'],
                    'Malanje' => ['-9.5', '16.3']
                ];
                
                // Adicionar pequena variação às coordenadas
                $latitude = $coordinates[$province][0] + (rand(-100, 100) / 1000);
                $longitude = $coordinates[$province][1] + (rand(-100, 100) / 1000);
                
                // Preço baseado na quantidade de estrelas
                $stars = rand(3, 5);
                $minPrice = 0;
                switch ($stars) {
                    case 3:
                        $minPrice = rand(20000, 35000);
                        break;
                    case 4:
                        $minPrice = rand(35000, 60000);
                        break;
                    case 5:
                        $minPrice = rand(60000, 90000);
                        break;
                }
                
                // Rating (3.5 a 5.0)
                $rating = rand(35, 50) / 10;
                
                // Quantidade de avaliações (10 a 300)
                $reviewsCount = rand(10, 300);
                
                // Adicionar à lista de hotéis
                $hotels[] = [
                    'name' => $name,
                    'description' => $description,
                    'address' => $address,
                    'location_id' => $location->id,
                    'stars' => $stars,
                    'thumbnail' => $thumbnail,
                    'images' => json_encode($images),
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'amenities' => json_encode($hotelAmenities),
                    'check_in_time' => sprintf('%02d:00:00', rand(12, 15)),
                    'check_out_time' => sprintf('%02d:00:00', rand(10, 12)),
                    'phone' => '+244 ' . rand(900, 999) . ' ' . rand(100, 999) . ' ' . rand(100, 999),
                    'email' => 'reservas@' . strtolower(str_replace(' ', '', $name)) . '.co.ao',
                    'website' => 'https://www.' . strtolower(str_replace(' ', '', $name)) . '.co.ao',
                    'min_price' => $minPrice,
                    'rating' => $rating,
                    'reviews_count' => $reviewsCount,
                    'is_featured' => rand(0, 5) == 0, // 20% de chance de ser featured
                    'is_active' => true,
                ];
            }
        }
        
        // Criar os hotéis no banco de dados
        foreach ($hotels as $hotelData) {
            // Gerar slug base a partir do nome
            $baseSlug = Str::slug($hotelData['name']);
            
            // Verificar se já existe um hotel com este slug
            $slugExists = Hotel::where('slug', $baseSlug)->exists();
            
            // Se já existe, adicionar um sufixo único (timestamp + número aleatório)
            if ($slugExists) {
                $uniqueSuffix = time() . '-' . rand(100, 999);
                $hotelData['slug'] = $baseSlug . '-' . $uniqueSuffix;
            } else {
                $hotelData['slug'] = $baseSlug;
            }
            
            // Criar o hotel
            Hotel::create($hotelData);
        }
    }
}
