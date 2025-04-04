<?php

namespace App\Http\Controllers;



class WeBuyYourBagController extends Controller
{
    
    public function index()
    {   


        //$this->setSeo();

        return view('pages.we_buy_your_bag.show');

    }

    public function setSeo(){

        seo()
        ->title(trans('pages/we-buy-your-bag.page-seo-title'), env('APP_NAME'))
        ->description(trans('pages/we-buy-your-bag.page-seo-description'));
        
    }

}
