<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use App\Models\AgentMedia;
use App\Services\AgentAuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function __construct(private AgentAuditService $audit) {}

    public function store(Request $request)
    {
        $data = $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,gif,svg,pdf', 'max:10240'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'folder' => ['nullable', 'string', 'max:80', 'regex:/^[a-zA-Z0-9\/_-]+$/'],
        ]);
        $file = $data['file'];
        $folder = trim($data['folder'] ?? 'general', '/');
        $filename = now()->format('YmdHis').'-'.Str::random(10).'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs($folder, $filename, 'agent_media');
        $dimensions = str_starts_with((string) $file->getMimeType(), 'image/') ? @getimagesize($file->getRealPath()) : null;

        $media = AgentMedia::create([
            'disk' => 'agent_media',
            'path' => $path,
            'url' => url('/uploads/agent/'.$path),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'width' => $dimensions[0] ?? null,
            'height' => $dimensions[1] ?? null,
            'alt_text' => $data['alt_text'] ?? null,
            'title' => $data['title'] ?? null,
            'uploaded_by_token_id' => $request->attributes->get('agentToken')->id,
        ]);
        $this->audit->record($request, 'media.uploaded', $media, null, $media, 201);

        return response()->json(['data' => $media], 201);
    }
}
