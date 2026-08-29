<?php

namespace App\Console\Commands;

use App\Models\Hotel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class LocalizeRemoteImages extends Command
{
    protected $signature = 'images:localize';

    protected $description = 'Descarrega imagens remotas dos hotéis e substitui as referências por ficheiros locais';

    private array $cache = [];

    public function handle(): int
    {
        Storage::disk('public')->makeDirectory('media-cache');

        $hotels = Hotel::query()->get();
        $bar = $this->output->createProgressBar($hotels->count());
        $bar->start();

        foreach ($hotels as $hotel) {
            $dirty = false;

            foreach (['thumbnail', 'featured_image'] as $field) {
                if ($this->isRemote($hotel->{$field})) {
                    $local = $this->download($hotel->{$field});
                    $hotel->{$field} = $local;
                    $dirty = true;
                }
            }

            $images = $hotel->images;
            $localizedImages = $this->localizeValue($images);
            if ($localizedImages !== $images) {
                $hotel->images = $localizedImages;
                $dirty = true;
            }

            if ($dirty) {
                $hotel->save();
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Imagens locais: ' . count($this->cache));

        return self::SUCCESS;
    }

    private function localizeValue(mixed $value): mixed
    {
        if ($this->isRemote($value)) {
            return $this->download($value);
        }

        if (is_array($value)) {
            return array_values(array_filter(
                array_map(fn ($item) => $this->localizeValue($item), $value),
                fn ($item) => $item !== null && $item !== ''
            ));
        }

        return $value;
    }

    private function download(string $url): ?string
    {
        if (isset($this->cache[$url])) {
            return $this->cache[$url];
        }

        $hash = sha1($url);
        $existing = glob(storage_path("app/public/media-cache/{$hash}.*"));
        if (!empty($existing)) {
            return $this->cache[$url] = 'media-cache/' . basename($existing[0]);
        }

        try {
            $response = Http::retry(2, 300)
                ->timeout(30)
                ->withHeaders(['User-Agent' => 'KiandaStay/1.0'])
                ->get($url);

            if (!$response->successful() || $response->body() === '') {
                return null;
            }

            $contentType = strtolower((string) $response->header('Content-Type'));
            $extension = match (true) {
                str_contains($contentType, 'png') => 'png',
                str_contains($contentType, 'webp') => 'webp',
                str_contains($contentType, 'gif') => 'gif',
                default => 'jpg',
            };

            $path = "media-cache/{$hash}.{$extension}";
            Storage::disk('public')->put($path, $response->body());

            return $this->cache[$url] = $path;
        } catch (\Throwable $exception) {
            $this->warn("Falha ao descarregar uma imagem: {$exception->getMessage()}");
            return null;
        }
    }

    private function isRemote(mixed $value): bool
    {
        return is_string($value)
            && (str_starts_with($value, 'http://') || str_starts_with($value, 'https://'));
    }
}
