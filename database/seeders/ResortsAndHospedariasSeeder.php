<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Hotel;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Str;

class ResortsAndHospedariasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar um usuário admin para associar aos hotéis
        $admin = User::whereHas('roles', function($query) {
            $query->where('name', 'Admin');
        })->first();
        
        if (!$admin) {
            $this->command->error('Nenhum usuário Admin encontrado. Execute o UserSeeder primeiro.');
            return;
        }
        
        // Buscar ou criar localizações
        $luanda = Location::firstOrCreate(
            ['name' => 'Luanda'],
            ['province' => 'Luanda', 'is_featured' => true]
        );
        
        $benguela = Location::firstOrCreate(
            ['name' => 'Benguela'],
            ['province' => 'Benguela', 'is_featured' => true]
        );
        
        $lubango = Location::firstOrCreate(
            ['name' => 'Lubango'],
            ['province' => 'Huíla', 'is_featured' => true]
        );
        
        $namibe = Location::firstOrCreate(
            ['name' => 'Namibe'],
            ['province' => 'Namibe', 'is_featured' => false]
        );
        
        $huambo = Location::firstOrCreate(
            ['name' => 'Huambo'],
            ['province' => 'Huambo', 'is_featured' => true]
        );
        
        // CRIAR 3 RESORTS
        $this->createResorts($admin, $luanda, $benguela, $lubango);
        
        // CRIAR 3 HOSPEDARIAS
        $this->createHospedarias($admin, $namibe, $huambo, $benguela);
        
        $this->command->info('✅ 3 Resorts e 3 Hospedarias criados com sucesso!');
    }
    
    private function createResorts($admin, $luanda, $benguela, $lubango)
    {
        // RESORT 1: Mussulo Bay Resort - Luanda
        Hotel::create([
            'name' => 'Mussulo Bay Resort & Spa',
            'property_type' => 'resort',
            'slug' => Str::slug('Mussulo Bay Resort Spa'),
            'description' => 'Situado na paradisíaca Ilha do Mussulo, o Mussulo Bay Resort & Spa é um refúgio de luxo que combina sofisticação com a beleza natural de Angola. Com vistas deslumbrantes para o Oceano Atlântico, oferecemos uma experiência inesquecível de relaxamento e conforto. Desfrute de piscinas infinitas, spa de classe mundial, restaurantes gourmet e acesso privado à praia. Perfeito para lua de mel, eventos corporativos ou simplesmente para quem busca o melhor em hospitalidade.',
            'address' => 'Ilha do Mussulo, Baía de Luanda',
            'location_id' => $luanda->id,
            'user_id' => $admin->id,
            'stars' => 5,
            'phone' => '+244 222 123 456',
            'email' => 'reservas@mussulobayresort.ao',
            'website' => 'https://mussulobayresort.ao',
            'latitude' => -8.935157,
            'longitude' => 13.184315,
            'check_in_time' => '15:00',
            'check_out_time' => '12:00',
            'min_price' => 150000,
            'rating' => 4.9,
            'reviews_count' => 287,
            'is_featured' => true,
            'is_active' => true,
            'accept_transfer' => true,
            'accept_tpa_onsite' => true,
            'transfer_instructions' => 'Transferência bancária para conta do resort. Após confirmação, enviaremos voucher por email.',
            'bank_name' => 'Banco BAI',
            'account_number' => '0012345678901',
            'iban' => 'AO06000400000012345678901',
            'thumbnail' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800',
            'images' => [
                'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200',
                'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=1200',
                'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200',
                'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=1200',
                'https://images.unsplash.com/photo-1584132967334-10e028bd69f7?w=1200'
            ],
            'amenities' => [
                'WiFi gratuito em todo o resort',
                'Piscina infinita com vista para o mar',
                'Spa & Centro de Wellness',
                'Restaurante gourmet à beira-mar',
                'Bar na piscina',
                'Ginásio completo 24h',
                'Praia privativa',
                'Concierge 24h',
                'Estacionamento gratuito',
                'Transfer aeroporto',
                'Kids Club',
                'Campo de ténis',
                'Sala de conferências',
                'Serviço de quarto 24h'
            ]
        ]);
        
        // RESORT 2: Praia Morena Resort - Benguela
        Hotel::create([
            'name' => 'Praia Morena Eco Resort',
            'property_type' => 'resort',
            'slug' => Str::slug('Praia Morena Eco Resort'),
            'description' => 'Localizado na deslumbrante costa de Benguela, o Praia Morena Eco Resort é pioneiro em turismo sustentável em Angola. Construído com materiais ecológicos e operando com energia solar, oferecemos luxo consciente sem comprometer o conforto. Mergulhe nas nossas piscinas naturais, explore trilhas ecológicas, ou simplesmente relaxe nas nossas cabanas de praia premium. Com restaurante orgânico farm-to-table e programas de conservação marinha, é o destino perfeito para viajantes conscientes.',
            'address' => 'Baía Farta, Praia Morena',
            'location_id' => $benguela->id,
            'user_id' => $admin->id,
            'stars' => 5,
            'phone' => '+244 272 345 678',
            'email' => 'info@praiamorenare sort.ao',
            'website' => 'https://praiamorenaresort.ao',
            'latitude' => -12.542823,
            'longitude' => 13.391829,
            'check_in_time' => '14:00',
            'check_out_time' => '11:00',
            'min_price' => 120000,
            'rating' => 4.8,
            'reviews_count' => 156,
            'is_featured' => true,
            'is_active' => true,
            'accept_transfer' => true,
            'accept_tpa_onsite' => true,
            'transfer_instructions' => 'Pagamento antecipado via transferência. Confirmação em 24h.',
            'bank_name' => 'BFA',
            'account_number' => '0098765432109',
            'iban' => 'AO06001200000098765432109',
            'thumbnail' => 'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?w=800',
            'images' => [
                'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?w=1200',
                'https://images.unsplash.com/photo-1540541338287-41700207dee6?w=1200',
                'https://images.unsplash.com/photo-1568084680786-a84f91d1153c?w=1200',
                'https://images.unsplash.com/photo-1602002418082-a4443e081dd1?w=1200',
                'https://images.unsplash.com/photo-1596436889106-be35e843f974?w=1200'
            ],
            'amenities' => [
                'WiFi de alta velocidade',
                'Piscinas naturais aquecidas',
                'Spa ecológico',
                'Restaurante orgânico',
                'Bar de sumos naturais',
                'Centro de yoga & meditação',
                'Praia privativa ecológica',
                'Passeios de bicicleta',
                'Observação de golfinhos',
                'Trilhas na natureza',
                'Aulas de surf',
                'Jardim orgânico',
                'Biblioteca',
                'Estacionamento solar'
            ]
        ]);
        
        // RESORT 3: Serra da Leba Mountain Resort - Lubango
        Hotel::create([
            'name' => 'Serra da Leba Mountain Resort',
            'property_type' => 'resort',
            'slug' => Str::slug('Serra da Leba Mountain Resort'),
            'description' => 'Empoleirado nas majestosas montanhas da Huíla, o Serra da Leba Mountain Resort oferece uma experiência única de montanha com vistas panorâmicas de cortar a respiração. Inspire-se com o ar puro da serra, desfrute de trilhas exclusivas pela natureza, e relaxe no nosso spa alpino com tratamentos inspirados na flora local. Com arquitetura que harmoniza com a paisagem natural e gastronomia que celebra os sabores regionais, é o refúgio perfeito para quem busca tranquilidade e aventura.',
            'address' => 'Serra da Leba, Via Lubango-Namibe',
            'location_id' => $lubango->id,
            'user_id' => $admin->id,
            'stars' => 5,
            'phone' => '+244 261 234 567',
            'email' => 'reservations@serradaleba.ao',
            'website' => 'https://serradaleba.ao',
            'latitude' => -14.935789,
            'longitude' => 13.482456,
            'check_in_time' => '15:00',
            'check_out_time' => '12:00',
            'min_price' => 95000,
            'rating' => 4.9,
            'reviews_count' => 203,
            'is_featured' => true,
            'is_active' => true,
            'accept_transfer' => true,
            'accept_tpa_onsite' => true,
            'transfer_instructions' => 'Transferência aceita com 48h de antecedência. Envie comprovativo para confirmar.',
            'bank_name' => 'Banco Angolano de Investimentos',
            'account_number' => '0011223344556',
            'iban' => 'AO06004300000011223344556',
            'thumbnail' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800',
            'images' => [
                'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200',
                'https://images.unsplash.com/photo-1519904981063-b0cf448d479e?w=1200',
                'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=1200',
                'https://images.unsplash.com/photo-1551632811-561732d1e306?w=1200',
                'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=1200'
            ],
            'amenities' => [
                'WiFi em todas as áreas',
                'Piscina aquecida coberta',
                'Spa alpino & sauna',
                'Restaurante panorâmico',
                'Wine bar',
                'Sala de jogos',
                'Trilhas guiadas',
                'Observatório de estrelas',
                'Fogueira ao ar livre',
                'Biblioteca & sala de leitura',
                'Aluguel de bicicletas de montanha',
                'Passeios 4x4',
                'Centro de conferências',
                'Estacionamento coberto'
            ]
        ]);
        
        $this->command->info('✅ 3 Resorts criados');
    }
    
    private function createHospedarias($admin, $namibe, $huambo, $benguela)
    {
        // HOSPEDARIA 1: Casa do Deserto - Namibe
        Hotel::create([
            'name' => 'Casa do Deserto - Hospedaria Boutique',
            'property_type' => 'hospedaria',
            'slug' => Str::slug('Casa do Deserto Hospedaria Boutique'),
            'description' => 'No coração do Namibe, a Casa do Deserto é uma hospedaria boutique que celebra a cultura e tradições locais. Com apenas 8 quartos decorados com artesanato angolano, oferecemos uma experiência íntima e acolhedora. Desfrute do nosso terraço com vista para o deserto, saboreie refeições caseiras preparadas com ingredientes locais, e deixe-se envolver pela hospitalidade genuína. Perfeito para viajantes que buscam autenticidade e conexão com a comunidade local.',
            'address' => 'Rua da Liberdade, Centro Histórico',
            'location_id' => $namibe->id,
            'user_id' => $admin->id,
            'stars' => 3,
            'phone' => '+244 264 123 456',
            'email' => 'contato@casadodeserto.ao',
            'website' => null,
            'latitude' => -15.194444,
            'longitude' => 12.155556,
            'check_in_time' => '14:00',
            'check_out_time' => '11:00',
            'min_price' => 25000,
            'rating' => 4.6,
            'reviews_count' => 89,
            'is_featured' => false,
            'is_active' => true,
            'accept_transfer' => true,
            'accept_tpa_onsite' => false,
            'transfer_instructions' => 'Transferência para conta pessoal. Confirme com 2 dias de antecedência.',
            'bank_name' => 'BPC',
            'account_number' => '0055667788990',
            'iban' => null,
            'thumbnail' => 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=800',
            'images' => [
                'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=1200',
                'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=1200',
                'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=1200'
            ],
            'amenities' => [
                'WiFi gratuito',
                'Pequeno-almoço incluído',
                'Terraço panorâmico',
                'Cozinha partilhada',
                'Sala de estar comum',
                'Biblioteca',
                'Estacionamento gratuito',
                'Tours locais organizados',
                'Artesanato local à venda'
            ]
        ]);
        
        // HOSPEDARIA 2: Quinta das Acácias - Huambo
        Hotel::create([
            'name' => 'Quinta das Acácias - Hospedaria Rural',
            'property_type' => 'hospedaria',
            'slug' => Str::slug('Quinta das Acacias Hospedaria Rural'),
            'description' => 'Situada numa quinta centenária no planalto do Huambo, a Quinta das Acácias oferece uma experiência rural autêntica. Rodeada por jardins de acácias floridos e campos de cultivo orgânico, é o refúgio perfeito para quem busca paz e contacto com a natureza. Participe na colheita de legumes, desfrute de refeições preparadas com produtos da quinta, ou simplesmente relaxe à sombra das árvores centenárias. Hospitalidade familiar num ambiente bucólico.',
            'address' => 'Fazenda do Planalto, Km 15 Via Huambo-Bailundo',
            'location_id' => $huambo->id,
            'user_id' => $admin->id,
            'stars' => 3,
            'phone' => '+244 241 987 654',
            'email' => 'quintaacacias@gmail.com',
            'website' => null,
            'latitude' => -12.776189,
            'longitude' => 15.738888,
            'check_in_time' => '13:00',
            'check_out_time' => '11:00',
            'min_price' => 18000,
            'rating' => 4.7,
            'reviews_count' => 62,
            'is_featured' => false,
            'is_active' => true,
            'accept_transfer' => true,
            'accept_tpa_onsite' => false,
            'transfer_instructions' => 'Transferência aceita. Contacte-nos para detalhes bancários.',
            'bank_name' => 'Banco Sol',
            'account_number' => '0099887766554',
            'iban' => null,
            'thumbnail' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800',
            'images' => [
                'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200',
                'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=1200',
                'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?w=1200'
            ],
            'amenities' => [
                'WiFi nas áreas comuns',
                'Pequeno-almoço caseiro incluído',
                'Jardim orgânico',
                'Granja com animais',
                'Passeios a cavalo',
                'Ciclismo',
                'Piscina natural',
                'Churrasqueira ao ar livre',
                'Produtos da quinta à venda',
                'Estacionamento amplo'
            ]
        ]);
        
        // HOSPEDARIA 3: Marisol Guest House - Benguela
        Hotel::create([
            'name' => 'Marisol Guest House',
            'property_type' => 'hospedaria',
            'slug' => Str::slug('Marisol Guest House'),
            'description' => 'A poucos passos da praia em Benguela, a Marisol Guest House é uma hospedaria familiar que combina simplicidade com conforto. Com vista para o mar, quartos arejados e um ambiente descontraído, é ideal para surfistas, mochileiros e famílias que procuram uma base acessível para explorar as maravilhas de Benguela. Nossa anfitriã, Dona Maria, é conhecida pela sua hospitalidade calorosa e pelo delicioso peixe grelhado servido no terraço.',
            'address' => 'Avenida Marginal, Praia Morena',
            'location_id' => $benguela->id,
            'user_id' => $admin->id,
            'stars' => 2,
            'phone' => '+244 272 456 789',
            'email' => 'marisolguest@hotmail.com',
            'website' => null,
            'latitude' => -12.608333,
            'longitude' => 13.405556,
            'check_in_time' => '14:00',
            'check_out_time' => '10:00',
            'min_price' => 12000,
            'rating' => 4.4,
            'reviews_count' => 128,
            'is_featured' => false,
            'is_active' => true,
            'accept_transfer' => false,
            'accept_tpa_onsite' => false,
            'transfer_instructions' => null,
            'bank_name' => null,
            'account_number' => null,
            'iban' => null,
            'thumbnail' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800',
            'images' => [
                'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=1200',
                'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=1200',
                'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200'
            ],
            'amenities' => [
                'WiFi gratuito',
                'Vista para o mar',
                'Terraço partilhado',
                'Cozinha de uso comum',
                'Pequeno-almoço simples',
                'Armazenamento de pranchas de surf',
                'Chuveiro exterior',
                'Estacionamento na rua',
                'Acesso direto à praia'
            ]
        ]);
        
        $this->command->info('✅ 3 Hospedarias criadas');
    }
}
