<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Senha provisória (cadastro manual pelo Sysadmin)
    |--------------------------------------------------------------------------
    |
    | Usada quando uma loja é criada manualmente. O dono deve alterar no
    | primeiro acesso (must_change_password).
    |
    */
    'default_manual_store_password' => (string) env('PARTNER_DEFAULT_MANUAL_PASSWORD', 'vistuu@1234'),
];
