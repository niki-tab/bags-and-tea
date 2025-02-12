<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    
    public function index()
    {   

        $featuredProducts = ProductModel::where('featured', true)->get();

        //$this->setSeo();

        return view('home', compact('featuredProducts'));

    }

    public function setSeo(){

        seo()
        ->title(env('APP_NAME'), 'Tu lonja online para comprar pescado y marisco fresco')
        ->description('Encuentra pescados y mariscos frescos en nuestra tienda online. Del mar a tu casa en menos de 24 horas.')
        ->images(
            env('APP_LOGO_1_PATH'),
                env('APP_LOGO_2_PATH'),
        );
        
    }

}
