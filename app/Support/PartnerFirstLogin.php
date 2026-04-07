<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Dados temporários do fluxo de primeiro acesso da loja (troca de senha provisória).
 */
final class PartnerFirstLogin
{
    /**
     * Senha em texto puro digitada no login; usada só para pré-preencher o campo
     * "senha atual" e removida ao concluir a troca ou ao invalidar a sessão.
     */
    public const SESSION_PLAIN_PASSWORD_KEY = 'partner_first_login_plain_password';
}
