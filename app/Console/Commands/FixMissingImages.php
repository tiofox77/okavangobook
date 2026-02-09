<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Hotel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FixMissingImages extends Command
{
    protected $signature = 'images:fix-missing';
    protected $description = 'Fix hotels that still have SVG images instead of JPG';

    private $fallbackImages = [
        'https://images.pexels.com/photos/271624/pexels-photo-271624.jpeg?auto=compress&cs=tinysrgb&w=1200',
        'https://images.pexels.com/photos/164595/pexels-photo-164595.jpeg?auto=compress&cs=tinysrgb&w=1200',
        'https://images.pexels.com/photos/262048/pexels-photo-262048.jpeg?auto=compress&cs=tinysrgb&w=1200',
        'https://images.pexels.com/photos/271639/pexels-photo-271639.jpeg?auto=compress&cs=tinysrgb&w=1200',
        'https://images.pexels.com/photos/460537/pexels-photo-460537.jpeg?auto=compress&cs=tinysrgb&w=1200',
    ];

    public function handle()
    {
        $this->info('🔧 Corrigindo imagens faltantes...');
        
        // Buscar hotéis com SVG
        $hotels = Hotel::where('images', 'like', '%.svg%')->get();
        
        $this->info("   Encontrados {$hotels->count()} hotéis com SVG");
        
        $bar = $this->output->createProgressBar($hotels->count());
        $bar->start();

        foreach ($hotels as $hotel) {
            try {
                $newImages = [];
                
                // Baixar 3 imagens
                for ($i = 1; $i <= 3; $i++) {
                    $imageUrl = $this->fallbackImages[($i - 1) % count($this->fallbackImages)];
                    
                    $response = Http::withOptions(['verify' => false, 'timeout' => 30])->get($imageUrl);
                    
                    if ($response->successful()) {
                        $fileName = Str::slug($hotel->name) . "-{$i}.jpg";
                        Storage::disk('public')->put('hotels/' . $fileName, $response->body());
                        $newImages[] = 'hotels/' . $fileName;
                    }
                    
                    usleep(500000); // 0.5 segundo
                }
                
                if (!empty($newImages)) {
                    $hotel->update(['images' => json_encode($newImages)]);
                    $this->info("   ✓ {$hotel->name}");
                }
                
                $bar->advance();
                
            } catch (\Exception $e) {
                $this->error("   ❌ {$hotel->name}: " . $e->getMessage());
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info('✅ Correção concluída!');
    }
}
