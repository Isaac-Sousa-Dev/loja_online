<?php

namespace App\Console\Commands;

use App\Models\AddressStore;
use App\Models\Brand;
use App\Models\BrandsDefault;
use App\Models\CategoriesDefault;
use App\Models\Category;
use App\Models\Client;
use App\Models\Image;
use App\Models\Modelo;
use App\Models\Partner;
use App\Models\Plan;
use App\Models\PlanModules;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreCategories;
use App\Models\StoreHour;
use App\Models\Subcategories;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;

class InitialData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inserindo dados iniciais no banco de dados.';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $faker = \Faker\Factory::create();

        $this->info('Inserindo dados iniciais no banco de dados...');

        // INSERINDO ADMINISTRADOR
        $this->info('Inserindo usuário administrador...');
        User::create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('abcd@1234'),
            'role' =>  'admin',
        ]);


        // INSERINDO PLANO
        $plan = Plan::create([
            'name' => 'Teste',
            'slug' => 'teste-teste',
            'description' => 'Plano Teste',
            'price' => 48.00,
            'duration' => '90',
            'status' => 'active',
            'type' => 'monthly'
        ]);

        // INSERINDO MÓDULOS DO PLANO
        $modules = ['dashboard', 'analitycs', 'requests', 'agentia', 'sales', 'team', 'vehicles', 'categories', 'brands'];
        foreach ($modules as $module) {
            PlanModules::create([
                'plan_id' => $plan->id,
                'module' => $module
            ]);
        }

        // INSERINDO SÓCIOS
        $this->info('Inserindo Sócios e Lojas...');
        $qtd_partners = 2;
        for ($i = 1; $i <= $qtd_partners; $i++) {
            $user = User::create([
                'name' => "Lojista " . $i,
                'email' => "lojista{$i}@mail.com",
                'password' => Hash::make('abcd@1234'),
                'role' =>  'partner',
            ]);

            $partner = Partner::create([
                'user_id' => $user->id,
                'phone' => $faker->phoneNumber,
                'status' => 'active',
                'address' =>  $faker->streetAddress,
                'partner_link' => $faker->slug(5),
                'city' => $faker->city,
                'zip_code' => $faker->postcode,
                'neighborhood' => $faker->city,
                'number' => $faker->buildingNumber,
            ]);

            // INSERINDO ASSINATURA
            $subscription = Subscription::create([
                'partner_id' => $partner->id,
                'plan_id' => $plan->id,
                'status' => 'active',
                'start_date' => Date::now(),
                'end_date' => '2026-12-12',
                'payment_method' => 'pix',
                'appellant' => true
            ]);

            // INSERINDO LOJA
            $store = Store::create([
                'store_name' => "Loja $i",
                'store_email' => "loja{$i}@mail.com",
                'store_phone' => $faker->phoneNumber(),
                'store_cpf_cnpj' => '6135573837' . $i,
                'qtd_vehicles_in_stock' => null,
                'logo' => null,
                'banner' => null,
                'partner_id' => $partner->id,
                'plan_id' => $plan->id
            ]);

            // CONFIGURAÇÕES DA LOJA (HORÁRIOS DE FUNCIONAMENTO)
            $this->info("Inserindo configurações de funcionamento para a Loja $i...");
            $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            foreach ($daysOfWeek as $day) {
                // Fechado aos domingos, aberto demais dias das 08:00 às 18:00
                $isOpen = $day !== 'sunday';
                StoreHour::create([
                    'store_id' => $store->id,
                    'day_of_week' => $day,
                    'open_time' => $isOpen ? '08:00' : null,
                    'close_time' => $isOpen ? '18:00' : null,
                    'is_open' => $isOpen
                ]);
            }

            // CONFIGURAÇÕES DE ENDEREÇO DA LOJA
            $this->info("Inserindo endereço para a Loja $i...");
            AddressStore::create([
                'store_id' => $store->id,
                'country' => 'Brasil',
                'state' => $faker->stateAbbr, // or random string
                'city' => $faker->city,
                'neighborhood' => 'Centro',
                'street' => $faker->streetName,
                'number' => $faker->buildingNumber,
                'zip_code' => $faker->postcode,
            ]);

            // INSERINDO CATEGORIAS
            $this->info("Inserindo 3 Categorias para a Loja $i...");
            $arrayCategories = ["Categoria A (Loja $i)", "Categoria B (Loja $i)", "Categoria C (Loja $i)"];
            $createdCategories = [];
            foreach ($arrayCategories as $catName) {
                $category = Category::create([
                    'name' => $catName,
                    'created_by' => $partner->id
                ]);

                $storeCategory = StoreCategories::create([
                    'description' => $faker->paragraph,
                    'store_id' => $store->id,
                    'category_id' => $category->id
                ]);
                
                $createdCategories[] = $category;
            }

            // INSERINDO MARCAS
            $this->info("Inserindo 3 Marcas para a Loja $i...");
            $arrayOfBrands = ["Marca X (Loja $i)", "Marca Y (Loja $i)", "Marca Z (Loja $i)"];
            $createdBrands = [];
            foreach ($arrayOfBrands as $brandName) {
                $brand = Brand::create([
                    'codigo' => rand(1000, 9999),
                    'name' => $brandName,
                    'type' => 'geral',
                    'partner_id' => $partner->id,
                    'logo_brand' => null
                ]);
                $createdBrands[] = $brand;
            }

            // INSERINDO PRODUTOS
            $this->info("Inserindo 10 Produtos para a Loja $i...");
            for ($j = 1; $j <= 10; $j++) {
                $randomBrand = $faker->randomElement($createdBrands);
                $randomCategory = $faker->randomElement($createdCategories);

                $product = Product::create([
                    'name' => "Produto " . str_pad($j, 2, '0', STR_PAD_LEFT) . " da Loja $i",
                    'description' => $faker->words(4, true),
                    'price' => rand(50, 500),
                    'stock' => rand(1, 20),
                    'brand_id' => $randomBrand->id,
                    'tags' => $faker->randomElement(['novo, oferta', 'destaque, moda', 'casual, unissex']),
                    'gender' => $faker->randomElement(['masculine', 'feminine']),
                    'category_id' => $randomCategory->id,
                    'partner_id' => $partner->id,
                    'image_main' => 'https://picsum.photos/seed/' . rand(1, 9999) . '/500/500',
                    'color' => $faker->randomElement(['red', 'black', 'white', 'blue', 'green'])
                ]);

                // INSERINDO IMAGENS DOS PRODUTOS
                $qtd_images = rand(1, 3);
                for ($k = 1; $k <= $qtd_images; $k++) {
                    Image::create([
                        'product_id' => $product->id,
                        'url' => 'https://picsum.photos/seed/' . rand(1, 9999) . '/500/500',
                        'mimeType' => 'image',
                    ]);
                }
            }
        }

        // INSERINDO CLIENTES
        $this->info('Inserindo clientes...');
        $qtd_clients = 5;
        for ($i = 1; $i <= $qtd_clients; $i++) {
            Client::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'address' =>  $faker->streetAddress,
                'city' => $faker->city,
                'neighborhood' => $faker->city,
                'zip_code' => $faker->postcode,
                'number' => $faker->buildingNumber,
            ]);
        }

        $this->info('Dados inseridos com sucesso!');
    }
}
