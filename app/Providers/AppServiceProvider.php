<?php

namespace App\Providers;

use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use Src\Crm\Forms\Domain\FormRepository;
use Src\Products\Product\Domain\ProductRepository;
use Src\Blog\Articles\Domain\ArticleRepository;
use Src\Crm\Forms\Infrastructure\EloquentFormRepository;
use Src\Products\Product\Infrastructure\EloquentProductRepository;
use Src\Blog\Articles\Infrastructure\EloquentArticleRepository;

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
        Livewire::component('products/show', \Src\Admin\Product\Frontend\ShowAllProduct::class);
        Livewire::component('admin.products.product-form', \App\Livewire\Admin\Products\ProductForm::class);
        Livewire::component('src.products.product.show', \Src\Products\Product\Frontend\ProductDetail::class);
        Livewire::component('src.products.product.additional-information', \Src\Products\Product\Frontend\ProductAdditionalInformation::class);

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
