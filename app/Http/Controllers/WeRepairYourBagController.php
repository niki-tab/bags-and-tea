<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;

class WeRepairYourBagController extends Controller
{
    
    public function index()
    {   

        $this->setSeo();

        return view('pages/repair-your-bag/show');

    }

    public function setSeo(){

        

        seo()
        ->title(trans('pages/repair-your-bag.page-seo-title'), env('APP_NAME'))
        ->description(trans('pages/repair-your-bag.page-seo-description'))
        ->images(
            env('APP_LOGO_1_PATH'),
                env('APP_LOGO_2_PATH'),
        );
            
        

        
        
        
    }

}
