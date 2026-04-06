<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class InitialDataProd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert-data-prod
                            {--force : Permite executar fora do ambiente production (ex.: staging ou testes)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria o usuário SysAdmin inicial (credenciais via SYSADMIN_* no .env).';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (! $this->environmentAllowsExecution()) {
            $this->components->error(
                'Este comando é restrito ao ambiente production. Use --force apenas se souber o que está fazendo.',
            );

            return self::FAILURE;
        }

        $name = config('sysadmin.name');
        $email = config('sysadmin.email');
        $password = config('sysadmin.password');

        if (! is_string($email) || trim($email) === '') {
            $this->components->error('Defina SYSADMIN_EMAIL no .env (e-mail do SysAdmin).');

            return self::FAILURE;
        }

        if (! is_string($password) || $password === '') {
            $this->components->error('Defina SYSADMIN_PASSWORD no .env (senha inicial do SysAdmin).');

            return self::FAILURE;
        }

        $existing = User::query()->where('email', $email)->first();

        if ($existing !== null) {
            if ($existing->role === 'admin') {
                $this->components->info('SysAdmin já existe para este e-mail; nada a fazer.');

                return self::SUCCESS;
            }

            $this->components->error(sprintf(
                'Já existe um usuário com o e-mail "%s" e role "%s". Remova o conflito antes de prosseguir.',
                $email,
                (string) $existing->role,
            ));

            return self::FAILURE;
        }

        $displayName = is_string($name) && trim($name) !== '' ? $name : 'Sysadmin';

        User::create([
            'name' => $displayName,
            'email' => $email,
            'password' => $password,
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->components->info(sprintf('Usuário SysAdmin criado: %s.', $email));

        return self::SUCCESS;
    }

    private function environmentAllowsExecution(): bool
    {
        if ($this->option('force')) {
            return true;
        }

        return app()->environment('production');
    }
}
