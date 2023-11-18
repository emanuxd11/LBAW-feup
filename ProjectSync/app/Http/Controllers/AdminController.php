<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Project;

class AdminController extends Controller{
    public function showAdminPage()
    {
        if (!Auth::check()){
            return redirect("/login");
        }
        if(!Auth::user()->isAdmin){
            return redirect("/projects");
        }
        if (Auth::check() && Auth::user()->isAdmin) {
            $userResults = User::all();
            $projectResults = Project::all();
            return view('pages.adminPage', [
                'userResults' => $userResults,
                'projectResults' => $projectResults,
            ]);
        }

        abort(403, 'Unauthorized'); // Or redirect to a different page
    }

    public function search(Request $request)
    {
        $userQuery = $request->input('user_query');
        $projectQuery = $request->input('project_query');

        $userResults = User::where('username','LIKE','%'.$userQuery.'%')->paginate(10);
        $projectResults = Project::where('name','LIKE','%'.$projectQuery.'%')->paginate(10);

        return view('pages.adminPage', [
            'userResults' => $userResults,
            'projectResults' => $projectResults,
        ]);
    }

    public function blockUser(Request $request){
        $userId = $request->input('userId');
        $user = User::findOrFail($userId);
        if($user->isAdmin){
            return redirect()->route('adminPage')->with('failed','Cant block an admin');
        }
        $user->isdeactivated = !$user->isdeactivated;
        $user->save();

        return redirect()->route('adminPage')->with('success','User blocked successfully');
    }
}

?>