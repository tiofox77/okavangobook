<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Location;
use App\Models\Hotel;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateLocalImages extends Command
{
    protected $signature = 'images:generate {--type=all : Type of images to generate (all, locations, hotels)}';
    protected $description = 'Generate local SVG images for locations and hotels';

    public function handle()
    {
        $type = $this->option('type');

        $this->info('🎨 Gerando imagens locais...');
        $this->newLine();

        if ($type === 'all' || $type === 'locations') {
            $this->generateLocationImages();
        }

        if ($type === 'all' || $type === 'hotels') {
            $this->generateHotelImages();
        }

        $this->newLine();
        $this->info('✅ Geração de imagens concluída!');
    }

    private function generateLocationImages()
    {
        $this->info('📍 Gerando imagens para localizações...');
        
        $locations = Location::all();

        $bar = $this->output->createProgressBar($locations->count());
        $bar->start();

        foreach ($locations as $location) {
            try {
                // Gerar SVG usando o ImageHelper
                $svgData = ImageHelper::generateDefaultSvg('location', $location->name, 800, 600);
                
                // Converter data URL para conteúdo binário
                if (preg_match('/^data:image\/svg\+xml;base64,(.+)$/', $svgData, $matches)) {
                    $svgContent = base64_decode($matches[1]);
                    
                    // Gerar nome do arquivo
                    $fileName = Str::slug($location->province . '-' . $location->name) . '.svg';
                    
                    // Salvar no storage
                    Storage::disk('public')->put('locations/' . $fileName, $svgContent);
                    
                    // Atualizar o registro no banco
                    $location->update([
                        'image' => 'locations/' . $fileName
                    ]);
                }
                
                $bar->advance();
                
            } catch (\Exception $e) {
                $this->error("   ❌ Erro em {$location->name}: " . $e->getMessage());
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info("   ✅ {$locations->count()} imagens de localizações geradas!");
    }

    private function generateHotelImages()
    {
        $this->info('🏨 Gerando imagens para hotéis...');
        
        $hotels = Hotel::all();
        
        $bar = $this->output->createProgressBar($hotels->count());
        $bar->start();

        foreach ($hotels as $hotel) {
            try {
                // Gerar 3 imagens SVG por hotel
                $newImages = [];
                
                for ($i = 1; $i <= 3; $i++) {
                    // Gerar SVG usando o ImageHelper
                    $svgData = ImageHelper::generateDefaultSvg('hotel', $hotel->name, 800, 600);
                    
                    // Converter data URL para conteúdo binário
                    if (preg_match('/^data:image\/svg\+xml;base64,(.+)$/', $svgData, $matches)) {
                        $svgContent = base64_decode($matches[1]);
                        
                        // Gerar nome do arquivo
                        $fileName = Str::slug($hotel->name) . '-' . $i . '.svg';
                        
                        // Salvar no storage
                        Storage::disk('public')->put('hotels/' . $fileName, $svgContent);
                        
                        // Adicionar ao array de novas imagens
                        $newImages[] = 'hotels/' . $fileName;
                    }
                }
                
                // Atualizar o hotel com as novas imagens
                if (!empty($newImages)) {
                    $hotel->update([
                        'images' => json_encode($newImages)
                    ]);
                }
                
                $bar->advance();
                
            } catch (\Exception $e) {
                $this->error("   ❌ Erro no hotel {$hotel->name}: " . $e->getMessage());
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info("   ✅ {$hotels->count()} hotéis com imagens geradas!");
    }
}
