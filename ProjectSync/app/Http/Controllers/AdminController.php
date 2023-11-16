<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\User;

class AdminController extends Controller{
    public function showAdminPage()
    {
        if (!Auth::check()){
            return redirect("/login");
        }
        if(!$this->isAdmin(Auth::user()->id)){
            return redirect("/cards");
        }
        if (Auth::check() && $this->isAdmin(Auth::user()->id)) {
            return view('pages.adminPage');
        }

        abort(403, 'Unauthorized'); // Or redirect to a different page
    }

    public function isAdmin($userId){
        return DB::table('admin')->where('id', $userId)->exists();
    }
}

?>