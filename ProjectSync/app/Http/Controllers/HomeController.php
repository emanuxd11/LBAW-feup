<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {   
        if (!Auth::user()) {
            return view('home');
        }
        if(Auth::user()->isAdmin){
            return redirect()->route('adminPage');
        }
        return view('pages.projects');
    }
}
