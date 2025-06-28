<?php

use App\Livewire\Cart;
use App\Livewire\Shop;

use App\Livewire\ProductDetail;
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
        // Dashboard Home
        Route::get('/', [AdminPanelController::class, 'home'])->name('home');
        
        // Products Management
        Route::get('/products', [AdminPanelController::class, 'products'])->name('products');
        
        // Orders Management
        Route::get('/orders', [AdminPanelController::class, 'orders'])->name('orders');
        
        // Blog & Content Management
        Route::get('/blog', [AdminPanelController::class, 'blog'])->name('blog');
        
        // Blog Articles Management
        Route::prefix('blog/articles')->name('blog.articles.')->group(function () {
            Route::get('/create', function () {
                return view('pages.admin-panel.dashboard.blog.articles.show');
            })->name('create');
            
            Route::get('/edit/{id}', function ($id) {
                return view('pages.admin-panel.dashboard.blog.articles.show', ['id' => $id]);
            })->name('edit');
        });
        
        // Settings & Configuration
        Route::get('/settings', [AdminPanelController::class, 'settings'])->name('settings');
        
        // Logout (POST)
        Route::post('/logout', [AdminPanelController::class, 'logout'])->name('logout');
    });
});


Route::group(['prefix' => '{locale}', 'middleware' => 'set.language'], function () {
    
    /*Route::get('/shop', function () {
        return view('pages/shop');
    })->name('shop.show');*/
    /*Route::get('/tienda', Shop::class)->name('shop.show.es')->where('locale', 'es');

    Route::get('/shop', Shop::class)->name('shop.show.en')->where('locale', 'en');*/

    Route::get('/tienda/{categorySlug?}', function ($locale, $categorySlug = null) {
        return view('pages/shop', ['categorySlug' => $categorySlug]);
    })->name('shop.show.es')->where('locale', 'es');

    Route::get('/shop/{categorySlug?}', function ($locale, $categorySlug = null) {
        return view('pages/shop', ['categorySlug' => $categorySlug]);
    })->name('shop.show.en')->where('locale', 'en');

    Route::get('/tienda/producto/{productSlug}', function () {
        return view('pages/product-detail');
    })->name('product.show.es')->where('locale', 'es');

    Route::get('/shop/product/{productSlug}', function () {
        return view('pages/product-detail');
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
        return view('pages/we_are_under_construction/show');
    })->name('about-us.show.es')->where('locale', 'es');

    Route::get('/about-us', function () {
        return view('pages/we_are_under_construction/show');
    })->name('about-us.show.en')->where('locale', 'en');

    Route::get('/nuestros-bolsos', function () {
        return view('pages/we_are_under_construction/show');
    })->name('our-bags.show.es')->where('locale', 'es');

    Route::get('/our-bags', function () {
        return view('pages/we_are_under_construction/show');
    })->name('our-bags.show.en')->where('locale', 'en');

    Route::get('/compramos-tu-bolso/{bagName?}',[WeBuyYourBagController::class, 'index'])->name('we-buy-your-bag.show.es')->where('locale', 'es');

    Route::get('/we-buy-your-bag/{bagName?}',[WeBuyYourBagController::class, 'index'])->name('we-buy-your-bag.show.en')->where('locale', 'en');

    Route::get('/certifica-tu-bolso', function () {
        return view('pages/we_are_under_construction/show');
    })->name('certify-your-bag.show.es')->where('locale', 'es');

    Route::get('/certify-your-bag', function () {
        return view('pages/we_are_under_construction/show');
    })->name('certify-your-bag.show.en')->where('locale', 'en');

    Route::get('/login', function () {
        return view('pages/we_are_under_construction/show');
    })->name('login.show.en-es')->where('locale', 'en|es');

    Route::get('/carrito', function () {
        return view('pages/we_are_under_construction/show');
    })->name('cart.edit.es')->where('locale', 'es');

    Route::get('/cart', function () {
        return view('pages/we_are_under_construction/show');
    })->name('cart.edit.en')->where('locale', 'en');

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


