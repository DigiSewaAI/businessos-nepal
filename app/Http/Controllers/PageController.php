<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function features()
    {
        return view('pages.features');
    }

    public function industries()
    {
        return view('pages.industries');
    }

    public function pricing()
    {
        return view('pages.pricing');
    }

    public function changelog()
    {
        return view('pages.changelog');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function careers()
    {
        return view('pages.careers');
    }

    public function help()
    {
        return view('pages.help');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }
}