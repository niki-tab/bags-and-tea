<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $this->setSeo();

        return view('pages/faq/show');
    }

    public function setSeo()
    {
        seo()
            ->title(trans('pages/faq.page-seo-title'), env('APP_NAME'))
            ->description(trans('pages/faq.page-seo-description'));
    }
}
