<?php

use App\Http\Controllers\AgentAIController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CelcoinController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PopulateController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\RequestPlanController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\SubscriptionTestController;
use App\Http\Controllers\UpgradeController;
use App\Http\Controllers\UserController;
use App\Mail\UserRegistrationMail;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/preview-email', function () {

    $user = [
        'name' => 'John Doe',
        'email' => 'john@doe.com.br'
    ];

    return new UserRegistrationMail($user);
});

Route::get('/', function () {
    return view('welcome');    
})->name('welcome');


Route::middleware('auth')->group(function () {

    Route::middleware('role:admin')->group(function () {

        Route::get('populate/brands/{type}', [PopulateController::class, 'populateBrands']);


        // Routes for Partners
        Route::get('partners', [PartnerController::class, 'index'])->name('partners.index');
        Route::get('partners/create', [PartnerController::class, 'create'])->name('partners.create');
        Route::post('partners/store', [PartnerController::class, 'store'])->name('partners.store');
        Route::delete('partners/destroy/{id}', [PartnerController::class, 'destroy'])->name('partners.destroy');
        Route::get('partners/edit/{id}', [PartnerController::class, 'edit'])->name('partners.edit');
        Route::put('partners/update/{id}', [PartnerController::class, 'update'])->name('partners.update');

        // Routes for plans
        Route::get('plans', [PlanController::class, 'index'])->name('plans.index'); 
        Route::get('plans/create', [PlanController::class, 'create'])->name('plans.create');
        Route::post('plans/store', [PlanController::class, 'store'])->name('plans.store');
        Route::delete('plans/destroy/{id}', [PlanController::class, 'destroy'])->name('plans.destroy');
        Route::get('plans/edit/{id}', [PlanController::class, 'edit'])->name('plans.edit');
        Route::put('plans/update/{id}', [PlanController::class, 'update'])->name('plans.update');

         // REQUEST PLANS
        Route::get('request-plans', [RequestPlanController::class, 'listRequestPlans'])->name('list.request.plans');

        // REGISTRATION USER
        Route::post('new-subscribe-user', [UserController::class, 'newSubscribeUser'])->name('new.subscribe.user');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes for Clients
    Route::get('clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('clients/store', [ClientController::class, 'store'])->name('clients.store');
    Route::delete('clients/destroy/{id}', [ClientController::class, 'destroy'])->name('clients.destroy');
    Route::get('clients/edit/{id}', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('clients/update/{id}', [ClientController::class, 'update'])->name('clients.update');

    // Routes for Products
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products/store', [ProductController::class, 'store'])->name('products.store');
    Route::delete('products/destroy/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('products/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');
    Route::post('products/update/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::post('products/update-price-promotional', [ProductController::class, 'updatePricePromotional'])->name('products.updatePricePromotional');
    Route::post('products/update-price', [ProductController::class, 'updatePrice'])->name('products.updatePrice');
    
    // Routes for Categories
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('categories/destroy/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('categories/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::post('categories/update/{id}', [CategoryController::class, 'update'])->name('categories.update');

    // Routes for Brands
    Route::get('brands', [BrandController::class, 'index'])->name('brands.index');
    Route::post('brands/store', [BrandController::class, 'store'])->name('brands.store');
    Route::delete('brands/destroy/{id}', [BrandController::class, 'destroy'])->name('brands.destroy');
    Route::get('brands/edit/{id}', [BrandController::class, 'edit'])->name('brands.edit');
    Route::put('brands/update/{id}', [BrandController::class, 'update'])->name('brands.update');

    // Routes for Subcategories
    Route::get('subcategories', [SubcategoryController::class, 'index'])->name('subcategories.index');
    Route::get('subcategories/create', [SubcategoryController::class, 'create'])->name('subcategories.create');
    Route::post('subcategories/store', [SubcategoryController::class, 'store'])->name('subcategories.store');
    Route::delete('subcategories/destroy/{id}', [SubcategoryController::class, 'destroy'])->name('subcategories.destroy');
    Route::get('subcategories/edit/{id}', [SubcategoryController::class, 'edit'])->name('subcategories.edit');
    Route::post('subcategories/update/{id}', [SubcategoryController::class, 'update'])->name('subcategories.update');

    // Routes for Modelos
    Route::get('modelos', [ModeloController::class, 'index'])->name('modelos.index');
    Route::get('modelos/create', [ModeloController::class, 'create'])->name('modelos.create');
    Route::post('modelos/store', [ModeloController::class, 'store'])->name('modelos.store');
    Route::delete('modelos/destroy/{id}', [ModeloController::class, 'destroy'])->name('modelos.destroy');
    Route::get('modelos/edit/{id}', [ModeloController::class, 'edit'])->name('modelos.edit');
    Route::put('modelos/update/{id}', [ModeloController::class, 'update'])->name('modelos.update');

    // Route for Orders
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('orders/store', [OrderController::class, 'store'])->name('orders.store');
    Route::delete('orders/destroy/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('orders/edit/{id}', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('orders/update/{id}', [OrderController::class, 'update'])->name('orders.update');
   
    // Route for Catalog
    Route::get('requests', [RequestController::class, 'index'])->name('requests.index');
    Route::post('requests/init', [RequestController::class, 'init'])->name('requests.init');
    Route::post('requests/sold', [RequestController::class, 'sold'])->name('requests.sold');
    Route::post('requests/unsold', [RequestController::class, 'unsold'])->name('requests.unsold');

    // Route for Store
    Route::put('store/update/{id}', [StoreController::class, 'update'])->name('store.update');
    Route::post('update-hour/{id}', [StoreController::class, 'updateHour'])->name('store_hours.update');
    Route::post('configured-store/{id}', [StoreController::class, 'configuredStore'])->name('configured_store');
    Route::get('my-store-page', [StoreController::class, 'edit'])->name('store.edit'); 

    // Route for Seller 
    Route::get('selles', [SellerController::class, 'listSellers'])->name('sellers.index');
    Route::get('selles/create', [SellerController::class, 'createSeller'])->name('sellers.create');
    Route::post('selles/store', [SellerController::class, 'storeSeller'])->name('sellers.store');
    Route::get('selles/edit/{id}', [SellerController::class, 'editSeller'])->name('sellers.edit');
    Route::post('selles/destroy', [SellerController::class, 'destroySeller'])->name('sellers.destroy');
    Route::post('selles/update/{id}', [SellerController::class, 'updateSeller'])->name('sellers.update');

     // Route for Members 
    Route::get('members', [MemberController::class, 'list'])->name('members.index');
    Route::get('members/create', [MemberController::class, 'create'])->name('members.create');
    Route::post('members/store', [MemberController::class, 'store'])->name('members.store');
    Route::get('members/edit/{id}', [MemberController::class, 'edit'])->name('members.edit');
    Route::post('members/destroy/{id}', [MemberController::class, 'destroy'])->name('members.destroy');
    Route::post('members/update/{id}', [MemberController::class, 'update'])->name('members.update');

    // Route Agent IA
    Route::get('agent-ai', [AgentAIController::class, 'index'])->name('index.agent_ai');

    // Route Analytics
    Route::get('analytics', [AnalyticsController::class, 'index'])->name('index.analytics');

    // Route Sales
    Route::get('sales', [SaleController::class, 'index'])->name('index.sales');

    Route::get('upgrade', [UpgradeController::class, 'index'])->name('upgrade.index');
});





Route::post('requests/store', [RequestController::class, 'store'])->name('requests.store');
Route::get('catalog/message-sent-page/{store_partner_link}', function(Request $request) {
    $storePartnerLink = $request->store_partner_link;
    return view('orders.message-sent', ['storePartnerLink' => $storePartnerLink]);
})->name('catalog.message_sent');

// Routes for Catalog
Route::get('orders/{partner_link}', [OrderController::class, 'index'])->name('orders.index');
Route::get('orders/{partnerLink}/product/{productId}', [OrderController::class, 'getProductPage'])->name('orders.productPage');
Route::get('get-products-by-partner', [OrderController::class, 'getAllProductByPartner'])->name('orders.getAllProductByPartner');
Route::get('orders/get-products-by-category/{category_id}', [OrderController::class, 'getProductsByCategory'])->name('orders.getProductsByCategory');  
Route::get('catalog/search', [OrderController::class, 'search'])->name('catalog.search'); 

Route::get('register-new-store', [StoreController::class, 'newStorePage'])->name('index.newstore');
Route::get('new-store-info', [StoreController::class, 'newStoreInfo'])->name('store.newstore.info');
Route::post('/register-store', [RegisterController::class, 'register']);
Route::post('/check-email', [RegisterController::class, 'checkEmail']);
Route::post('/verificar-cpf-cnpj', function (\Illuminate\Http\Request $request) {
    $cpfCnpj = preg_replace('/\D/', '', $request->cpf_cnpj);
    $exists = \App\Models\Store::whereRaw("REPLACE(REPLACE(REPLACE(store_cpf_cnpj, '.', ''), '-', ''), '/', '') = ?", [$cpfCnpj])->exists();
    return response()->json(['exists' => $exists]);
});


Route::post('/payment/pix',     'CheckoutController@pixPayment');
Route::post('/payment/card',    'CheckoutController@cardPayment');

Route::post('/webhook/mercadopago', 'WebhookController@handle');

Route::post('new-request-plan', [RequestPlanController::class, 'newRequestPlan'])->name('new.request.plan');

Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');

Route::get('/testar-assinatura', [SubscriptionTestController::class, 'createPlan'])->name('subscription.test.create');
Route::get('/assinatura-teste-sucesso', [SubscriptionTestController::class, 'success'])->name('subscription.test.success');

require __DIR__.'/auth.php';
