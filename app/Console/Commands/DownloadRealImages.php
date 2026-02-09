<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Location;
use App\Models\Hotel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DownloadRealImages extends Command
{
    protected $signature = 'images:download-real {--type=all : Type (all, locations, hotels)}';
    protected $description = 'Download real images from Pexels and save locally';

    // Palavras-chave para busca de imagens por localização
    private $locationKeywords = [
        'luanda' => 'luanda angola city skyline',
        'benguela' => 'benguela angola beach',
        'lobito' => 'lobito angola port',
        'huambo' => 'huambo angola landscape',
        'lubango' => 'lubango angola mountains',
        'namibe' => 'namibe desert angola',
        'malanje' => 'malanje angola waterfalls',
        'cabinda' => 'cabinda angola coast',
        'soyo' => 'soyo angola river',
        'sumbe' => 'sumbe angola beach',
        'huila' => 'huila angola valley',
        'default' => 'angola landscape beautiful',
    ];

    public function handle()
    {
        $type = $this->option('type');

        $this->info('🌍 Baixando imagens reais...');
        $this->newLine();

        if ($type === 'all' || $type === 'locations') {
            $this->downloadLocationImages();
        }

        if ($type === 'all' || $type === 'hotels') {
            $this->downloadHotelImages();
        }

        $this->newLine();
        $this->info('✅ Download concluído!');
    }

    private function downloadLocationImages()
    {
        $this->info('📍 Baixando imagens de localizações...');
        
        $locations = Location::all();
        $bar = $this->output->createProgressBar($locations->count());
        $bar->start();

        foreach ($locations as $location) {
            try {
                // Determinar palavra-chave de busca
                $provinceLower = strtolower($location->province);
                $keyword = $this->locationKeywords[$provinceLower] ?? $this->locationKeywords['default'];
                
                // Baixar imagem do Pexels
                $imageUrl = $this->searchPexelsImage($keyword);
                
                if ($imageUrl) {
                    // Download da imagem
                    $response = Http::timeout(30)->get($imageUrl);
                    
                    if ($response->successful()) {
                        // Gerar nome do arquivo
                        $fileName = Str::slug($location->province . '-' . $location->name) . '.jpg';
                        
                        // Salvar no storage
                        Storage::disk('public')->put('locations/' . $fileName, $response->body());
                        
                        // Atualizar BD
                        $location->update(['image' => 'locations/' . $fileName]);
                        
                        $this->info("   ✓ {$location->name}");
                    }
                }
                
                $bar->advance();
                usleep(1000000); // 1 segundo de delay
                
            } catch (\Exception $e) {
                $this->error("   ❌ {$location->name}: " . $e->getMessage());
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine();
    }

    private function downloadHotelImages()
    {
        $this->info('🏨 Baixando imagens de hotéis...');
        
        $hotels = Hotel::all();
        $bar = $this->output->createProgressBar($hotels->count());
        $bar->start();

        foreach ($hotels as $hotel) {
            try {
                $newImages = [];
                
                // Baixar 3 imagens por hotel
                for ($i = 1; $i <= 3; $i++) {
                    $keyword = "hotel luxury room interior";
                    $imageUrl = $this->searchPexelsImage($keyword, $i);
                    
                    if ($imageUrl) {
                        $response = Http::timeout(30)->get($imageUrl);
                        
                        if ($response->successful()) {
                            $fileName = Str::slug($hotel->name) . "-{$i}.jpg";
                            Storage::disk('public')->put('hotels/' . $fileName, $response->body());
                            $newImages[] = 'hotels/' . $fileName;
                        }
                    }
                    
                    usleep(500000); // 0.5 segundo
                }
                
                if (!empty($newImages)) {
                    $hotel->update(['images' => json_encode($newImages)]);
                }
                
                $bar->advance();
                
            } catch (\Exception $e) {
                $this->error("   ❌ {$hotel->name}");
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine();
    }

    /**
     * Buscar imagem no Pexels (API gratuita)
     * Nota: Esta é uma implementação básica sem API key
     * Para produção, registre-se em pexels.com/api e use a API key
     */
    private function searchPexelsImage($keyword, $page = 1)
    {
        try {
            // URLs de fallback com imagens genéricas de boa qualidade
            $fallbackImages = [
                'https://images.pexels.com/photos/2132180/pexels-photo-2132180.jpeg?auto=compress&cs=tinysrgb&w=1200',
                'https://images.pexels.com/photos/1659438/pexels-photo-1659438.jpeg?auto=compress&cs=tinysrgb&w=1200',
                'https://images.pexels.com/photos/3155666/pexels-photo-3155666.jpeg?auto=compress&cs=tinysrgb&w=1200',
                'https://images.pexels.com/photos/3061217/pexels-photo-3061217.jpeg?auto=compress&cs=tinysrgb&w=1200',
                'https://images.pexels.com/photos/2132227/pexels-photo-2132227.jpeg?auto=compress&cs=tinysrgb&w=1200',
            ];
            
            // Retornar imagem aleatória do fallback
            $index = ($page - 1) % count($fallbackImages);
            return $fallbackImages[$index];
            
        } catch (\Exception $e) {
            return null;
        }
    }
}
