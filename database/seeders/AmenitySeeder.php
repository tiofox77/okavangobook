<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpar registos existentes para evitar duplicação
        DB::table('amenities')->truncate();
        
        // Comodidades de Hotel
        $hotelAmenities = [
            ['name' => 'Wi-Fi Grátis', 'icon' => 'fas fa-wifi', 'description' => 'Internet sem fios gratuita em todas as áreas', 'display_order' => 10],
            ['name' => 'Estacionamento', 'icon' => 'fas fa-parking', 'description' => 'Estacionamento privativo', 'display_order' => 20],
            ['name' => 'Pequeno-almoço', 'icon' => 'fas fa-coffee', 'description' => 'Pequeno-almoço disponível', 'display_order' => 30],
            ['name' => 'Piscina', 'icon' => 'fas fa-swimming-pool', 'description' => 'Piscina exterior ou interior', 'display_order' => 40],
            ['name' => 'Restaurante', 'icon' => 'fas fa-utensils', 'description' => 'Restaurante no local', 'display_order' => 50],
            ['name' => 'Bar', 'icon' => 'fas fa-glass-martini-alt', 'description' => 'Bar/lounge', 'display_order' => 60],
            ['name' => 'Ginásio', 'icon' => 'fas fa-dumbbell', 'description' => 'Centro de fitness', 'display_order' => 70],
            ['name' => 'Spa', 'icon' => 'fas fa-spa', 'description' => 'Serviços de spa e bem-estar', 'display_order' => 80],
            ['name' => 'Ar condicionado', 'icon' => 'fas fa-snowflake', 'description' => 'Controlo de temperatura', 'display_order' => 90],
            ['name' => 'Serviço de quarto', 'icon' => 'fas fa-concierge-bell', 'description' => 'Serviço de quarto disponível', 'display_order' => 100],
            ['name' => 'Recepção 24h', 'icon' => 'fas fa-clock', 'description' => 'Recepção aberta 24 horas', 'display_order' => 110],
            ['name' => 'Acessibilidade', 'icon' => 'fas fa-wheelchair', 'description' => 'Instalações para hóspedes com mobilidade reduzida', 'display_order' => 120],
            ['name' => 'Elevador', 'icon' => 'fas fa-angle-double-up', 'description' => 'Elevador disponível', 'display_order' => 130],
            ['name' => 'Sala de reuniões', 'icon' => 'fas fa-briefcase', 'description' => 'Salas para eventos e reuniões', 'display_order' => 140],
            ['name' => 'Espaço para crianças', 'icon' => 'fas fa-baby', 'description' => 'Área de jogos para crianças', 'display_order' => 150],
        ];
        
        // Comodidades de Quarto
        $roomAmenities = [
            ['name' => 'Wi-Fi no quarto', 'icon' => 'fas fa-wifi', 'description' => 'Internet sem fios no quarto', 'display_order' => 10],
            ['name' => 'TV de ecrã plano', 'icon' => 'fas fa-tv', 'description' => 'Televisão de ecrã plano', 'display_order' => 20],
            ['name' => 'Ar condicionado', 'icon' => 'fas fa-snowflake', 'description' => 'Controlo de temperatura individual', 'display_order' => 30],
            ['name' => 'Mini-bar', 'icon' => 'fas fa-glass-whiskey', 'description' => 'Refrigerador com bebidas', 'display_order' => 40],
            ['name' => 'Casa de banho privativa', 'icon' => 'fas fa-bath', 'description' => 'Casa de banho privativa dentro do quarto', 'display_order' => 50],
            ['name' => 'Secador de cabelo', 'icon' => 'fas fa-wind', 'description' => 'Secador disponível na casa de banho', 'display_order' => 60],
            ['name' => 'Amenities de banho', 'icon' => 'fas fa-pump-soap', 'description' => 'Produtos de higiene pessoal', 'display_order' => 70],
            ['name' => 'Roupões e chinelos', 'icon' => 'fas fa-socks', 'description' => 'Roupões de banho e chinelos', 'display_order' => 80],
            ['name' => 'Cofre', 'icon' => 'fas fa-lock', 'description' => 'Cofre no quarto', 'display_order' => 90],
            ['name' => 'Mesa de trabalho', 'icon' => 'fas fa-desk', 'description' => 'Escritório/área de trabalho', 'display_order' => 100],
            ['name' => 'Ferro de engomar', 'icon' => 'fas fa-tshirt', 'description' => 'Ferro e tábua de engomar disponível', 'display_order' => 110],
            ['name' => 'Telefone', 'icon' => 'fas fa-phone-alt', 'description' => 'Telefone no quarto', 'display_order' => 120],
            ['name' => 'Máquina de café', 'icon' => 'fas fa-coffee', 'description' => 'Facilidades para preparação de café/chá', 'display_order' => 130],
            ['name' => 'Varanda/Terraço', 'icon' => 'fas fa-door-open', 'description' => 'Varanda ou terraço privativo', 'display_order' => 140],
        ];
        
        // Inserir comodidades de hotel
        foreach ($hotelAmenities as $amenity) {
            Amenity::create([
                'name' => $amenity['name'], 
                'icon' => $amenity['icon'], 
                'type' => Amenity::TYPE_HOTEL, 
                'description' => $amenity['description'],
                'is_active' => true,
                'display_order' => $amenity['display_order']
            ]);
        }
        
        // Inserir comodidades de quarto
        foreach ($roomAmenities as $amenity) {
            Amenity::create([
                'name' => $amenity['name'], 
                'icon' => $amenity['icon'], 
                'type' => Amenity::TYPE_ROOM, 
                'description' => $amenity['description'],
                'is_active' => true,
                'display_order' => $amenity['display_order']
            ]);
        }
        
        $this->command->info('Foram inseridas ' . count($hotelAmenities) . ' comodidades de hotel e ' . 
                              count($roomAmenities) . ' comodidades de quarto.');
    }
}
