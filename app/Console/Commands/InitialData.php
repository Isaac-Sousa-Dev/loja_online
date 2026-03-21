<?php

namespace App\Console\Commands;

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
        foreach($modules as $module) {
            PlanModules::create([
                'plan_id' => $plan->id,
                'module' => $module
            ]);
        }
       


        // // INSERINDO CATEGORIAS
        // $this->info('Inserindo categorias padrão...');
        // $categories = ['Camisetas', 'Vestidos', 'Polos', 'Jeans'];
        // foreach($categories as $category) {
        //     Category::create([
        //         'name' => $category,
        //         'description' => null,
        //         'default' => 1,
        //         'partner_id' => null
        //     ]);
        // }


        // // INSERINDO MARCAS
        // $this->info('Inserindo marcas padrão');
        // $brands = ['Nike', 'Adidas', 'Lacoste', 'Zara'];
        // foreach($brands as $brand) {
        //     Brand::create([
        //         'name' => $brand,
        //         'logo_brand' => null,
        //         'codigo' => $faker->randomDigit(0, 999),
        //         'partner_id' => null
        //     ]); 
        // }


        // INSERINDO SÓCIOS
        $this->info('Inserindo Sócios...');
        $qtd_partners = 4;
        for ($i = 1; $i <= $qtd_partners; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => Hash::make('abcd@1234'),
                'role' =>  'partner',
            ]);

            $partner = Partner::create([
                'user_id' => $user->id,
                'phone' => $faker->phoneNumber,
                'status' => $faker->randomElement(['active', 'inactive']),
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
                'store_name' => "Loja $user->name",
                'store_email' => "loja.$user->name@gmail.com",
                'store_phone' => $faker->phoneNumber(),
                'store_cpf_cnpj' => '61355738377',
                'qtd_vehicles_in_stock' => 2,
                'logo' => null,
                'banner' => null,
                'partner_id' => $partner->id,
                'plan_id' => $plan->id
            ]);

            // INSERINDO CATEGORIAS
            $this->info('Inserindo Categorias');
            $arrayCategories = ['Camisetas', 'Vestidos', 'Polos', 'Jeans'];
            $qtd_categories = 3;
            for($l = 0; $l <= $qtd_categories; $l++){
                $category = Category::create([
                    'name' => $arrayCategories[$l],
                    'created_by' => $partner->id
                ]);

                StoreCategories::create([
                    'description' => $faker->paragraph,
                    'store_id' => $store->id,
                    'category_id' => $category->id
                ]);

                // INSERINDO MARCAS
                $this->info('Inserindo Marcas');
                $arrayOfBrands = ['Nike', 'Adidas', 'Lacoste', 'Zara'];
                $qtd_brands = 5;
                for($n = 0; $n <= $qtd_brands; $n++){
                    $brand = Brand::create([
                        'codigo' => $n,
                        'name' => $faker->randomElement($arrayOfBrands),
                        'type' => 'teste',
                        'partner_id' => $partner->id,
                        'logo_brand' => 'teste'
                    ]);

                    // INSERINDO PRODUTOS
                    $this->info('Inserindo Produtos');
                    $qtd_products = rand(2, 3);
                    for($j = 1; $j <= $qtd_products; $j++){
                        $product = Product::create([
                            'name' => $faker->name,
                            'description' => $faker->words(3, true),
                            'price' => rand(30, 100),
                            'stock' => rand(1, 10),
                            'brand_id' => $brand->id,
                            'tags' => $faker->randomElement(['jeans, escuro, jogger', 'tenis, calçados, correr', 'longos, algodão']),
                            'gender' => $faker->randomElement(['masculine', 'feminine']),
                            'category_id' => $category->id,
                            'partner_id' => $partner->id,
                            'image_main' => $faker->randomElement([
                                'https://images.pexels.com/photos/90946/pexels-photo-90946.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500',
                                'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8cHJvZHVjdHxlbnwwfHwwfHx8MA%3D%3D',
                                'https://assets-global.website-files.com/619e8d2e8bd4838a9340a810/64c590c754d6bc13ebd90cbc_ai_product_photo_styles.webp'
                            ]),
                            'color' => $faker->randomElement(['red', 'green', 'blue'])
                        ]);

                        // INSERINDO IMAGES
                        $this->info('Inserindo Imagens para produto');
                        $qtd_images = rand(1, 3);
                        for($k = 1; $k <= $qtd_images; $k++){
                            Image::create([
                                'product_id' => $product->id,
                                'url' => $faker->randomElement([
                                    'https://images.pexels.com/photos/90946/pexels-photo-90946.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500',
                                    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8cHJvZHVjdHxlbnwwfHwwfHx8MA%3D%3D',
                                    'https://assets-global.website-files.com/619e8d2e8bd4838a9340a810/64c590c754d6bc13ebd90cbc_ai_product_photo_styles.webp'
                                ]),
                                'mimeType' => 'image',
                                
                            ]);
                        }

                    }
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
