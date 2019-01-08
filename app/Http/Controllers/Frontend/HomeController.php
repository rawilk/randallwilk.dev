<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BaseController;

class HomeController extends BaseController
{
    /**
     * Display the home page of the site.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('frontend.index');
    }
}
