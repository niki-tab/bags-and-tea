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


Route::group(['prefix' => '{locale}', 'middleware' => 'set.locale'], function () {
    
    /*Route::get('/shop', function () {
        return view('pages/shop');
    })->name('shop.show');*/
    Route::get('/tienda', function () {
        return view('pages/shop');
    })->name('shop.show.es')->where('locale', 'es');

    Route::get('/blog', function () {
        return view('pages/blog/blog');
    })->name('blog.show.es')->where('locale', 'es');

    Route::get('/shop', function () {
        return view('pages/shop');
    })->name('shop.show.en')->where('locale', 'en');

    Route::get('/tienda/producto/{productSlug}', function () {
        return view('pages/product-detail');
    })->name('product.show.es')->where('locale', 'es');

    Route::get('/shop/product/{productSlug}', function () {
        return view('pages/product-detail');
    })->name('product.show.en')->where('locale', 'en');

    Route::get('/carrito', function () {
        return view('pages/cart');
    })->name('cart.show.es')->where('locale', 'es');

    Route::get('/cart', function () {
        return view('pages/cart');
    })->name('cart.show.en')->where('locale', 'en');

    Route::get('/contacto', function () {
        return view('pages/contact');
    })->name('contact.send.es')->where('locale', 'es');

    Route::get('/contact', function () {
        return view('pages/contact');
    })->name('contact.send.en')->where('locale', 'en');

    Route::get('/blog', function () {
        return view('pages/blog/blog');
    })->name('blog.show.es')->where('locale', 'es');
    
    
    Route::get('/', [HomeController::class, 'index'])->name('home.show');
    
});


Route::get('/test', function () {
    return view('pages/test');
})->name('test');

