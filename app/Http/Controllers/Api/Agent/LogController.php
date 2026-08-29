<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use App\Models\AgentAuditLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'actor' => ['sometimes', 'string', 'max:255'],
            'subject_type' => ['sometimes', 'string', 'max:255'],
            'subject_id' => ['sometimes', 'string', 'max:255'],
            'from' => ['sometimes', 'date'],
            'to' => ['sometimes', 'date', 'after_or_equal:from'],
            'per_page' => ['sometimes', 'integer', 'between:1,100'],
        ]);
        $query = AgentAuditLog::query()->latest();
        if (!empty($filters['actor'])) $query->where('actor', $filters['actor']);
        if (!empty($filters['subject_type'])) $query->where('subject_type', $filters['subject_type']);
        if (!empty($filters['subject_id'])) $query->where('subject_id', $filters['subject_id']);
        if (!empty($filters['from'])) $query->whereDate('created_at', '>=', $filters['from']);
        if (!empty($filters['to'])) $query->whereDate('created_at', '<=', $filters['to']);

        return response()->json($query->paginate((int) ($filters['per_page'] ?? 50)));
    }
}
