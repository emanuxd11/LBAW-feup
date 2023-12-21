<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Changes;

class ChangesController extends Controller
{
    public function index()
    {
        $changes = Changes::all();
        return view('changes.index', compact('changes'));
    }

    public function show($id)
    {
        $change = Changes::find($id);
        return view('changes.show', compact('change'));
    }
}
