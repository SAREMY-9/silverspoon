<?php

namespace App\Http\Controllers;

class LegalController extends Controller
{
    /**
     * Display Silver Spoon Terms & Conditions.
     */
    public function terms()
    {
        return view('legal.terms');
    }

    
    public function privacy()
    {
        return view('legal.privacy');
    }

}

