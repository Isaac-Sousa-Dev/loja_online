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
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreHour;
use App\Models\Subcategories;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class InitialDataProd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert-data-prod';

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

        // INSERINDO PLANOS PADRÃO
        $this->info('Inserindo planos padrão...');
        $plans = [
            [
                "name" => "Test",
                "slug" => "test",
                "description" => "Teste",
                "price" => 00.00,
                "duration" => 90,   
                "type" => "unique_payment"
            ],
            [
                "name" => "Start Plus",
                "slug" => "start-plus",
                "description" => "Start Plus",
                "price" => 89.99,
                "duration" => 30,
                "type" => "monthly"
            ],
            [
                "name" => "Advanced",
                "slug" => "advanced",
                "description" => "Advanced",
                "price" => 119.99,
                "duration" => 30,
                "type" => "monthly"
            ]
        ];
        foreach($plans as $plan) {
            Plan::create([
                'name' => $plan['name'],
                'slug' => $plan['slug'],
                'description' => $plan['description'],
                'price' => $plan['price'],
                'status' => 'active',
                'duration' => $plan['duration'],
                'type' => $plan['type']
            ]);
        }

        $userPartnerDefault = User::create([
            'name' => 'Isaac Sousa',
            'email' => 'isaac.sousa.1202@gmail.com',
            'password' => Hash::make('abcd@1234'),
            'role' =>  'partner',
        ]);

        $partner = Partner::create([
            'user_id' => $userPartnerDefault->id,
            'partner_link' => 'isaac-sousa',
            'phone' => '85999999999',
            'status' => 'pending',
            'address_partner_id' => null
        ]);

        $subcription = Subscription::create([
            'partner_id' => $partner->id,
            'plan_id' => 1,
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'payment_method' => 'credit_card',
            'appellant' => 1
        ]);

        $store = Store::create([
            'store_name' => 'Loja do Isaac',
            'store_email' => 'loja@isaac.com.br',
            'store_phone' => '85999999999',
            'store_cpf_cnpj' => '123456789',
            'logo' => null,
            'banner' => null,
            'partner_id' => $partner->id,
            'plan_id' => 1
        ]);

        $addressStore = AddressStore::create([
            'store_id' => $store->id,   
            'city' => 'Rua 1',
            'street' => 'Fortaleza',
            'number' => 'CE',
            'zip_code' => '60000000',
            'neighborhood' => 'Brasil'
        ]);

        foreach(range(0, 6) as $i) {
            StoreHour::create([
                'store_id' => $store->id,
                'day_of_week' => $i,
                'open_time' => '08:00',
                'close_time' => '18:00',
                'is_open' => $i == 0 ? 0 : 1
            ]);
        }

        $this->info('Dados inseridos com sucesso!');
    }
}
