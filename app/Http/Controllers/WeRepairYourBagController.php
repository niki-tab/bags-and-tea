<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;

class WeRepairYourBagController extends Controller
{
    
    public function index()
    {
        $this->setSeo();

        return view('pages/repair-your-bag/show', [
            'images' => $this->getImages(),
        ]);
    }

    public function setSeo()
    {
        seo()
        ->title(site_trans('pages/repair-your-bag.page-seo-title'), env('APP_NAME'))
        ->description(site_trans('pages/repair-your-bag.page-seo-description'))
        ->images(
            env('APP_LOGO_1_PATH'),
            env('APP_LOGO_2_PATH'),
        );
    }

    private function getImages(): array
    {
        return match(site_slug()) {
            'walletsandtea' => [
                'hero' => asset('images/sites/walletsandtea/repair-your-bag1.png'),
                'process_left' => asset('images/sites/walletsandtea/repair-your-bag2.png'),
                'process_right' => asset('images/sites/walletsandtea/repair-your-bag3.png'),
            ],
            default => [ // bagsandtea
                'hero' => asset('images/repair-your-bag/repair-your-bag1.png'),
                'process_left' => asset('images/repair-your-bag/repair-your-bag2.png'),
                'process_right' => asset('images/repair-your-bag/repair-your-bag3.png'),
            ],
        };
    }

}
