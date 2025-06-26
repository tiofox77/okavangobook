<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Price;
use App\Models\RoomType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obter todos os tipos de quarto cadastrados
        $roomTypes = RoomType::all();
        $providers = ['Booking.com', 'Expedia', 'Direto', 'Hotels.com', 'Trivago'];
        
        // Definição de datas para preços
        $today = Carbon::today();
        $nextMonth = $today->copy()->addMonth();
        $twoMonths = $today->copy()->addMonths(2);
        $threeMonths = $today->copy()->addMonths(3);
        
        // Definição de temporadas
        $lowSeason = [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()];
        $midSeason = [$nextMonth->copy()->startOfMonth(), $twoMonths->copy()->endOfMonth()];
        $highSeason = [$threeMonths->copy()->startOfMonth(), $threeMonths->copy()->endOfMonth()];
        
        foreach ($roomTypes as $roomType) {
            // Buscar o hotel associado ao tipo de quarto
            $hotel = Hotel::find($roomType->hotel_id);
            
            // Preço base durante a semana em baixa temporada
            $basePrice = $roomType->base_price;
            
            // Criar preços para cada mês (próximos 3 meses)
            $startDate = $today->copy();
            $endDate = $threeMonths->copy();
            
            for ($checkIn = $startDate; $checkIn->lte($endDate); $checkIn->addDay()) {
                // Para cada data de check-in, criar preços para estadias de 1, 2, 3 noites
                foreach ([1, 2, 3] as $nights) {
                    $checkOut = $checkIn->copy()->addDays($nights);
                    
                    // Determinar o preço base de acordo com a temporada e dia da semana
                    $priceMultiplier = 1.0;
                    
                    // Ajuste por temporada
                    if ($checkIn->between($lowSeason[0], $lowSeason[1])) {
                        $priceMultiplier = 1.0; // Temporada baixa - preço base
                    } elseif ($checkIn->between($midSeason[0], $midSeason[1])) {
                        $priceMultiplier = 1.15; // Temporada média - 15% mais caro
                    } elseif ($checkIn->between($highSeason[0], $highSeason[1])) {
                        $priceMultiplier = 1.3; // Temporada alta - 30% mais caro
                    }
                    
                    // Ajuste por dia da semana
                    if ($checkIn->isWeekend()) {
                        $priceMultiplier *= 1.2; // Final de semana - 20% mais caro
                    }
                    
                    // Verificar se é feriado
                    $holidays = [
                        Carbon::create(date('Y'), 12, 25), // Natal
                        Carbon::create(date('Y'), 12, 31), // Ano Novo
                        Carbon::create(date('Y'), 2, 28),  // Carnaval (aproximado)
                        Carbon::create(date('Y'), 4, 21),  // Tiradentes
                        Carbon::create(date('Y'), 5, 1),   // Dia do Trabalho
                        Carbon::create(date('Y'), 11, 2),  // Finados
                        Carbon::create(date('Y'), 11, 15), // Proclamação da República
                    ];
                    
                    foreach ($holidays as $holiday) {
                        if ($checkIn->isSameDay($holiday) || $checkOut->isSameDay($holiday)) {
                            $priceMultiplier *= 1.5; // Feriado - 50% mais caro
                            break;
                        }
                    }
                    
                    // Preço final com ajustes
                    $finalPrice = $basePrice * $priceMultiplier * $nights;
                    
                    // Desconto ocasional
                    $hasDiscount = (rand(1, 10) <= 3); // 30% de chance de ter desconto
                    $originalPrice = $hasDiscount ? $finalPrice * 1.2 : null; // Preço original 20% maior
                    $discountPercentage = $hasDiscount ? 20 : null; // Desconto de 20%
                    
                    // Definir beneficios aleatórios
                    $breakfastIncluded = (rand(1, 10) <= 7); // 70% de chance de incluir café da manhã
                    $freeCancellation = (rand(1, 10) <= 5); // 50% de chance de permitir cancelamento gratuito
                    $payAtHotel = (rand(1, 10) <= 4); // 40% de chance de permitir pagamento no hotel
                    
                    // Política de cancelamento
                    $cancellationPolicy = $freeCancellation ? 
                        'Cancelamento gratuito até 24 horas antes do check-in' : 
                        'Sem cancelamento gratuito. Pagamento 100% adiantado.';                    
                    
                    // Taxas e impostos
                    $taxRate = 0.1; // 10% de impostos
                    $serviceCharge = 0.05; // 5% de taxa de serviço
                    $taxes = [
                        'IVA' => $finalPrice * $taxRate,
                        'Taxa de serviço' => $finalPrice * $serviceCharge
                    ];
                    
                    // Para cada provedor, criar uma opção de preço com pequenas variações
                    foreach ($providers as $index => $provider) {
                        // Variar o preço levemente entre provedores (-5% a +5%)
                        $providerMultiplier = 1 + (rand(-5, 5) / 100);
                        $providerPrice = round($finalPrice * $providerMultiplier, 2);
                        
                        // Link de reserva (exemplo simples)
                        $slug = strtolower(str_replace(' ', '-', $hotel->name));
                        $link = "https://www.{$provider}/hotel/{$slug}?checkin={$checkIn->format('Y-m-d')}&checkout={$checkOut->format('Y-m-d')}&rooms=1";
                        
                        Price::create([
                            'hotel_id' => $hotel->id,
                            'room_type_id' => $roomType->id,
                            'provider' => $provider,
                            'price' => $providerPrice,
                            'currency' => 'AKZ',
                            'original_price' => $hasDiscount ? $originalPrice * $providerMultiplier : null,
                            'discount_percentage' => $discountPercentage,
                            'link' => $link,
                            'check_in' => $checkIn->format('Y-m-d'),
                            'check_out' => $checkOut->format('Y-m-d'),
                            'breakfast_included' => $breakfastIncluded,
                            'free_cancellation' => $freeCancellation,
                            'pay_at_hotel' => $payAtHotel,
                            'cancellation_policy' => $cancellationPolicy,
                            'taxes_fees' => json_encode($taxes),
                            'last_updated' => now()
                        ]);
                        
                        // Limitar o número de provedores por data para não criar muitos registros
                        if ($index >= 2) break; // Usar apenas 3 provedores por data
                    }
                }
                
                // Pular alguns dias para não criar registros para cada dia (para reduzir o volume de dados)
                $checkIn->addDays(rand(2, 4)); // Pular 2 a 4 dias
            }
        }
    }
}
