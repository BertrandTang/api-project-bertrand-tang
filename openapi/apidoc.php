<?php

namespace OpenApi;

use OpenApi\Attributes as OA;

/**
 * Métadonnées globales de l'API (scannées par L5-Swagger, sans lien avec les contrôleurs).
 */
#[OA\Info(title: 'API Orpheus', version: '1.0.0')]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    description: 'Token Sanctum obtenu via POST /api/v1/login (Authorization: Bearer {token})'
)]
class ApiDoc
{
}

class BooksDoc
{
    #[OA\Get(
        path: '/api/v1/books',
        summary: 'Liste des livres',
        description: 'Retourne la liste paginée des livres (2 par page). Route publique, sans authentification.',
        tags: ['Books'],
        parameters: [
            new OA\Parameter(
                name: 'Accept',
                in: 'header',
                required: true,
                schema: new OA\Schema(type: 'string', default: 'application/json'),
                example: 'application/json'
            ),
            new OA\Parameter(
                name: 'page',
                in: 'query',
                required: false,
                description: 'Numéro de page',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Liste paginée',
                content: new OA\JsonContent(
                    example: [
                        'data' => [
                            [
                                'title' => '1984',
                                'author' => 'GEORGE ORWELL',
                                'summary' => 'Roman dystopique...',
                                'isbn' => '9782070368228',
                                '_links' => [
                                    'self' => 'http://127.0.0.1:8000/api/v1/books/1',
                                    'update' => 'http://127.0.0.1:8000/api/v1/books/1',
                                    'delete' => 'http://127.0.0.1:8000/api/v1/books/1',
                                    'all' => 'http://127.0.0.1:8000/api/v1/books',
                                ],
                            ],
                        ],
                        'links' => [
                            'first' => 'http://127.0.0.1:8000/api/v1/books?page=1',
                            'last' => 'http://127.0.0.1:8000/api/v1/books?page=2',
                            'prev' => null,
                            'next' => 'http://127.0.0.1:8000/api/v1/books?page=2',
                        ],
                        'meta' => [
                            'current_page' => 1,
                            'per_page' => 2,
                            'total' => 3,
                        ],
                    ]
                )
            ),
        ]
    )]
    public function index(): void
    {
    }

    #[OA\Post(
        path: '/api/v1/books',
        summary: 'Création d\'un livre',
        description: 'Crée un livre et l\'ajoute en base. Route protégée : token Bearer Sanctum requis.',
        tags: ['Books'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'Accept',
                in: 'header',
                required: true,
                schema: new OA\Schema(type: 'string', default: 'application/json'),
                example: 'application/json'
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title', 'author', 'summary', 'isbn'],
                properties: [
                    new OA\Property(property: 'title', type: 'string', minLength: 3, maxLength: 255, example: '1984'),
                    new OA\Property(property: 'author', type: 'string', minLength: 3, maxLength: 100, example: 'George Orwell'),
                    new OA\Property(property: 'summary', type: 'string', minLength: 10, maxLength: 500, example: 'Roman dystopique dans une société totalitaire.'),
                    new OA\Property(property: 'isbn', type: 'string', example: '9782070368228'),
                ],
                example: [
                    'title' => '1984',
                    'author' => 'George Orwell',
                    'summary' => 'Roman dystopique dans une société totalitaire.',
                    'isbn' => '9782070368228',
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Livre créé',
                content: new OA\JsonContent(
                    example: [
                        'title' => '1984',
                        'author' => 'GEORGE ORWELL',
                        'summary' => 'Roman dystopique dans une société totalitaire.',
                        'isbn' => '9782070368228',
                        '_links' => [
                            'self' => 'http://127.0.0.1:8000/api/v1/books/1',
                            'update' => 'http://127.0.0.1:8000/api/v1/books/1',
                            'delete' => 'http://127.0.0.1:8000/api/v1/books/1',
                            'all' => 'http://127.0.0.1:8000/api/v1/books',
                        ],
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Non authentifié',
                content: new OA\JsonContent(
                    example: ['message' => 'Unauthenticated.']
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Erreur de validation',
                content: new OA\JsonContent(
                    example: [
                        'message' => 'The isbn has already been taken.',
                        'errors' => [
                            'isbn' => ['The isbn has already been taken.'],
                        ],
                    ]
                )
            ),
        ]
    )]
    public function store(): void
    {
    }
}

class AuthDoc
{
    #[OA\Post(
        path: '/api/v1/register',
        summary: 'Inscription',
        description: 'Crée un nouvel utilisateur. Le mot de passe est hashé automatiquement.',
        tags: ['Auth'],
        parameters: [
            new OA\Parameter(
                name: 'Accept',
                in: 'header',
                required: true,
                schema: new OA\Schema(type: 'string', default: 'application/json'),
                example: 'application/json'
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'password'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Jean Dupont'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', maxLength: 255, example: 'jean@example.com'),
                    new OA\Property(property: 'password', type: 'string', minLength: 8, example: 'password123'),
                ],
                example: [
                    'name' => 'Jean Dupont',
                    'email' => 'jean@example.com',
                    'password' => 'password123',
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Utilisateur créé',
                content: new OA\JsonContent(
                    example: [
                        'message' => 'User created successfully',
                        'user' => [
                            'id' => 1,
                            'name' => 'Jean Dupont',
                            'email' => 'jean@example.com',
                            'email_verified_at' => null,
                            'created_at' => '2026-05-18T10:00:00.000000Z',
                            'updated_at' => '2026-05-18T10:00:00.000000Z',
                        ],
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Erreur de validation',
                content: new OA\JsonContent(
                    example: [
                        'message' => 'The email has already been taken.',
                        'errors' => [
                            'email' => ['The email has already been taken.'],
                        ],
                    ]
                )
            ),
        ]
    )]
    public function register(): void
    {
    }

    #[OA\Post(
        path: '/api/v1/login',
        summary: 'Connexion',
        description: 'Authentifie un utilisateur et retourne un token Bearer Sanctum.',
        tags: ['Auth'],
        parameters: [
            new OA\Parameter(
                name: 'Accept',
                in: 'header',
                required: true,
                schema: new OA\Schema(type: 'string', default: 'application/json'),
                example: 'application/json'
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'jean@example.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'password123'),
                ],
                example: [
                    'email' => 'jean@example.com',
                    'password' => 'password123',
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Connexion réussie',
                content: new OA\JsonContent(
                    example: [
                        'message' => 'Logged in successfully',
                        'token' => '1|abcdefghijklmnopqrstuvwxyz',
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Identifiants invalides',
                content: new OA\JsonContent(
                    example: ['message' => 'Identifiants invalides']
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Erreur de validation',
                content: new OA\JsonContent(
                    example: [
                        'message' => 'The email field is required.',
                        'errors' => [
                            'email' => ['The email field is required.'],
                        ],
                    ]
                )
            ),
        ]
    )]
    public function login(): void
    {
    }

    #[OA\Post(
        path: '/api/v1/logout',
        summary: 'Déconnexion',
        description: 'Révoque le token Sanctum courant (Bearer obligatoire).',
        tags: ['Auth'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'Accept',
                in: 'header',
                required: true,
                schema: new OA\Schema(type: 'string', default: 'application/json'),
                example: 'application/json'
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Déconnexion réussie',
                content: new OA\JsonContent(
                    example: ['message' => 'Logged out successfully']
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Non authentifié',
                content: new OA\JsonContent(
                    example: ['message' => 'Unauthenticated.']
                )
            ),
        ]
    )]
    public function logout(): void
    {
    }
}
