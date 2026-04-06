<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Plan;
use App\Models\PlanModules;
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
    protected $description = 'Garante planos Essencial e Profissional e cria o SysAdmin (SYSADMIN_* no .env).';

    /**
     * Módulos alinhados ao seed de desenvolvimento (plan_modules).
     *
     * @var list<string>
     */
    private const PLAN_MODULE_KEYS = [
        'dashboard',
        'analitycs',
        'orders',
        'agentia',
        'sales',
        'team',
        'products',
        'categories',
        'brands',
    ];

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

        $this->ensureSubscriptionPlans();

        $name = config('sysadmin.name');
        $email = config('sysadmin.email');
        $password = config('sysadmin.password');

        if (! is_string($email) || trim($email) === '') {
            $this->components->error('Defina SYSADMIN_EMAIL no .env (e-mail do SysAdmin).');

            return self::FAILURE;
        }

        $existing = User::query()->where('email', $email)->first();

        if ($existing !== null) {
            if ($existing->role === 'admin') {
                $this->components->info('SysAdmin já existe para este e-mail; planos foram sincronizados.');

                return self::SUCCESS;
            }

            $this->components->error(sprintf(
                'Já existe um usuário com o e-mail "%s" e role "%s". Remova o conflito antes de prosseguir.',
                $email,
                (string) $existing->role,
            ));

            return self::FAILURE;
        }

        if (! is_string($password) || $password === '') {
            $this->components->error('Defina SYSADMIN_PASSWORD no .env (senha inicial do SysAdmin).');

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

    private function ensureSubscriptionPlans(): void
    {
        $definitions = [
            [
                'slug' => 'essencial',
                'name' => 'Essencial',
                'description' => 'Plano Essencial — vitrine, pedidos e catálogo.',
                'price' => 39.90,
            ],
            [
                'slug' => 'profissional',
                'name' => 'Profissional',
                'description' => 'Plano Profissional — operação completa e equipe.',
                'price' => 69.90,
            ],
        ];

        foreach ($definitions as $planData) {
            $plan = Plan::query()->updateOrCreate(
                ['slug' => $planData['slug']],
                [
                    'name' => $planData['name'],
                    'description' => $planData['description'],
                    'price' => $planData['price'],
                    'duration' => 30,
                    'status' => 'active',
                    'type' => 'monthly',
                ],
            );

            foreach (self::PLAN_MODULE_KEYS as $moduleKey) {
                PlanModules::query()->firstOrCreate(
                    [
                        'plan_id' => $plan->id,
                        'module' => $moduleKey,
                    ],
                );
            }

            $this->components->info(sprintf(
                'Plano "%s" garantido (R$ %s/mês).',
                $planData['name'],
                number_format((float) $planData['price'], 2, ',', '.'),
            ));
        }
    }

    private function environmentAllowsExecution(): bool
    {
        if ($this->option('force')) {
            return true;
        }

        return app()->environment('production');
    }
}
