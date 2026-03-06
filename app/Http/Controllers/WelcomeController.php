<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SeoHelper;

class WelcomeController extends Controller
{
    public function index()
    {
        $meta = SeoHelper::getPageMeta('welcome');
        return view('welcome', [
            'pageTitle' => $meta['title'],
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
        ]);
    }

    public function about()
    {
        $meta = SeoHelper::getPageMeta('about');
        return view('about', [
            'pageTitle' => $meta['title'],
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
        ]);
    }

    public function terms()
    {
        $meta = SeoHelper::getPageMeta('terms-of-service');
        return view('terms-of-service', [
            'pageTitle' => $meta['title'],
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
        ]);
    }

    public function privacy()
    {
        $meta = SeoHelper::getPageMeta('privacy-policy');
        return view('privacy-policy', [
            'pageTitle' => $meta['title'],
            'pageDescription' => $meta['description'],
            'pageKeywords' => $meta['keywords'],
            'ogImage' => $meta['ogImage'],
            'ogType' => $meta['ogType'],
        ]);
    }
}
