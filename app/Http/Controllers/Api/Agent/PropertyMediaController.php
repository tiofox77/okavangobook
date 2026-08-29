<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use App\Models\AgentMedia;
use App\Models\Hotel;
use App\Services\AgentAuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PropertyMediaController extends Controller
{
    public function __construct(private AgentAuditService $audit) {}

    public function index(int $id)
    {
        $hotel = Hotel::findOrFail($id);

        return response()->json(['data' => $hotel->agentMedia()->get()]);
    }

    public function store(Request $request, int $id)
    {
        $hotel = Hotel::findOrFail($id);
        $data = $request->validate([
            'media_id' => ['nullable', 'integer', 'exists:agent_media,id'],
            'file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:10240'],
            'url' => ['nullable', 'url:http,https', 'max:1000'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'integer', 'min:0'],
            'is_cover' => ['nullable', 'boolean'],
        ]);

        $sources = collect(['media_id', 'file', 'url'])->filter(fn ($key) => $request->has($key) && $request->input($key) !== null);
        if ($request->hasFile('file') && !$sources->contains('file')) $sources->push('file');
        if ($sources->count() !== 1) {
            throw ValidationException::withMessages([
                'source' => ['Envie exatamente uma origem: media_id, file ou url.'],
            ]);
        }

        $before = $hotel->agentMedia()->get()->toArray();

        $media = DB::transaction(function () use ($request, $data, $hotel) {
            if (!empty($data['media_id'])) {
                $media = AgentMedia::findOrFail($data['media_id']);
                if ($media->hotel_id && $media->hotel_id !== $hotel->id) {
                    throw ValidationException::withMessages(['media_id' => ['Este media já pertence a outra propriedade.']]);
                }
            } elseif ($request->hasFile('file')) {
                $file = $request->file('file');
                $folder = 'properties/'.$hotel->id;
                $filename = now()->format('YmdHis').'-'.Str::random(10).'.'.$file->getClientOriginalExtension();
                $path = $file->storeAs($folder, $filename, 'agent_media');
                $dimensions = @getimagesize($file->getRealPath());
                $media = new AgentMedia([
                    'disk' => 'agent_media',
                    'path' => $path,
                    'url' => url('/uploads/agent/'.$path),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'width' => $dimensions[0] ?? null,
                    'height' => $dimensions[1] ?? null,
                    'uploaded_by_token_id' => $request->attributes->get('agentToken')->id,
                ]);
            } else {
                $media = new AgentMedia([
                    'disk' => 'external',
                    'path' => $data['url'],
                    'url' => $data['url'],
                    'mime_type' => 'image/external',
                    'size' => 0,
                    'uploaded_by_token_id' => $request->attributes->get('agentToken')->id,
                ]);
            }

            $media->fill([
                'hotel_id' => $hotel->id,
                'alt_text' => $data['alt_text'] ?? $media->alt_text,
                'title' => $data['title'] ?? $media->title,
                'position' => $data['position'] ?? $hotel->agentMedia()->max('position') + 1,
                'is_cover' => (bool) ($data['is_cover'] ?? false),
            ])->save();

            if ($media->is_cover) $this->makeCover($hotel, $media);

            return $media->fresh();
        });

        $this->syncHotelImages($hotel);

        $this->audit->record($request, 'property.media.attached', $hotel, $before, $hotel->agentMedia()->get(), 201);

        return response()->json(['data' => $media], 201);
    }

    public function update(Request $request, int $id, int $mediaId)
    {
        $hotel = Hotel::findOrFail($id);
        $media = $hotel->agentMedia()->findOrFail($mediaId);
        $before = $media->toArray();
        $data = $request->validate([
            'alt_text' => ['sometimes', 'nullable', 'string', 'max:255'],
            'title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'position' => ['sometimes', 'integer', 'min:0'],
            'is_cover' => ['sometimes', 'boolean'],
            'dry_run' => ['sometimes', 'boolean'],
        ]);
        $dryRun = (bool) ($data['dry_run'] ?? false);
        unset($data['dry_run']);

        if (!$dryRun) {
            $media->update($data);
            if ($media->is_cover) $this->makeCover($hotel, $media);
        }

        $after = $dryRun ? array_replace($before, $data) : $media->fresh()->toArray();
        $this->audit->record($request, 'property.media.updated', $media, $before, $after, 200, $dryRun);

        return response()->json(['data' => $after, 'dry_run' => $dryRun]);
    }

    public function destroy(Request $request, int $id, int $mediaId)
    {
        $hotel = Hotel::findOrFail($id);
        $media = $hotel->agentMedia()->findOrFail($mediaId);
        $before = $media->toArray();
        $dryRun = $request->boolean('dry_run');

        if (!$dryRun && $request->header('X-Confirm-Critical') !== 'true') {
            return response()->json(['message' => 'Faça dry_run e envie X-Confirm-Critical: true para remover media.'], 409);
        }

        if (!$dryRun) {
            if ($media->is_cover && $hotel->thumbnail === $media->url) $hotel->update(['thumbnail' => null]);
            if ($media->disk === 'agent_media') Storage::disk('agent_media')->delete($media->path);
            $media->delete();
            $this->syncHotelImages($hotel, $before['url']);
        }

        $this->audit->record($request, 'property.media.deleted', AgentMedia::class, $before, null, 200, $dryRun);

        return response()->json(['deleted' => !$dryRun, 'dry_run' => $dryRun, 'data' => $before]);
    }

    public function reorder(Request $request, int $id)
    {
        $hotel = Hotel::findOrFail($id);
        $data = $request->validate([
            'media_ids' => ['required', 'array', 'min:1'],
            'media_ids.*' => ['required', 'integer', 'distinct'],
            'dry_run' => ['sometimes', 'boolean'],
        ]);
        $existing = $hotel->agentMedia()->pluck('id')->all();
        if (array_diff($data['media_ids'], $existing) !== [] || array_diff($existing, $data['media_ids']) !== []) {
            throw ValidationException::withMessages(['media_ids' => ['A lista deve conter todos e apenas os IDs de media desta propriedade.']]);
        }
        $before = $hotel->agentMedia()->get()->toArray();
        $dryRun = (bool) ($data['dry_run'] ?? false);

        if (!$dryRun) {
            DB::transaction(fn () => collect($data['media_ids'])->each(
                fn ($mediaId, $position) => AgentMedia::whereKey($mediaId)->update(['position' => $position])
            ));
            $this->syncHotelImages($hotel);
        }

        $after = $dryRun ? $data['media_ids'] : $hotel->agentMedia()->get()->toArray();
        $this->audit->record($request, 'property.media.reordered', $hotel, $before, $after, 200, $dryRun);

        return response()->json(['data' => $after, 'dry_run' => $dryRun]);
    }

    private function makeCover(Hotel $hotel, AgentMedia $media): void
    {
        AgentMedia::where('hotel_id', $hotel->id)->whereKeyNot($media->id)->update(['is_cover' => false]);
        $hotel->update(['thumbnail' => $media->url]);
    }

    private function syncHotelImages(Hotel $hotel, ?string $removedUrl = null): void
    {
        $managed = $hotel->agentMedia()->pluck('url')->filter()->values();
        $allManaged = AgentMedia::where('hotel_id', $hotel->id)->pluck('url')->filter();
        $legacy = collect($hotel->images ?? [])
            ->filter(fn ($url) => is_string($url) && $url !== '' && $url !== $removedUrl)
            ->reject(fn ($url) => $allManaged->contains($url));

        $hotel->update(['images' => $legacy->concat($managed)->unique()->values()->all()]);
    }
}
