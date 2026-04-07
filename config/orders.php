<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Server-Sent Events — badge de pedidos pendentes
    |--------------------------------------------------------------------------
    |
    | Quando true, o painel do parceiro abre uma conexão SSE para atualizar o
    | contador em tempo real. Defina ORDER_SSE_ENABLED=true no .env para ligar.
    |
    */
    'sse_enabled' => filter_var(
        env('ORDER_SSE_ENABLED', false),
        FILTER_VALIDATE_BOOLEAN
    ),

];
