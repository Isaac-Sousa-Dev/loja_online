<?php

declare(strict_types=1);

namespace App\DTO;

/**
 * Resultado do seed inicial: credenciais exibidas no Artisan.
 *
 * @phpstan-type AccountRow array{label: string, email: string, password: string}
 */
final readonly class InitialDataSeedResultDTO
{
    /**
     * @param  list<AccountRow>  $accounts
     */
    public function __construct(
        public array $accounts,
    ) {
    }

    /**
     * @return list<list<string>>
     */
    public function toTableRows(): array
    {
        return array_map(
            static fn (array $a): array => [$a['label'], $a['email'], $a['password']],
            $this->accounts,
        );
    }
}
