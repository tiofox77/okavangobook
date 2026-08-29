<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use App\Models\AgentPage;
use App\Services\AgentAuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public function __construct(private AgentAuditService $audit) {}

    public function index(Request $request)
    {
        $query = AgentPage::query();
        if ($request->filled('status')) $query->where('status', $request->string('status'));
        if ($request->filled('q')) $query->where('title', 'like', '%'.$request->string('q').'%');

        return response()->json($query->latest('updated_at')->paginate(min((int) $request->input('per_page', 20), 100)));
    }

    public function show(string $slug)
    {
        return response()->json(['data' => AgentPage::where('slug', $slug)->firstOrFail()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        $data['created_by'] = $request->attributes->get('agentToken')->created_by;
        $page = AgentPage::create($data);
        $this->audit->record($request, 'page.created', $page, null, $page, 201);

        return response()->json(['data' => $page], 201);
    }

    public function update(Request $request, string $slug)
    {
        $page = AgentPage::where('slug', $slug)->firstOrFail();
        $before = $page->toArray();
        $data = $this->validated($request, $page);
        $dryRun = $request->boolean('dry_run');

        if (!$dryRun) {
            $page->fill($data);
            $page->preview_token = null;
            $page->save();
        }

        $after = $dryRun ? array_replace($before, $data) : $page->fresh()->toArray();
        $this->audit->record($request, 'page.updated', $page, $before, $after, 200, $dryRun);

        return response()->json(['data' => $after, 'dry_run' => $dryRun]);
    }

    public function preview(Request $request, string $slug)
    {
        $page = AgentPage::where('slug', $slug)->firstOrFail();
        $token = Str::random(48);
        $page->update(['preview_token' => hash('sha256', $token)]);
        $this->audit->record($request, 'page.previewed', $page, null, ['slug' => $slug], 200, true);

        return response()->json(['data' => [
            'page' => $page->fresh(),
            'preview_token' => $token,
            'expires_in_seconds' => 1800,
            'render_contract' => 'blocks[].type + blocks[].props',
        ]]);
    }

    public function publish(Request $request, string $slug)
    {
        $page = AgentPage::where('slug', $slug)->firstOrFail();
        $request->validate(['preview_token' => ['required', 'string']]);

        if (!$page->preview_token
            || $page->updated_at->lt(now()->subMinutes(30))
            || !hash_equals($page->preview_token, hash('sha256', $request->string('preview_token')))) {
            return response()->json(['message' => 'Preview obrigatório ou token de preview inválido.'], 409);
        }

        $before = $page->toArray();
        $page->update(['status' => 'published', 'published_at' => now(), 'preview_token' => null]);
        $this->audit->record($request, 'page.published', $page, $before, $page->fresh(), 200);

        return response()->json(['data' => $page->fresh(), 'message' => 'Página publicada.']);
    }

    public function archive(Request $request, string $slug)
    {
        $page = AgentPage::where('slug', $slug)->firstOrFail();
        $before = $page->toArray();
        $page->update(['status' => 'archived', 'preview_token' => null]);
        $this->audit->record($request, 'page.archived', $page, $before, $page->fresh(), 200);

        return response()->json(['data' => $page->fresh()]);
    }

    private function validated(Request $request, ?AgentPage $page = null): array
    {
        return $request->validate([
            'title' => [$page ? 'sometimes' : 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'string', 'max:255', Rule::unique('agent_pages', 'slug')->ignore($page?->id)],
            'status' => ['sometimes', Rule::in(['draft', 'archived'])],
            'blocks' => [$page ? 'sometimes' : 'required', 'array'],
            'blocks.*.type' => ['required', Rule::in(config('agent_api.react_block_types', []))],
            'blocks.*.props' => ['required', 'array'],
            'seo' => ['sometimes', 'array'],
            'seo.meta_title' => ['nullable', 'string', 'max:70'],
            'seo.description' => ['nullable', 'string', 'max:170'],
            'seo.canonical' => ['nullable', 'url', 'max:500'],
            'seo.open_graph' => ['nullable', 'array'],
            'seo.schema' => ['nullable', 'array'],
            'dry_run' => ['sometimes', 'boolean'],
        ]);
    }
}
