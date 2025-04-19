<?php

namespace App\Providers;

use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Livewire::component('blog/articles/show', \Src\Blog\Articles\Frontend\ShowArticle::class);
        Livewire::component('blog/show', \Src\Blog\Articles\Frontend\ShowAllArticle::class);
        Livewire::component('shared/language-selector', \Src\Shared\Frontend\LanguageSelector::class);
        Livewire::component('crm/forms/show', \Src\Crm\Forms\Frontend\Form::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
