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
            $userResults = User::all();
            $projectResults = [];
            return view('pages.adminPage', [
                'userResults' => $userResults,
                'projectResults' => $projectResults,
            ]);
        }

        abort(403, 'Unauthorized'); // Or redirect to a different page
    }

    public function isAdmin($userId){
        return DB::table('admin')->where('id', $userId)->exists();
    }

    public function search(Request $request)
    {
        $userQuery = $request->input('user_query');
        $projectQuery = $request->input('project_query');

        $userResults = User::where('username','LIKE','%'.$userQuery.'%')->paginate(10);
        $projectResults = [];

        return view('pages.adminPage', [
            'userQuery' => $userQuery,
            'projectQuery' => $projectQuery,
            'userResults' => $userResults,
            'projectResults' => $projectResults,
        ]);
    }
}

?>