<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Lang;



class WeBuyYourBagController extends Controller
{
    
    public function index()
    {   


        $this->setSeo();

        return view('pages.we_buy_your_bag.show');

    }

    public function setSeo(){

        if(request()->route('bagName')){

            $bagName = request()->route('bagName');
    
            $translationKey = 'pages/we-buy-your-bag.page-seo-title-' . $bagName;
            $seoMetaTitle = Lang::has(key: $translationKey) 
                ? trans($translationKey) 
                : trans('pages/we-buy-your-bag.page-seo-title');    
            
            $translationKey = 'pages/we-buy-your-bag.page-seo-description-' . $bagName;
            $seoMetaDescription = Lang::has(key: $translationKey) 
                ? trans($translationKey) 
                : trans('pages/we-buy-your-bag.page-seo-description');    

                seo()
            ->title($seoMetaTitle, env('APP_NAME'))
            ->description($seoMetaDescription);

        }else{

            seo()
            ->title(trans('pages/we-buy-your-bag.page-seo-title'), env('APP_NAME'))
            ->description(trans('pages/we-buy-your-bag.page-seo-description'));
            
        }

        
        
        
    }

}
