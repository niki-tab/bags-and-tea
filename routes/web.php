<?php

use App\Livewire\Cart;
use App\Livewire\Shop;

use App\Livewire\ProductDetail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;

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


Route::group(['prefix' => '{locale}', 'middleware' => 'set.language'], function () {
    
    /*Route::get('/shop', function () {
        return view('pages/shop');
    })->name('shop.show');*/
    Route::get('/tienda', function () {
        return view('pages/shop');
    })->name('shop.show.es')->where('locale', 'es');

    Route::get('/shop', function () {
        return view('pages/shop');
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
        return view('pages/we_buy_your_bag/show');
    })->name('contact.send.es')->where('locale', 'es');

    Route::get('/contact', function () {
        return view('pages/we_buy_your_bag/show');
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
        return view('pages/we_buy_your_bag/show');
    })->name('about-us.show.es')->where('locale', 'es');

    Route::get('/about-us', function () {
        return view('pages/we_buy_your_bag/show');
    })->name('about-us.show.en')->where('locale', 'en');

    Route::get('/nuestros-bolsos', function () {
        return view('pages/we_buy_your_bag/show');
    })->name('our-bags.show.es')->where('locale', 'es');

    Route::get('/our-bags', function () {
        return view('pages/we_buy_your_bag/show');
    })->name('our-bags.show.en')->where('locale', 'en');

    Route::get('/compramos-tu-bolso', function () {
        return view('pages/we_buy_your_bag/show');
    })->name('we-buy-your-bag.show.es')->where('locale', 'es');

    Route::get('/we-buy-your-bag', function () {
        return view('pages/we_buy_your_bag/show');
    })->name('we-buy-your-bag.show.en')->where('locale', 'en');

    Route::get('/certifica-tu-bolso', function () {
        return view('pages/we_buy_your_bag/show');
    })->name('certify-your-bag.show.es')->where('locale', 'es');

    Route::get('/certify-your-bag', function () {
        return view('pages/we_buy_your_bag/show');
    })->name('certify-your-bag.show.en')->where('locale', 'en');

    Route::get('/login', function () {
        return view('pages/we_buy_your_bag/show');
    })->name('login.show.en-es')->where('locale', 'en|es');

    Route::get('/carrito', function () {
        return view('pages/we_buy_your_bag/show');
    })->name('cart.edit.es')->where('locale', 'es');

    Route::get('/cart', function () {
        return view('pages/we_buy_your_bag/show');
    })->name('cart.edit.en')->where('locale', 'en');

    Route::get('/privacidad', function () {
        return view('pages/we_buy_your_bag/show');
    })->name('privacy.show.es')->where('locale', 'es');

    Route::get('/privacy', function () {
        return view('pages/we_buy_your_bag/show');
    })->name('privacy.show.en')->where('locale', 'en');

    Route::get('/cookies', function () {
        return view('pages/we_buy_your_bag/show');
    })->name('cookies.show.en-es')->where('locale', 'en|es');

    Route::get('/aviso-legal', function () {
        return view('pages/we_buy_your_bag/show');
    })->name('legal-notice.show.es')->where('locale', 'es');

    Route::get('/legal-notice', function () {
        return view('pages/we_buy_your_bag/show');
    })->name('legal-notice.show.en')->where('locale', 'en');

    Route::get('/preguntas-frecuentes', function () {
        return view('pages/we_buy_your_bag/show');
    })->name('faq.show.es')->where('locale', 'es');

    Route::get('/frequently-asked-questions', function () {
        return view('pages/we_buy_your_bag/show');
    })->name('faq.show.en')->where('locale', 'en');
    
    
    Route::get('/', [HomeController::class, 'index'])->name('home.show.en-es');
    
});


Route::get('/test', function () {
    return view('pages/test');
})->name('test');

