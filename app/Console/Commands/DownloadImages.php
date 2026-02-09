<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Location;
use App\Models\Hotel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DownloadImages extends Command
{
    protected $signature = 'images:download {--type=all : Type of images to download (all, locations, hotels)}';
    protected $description = 'Download images from external URLs and store them locally';

    public function handle()
    {
        $type = $this->option('type');

        $this->info('🚀 Iniciando download de imagens...');
        $this->newLine();

        if ($type === 'all' || $type === 'locations') {
            $this->downloadLocationImages();
        }

        if ($type === 'all' || $type === 'hotels') {
            $this->downloadHotelImages();
        }

        $this->newLine();
        $this->info('✅ Download de imagens concluído!');
    }

    private function downloadLocationImages()
    {
        $this->info('📍 Baixando imagens de localizações...');
        
        $locations = Location::whereNotNull('image')
            ->where('image', 'like', 'http%')
            ->get();

        $this->info("   Encontradas {$locations->count()} localizações com imagens externas");
        
        $bar = $this->output->createProgressBar($locations->count());
        $bar->start();

        foreach ($locations as $location) {
            try {
                $imageUrl = $location->image;
                
                // Baixar a imagem com SSL desabilitado
                $response = Http::withOptions([
                    'verify' => false,
                    'timeout' => 30,
                ])->get($imageUrl);
                
                if ($response->successful()) {
                    // Gerar nome do arquivo
                    $extension = 'jpg';
                    $fileName = Str::slug($location->province . '-' . $location->name) . '-' . time() . '.' . $extension;
                    
                    // Salvar no storage
                    Storage::disk('public')->put('locations/' . $fileName, $response->body());
                    
                    // Atualizar o registro no banco
                    $location->update([
                        'image' => 'locations/' . $fileName
                    ]);
                    
                    $bar->advance();
                } else {
                    $this->warn("   ⚠️ Falha ao baixar imagem de {$location->name} - Status: {$response->status()}");
                }
                
                // Pequeno delay para não sobrecarregar o servidor
                usleep(500000); // 0.5 segundos
                
            } catch (\Exception $e) {
                $this->error("   ❌ Erro em {$location->name}: " . $e->getMessage());
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info("   ✅ Imagens de localizações baixadas com sucesso!");
    }

    private function downloadHotelImages()
    {
        $this->info('🏨 Baixando imagens de hotéis...');
        
        $hotels = Hotel::whereNotNull('images')->get();
        
        $totalImages = 0;
        $downloadedImages = 0;

        foreach ($hotels as $hotel) {
            try {
                $images = json_decode($hotel->images, true);
                
                if (!is_array($images) || empty($images)) {
                    continue;
                }

                $newImages = [];
                
                foreach ($images as $imageUrl) {
                    $totalImages++;
                    
                    // Se já é uma imagem local, manter
                    if (!str_starts_with($imageUrl, 'http')) {
                        $newImages[] = $imageUrl;
                        continue;
                    }
                    
                    try {
                        // Baixar a imagem
                        $response = Http::timeout(30)->get($imageUrl);
                        
                        if ($response->successful()) {
                            // Gerar nome do arquivo
                            $extension = 'jpg';
                            $fileName = Str::slug($hotel->name) . '-' . uniqid() . '.' . $extension;
                            
                            // Salvar no storage
                            Storage::disk('public')->put('hotels/' . $fileName, $response->body());
                            
                            // Adicionar ao array de novas imagens
                            $newImages[] = 'hotels/' . $fileName;
                            $downloadedImages++;
                            
                            $this->info("   ✓ {$hotel->name}: imagem baixada");
                        }
                        
                        // Pequeno delay
                        usleep(500000); // 0.5 segundos
                        
                    } catch (\Exception $e) {
                        $this->warn("   ⚠️ Erro ao baixar imagem de {$hotel->name}");
                        // Manter a URL original em caso de erro
                        $newImages[] = $imageUrl;
                    }
                }
                
                // Atualizar o hotel com as novas imagens
                if (!empty($newImages)) {
                    $hotel->update([
                        'images' => json_encode($newImages)
                    ]);
                }
                
            } catch (\Exception $e) {
                $this->error("   ❌ Erro no hotel {$hotel->name}: " . $e->getMessage());
            }
        }

        $this->info("   ✅ {$downloadedImages}/{$totalImages} imagens de hotéis baixadas com sucesso!");
    }
}
