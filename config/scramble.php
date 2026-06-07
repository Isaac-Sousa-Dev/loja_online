<?php

use Dedoc\Scramble\Http\Middleware\RestrictedDocsAccess;

return [
    /*
     * Prefix das rotas de API que o Scramble vai documentar.
     * Todas as rotas que começam com "api" serão incluídas.
     */
    'api_path' => 'api',

    'api_domain' => null,

    /*
     * Caminho onde o JSON do OpenAPI será exportado (php artisan scramble:export).
     */
    'export_path' => 'api.json',

    'info' => [
        'title'       => 'Doc Vistuu APP',
        'version'     => env('APP_VERSION', '1.0.0'),
        'description' => 'Documentação da API Vistoo',
    ],

    'servers' => null,

    /*
     * Middlewares aplicados às rotas de documentação (/docs/api e /docs/api.json).
     * Em produção, RestrictedDocsAccess bloqueia acesso de não-localhost por padrão.
     * Remova RestrictedDocsAccess (ou configure a gate 'viewApiDocs') para expor em prod.
     */
    'middleware' => [
        'web',
        RestrictedDocsAccess::class,
    ],

    'tags' => [],
];
