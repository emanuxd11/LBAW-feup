<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {   
        if (Auth::user()) {
            return view('pages.projects');
        }
        return view('home');
    }
}
