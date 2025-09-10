<?php

use App\Livewire\Cart;
use App\Livewire\Shop;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\WeBuyYourBagController;
use App\Http\Controllers\Admin\AdminPanelController;

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

/*Route::get('/', function () {
    return view('welcome');
});*/

// Add Livewire routes without locale prefix

// Admin Authentication Routes
Route::prefix('admin-panel')->name('admin.')->group(function () {
    // Login page (GET)
    Route::get('/login', [AdminPanelController::class, 'login'])->name('login');
    
    // Authentication (POST)
    Route::post('/authenticate', [AdminPanelController::class, 'authenticate'])->name('authenticate');
    
    // Protected Admin Routes
    Route::middleware(['admin.auth'])->group(function () {
        // Routes accessible by both admin and vendor
        // Dashboard Home
        Route::get('/', [AdminPanelController::class, 'home'])->name('home');
        
        // Products Management
        Route::get('/products', [AdminPanelController::class, 'products'])->name('products');
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/create', function () {
                return view('pages.admin-panel.dashboard.products.create');
            })->name('create');
            
            Route::get('/edit/{id}', function ($id) {
                return view('pages.admin-panel.dashboard.products.edit', ['id' => $id]);
            })->name('edit');
        });
        
        // Orders Management
        Route::get('/orders', [AdminPanelController::class, 'orders'])->name('orders');
        
        // Logout (POST)
        Route::post('/logout', [AdminPanelController::class, 'logout'])->name('logout');
        
        // Admin-only routes
        Route::middleware(['admin.only'])->group(function () {
            // Blog & Content Management
            Route::get('/blog', [AdminPanelController::class, 'blog'])->name('blog');
            
            // Blog Articles Management
            Route::prefix('blog/articles')->name('blog.articles.')->group(function () {
                Route::get('/create/{uuid}', function ($uuid) {
                    return view('pages.admin-panel.dashboard.blog.articles.show', ['uuid' => $uuid, 'mode' => 'create']);
                })->name('create');
                
                Route::get('/edit/{id}', function ($id) {
                    return view('pages.admin-panel.dashboard.blog.articles.show', ['id' => $id, 'mode' => 'edit']);
                })->name('edit');
            });
            
            // Blog Categories Management
            Route::get('/blog/categories', [AdminPanelController::class, 'blogCategories'])->name('blog.categories');
            Route::prefix('blog/categories')->name('blog.categories.')->group(function () {
                Route::get('/create', function () {
                    return view('pages.admin-panel.dashboard.blog.categories.create');
                })->name('create');
                
                Route::get('/edit/{id}', function ($id) {
                    return view('pages.admin-panel.dashboard.blog.categories.edit', ['id' => $id]);
                })->name('edit');
            });
            
            // Categories Management
            Route::get('/categories', [AdminPanelController::class, 'categories'])->name('categories');
            Route::prefix('categories')->name('categories.')->group(function () {
                Route::get('/create', function () {
                    return view('pages.admin-panel.dashboard.categories.create');
                })->name('create');
                
                Route::get('/edit/{id}', function ($id) {
                    return view('pages.admin-panel.dashboard.categories.edit', ['id' => $id]);
                })->name('edit');
            });
            
            // Attributes Management
            Route::get('/attributes', [AdminPanelController::class, 'attributes'])->name('attributes');
            Route::prefix('attributes')->name('attributes.')->group(function () {
                Route::get('/create', function () {
                    return view('pages.admin-panel.dashboard.attributes.create');
                })->name('create');
                
                Route::get('/edit/{id}', function ($id) {
                    return view('pages.admin-panel.dashboard.attributes.edit', ['id' => $id]);
                })->name('edit');
            });
            
            // Settings & Configuration
            Route::get('/settings', [AdminPanelController::class, 'settings'])->name('settings');
            
            // Shipping Management
            Route::get('/shipping', [AdminPanelController::class, 'shipping'])->name('shipping');
            
            // Marketplace Fees Management
            Route::get('/marketplace-fees', [AdminPanelController::class, 'marketplaceFees'])->name('marketplace-fees');
            
            // Form Submissions Management
            Route::get('/form-submissions', [AdminPanelController::class, 'formSubmissions'])->name('form-submissions');
            Route::get('/form-submissions/{id}', [AdminPanelController::class, 'formSubmissionDetail'])->name('form-submissions.detail');
        });
    });
});


Route::group(['prefix' => '{locale}', 'middleware' => 'set.language'], function () {
    
    /*Route::get('/shop', function () {
        return view('pages/shop');
    })->name('shop.show');*/
    /*Route::get('/tienda', Shop::class)->name('shop.show.es')->where('locale', 'es');

    Route::get('/shop', Shop::class)->name('shop.show.en')->where('locale', 'en');*/

    Route::get('/tienda/{slug?}', function ($locale, $slug = null) {
        return view('pages/shop', ['categorySlug' => $slug]);
    })->name('shop.show.es')->where('locale', 'es');

    Route::get('/shop/{slug?}', function ($locale, $slug = null) {
        return view('pages/shop', ['categorySlug' => $slug]);
    })->name('shop.show.en')->where('locale', 'en');

    Route::get('/tienda/producto/{productSlug}', function ($locale, $productSlug) {
        return view('pages/product-detail/show', ['productSlug' => $productSlug]);
    })->name('product.show.es')->where('locale', 'es');

    Route::get('/shop/product/{productSlug}', function ($locale, $productSlug) {
        return view('pages/product-detail/show', ['productSlug' => $productSlug]);
    })->name('product.show.en')->where('locale', 'en');

    /*Route::get('/carrito', function () {
        return view('pages/cart');
    })->name('cart.show.es')->where('locale', 'es');

    Route::get('/cart', function () {
        return view('pages/cart');
    })->name('cart.show.en')->where('locale', 'en');*/

    Route::get('/contacto', function () {
        return view('pages/contact/show');
    })->name('contact.send.es')->where('locale', 'es');

    Route::get('/contact', function () {
        return view('pages/contact/show');
    })->name('contact.send.en')->where('locale', 'en');

    Route::get('/blog', function () {
        return view('pages/blog/show');
    })->name('blog.show.en-es')->where('locale', 'en|es');

    Route::get('/blog/articulo/{articleSlug}', function () {
        return view('pages/blog/articles/show');
    })->name('article.show.es')->where('locale', 'es');

    Route::get('/blog/article/{articleSlug}', function () {
        return view('pages/blog/articles/show');
    })->name('article.show.en')->where('locale', 'en');

    Route::get('/quienes-somos', function () {
        return view('pages/about-us/show');
    })->name('about-us.show.es')->where('locale', 'es');

    Route::get('/about-us', function () {
        return view('pages/about-us/show');
    })->name('about-us.show.en')->where('locale', 'en');

    Route::get('/nuestros-bolsos', function () {
        return view('pages/we_are_under_construction/show');
    })->name('our-bags.show.es')->where('locale', 'es');

    Route::get('/our-bags', function () {
        return view('pages/we_are_under_construction/show');
    })->name('our-bags.show.en')->where('locale', 'en');

    Route::get('/compramos-tu-bolso/{bagName?}',[WeBuyYourBagController::class, 'index'])->name('we-buy-your-bag.show.es')->where('locale', 'es');

    Route::get('/we-buy-your-bag/{bagName?}',[WeBuyYourBagController::class, 'index'])->name('we-buy-your-bag.show.en')->where('locale', 'en');

    Route::get('/repara-tu-bolso', function () {
        return view('pages/repair-your-bag/show');
    })->name('repair-your-bag.show.es')->where('locale', 'es');

    Route::get('/repair-your-bag', function () {
        return view('pages/repair-your-bag/show');
    })->name('repair-your-bag.show.en')->where('locale', 'en');

    Route::get('/certifica-tu-bolso', function () {
        return view('pages/we_are_under_construction/show');
    })->name('certify-your-bag.show.es')->where('locale', 'es');

    Route::get('/certify-your-bag', function () {
        return view('pages/we_are_under_construction/show');
    })->name('certify-your-bag.show.en')->where('locale', 'en');

    Route::get('/login', function () {
        return view('pages/auth/show');
    })->name('login.show.en-es')->where('locale', 'en|es')->middleware('guest');

    Route::get('/carrito', function () {
        return view('pages/cart/show');
    })->name('cart.show.es')->where('locale', 'es');

    Route::get('/cart', function () {
        return view('pages/cart/show');
    })->name('cart.show.en')->where('locale', 'en');

    Route::get('/finalizar-compra', function () {
        return view('pages/checkout/show');
    })->name('checkout.show.es')->where('locale', 'es');

    Route::get('/checkout', function () {
        return view('pages/checkout/show');
    })->name('checkout.show.en')->where('locale', 'en');

    Route::get('/pedido-confirmado/{order_number}', function ($locale, $order_number) {
        return view('pages/checkout/success', ['order_number' => $order_number]);
    })->name('checkout.success.es')->where('locale', 'es');

    Route::get('/order-confirmed/{order_number}', function ($locale, $order_number) {
        return view('pages/checkout/success', ['order_number' => $order_number]);
    })->name('checkout.success.en')->where('locale', 'en');

    Route::get('/privacidad', function () {
        return view('pages/legal/policy');
    })->name('privacy.show.es')->where('locale', 'es');

    Route::get('/privacy', function () {
        return view('pages/legal/policy');
    })->name('privacy.show.en')->where('locale', 'en');

    Route::get('/cookies', function () {
        return view('pages/legal/cookies');
    })->name('cookies.show.en-es')->where('locale', 'en|es');

    Route::get('/aviso-legal', function () {
        return view('pages/legal/legal');
    })->name('legal-notice.show.es')->where('locale', 'es');

    Route::get('/legal-notice', function () {
        return view('pages/legal/legal');
    })->name('legal-notice.show.en')->where('locale', 'en');

    Route::get('/preguntas-frecuentes', function () {
        return view('pages/we_are_under_construction/show');
    })->name('faq.show.es')->where('locale', 'es');

    Route::get('/frequently-asked-questions', function () {
        return view('pages/we_are_under_construction/show');
    })->name('faq.show.en')->where('locale', 'en');
    
    
    Route::get('/', [HomeController::class, 'index'])->name('home.show.en-es');

    Route::get('/calendar-test', function () {
        return view('pages/calendar-test');
    })->name('calendar-test.en-es')->where('locale', 'en|es');

    Route::get('/my-account', function () {
        return view('pages/my-account');
    })->name('my-account.show.en')->where('locale', 'en')->middleware('auth');

    Route::get('/mi-cuenta', function () {
        return view('pages/my-account');
    })->name('my-account.show.es')->where('locale', 'es')->middleware('auth');
    
});


Route::get('/test', function () {
    return view('pages/test');
})->name('test');


/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
|
| Routes for the admin panel with authentication and authorization.
| All admin routes are prefixed with 'admin-panel' and use 'admin.' names.
|
*/


