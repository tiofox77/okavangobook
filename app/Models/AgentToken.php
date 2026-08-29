<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentToken extends Model
{
    public const PREFIX = 'kstay__';

    protected $fillable = [
        'name', 'token_hash', 'scopes', 'allowed_ips', 'expires_at',
        'last_used_at', 'revoked_at', 'created_by',
    ];

    protected $hidden = ['token_hash'];

    protected $casts = [
        'scopes' => 'array',
        'allowed_ips' => 'array',
        'expires_at' => 'datetime',
        'last_used_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function hasScope(string $scope): bool
    {
        return in_array('*', $this->scopes ?? [], true)
            || in_array($scope, $this->scopes ?? [], true);
    }

    public function isUsableFrom(?string $ip): bool
    {
        if ($this->revoked_at || $this->expires_at->isPast()) {
            return false;
        }

        $allowed = array_filter($this->allowed_ips ?? []);

        return $allowed === [] || ($ip !== null && in_array($ip, $allowed, true));
    }

    public static function issue(array $attributes): array
    {
        $plain = self::PREFIX.bin2hex(random_bytes(32));
        $token = self::create($attributes + ['token_hash' => hash('sha256', $plain)]);

        return [$token, $plain];
    }
}
