<?php

return [
    // Routes sur lesquelles appliquer CORS (API et endpoint Sanctum)
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'], // Méthodes HTTP autorisées
    'allowed_origins' => ['http://localhost:5173'], // Origines autorisées
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'], // Headers acceptés
    'exposed_headers' => [], // Headers exposés
    'max_age' => 0, // Pas de mise en cache
    'supports_credentials' => false, // Pas de cookies
];