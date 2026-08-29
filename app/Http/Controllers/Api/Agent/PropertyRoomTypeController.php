<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoomTypeResource;
use App\Models\Hotel;
use App\Models\RoomType;
use App\Services\AgentAuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PropertyRoomTypeController extends Controller
{
    public function __construct(private AgentAuditService $audit) {}

    public function index(int $id)
    {
        $hotel = Hotel::findOrFail($id);

        return RoomTypeResource::collection($hotel->roomTypes()->with('prices')->get());
    }

    public function store(Request $request, int $id)
    {
        $hotel = Hotel::findOrFail($id);
        $data = $this->validated($request, true);
        $dryRun = (bool) ($data['dry_run'] ?? false);
        unset($data['dry_run']);
        $this->authorizePricing($request, $data);

        $data['capacity'] = $this->totalCapacity($data);
        $data['position'] ??= ((int) $hotel->roomTypes()->max('position')) + 1;
        $preview = array_merge(['hotel_id' => $hotel->id], $data);

        $roomType = null;
        if (!$dryRun) {
            $roomType = $hotel->roomTypes()->create($data);
            $preview = $roomType->fresh()->toArray();
        }

        $this->audit->record($request, 'property.room_type.created', $roomType ?? RoomType::class, null, $preview, $dryRun ? 200 : 201, $dryRun);

        return response()->json(['data' => $preview, 'dry_run' => $dryRun], $dryRun ? 200 : 201);
    }

    public function update(Request $request, int $id, int $roomTypeId)
    {
        $hotel = Hotel::findOrFail($id);
        $roomType = $hotel->roomTypes()->findOrFail($roomTypeId);
        $before = $roomType->toArray();
        $data = $this->validated($request, false);
        $dryRun = (bool) ($data['dry_run'] ?? false);
        unset($data['dry_run']);
        $this->authorizePricing($request, $data);

        if (array_key_exists('adult_capacity', $data) || array_key_exists('children_capacity', $data)) {
            $data['capacity'] = $this->totalCapacity(array_replace($before, $data));
        }

        if (!$dryRun) $roomType->update($data);
        $after = $dryRun ? array_replace($before, $data) : $roomType->fresh()->toArray();
        $this->audit->record($request, 'property.room_type.updated', $roomType, $before, $after, 200, $dryRun);

        return response()->json(['data' => $after, 'dry_run' => $dryRun]);
    }

    public function destroy(Request $request, int $id, int $roomTypeId)
    {
        $hotel = Hotel::findOrFail($id);
        $roomType = $hotel->roomTypes()->findOrFail($roomTypeId);
        $before = $roomType->toArray();
        $dryRun = $request->boolean('dry_run');

        if (!$dryRun && $request->header('X-Confirm-Critical') !== 'true') {
            return response()->json(['message' => 'Faça dry_run e envie X-Confirm-Critical: true para remover o tipo de quarto.'], 409);
        }
        if (!$dryRun && ($roomType->prices()->exists() || $roomType->rooms()->exists() || $roomType->reservations()->exists())) {
            return response()->json(['message' => 'O tipo de quarto possui dados associados e não pode ser removido. Desative-o com is_available=false.'], 409);
        }

        if (!$dryRun) $roomType->delete();
        $this->audit->record($request, 'property.room_type.deleted', RoomType::class, $before, null, 200, $dryRun);

        return response()->json(['deleted' => !$dryRun, 'dry_run' => $dryRun, 'data' => $before]);
    }

    public function reorder(Request $request, int $id)
    {
        $hotel = Hotel::findOrFail($id);
        $data = $request->validate([
            'room_type_ids' => ['required', 'array', 'min:1'],
            'room_type_ids.*' => ['required', 'integer', 'distinct'],
            'dry_run' => ['sometimes', 'boolean'],
        ]);
        $existing = $hotel->roomTypes()->pluck('id')->all();
        if (array_diff($data['room_type_ids'], $existing) !== [] || array_diff($existing, $data['room_type_ids']) !== []) {
            throw ValidationException::withMessages(['room_type_ids' => ['A lista deve conter todos e apenas os IDs de tipos de quarto desta propriedade.']]);
        }
        $before = $hotel->roomTypes()->get()->toArray();
        $dryRun = (bool) ($data['dry_run'] ?? false);

        if (!$dryRun) {
            DB::transaction(fn () => collect($data['room_type_ids'])->each(
                fn ($roomTypeId, $position) => RoomType::whereKey($roomTypeId)->update(['position' => $position])
            ));
        }

        $after = $dryRun ? $data['room_type_ids'] : $hotel->roomTypes()->get()->toArray();
        $this->audit->record($request, 'property.room_type.reordered', $hotel, $before, $after, 200, $dryRun);

        return response()->json(['data' => $after, 'dry_run' => $dryRun]);
    }

    private function validated(Request $request, bool $creating): array
    {
        $sometimes = $creating ? 'required' : 'sometimes';

        return $request->validate([
            'name' => [$sometimes, 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'capacity' => ['sometimes', 'integer', 'min:1', 'max:50'],
            'adult_capacity' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:50'],
            'children_capacity' => ['sometimes', 'integer', 'min:0', 'max:50'],
            'beds' => ['sometimes', 'integer', 'min:0', 'max:20'],
            'bed_type' => ['sometimes', 'nullable', 'string', 'max:100'],
            'size' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:10000'],
            'amenities' => ['sometimes', 'array'],
            'amenities.*' => ['required', 'string', 'max:100'],
            'images' => ['sometimes', 'array'],
            'images.*' => ['required', 'url:http,https', 'max:1000'],
            'is_available' => ['sometimes', 'boolean'],
            'base_price' => [$creating ? 'required' : 'sometimes', 'numeric', 'min:0', 'max:9999999999.99'],
            'rooms_count' => ['sometimes', 'integer', 'min:0', 'max:10000'],
            'is_featured' => ['sometimes', 'boolean'],
            'position' => ['sometimes', 'integer', 'min:0'],
            'source_url' => ['sometimes', 'nullable', 'url:http,https', 'max:1000'],
            'dry_run' => ['sometimes', 'boolean'],
        ]);
    }

    private function totalCapacity(array $data): int
    {
        if (isset($data['adult_capacity'])) {
            return (int) $data['adult_capacity'] + (int) ($data['children_capacity'] ?? 0);
        }

        return (int) ($data['capacity'] ?? 2);
    }

    private function authorizePricing(Request $request, array $data): void
    {
        if (array_key_exists('base_price', $data) && !$request->attributes->get('agentToken')->hasScope('pricing:write')) {
            abort(403, 'Escopo pricing:write obrigatório para alterar base_price.');
        }
    }
}
