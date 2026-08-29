<?php

return [
    'version' => 'v1',
    'token_max_days' => (int) env('AGENT_TOKEN_MAX_DAYS', 365),

    'scopes' => [
        'site:read', 'site:write',
        'pages:read', 'pages:write',
        'seo:write', 'menus:write',
        'properties:read', 'properties:write', 'properties:publish',
        'pricing:write', 'availability:write',
        'bookings:read', 'bookings:write', 'bookings:cancel',
        'leads:read', 'leads:write',
        'guests:read', 'contacts:read',
        'media:read', 'media:write',
        'payments:read', 'payments:write',
        'analytics:read', 'logs:read',
        'system:read', 'system:write', 'deploy:write',
    ],

    'modules' => [
        'identity' => 'available',
        'site' => 'available',
        'pages' => 'available',
        'react_blocks' => 'available',
        'seo' => 'available',
        'properties' => 'available',
        'media' => 'available',
        'audit_logs' => 'available',
        'menus' => 'planned',
        'pricing' => 'planned',
        'availability' => 'planned',
        'bookings' => 'planned',
        'leads' => 'planned',
        'guests' => 'planned',
        'promotions' => 'planned',
        'payments' => 'planned',
        'communications' => 'planned',
        'analytics' => 'planned',
        'content_health' => 'planned',
        'system_deploy' => 'planned',
    ],

    'react_block_types' => [
        'hero', 'gallery', 'cta', 'faq', 'property_grid', 'location', 'rich_text',
    ],
];
