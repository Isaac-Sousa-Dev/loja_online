<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\seed\InitialDataSeedService;
use Illuminate\Console\Command;

/**
 * Comando magro: delega o seed ao {@see InitialDataSeedService} (camada de negócio).
 */
class InitialData extends Command
{
    protected $signature = 'insert-data';

    protected $description = 'Inserindo dados iniciais no banco de dados (dev / catálogo moda).';

    public function handle(InitialDataSeedService $initialDataSeedService): int
    {
        $faker = \Faker\Factory::create('pt_BR');

        $this->info('Inserindo dados iniciais (planos, loja demo moda, equipe, vendas, pedidos)...');

        $result = $initialDataSeedService->run($faker);

        $this->info('Dados inseridos com sucesso!');
        $this->table(
            ['Conta', 'E-mail', 'Senha'],
            $result->toTableRows(),
        );

        return self::SUCCESS;
    }
}
