<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Bootstrap SysAdmin (comando insert-data-prod)
    |--------------------------------------------------------------------------
    |
    | Defina no .env da produção: SYSADMIN_EMAIL e SYSADMIN_PASSWORD.
    | SYSADMIN_NAME é opcional (padrão: Sysadmin).
    |
    */
    'name' => env('SYSADMIN_NAME', 'Sysadmin'),
    'email' => env('SYSADMIN_EMAIL'),
    'password' => env('SYSADMIN_PASSWORD'),
];
