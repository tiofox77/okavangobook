<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RoomType;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FixRoomTypeImages extends Command
{
    protected $signature = 'images:fix-room-types';
    protected $description = 'Fix room types that have external URLs instead of local images';

    public function handle()
    {
        $this->info('🔧 Corrigindo imagens dos tipos de quarto...');
        
        $roomTypes = RoomType::whereRaw("images LIKE '%http%'")->get();
        
        $this->info("   Encontrados {$roomTypes->count()} tipos de quarto com URLs externas");
        
        $bar = $this->output->createProgressBar($roomTypes->count());
        $bar->start();

        foreach ($roomTypes as $roomType) {
            try {
                // Copiar imagens do hotel pai para o room_type
                $hotel = $roomType->hotel;
                
                if ($hotel && $hotel->images) {
                    $hotelImages = is_string($hotel->images) ? json_decode($hotel->images, true) : $hotel->images;
                    
                    if (is_array($hotelImages) && !empty($hotelImages)) {
                        // Usar as mesmas imagens do hotel
                        $roomType->update(['images' => json_encode($hotelImages)]);
                        $this->newLine();
                        $this->info("   ✓ {$hotel->name} - {$roomType->name}");
                    }
                }
                
                $bar->advance();
                
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("   ❌ {$roomType->name}: " . $e->getMessage());
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info('✅ Correção concluída!');
        
        $remaining = RoomType::whereRaw("images LIKE '%http%'")->count();
        $this->info("   Tipos de quarto restantes com URLs externas: {$remaining}");
    }
}
