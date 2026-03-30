<?php

declare(strict_types=1);

namespace App\Services\seed;

use App\DTO\InitialDataSeedResultDTO;
use App\Repository\seed\InitialDataRepository;
use Faker\Generator;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

/**
 * Orquestra o seed inicial alinhado aos módulos do SaaS (planos, loja, catálogo, equipe, vendas, pedidos).
 * Conteúdo demo: nicho catálogo de moda (vitrine / loja online).
 */
final class InitialDataSeedService
{
    public const DEFAULT_PASSWORD = 'abcd@1234';

    /**
     * @var list<string>
     */
    private const PLAN_MODULES = [
        'dashboard',
        'analitycs',
        'requests',
        'agentia',
        'sales',
        'team',
        'vehicles',
        'categories',
        'brands',
    ];

    public function __construct(
        private readonly InitialDataRepository $repository,
    ) {
    }

    public function run(Generator $faker): InitialDataSeedResultDTO
    {
        $sysadmin = $this->repository->createUser([
            'name' => 'Sysadmin',
            'email' => 'sysadmin@vistoo.test',
            'password' => self::DEFAULT_PASSWORD,
            'role' => 'admin',
        ]);

        $this->repository->createPlanWithModules(
            'Essencial',
            'essencial',
            'Plano Essencial — vitrine, pedidos e catálogo.',
            49.99,
            30,
            self::PLAN_MODULES,
        );
        $this->repository->createPlanWithModules(
            'Profissional',
            'profissional',
            'Plano Profissional — operação completa e equipe.',
            79.99,
            30,
            self::PLAN_MODULES,
        );
        $planDemo = $this->repository->createPlanWithModules(
            'Demonstração',
            'demo-interno',
            'Plano interno para ambiente de desenvolvimento.',
            48.00,
            90,
            self::PLAN_MODULES,
        );

        $this->seedRequestPlans();

        $partnerUser = $this->repository->createUser([
            'name' => 'Lojista Demo',
            'email' => 'lojista.demo@vistoo.test',
            'password' => self::DEFAULT_PASSWORD,
            'role' => 'partner',
        ]);

        $partner = $this->repository->createPartner([
            'user_id' => $partnerUser->id,
            'partner_link' => 'loja-moda-' . Str::lower(Str::random(6)),
            'is_testing' => true,
        ]);

        $this->repository->createSubscription([
            'partner_id' => $partner->id,
            'plan_id' => $planDemo->id,
            'status' => 'active',
            'start_date' => Date::now(),
            'end_date' => '2026-12-31',
            'payment_method' => 'pix',
            'appellant' => true,
        ]);

        $store = $this->repository->createStore([
            'store_name' => 'Studio Vitrine Demo',
            'store_email' => 'contato@studio-vitrine-demo.test',
            'store_phone' => $faker->phoneNumber(),
            'store_cpf_cnpj' => '12345678000199',
            'qtd_vehicles_in_stock' => null,
            'logo' => null,
            'banner' => null,
            'partner_id' => $partner->id,
            'plan_id' => $planDemo->id,
        ]);

        $this->repository->insertStoreHoursRow([
            'store_id' => $store->id,
            'open_in_weekdays' => '08:00:00',
            'close_in_weekdays' => '18:00:00',
            'open_saturday' => '09:00:00',
            'close_saturday' => '14:00:00',
            'open_sunday' => null,
            'close_sunday' => null,
        ]);

        $this->repository->createAddressStore([
            'store_id' => $store->id,
            'country' => 'Brasil',
            'state' => 'SP',
            'city' => 'São Paulo',
            'neighborhood' => 'Jardins',
            'street' => 'Rua Oscar Freire',
            'number' => '200',
            'zip_code' => '01426000',
        ]);

        $categoryNames = ['Camisetas', 'Calças', 'Acessórios'];
        $categories = [];
        foreach ($categoryNames as $catName) {
            $category = $this->repository->createCategory([
                'name' => $catName,
                'created_by' => $partner->id,
            ]);
            $this->repository->createStoreCategory([
                'description' => $faker->sentence(8),
                'store_id' => $store->id,
                'category_id' => $category->id,
            ]);
            $categories[] = $category;
        }

        $brandNames = ['Linha Urbana', 'Ateliê Norte'];
        $brands = [];
        foreach ($brandNames as $idx => $brandName) {
            $brands[] = $this->repository->createBrand([
                'codigo' => 8000 + $idx,
                'name' => $brandName,
                'type' => 'geral',
                'partner_id' => $partner->id,
                'logo_brand' => null,
            ]);
        }

        $productSpecs = [
            ['name' => 'Camiseta básica algodão', 'price' => 79.90, 'stock' => 40, 'brand' => 0, 'category' => 0],
            ['name' => 'Calça jeans slim', 'price' => 189.00, 'stock' => 25, 'brand' => 1, 'category' => 1],
            ['name' => 'Cinto couro legítimo', 'price' => 129.50, 'stock' => 15, 'brand' => 1, 'category' => 2],
        ];
        $products = [];
        foreach ($productSpecs as $spec) {
            $products[] = $this->repository->createProduct([
                'name' => $spec['name'],
                'description' => $faker->sentence(12),
                'price' => $spec['price'],
                'stock' => $spec['stock'],
                'brand_id' => $brands[$spec['brand']]->id,
                'category_id' => $categories[$spec['category']]->id,
                'partner_id' => $partner->id,
                'gender' => $spec['category'] === 1 ? 'M' : 'F',
                'image_main' => 'https://picsum.photos/seed/mo  da' . $spec['brand'] . '/500/500',
                'color' => 'Preto',
                'is_active' => true,
            ]);
        }

        foreach ($products as $product) {
            $this->repository->createProductImage([
                'product_id' => $product->id,
                'url' => 'https://picsum.photos/seed/p' . $product->id . '/500/500',
                'mimeType' => 'image',
            ]);
        }

        $sellerUser1 = $this->repository->createUser([
            'name' => 'Consultora Ana',
            'email' => 'vendedor1.demo@vistoo.test',
            'password' => self::DEFAULT_PASSWORD,
            'role' => 'seller',
            'phone' => '11911112222',
        ]);
        $sellerUser2 = $this->repository->createUser([
            'name' => 'Consultor Bruno',
            'email' => 'vendedor2.demo@vistoo.test',
            'password' => self::DEFAULT_PASSWORD,
            'role' => 'seller',
            'phone' => '11933334444',
        ]);

        $this->repository->insertSalesTeamRows([
            [
                'phone' => $sellerUser1->phone ?? '11911112222',
                'status' => 'active',
                'initial_message' => 'Olá! Sou a Ana da Studio Vitrine — posso ajudar com tamanhos e prazos.',
                'address' => 'Rua Alpha, 10',
                'city' => 'São Paulo',
                'zip_code' => '01305000',
                'neighborhood' => 'Bela Vista',
                'number' => '10',
                'user_id' => $sellerUser1->id,
                'partner_id' => $partner->id,
            ],
            [
                'phone' => $sellerUser2->phone ?? '11933334444',
                'status' => 'active',
                'initial_message' => 'Olá! Sou o Bruno — consultoria de looks e catálogo.',
                'address' => 'Rua Beta, 20',
                'city' => 'São Paulo',
                'zip_code' => '01306000',
                'neighborhood' => 'Consolação',
                'number' => '20',
                'user_id' => $sellerUser2->id,
                'partner_id' => $partner->id,
            ],
        ]);

        $clients = [];
        for ($c = 1; $c <= 5; $c++) {
            $clients[] = $this->repository->createClient([
                'name' => "Cliente Demo {$c}",
                'email' => "cliente.demo.{$c}@vistoo.test",
                'phone' => $faker->phoneNumber(),
                'address' => $faker->streetAddress,
                'city' => $faker->city,
                'neighborhood' => 'Centro',
                'zip_code' => $faker->postcode,
                'number' => $faker->buildingNumber,
            ]);
        }

        $saleTypes = [1, 2, 3, 4];
        foreach ($saleTypes as $i => $type) {
            $this->repository->createSale([
                'store_id' => $store->id,
                'client_id' => $clients[$i]->id,
                'seller_id' => $i % 2 === 0 ? $sellerUser1->id : $sellerUser2->id,
                'product_id' => $products[$i % 3]->id,
                'total_amount' => (float) (50 + ($i * 25)),
                'type' => $type,
                'status' => match ($i) {
                    0 => 'completed',
                    1 => 'pending',
                    2 => 'pending',
                    default => 'cancelled',
                },
                'payment_method' => match ($i) {
                    0 => 'pix',
                    1 => 'credit_card',
                    2 => 'cash',
                    default => 'cash',
                },
                'nf_number' => null,
                'delivery_date' => Date::now()->addDays($i + 1)->toDateString(),
                'fees' => 0,
                'discount' => $i === 3 ? 5.0 : 0,
                'observations' => "Venda seed — tipo {$type} (catálogo moda).",
            ]);
        }

        $orderRefBase = 'SV-DEMO-' . strtoupper(Str::random(4));
        $statuses = ['pending', 'paid', 'pending', 'sold', 'canceled'];
        $orderRows = [];
        foreach (range(0, 4) as $idx) {
            $orderRows[] = [
                'store_id' => $store->id,
                'client_id' => $clients[$idx]->id,
                'product_id' => $products[$idx % 3]->id,
                'seller_id' => $idx % 2 === 0 ? $sellerUser1->id : $sellerUser2->id,
                'status' => $statuses[$idx],
                'message' => 'Pedido gerado pelo seed inicial (Studio Vitrine Demo).',
                'shift' => $idx === 1,
                'finance' => $idx === 2,
                'product_variant_id' => null,
                'selected_color' => null,
                'selected_size' => null,
                'payment_method' => $idx % 2 === 0 ? 'pix' : 'cash',
                'delivery_type' => $idx < 3 ? 'delivery' : 'pickup',
                'delivery_address' => $idx < 3 ? 'Av. Paulista, 1000' : null,
                'delivery_city' => $idx < 3 ? 'São Paulo' : null,
                'delivery_state' => $idx < 3 ? 'SP' : null,
                'delivery_zip' => $idx < 3 ? '01310100' : null,
                'delivery_complement' => $idx < 3 ? 'Sala 42' : null,
                'order_ref' => $orderRefBase . '-' . ($idx + 1),
                'quantity' => ($idx % 3) + 1,
            ];
        }
        $this->repository->insertOrderRows($orderRows);

        return new InitialDataSeedResultDTO(accounts: [
            ['label' => 'Sysadmin', 'email' => $sysadmin->email, 'password' => self::DEFAULT_PASSWORD],
            ['label' => 'Lojista demo', 'email' => $partnerUser->email, 'password' => self::DEFAULT_PASSWORD],
            ['label' => 'Consultora (equipe)', 'email' => $sellerUser1->email, 'password' => self::DEFAULT_PASSWORD],
            ['label' => 'Consultor (equipe)', 'email' => $sellerUser2->email, 'password' => self::DEFAULT_PASSWORD],
        ]);
    }

    private function seedRequestPlans(): void
    {
        $this->repository->createRequestPlan([
            'name' => 'Marina Duarte',
            'email' => 'marina.solicitacao@vistoo.test',
            'phone' => '11988887777',
            'store_name' => 'Boutique Aurora',
            'qtd_vehicles_in_stock' => '',
            'plan_slug' => 'essencial',
            'payment_method' => 'pendente',
            'notes' => 'Nova loja de moda feminina — plano Essencial.',
        ]);
        $this->repository->createRequestPlan([
            'name' => 'Ricardo Melo',
            'email' => 'ricardo.solicitacao@vistoo.test',
            'phone' => '21977776666',
            'store_name' => 'Street Lab Multimarcas',
            'qtd_vehicles_in_stock' => '',
            'plan_slug' => 'profissional',
            'payment_method' => 'pix',
            'notes' => 'Catálogo streetwear — plano Profissional.',
        ]);
        $this->repository->createRequestPlan([
            'name' => 'Fernanda Reis',
            'email' => 'fernanda.solicitacao@vistoo.test',
            'phone' => '31966665555',
            'store_name' => 'Kids & Teen Style',
            'qtd_vehicles_in_stock' => '',
            'plan_slug' => 'essencial',
            'payment_method' => 'credit',
            'notes' => 'Segunda solicitação Essencial para testar a fila do admin.',
        ]);
    }
}
