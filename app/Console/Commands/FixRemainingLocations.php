<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Location;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FixRemainingLocations extends Command
{
    protected $signature = 'images:fix-locations';
    protected $description = 'Fix locations that still have SVG instead of JPG';

    private $alternativeImages = [
        'https://picsum.photos/1200/800?random=10',
        'https://picsum.photos/1200/800?random=11',
        'https://picsum.photos/1200/800?random=12',
    ];

    public function handle()
    {
        $this->info('🔧 Corrigindo localizações com SVG...');
        
        $locations = Location::where('image', 'like', '%.svg')->get();
        
        $this->info("   Encontradas {$locations->count()} localizações com SVG");
        
        foreach ($locations as $index => $location) {
            try {
                $this->info("   Processando: {$location->name}");
                
                $imageUrl = $this->alternativeImages[$index % count($this->alternativeImages)];
                
                $response = Http::withOptions([
                    'verify' => false,
                    'timeout' => 15,
                ])->get($imageUrl);
                
                if ($response->successful()) {
                    $fileName = Str::slug($location->province . '-' . $location->name) . '.jpg';
                    Storage::disk('public')->put('locations/' . $fileName, $response->body());
                    
                    $location->update(['image' => 'locations/' . $fileName]);
                    
                    $this->info("   ✅ {$location->name} - concluído!");
                }
                
                usleep(300000); // 0.3 segundo
                
            } catch (\Exception $e) {
                $this->error("   ❌ {$location->name}: " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info('✅ Correção concluída!');
        
        $remaining = Location::where('image', 'like', '%.svg')->count();
        $this->info("   Localizações restantes com SVG: {$remaining}");
    }
}
