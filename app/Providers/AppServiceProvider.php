<?php

namespace App\Providers;

use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use Src\Crm\Forms\Domain\FormRepository;
use Src\Products\Product\Domain\ProductRepository;
use Src\Blog\Articles\Domain\ArticleRepository;
use Src\Cart\Domain\CartRepository;
use Src\Crm\Forms\Infrastructure\EloquentFormRepository;
use Src\Products\Product\Infrastructure\EloquentProductRepository;
use Src\Blog\Articles\Infrastructure\EloquentArticleRepository;
use Src\Cart\Infrastructure\EloquentCartRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

     public $bindings = [
        /**
         * Repositories
         */
        ProductRepository::class => EloquentProductRepository::class,
        ArticleRepository::class => EloquentArticleRepository::class,
        CartRepository::class => EloquentCartRepository::class,

    ];

    public function register(): void
    {
        Livewire::component('blog/articles/show', \Src\Blog\Articles\Frontend\ShowArticle::class);
        Livewire::component('blog/show', \Src\Blog\Articles\Frontend\ShowAllArticle::class);
        Livewire::component('admin.blog.articles.show-all', \Src\Admin\Blog\Articles\Frontend\ShowAllArticle::class);
        Livewire::component('admin.blog.articles.add-edit', \Src\Blog\Articles\Frontend\AddEditArticle::class);
        Livewire::component('shared/language-selector', \Src\Shared\Frontend\LanguageSelector::class);
        Livewire::component('shared/cookie-banner', \Src\Shared\Frontend\CookieBanner::class);
        Livewire::component('shared/pagination', \Src\Shared\Frontend\Pagination::class);
        Livewire::component('crm/forms/show', \Src\Crm\Forms\Frontend\Form::class);
        Livewire::component('shared/whatsapp-widget', \Src\Shared\Frontend\WhatsappWidget::class);
        Livewire::component('shared.search-bar', \Src\Shared\Frontend\SearchBar::class);
        Livewire::component('shared.search-bar-mobile', \Src\Shared\Frontend\SearchBarMobile::class);
        Livewire::component('products/show', \Src\Admin\Product\Frontend\ShowAllProduct::class);
        Livewire::component('admin.products.product-form', \App\Livewire\Admin\Products\ProductForm::class);
        Livewire::component('src.products.product.show', \Src\Products\Product\Frontend\ProductDetail::class);
        Livewire::component('src.products.product.additional-information', \Src\Products\Product\Frontend\ProductAdditionalInformation::class);
        
        // Cart components
        Livewire::component('cart.page', \Src\Cart\Frontend\CartPage::class);
        Livewire::component('cart.icon', \Src\Cart\Frontend\CartIcon::class);
        Livewire::component('cart.add-to-cart-button', \Src\Cart\Frontend\AddToCartButton::class);

        // Admin CRM components
        Livewire::component('admin.crm.show-all-form-submissions', \Src\Admin\Crm\Frontend\ShowAllFormSubmissions::class);
        Livewire::component('admin.crm.show-submission-detail', \Src\Admin\Crm\Frontend\ShowSubmissionDetail::class);

        $this->app->bind(FormRepository::class, EloquentFormRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
