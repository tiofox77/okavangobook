<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\LocationMedia;
use App\Services\AgentAuditService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Galeria multimédia dos destinos: várias imagens + vídeos
 * (MP4/WebM diretos ou YouTube/Vimeo). Escopos locations:read/write.
 */
class LocationMediaController extends Controller
{
    public function __construct(private AgentAuditService $audit) {}

    private function resolve(string $idOrSlug): Location
    {
        return is_numeric($idOrSlug)
            ? Location::findOrFail((int) $idOrSlug)
            : Location::where('slug', $idOrSlug)->firstOrFail();
    }

    private function validateUrl(string $type, string $url): void
    {
        $isHttp = str_starts_with($url, 'http://') || str_starts_with($url, 'https://');

        if ($type === 'image') {
            if ($isHttp) {
                if (! filter_var($url, FILTER_VALIDATE_URL)) {
                    throw ValidationException::withMessages(['url' => ['URL de imagem inválida.']]);
                }
                return;
            }
            if (preg_match('#^[\w\-./]+\.(jpg|jpeg|png|webp|gif|avif)$#i', $url) !== 1) {
                throw ValidationException::withMessages(['url' => ['Imagem: use URL http(s) ou caminho de storage (ex.: locations/luanda-baia.jpg).']]);
            }
            return;
        }

        // vídeo: só URLs http(s) — MP4/WebM diretos ou YouTube/Vimeo
        if (! $isHttp || ! filter_var($url, FILTER_VALIDATE_URL)) {
            throw ValidationException::withMessages(['url' => ['Vídeo: use uma URL http(s) — MP4/WebM direto ou link YouTube/Vimeo.']]);
        }
    }

    public function index(string $idOrSlug)
    {
        $location = $this->resolve($idOrSlug);

        return response()->json([
            'data' => $location->media->map(fn ($m) => [
                'id' => $m->id,
                'type' => $m->type,
                'url' => $m->url,
                'title' => $m->title,
                'position' => $m->position,
                'youtube_id' => $m->youtubeId(),
                'vimeo_id' => $m->vimeoId(),
            ]),
        ]);
    }

    public function store(Request $request, string $idOrSlug)
    {
        $location = $this->resolve($idOrSlug);

        $data = $request->validate([
            'type' => ['required', Rule::in(['image', 'video'])],
            'url' => ['required', 'string', 'max:1000'],
            'title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'position' => ['sometimes', 'integer', 'min:0'],
            'dry_run' => ['sometimes', 'boolean'],
        ]);

        $this->validateUrl($data['type'], $data['url']);

        $dryRun = (bool) ($data['dry_run'] ?? false);
        unset($data['dry_run']);

        if (! array_key_exists('position', $data)) {
            $data['position'] = (int) ($location->media()->max('position') ?? -1) + 1;
        }

        if ($dryRun) {
            $this->audit->record($request, 'location_media.created', $location, null, $data, 201, true);

            return response()->json(['data' => $data, 'dry_run' => true], 201);
        }

        $media = $location->media()->create($data);
        $this->audit->record($request, 'location_media.created', $media, null, $media->toArray(), 201, false);

        return response()->json(['data' => $media, 'dry_run' => false], 201);
    }

    public function destroy(Request $request, string $idOrSlug, int $mediaId)
    {
        $location = $this->resolve($idOrSlug);
        $media = $location->media()->findOrFail($mediaId);
        $before = $media->toArray();

        if ($request->boolean('dry_run')) {
            $this->audit->record($request, 'location_media.deleted', $media, $before, null, 200, true);

            return response()->json(['data' => $before, 'dry_run' => true]);
        }

        $media->delete();
        $this->audit->record($request, 'location_media.deleted', $media, $before, null, 200, false);

        return response()->json(['data' => ['id' => $mediaId, 'deleted' => true], 'dry_run' => false]);
    }
}
