<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Hotel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FixRemainingImages extends Command
{
    protected $signature = 'images:fix-remaining';
    protected $description = 'Fix remaining hotels with SVG using alternative image sources';

    // URLs alternativas de imagens de hotel de alta qualidade
    private $alternativeImages = [
        'https://picsum.photos/1200/800?random=1',
        'https://picsum.photos/1200/800?random=2',
        'https://picsum.photos/1200/800?random=3',
    ];

    public function handle()
    {
        $this->info('🔧 Corrigindo imagens restantes...');
        
        $hotels = Hotel::where('images', 'like', '%.svg%')->get();
        
        $this->info("   Encontrados {$hotels->count()} hotéis com SVG");
        
        foreach ($hotels as $hotel) {
            try {
                $newImages = [];
                
                $this->info("   Processando: {$hotel->name}");
                
                for ($i = 1; $i <= 3; $i++) {
                    $imageUrl = $this->alternativeImages[$i - 1];
                    
                    $response = Http::withOptions([
                        'verify' => false,
                        'timeout' => 15,
                    ])->get($imageUrl);
                    
                    if ($response->successful()) {
                        $fileName = Str::slug($hotel->name) . "-{$i}.jpg";
                        Storage::disk('public')->put('hotels/' . $fileName, $response->body());
                        $newImages[] = 'hotels/' . $fileName;
                        $this->info("      ✓ Imagem {$i}/3");
                    }
                    
                    usleep(300000); // 0.3 segundo
                }
                
                if (count($newImages) === 3) {
                    $hotel->update(['images' => json_encode($newImages)]);
                    $this->info("   ✅ {$hotel->name} - concluído!");
                }
                
            } catch (\Exception $e) {
                $this->error("   ❌ {$hotel->name}: " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info('✅ Correção concluída!');
        
        // Verificar resultado final
        $remaining = Hotel::where('images', 'like', '%.svg%')->count();
        $this->info("   Hotéis restantes com SVG: {$remaining}");
    }
}
