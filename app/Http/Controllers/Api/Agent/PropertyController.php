<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use App\Http\Resources\HotelResource;
use App\Models\Hotel;
use App\Models\User;
use App\Services\AgentAuditService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PropertyController extends Controller
{
    public function __construct(private AgentAuditService $audit) {}

    public function index(Request $request)
    {
        $query = Hotel::with(['location', 'roomTypes.prices', 'agentMedia']);
        if ($request->filled('status')) $query->where('is_active', $request->input('status') === 'published');
        if ($request->filled('q')) $query->where('name', 'like', '%'.$request->string('q').'%');
        if ($request->filled('type')) $query->where('property_type', $request->string('type'));

        return HotelResource::collection($query->latest('updated_at')->paginate(min((int) $request->input('per_page', 20), 100)));
    }

    public function show(int $id)
    {
        return new HotelResource(Hotel::with([
            'location', 'roomTypes.prices', 'rooms', 'restaurantItems', 'leisureFacilities', 'agentMedia',
        ])->findOrFail($id));
    }

    /**
     * Cria uma nova propriedade (hotel/resort/etc.).
     *
     * Campos obrigatórios: name, address, location_id.
     * Por omissão a propriedade entra como RASCUNHO (is_active=false); publicar
     * de imediato (is_active=true) é uma ação crítica: exige o escopo
     * properties:publish e o cabeçalho X-Confirm-Critical: true (tal como no update).
     * Suporta dry_run para pré-visualizar sem gravar. O slug é gerado
     * automaticamente a partir do nome (único).
     */
    public function store(Request $request)
    {
        $allowed = [
            'name', 'property_type', 'description', 'address', 'location_id', 'stars', 'thumbnail', 'images',
            'latitude', 'longitude', 'amenities', 'policies', 'check_in_time', 'check_out_time', 'phone',
            'email', 'website', 'is_featured', 'is_active', 'dry_run',
        ];
        $unsupported = array_values(array_diff(array_keys($request->all()), $allowed));
        if ($unsupported !== []) {
            $messages = collect($unsupported)->mapWithKeys(fn ($field) => [
                $field => [$field === 'room_types'
                    ? 'Crie a propriedade primeiro e depois use POST /properties/{id}/room-types.'
                    : "O campo {$field} não é suportado neste endpoint."],
            ])->all();
            throw ValidationException::withMessages($messages);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'property_type' => ['sometimes', Rule::in(['hotel', 'resort', 'hospedaria', 'residencial', 'apartment', 'house'])],
            'description' => ['sometimes', 'nullable', 'string'],
            'address' => ['required', 'string', 'max:500'],
            'location_id' => ['required', 'integer', 'exists:locations,id'],
            'stars' => ['sometimes', 'integer', 'between:1,5'],
            'thumbnail' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'images' => ['sometimes', 'array'],
            'images.*' => ['required', 'url:http,https', 'max:1000'],
            'latitude' => ['sometimes', 'nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['sometimes', 'nullable', 'numeric', 'between:-180,180'],
            'amenities' => ['sometimes', 'array'],
            'policies' => ['sometimes', 'nullable'],
            'check_in_time' => ['sometimes', 'nullable', 'date_format:H:i'],
            'check_out_time' => ['sometimes', 'nullable', 'date_format:H:i'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:50'],
            'email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'website' => ['sometimes', 'nullable', 'url', 'max:500'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'dry_run' => ['sometimes', 'boolean'],
        ], [
            'images.array' => 'O campo images deve ser um array de URLs absolutas.',
            'images.*.url' => 'Cada item de images deve ser uma URL absoluta HTTP ou HTTPS válida.',
            'images.*.required' => 'O array images não pode conter itens vazios.',
        ]);

        $dryRun = (bool) ($data['dry_run'] ?? false);
        unset($data['dry_run']);

        // Publicar já na criação é ação crítica (mesmo gate do update()).
        $wantsPublish = array_key_exists('is_active', $data) && $data['is_active'];
        if ($wantsPublish && !$dryRun) {
            if (!$request->attributes->get('agentToken')->hasScope('properties:publish')) {
                return response()->json(['message' => 'Escopo properties:publish obrigatório para criar já publicado.'], 403);
            }
            if ($request->header('X-Confirm-Critical') !== 'true') {
                return response()->json(['message' => 'Para criar já publicado, faça dry_run e envie X-Confirm-Critical: true.'], 409);
            }
        }

        // Por omissão, novas propriedades entram como rascunho (não publicadas).
        if (!array_key_exists('is_active', $data)) {
            $data['is_active'] = false;
        }

        // Dono da propriedade: o utilizador que emitiu o token, ou um Admin.
        $ownerId = $request->attributes->get('agentToken')->created_by
            ?? optional(User::role('Admin')->first())->id
            ?? User::min('id');

        if ($dryRun) {
            $preview = array_merge($data, [
                'user_id' => $ownerId,
                'slug' => Hotel::generateUniqueSlug($data['name']),
            ]);
            $this->audit->record($request, 'property.created', null, null, $preview, 201, true);

            return response()->json(['data' => $preview, 'dry_run' => true], 201);
        }

        $hotel = new Hotel($data);
        $hotel->user_id = $ownerId;
        $hotel->save(); // slug gerado automaticamente no booted() saving

        $after = $hotel->fresh()->load(['location', 'roomTypes.prices', 'agentMedia'])->toArray();
        $this->audit->record($request, 'property.created', $hotel, null, $after, 201, false);

        return response()->json(['data' => $after, 'dry_run' => false], 201);
    }

    /**
     * Elimina uma propriedade (ação crítica e destrutiva).
     *
     * Salvaguardas:
     * - Recusa (409) se a propriedade tiver reservas — as FKs estão em cascade
     *   e apagá-la levaria o histórico de reservas atrás; nesses casos use
     *   PATCH is_active=false para a despublicar.
     * - Exige o cabeçalho X-Confirm-Critical: true (além de X-Reason e
     *   Idempotency-Key do agent.write). Suporta dry_run para pré-visualizar.
     * - Tipos de quarto, preços, media, avaliações e favoritos são removidos
     *   em cascade pela BD.
     */
    public function destroy(Request $request, int $id)
    {
        $hotel = Hotel::withCount(['reservations', 'roomTypes'])->findOrFail($id);
        $before = $hotel->toArray();
        $dryRun = $request->boolean('dry_run');

        if ($hotel->reservations_count > 0) {
            $this->audit->record($request, 'property.delete_blocked', $hotel, $before, null, 409, $dryRun);

            return response()->json([
                'message' => "Não é possível eliminar: a propriedade tem {$hotel->reservations_count} reserva(s) associada(s). Use PATCH /properties/{$id} com is_active=false para a despublicar.",
                'reservations' => $hotel->reservations_count,
            ], 409);
        }

        if (! $dryRun && $request->header('X-Confirm-Critical') !== 'true') {
            return response()->json([
                'message' => 'Eliminação é ação crítica: faça dry_run e reenvie com X-Confirm-Critical: true.',
            ], 409);
        }

        if ($dryRun) {
            $this->audit->record($request, 'property.deleted', $hotel, $before, null, 200, true);

            return response()->json([
                'data' => ['id' => $hotel->id, 'name' => $hotel->name, 'room_types' => $hotel->room_types_count],
                'dry_run' => true,
                'message' => 'Pré-visualização: nada foi eliminado.',
            ]);
        }

        $hotel->delete(); // cascade remove room_types, prices, media, reviews, favoritos

        $this->audit->record($request, 'property.deleted', $hotel, $before, null, 200, false);

        return response()->json(['data' => ['id' => (int) $id, 'deleted' => true], 'dry_run' => false]);
    }

    public function update(Request $request, int $id)
    {
        $hotel = Hotel::findOrFail($id);
        $before = $hotel->toArray();
        $allowed = [
            'name', 'property_type', 'description', 'address', 'location_id', 'stars', 'thumbnail', 'images',
            'latitude', 'longitude', 'amenities', 'policies', 'check_in_time', 'check_out_time', 'phone',
            'email', 'website', 'is_featured', 'is_active', 'dry_run',
        ];
        $unsupported = array_values(array_diff(array_keys($request->all()), $allowed));
        if ($unsupported !== []) {
            $messages = collect($unsupported)->mapWithKeys(fn ($field) => [
                $field => [$field === 'room_types'
                    ? 'Use /properties/{id}/room-types para gerir tipos de quarto.'
                    : "O campo {$field} não é suportado neste endpoint."],
            ])->all();
            throw ValidationException::withMessages($messages);
        }
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'property_type' => ['sometimes', Rule::in(['hotel', 'resort', 'hospedaria', 'residencial', 'apartment', 'house'])],
            'description' => ['sometimes', 'nullable', 'string'],
            'address' => ['sometimes', 'nullable', 'string', 'max:500'],
            'location_id' => ['sometimes', 'integer', 'exists:locations,id'],
            'stars' => ['sometimes', 'integer', 'between:1,5'],
            'thumbnail' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'images' => ['sometimes', 'array'],
            'images.*' => ['required', 'url:http,https', 'max:1000'],
            'latitude' => ['sometimes', 'nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['sometimes', 'nullable', 'numeric', 'between:-180,180'],
            'amenities' => ['sometimes', 'array'],
            'policies' => ['sometimes', 'nullable'],
            'check_in_time' => ['sometimes', 'nullable', 'date_format:H:i'],
            'check_out_time' => ['sometimes', 'nullable', 'date_format:H:i'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:50'],
            'email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'website' => ['sometimes', 'nullable', 'url', 'max:500'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'dry_run' => ['sometimes', 'boolean'],
        ], [
            'images.array' => 'O campo images deve ser um array de URLs absolutas.',
            'images.*.url' => 'Cada item de images deve ser uma URL absoluta HTTP ou HTTPS válida.',
            'images.*.required' => 'O array images não pode conter itens vazios.',
        ]);
        $dryRun = (bool) ($data['dry_run'] ?? false);
        unset($data['dry_run']);

        if (array_key_exists('is_active', $data) && $data['is_active'] && !$dryRun) {
            if (!$request->attributes->get('agentToken')->hasScope('properties:publish')) {
                return response()->json(['message' => 'Escopo properties:publish obrigatório.'], 403);
            }
            if ($request->header('X-Confirm-Critical') !== 'true') {
                return response()->json(['message' => 'Faça dry_run e envie X-Confirm-Critical: true para publicar.'], 409);
            }
        }

        if (!$dryRun) {
            $hotel->fill($data)->save();
        }

        $after = $dryRun ? array_replace($before, $data) : $hotel->fresh()->toArray();
        $this->audit->record($request, 'property.updated', $hotel, $before, $after, 200, $dryRun);

        return response()->json(['data' => $after, 'dry_run' => $dryRun]);
    }
}
