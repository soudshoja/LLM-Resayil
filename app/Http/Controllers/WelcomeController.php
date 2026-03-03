<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function about()
    {
        return view('about');
    }

    public function terms()
    {
        return view('terms-of-service');
    }

    public function privacy()
    {
        return view('privacy-policy');
    }
}
