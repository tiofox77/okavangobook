<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpdateHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'version_from',
        'version_to',
        'status',
        'release_name',
        'release_notes',
        'backup_file',
        'log',
        'performed_by',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function appendLog(string $message): void
    {
        $timestamp = now()->format('H:i:s');
        $currentLog = $this->log ?? '';
        $this->log = $currentLog . "[{$timestamp}] {$message}\n";
        $this->save();
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
