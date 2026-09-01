<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use App\Services\AgentAuditService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Destinos (Locations) — tudo o que o admin faz em /admin/locations:
 * listar, ver, criar, editar (descrição, imagem, capital, população,
 * coordenadas, destaque, estado) e eliminar (bloqueado se tiver hotéis).
 * Alimenta as páginas públicas /destino/{slug|província}.
 */
class LocationController extends Controller
{
    public function __construct(private AgentAuditService $audit) {}

    /** Campos aceites nos pedidos de escrita (allowlist). */
    private const ALLOWED = [
        'name', 'province', 'description', 'image', 'capital', 'population',
        'latitude', 'longitude', 'is_featured', 'is_active', 'slug', 'dry_run',
    ];

    private function validationRules(bool $creating): array
    {
        $required = $creating ? 'required' : 'sometimes';

        return [
            'name' => [$required, 'string', 'max:255'],
            'province' => [$required, Rule::in(array_keys(Location::PROVINCE_NAMES))],
            'description' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'image' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'capital' => ['sometimes', 'nullable', 'string', 'max:255'],
            'population' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'latitude' => ['sometimes', 'nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['sometimes', 'nullable', 'numeric', 'between:-180,180'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'slug' => ['sometimes', 'nullable', 'string', 'max:255', 'regex:/^[a-z0-9-]+$/'],
            'dry_run' => ['sometimes', 'boolean'],
        ];
    }

    /** Rejeita campos fora do allowlist com 422 explicativo (padrão da Agent API). */
    private function rejectUnsupported(Request $request): void
    {
        $unsupported = array_values(array_diff(array_keys($request->all()), self::ALLOWED));
        if ($unsupported !== []) {
            throw ValidationException::withMessages(
                collect($unsupported)->mapWithKeys(fn ($f) => [$f => ["O campo {$f} não é suportado neste endpoint."]])->all()
            );
        }
    }

    /** Valida a imagem: URL http(s) válida ou path relativo de storage. */
    private function validateImage(?string $image): void
    {
        if ($image === null || $image === '') {
            return;
        }
        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            if (! filter_var($image, FILTER_VALIDATE_URL)) {
                throw ValidationException::withMessages(['image' => ['A imagem deve ser uma URL http(s) válida ou um caminho de storage (ex.: locations/luanda.jpg).']]);
            }
            return;
        }
        if (preg_match('#^[\w\-./]+\.(jpg|jpeg|png|webp|gif|avif)$#i', $image) !== 1) {
            throw ValidationException::withMessages(['image' => ['Caminho de imagem inválido. Use uma URL http(s) ou um caminho tipo locations/nome.jpg.']]);
        }
    }

    /** Resolve por id numérico ou por slug. */
    private function resolve(string $idOrSlug): Location
    {
        return is_numeric($idOrSlug)
            ? Location::withCount('hotels')->findOrFail((int) $idOrSlug)
            : Location::withCount('hotels')->where('slug', $idOrSlug)->firstOrFail();
    }

    public function index(Request $request)
    {
        $query = Location::withCount('hotels');
        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(fn ($w) => $w->where('name', 'like', "%$q%")
                ->orWhere('province', 'like', "%$q%")
                ->orWhere('description', 'like', "%$q%"));
        }
        if ($request->filled('province')) $query->where('province', $request->string('province'));
        if ($request->has('featured')) $query->where('is_featured', $request->boolean('featured'));
        if ($request->has('active')) $query->where('is_active', $request->boolean('active'));

        return LocationResource::collection(
            $query->orderBy('province')->orderBy('name')
                ->paginate(min((int) $request->input('per_page', 25), 100))
        );
    }

    public function show(string $idOrSlug)
    {
        return new LocationResource($this->resolve($idOrSlug));
    }

    public function store(Request $request)
    {
        $this->rejectUnsupported($request);
        $data = $request->validate($this->validationRules(true));
        $this->validateImage($data['image'] ?? null);

        $dryRun = (bool) ($data['dry_run'] ?? false);
        unset($data['dry_run']);

        if (! array_key_exists('is_active', $data)) {
            $data['is_active'] = true;
        }
        if (empty($data['slug'])) {
            $data['slug'] = Location::generateUniqueSlug($data['name']);
        } elseif (Location::where('slug', $data['slug'])->exists()) {
            throw ValidationException::withMessages(['slug' => ['Este slug já está em uso.']]);
        }

        if ($dryRun) {
            $this->audit->record($request, 'location.created', null, null, $data, 201, true);

            return response()->json(['data' => $data, 'dry_run' => true], 201);
        }

        $location = Location::create($data);
        $after = $location->fresh()->toArray();
        $this->audit->record($request, 'location.created', $location, null, $after, 201, false);

        return response()->json(['data' => new LocationResource($location->loadCount('hotels')), 'dry_run' => false], 201);
    }

    public function update(Request $request, string $idOrSlug)
    {
        $location = $this->resolve($idOrSlug);
        $before = $location->toArray();

        $this->rejectUnsupported($request);
        $data = $request->validate($this->validationRules(false));
        if (array_key_exists('image', $data)) {
            $this->validateImage($data['image']);
        }

        $dryRun = (bool) ($data['dry_run'] ?? false);
        unset($data['dry_run']);

        if (! empty($data['slug']) && $data['slug'] !== $location->slug
            && Location::where('slug', $data['slug'])->where('id', '!=', $location->id)->exists()) {
            throw ValidationException::withMessages(['slug' => ['Este slug já está em uso.']]);
        }

        if (! $dryRun) {
            $location->fill($data)->save();
        }

        $after = $dryRun ? array_replace($before, $data) : $location->fresh()->toArray();
        $this->audit->record($request, 'location.updated', $location, $before, $after, 200, $dryRun);

        return response()->json([
            'data' => $dryRun ? $after : new LocationResource($location->fresh()->loadCount('hotels')),
            'dry_run' => $dryRun,
        ]);
    }

    /**
     * Elimina um destino. Espelha a regra do admin: bloqueado (409) se
     * existirem hotéis associados. Ação crítica: exige X-Confirm-Critical.
     */
    public function destroy(Request $request, string $idOrSlug)
    {
        $location = $this->resolve($idOrSlug);
        $before = $location->toArray();
        $dryRun = $request->boolean('dry_run');

        if ($location->hotels_count > 0) {
            $this->audit->record($request, 'location.delete_blocked', $location, $before, null, 409, $dryRun);

            return response()->json([
                'message' => "Não é possível eliminar: o destino tem {$location->hotels_count} hotel(éis) associado(s). Mova ou elimine os hotéis primeiro, ou despublique com is_active=false.",
                'hotels' => $location->hotels_count,
            ], 409);
        }

        if (! $dryRun && $request->header('X-Confirm-Critical') !== 'true') {
            return response()->json([
                'message' => 'Eliminação é ação crítica: faça dry_run e reenvie com X-Confirm-Critical: true.',
            ], 409);
        }

        if ($dryRun) {
            $this->audit->record($request, 'location.deleted', $location, $before, null, 200, true);

            return response()->json(['data' => ['id' => $location->id, 'name' => $location->name], 'dry_run' => true, 'message' => 'Pré-visualização: nada foi eliminado.']);
        }

        $location->delete();
        $this->audit->record($request, 'location.deleted', $location, $before, null, 200, false);

        return response()->json(['data' => ['id' => $location->id, 'deleted' => true], 'dry_run' => false]);
    }
}
