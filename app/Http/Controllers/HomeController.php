<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;

class HomeController extends Controller
{
    
    public function index()
    {   

        // Determine limit based on device (8 for mobile/tablet, 9 for desktop)
        $isMobileOrTablet = request()->header('User-Agent') && preg_match('/(Mobile|Android|iPhone|iPad|Tablet)/', request()->header('User-Agent'));
        $limit = $isMobileOrTablet ? 8 : 9;

        $featuredProducts = ProductEloquentModel::where('out_of_stock', false)
            ->where('is_sold_out', false)
            ->with(['brand', 'media'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

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
