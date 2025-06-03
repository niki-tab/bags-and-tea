<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;

class HomeController extends Controller
{
    
    public function index()
    {   

        $featuredProducts = ProductEloquentModel::where('featured', true)->orderBy('featured_position', 'asc')->limit(9)->get();

        $featuredProducts = $featuredProducts->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'brand' => $product->price,
                'image' => $product->image,
                'description' => $product->description,
                // Add any other fields you need
            ];
        })->toArray();

        $this->setSeo();

        return view('home', compact('featuredProducts'));

    }

    public function setSeo(){

        

        seo()
        ->title(trans('pages/home.page-seo-title'), env('APP_NAME'))
        ->description(trans('pages/home.page-seo-description'))
        ->images(
            env('APP_LOGO_1_PATH'),
                env('APP_LOGO_2_PATH'),
        );
            
        

        
        
        
    }

}
