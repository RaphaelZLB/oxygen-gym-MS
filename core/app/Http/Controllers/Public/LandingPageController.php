<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class LandingPageController extends Controller
{
    public function index(): View
    {
        return view('landing.index');
    }
}
