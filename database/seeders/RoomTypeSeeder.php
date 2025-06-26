<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obter todos os hotéis cadastrados
        $hotels = Hotel::all();
        
        foreach ($hotels as $hotel) {
            // Tipos de quarto padrão para hotéis de 3 estrelas
            if ($hotel->stars == 3) {
                // Quarto Standard
                RoomType::create([
                    'hotel_id' => $hotel->id,
                    'name' => 'Quarto Standard',
                    'description' => 'Quarto confortável com cama de casal ou duas camas de solteiro, ar-condicionado, TV a cabo, frigobar e banheiro privativo com chuveiro. Ideal para viajantes que buscam conforto com bom custo-benefício.',
                    'capacity' => 2,
                    'beds' => 1,
                    'bed_type' => 'Casal ou Twin',
                    'size' => 22,
                    'amenities' => json_encode([
                        'Ar-condicionado', 'TV a cabo', 'Frigobar', 'Wi-Fi', 'Telefone', 'Mesa de trabalho', 'Secador de cabelo'
                    ]),
                    'images' => json_encode([
                        'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'https://images.unsplash.com/photo-1618773928121-c32242e63f39?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
                    ]),
                    'is_available' => true,
                    'base_price' => $hotel->min_price,
                    'rooms_count' => 20,
                    'is_featured' => false
                ]);
                
                // Quarto Superior
                RoomType::create([
                    'hotel_id' => $hotel->id,
                    'name' => 'Quarto Superior',
                    'description' => 'Quarto mais espaçoso com vista para a cidade ou área externa do hotel, cama queen-size, ar-condicionado, TV a cabo, frigobar, cafeteira e banheiro privativo com amenidades. Ideal para casais ou viajantes que buscam mais conforto.',
                    'capacity' => 2,
                    'beds' => 1,
                    'bed_type' => 'Queen',
                    'size' => 28,
                    'amenities' => json_encode([
                        'Ar-condicionado', 'TV a cabo', 'Frigobar', 'Wi-Fi', 'Telefone', 'Mesa de trabalho', 
                        'Secador de cabelo', 'Cafeteira', 'Roupões', 'Vista para a cidade'
                    ]),
                    'images' => json_encode([
                        'https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'https://images.unsplash.com/photo-1566665797739-1674de7a421a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
                    ]),
                    'is_available' => true,
                    'base_price' => $hotel->min_price * 1.3,
                    'rooms_count' => 15,
                    'is_featured' => true
                ]);
            }
            
            // Tipos de quarto padrão para hotéis de 4 estrelas
            if ($hotel->stars == 4) {
                // Quarto Standard
                RoomType::create([
                    'hotel_id' => $hotel->id,
                    'name' => 'Quarto Standard',
                    'description' => 'Quarto elegante com cama de casal ou duas camas de solteiro, ar-condicionado, TV a cabo, frigobar, cofre digital e banheiro privativo com amenidades de qualidade. Ideal para viajantes que buscam conforto e praticidade.',
                    'capacity' => 2,
                    'beds' => 1,
                    'bed_type' => 'Casal ou Twin',
                    'size' => 25,
                    'amenities' => json_encode([
                        'Ar-condicionado', 'TV a cabo', 'Frigobar', 'Wi-Fi de alta velocidade', 'Telefone', 
                        'Mesa de trabalho', 'Secador de cabelo', 'Cofre digital', 'Roupões', 'Chinelos'
                    ]),
                    'images' => json_encode([
                        'https://images.unsplash.com/photo-1611892440504-42a792e24d32?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'https://images.unsplash.com/photo-1587985064135-0366536eab42?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
                    ]),
                    'is_available' => true,
                    'base_price' => $hotel->min_price,
                    'rooms_count' => 25,
                    'is_featured' => false
                ]);
                
                // Quarto Deluxe
                RoomType::create([
                    'hotel_id' => $hotel->id,
                    'name' => 'Quarto Deluxe',
                    'description' => 'Quarto amplo e sofisticado com cama king-size, ar-condicionado, TV Smart, frigobar, cafeteira Nespresso, área de estar e banheiro espaçoso com banheira ou chuveiro de efeito chuva. Ideal para casais ou executivos que buscam conforto premium.',
                    'capacity' => 2,
                    'beds' => 1,
                    'bed_type' => 'King',
                    'size' => 35,
                    'amenities' => json_encode([
                        'Ar-condicionado', 'Smart TV', 'Frigobar', 'Wi-Fi de alta velocidade', 'Telefone', 
                        'Mesa de trabalho', 'Secador de cabelo', 'Cafeteira Nespresso', 'Banheira ou Chuveiro Premium', 
                        'Roupões', 'Chinelos', 'Kit de Amenidades Premium', 'Cofre digital', 'Área de estar'
                    ]),
                    'images' => json_encode([
                        'https://images.unsplash.com/photo-1566195992011-5f6b21e539aa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'https://images.unsplash.com/photo-1560185127-6ed189bf02f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
                    ]),
                    'is_available' => true,
                    'base_price' => $hotel->min_price * 1.5,
                    'rooms_count' => 15,
                    'is_featured' => true
                ]);
                
                // Suite Executiva
                RoomType::create([
                    'hotel_id' => $hotel->id,
                    'name' => 'Suite Executiva',
                    'description' => 'Suite elegante com quarto e sala de estar separados, cama king-size, sofá-cama, ar-condicionado, duas Smart TVs, frigobar, cafeteira Nespresso, mesa de trabalho e banheiro luxuoso com banheira. Ideal para executivos, famílias pequenas ou casais que buscam uma experiência premium.',
                    'capacity' => 3,
                    'beds' => 1,
                    'bed_type' => 'King + Sofá-cama',
                    'size' => 50,
                    'amenities' => json_encode([
                        'Ar-condicionado', '2 Smart TVs', 'Frigobar', 'Wi-Fi de alta velocidade', 'Telefone', 
                        'Mesa de trabalho', 'Secador de cabelo', 'Cafeteira Nespresso', 'Banheira de hidromassagem', 
                        'Roupões', 'Chinelos', 'Kit de Amenidades Premium', 'Cofre digital', 'Sala de estar separada',
                        'Sofá-cama', 'Vista premium'
                    ]),
                    'images' => json_encode([
                        'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'https://images.unsplash.com/photo-1540518614846-7eded433c457?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
                    ]),
                    'is_available' => true,
                    'base_price' => $hotel->min_price * 2.2,
                    'rooms_count' => 8,
                    'is_featured' => true
                ]);
            }
            
            // Tipos de quarto padrão para hotéis de 5 estrelas
            if ($hotel->stars == 5) {
                // Quarto Premium
                RoomType::create([
                    'hotel_id' => $hotel->id,
                    'name' => 'Quarto Premium',
                    'description' => 'Quarto luxuoso com design sofisticado, cama king-size com roupa de cama egípcia, ar-condicionado digital, Smart TV 55", sistema de som Bluetooth, frigobar, cafeteira Nespresso e banheiro espaçoso com amenidades de luxo. Experiência de alto padrão para hóspedes exigentes.',
                    'capacity' => 2,
                    'beds' => 1,
                    'bed_type' => 'King',
                    'size' => 40,
                    'amenities' => json_encode([
                        'Ar-condicionado digital', 'Smart TV 55"', 'Sistema de som Bluetooth', 'Frigobar', 
                        'Wi-Fi de alta velocidade', 'Telefone', 'Mesa de trabalho', 'Secador de cabelo profissional', 
                        'Cafeteira Nespresso', 'Chuveiro efeito chuva', 'Roupões de algodão egípcio', 
                        'Chinelos', 'Kit de Amenidades de Luxo', 'Cofre digital', 'Menu de travesseiros'
                    ]),
                    'images' => json_encode([
                        'https://images.unsplash.com/photo-1590490359683-658d3d23f972?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'https://images.unsplash.com/photo-1595576508898-0ad5c879a061?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
                    ]),
                    'is_available' => true,
                    'base_price' => $hotel->min_price,
                    'rooms_count' => 30,
                    'is_featured' => false
                ]);
                
                // Suite Luxo
                RoomType::create([
                    'hotel_id' => $hotel->id,
                    'name' => 'Suite Luxo',
                    'description' => 'Suite de luxo com quarto e sala de estar integrados, cama king-size com roupa de cama premium, ar-condicionado digital, Smart TV 65", sistema de som Bose, frigobar, adega climatizada, cafeteira Nespresso, área de trabalho e banheiro em mármore com banheira de hidromassagem e chuveiro separado. Experiência de alto luxo.',
                    'capacity' => 2,
                    'beds' => 1,
                    'bed_type' => 'King',
                    'size' => 60,
                    'amenities' => json_encode([
                        'Ar-condicionado digital', 'Smart TV 65"', 'Sistema de som Bose', 'Frigobar', 'Adega climatizada',
                        'Wi-Fi de alta velocidade', 'Telefone', 'Escritório', 'Secador de cabelo profissional', 
                        'Cafeteira Nespresso', 'Banheira de hidromassagem', 'Chuveiro efeito chuva', 
                        'Roupões de algodão egípcio', 'Chinelos', 'Kit de Amenidades de Luxo', 
                        'Cofre digital', 'Menu de travesseiros', 'Serviço de mordomo (sob demanda)'
                    ]),
                    'images' => json_encode([
                        'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
                    ]),
                    'is_available' => true,
                    'base_price' => $hotel->min_price * 1.8,
                    'rooms_count' => 15,
                    'is_featured' => true
                ]);
                
                // Suite Presidencial
                RoomType::create([
                    'hotel_id' => $hotel->id,
                    'name' => 'Suite Presidencial',
                    'description' => 'A mais luxuosa acomodação do hotel, com ampla sala de estar, sala de jantar, quarto principal com cama king-size, segundo quarto opcional, banheiro em mármore com banheira de hidromassagem e vista panorâmica. Oferece serviço de mordomo 24h, bar privativo e acesso VIP a todas as facilidades do hotel. A escolha definitiva para uma experiência incomparável.',
                    'capacity' => 4,
                    'beds' => 2,
                    'bed_type' => 'King + Twin',
                    'size' => 120,
                    'amenities' => json_encode([
                        'Ar-condicionado digital em todas as salas', 'Smart TVs em todos os ambientes', 'Sistema de som integrado', 
                        'Bar privativo', 'Adega climatizada', 'Wi-Fi ultra-rápido', 'Telefones em todos os cômodos', 
                        'Escritório completo', 'Sala de jantar', 'Banheira de hidromassagem', 'Sauna privativa', 
                        'Chuveiro efeito chuva', 'Roupões e toalhas premium', 'Kit de Amenidades de Luxo', 
                        'Cofre digital', 'Menu de travesseiros', 'Serviço de mordomo 24h', 'Vista panorâmica',
                        'Transfer de/para aeroporto', 'Acesso VIP a todas facilidades do hotel'
                    ]),
                    'images' => json_encode([
                        'https://images.unsplash.com/photo-1551633550-64761302015c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'https://images.unsplash.com/photo-1629140727571-9b5c6f6267b4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
                    ]),
                    'is_available' => true,
                    'base_price' => $hotel->min_price * 4,
                    'rooms_count' => 2,
                    'is_featured' => true
                ]);
            }
        }
    }
}
