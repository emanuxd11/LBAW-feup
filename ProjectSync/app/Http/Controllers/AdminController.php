<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Project;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\Message;
use App\Models\Changes;
use App\Models\TaskComments;


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
            $userResults = User::paginate(4,['*'], 'users');
            $projectResults = Project::paginate(4,['*'], 'projects');
            return view('pages.adminPage', [
                'userResults' => $userResults,
                'projectResults' => $projectResults,
            ]);
        }

        abort(403, 'Unauthorized'); // Or redirect to a different page
    }

    public function showCreateUserForm(){
        if (!Auth::check()){
            return redirect("/login");
        }
        if(!Auth::user()->isAdmin){
            return redirect("/projects");
        }
        if (Auth::check() && Auth::user()->isAdmin) {
            return view('pages.adminCreateUser');
        }

        abort(403, 'Unauthorized'); // Or redirect to a different page
    }

    public function showUserLogicPage(Request $request){
        if (!Auth::check()){
            return redirect("/login");
        }
        if(!Auth::user()->isAdmin){
            return redirect("/projects");
        }
        $user = User::find($request->input('userId'));

        $coordinatedProjects = $user->getCoordinatedProjects();

        $arrayToPass = [];

        foreach($coordinatedProjects as $project){
            if(count($project->members) == 1){
                $project->archived = true;
                $project->save();
                continue;
            }
            $arrayToPass[] = $project;
        }

        if($arrayToPass == []){
            if($request->input('action') == 'delete'){
                return $this->deleteUser($request);
            }
            else{
                return $this->blockUser($request);
            }
        }

        return view('pages.showUserLogic', [
            'coordinatedProjects' => $arrayToPass,
            'user' => $user,
            'action' => $request->input('action'),
        ]);

    }

    public function search(Request $request)
    {
        $userQuery = $request->input('user_query');
        $projectQuery = $request->input('project_query');

        $userResults = User::where('username','ilike','%'.$userQuery.'%')->paginate(4,['*'], 'users');
        $projectResults = Project::where('name','ilike','%'.$projectQuery.'%')->paginate(4,['*'], 'projects');

        return view('pages.adminPage', [
            'userResults' => $userResults,
            'projectResults' => $projectResults,
        ]);
    }

    public function adminAssignNewCoordinator(Request $request){
        $projectId = $request->input('projectId');
        $project = Project::findOrFail($projectId);

        $request->validate([
            'old_id' => 'required',
            'new_id' => 'required',
        ]);

        $project->members()->updateExistingPivot($request->input('old_id'), ['iscoordinator' => false]);

        $project->members()->updateExistingPivot($request->input('new_id'), ['iscoordinator' => true]);
        
        
        return $this->showUserLogicPage($request);
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:250|unique:User,username',
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:User,email',
            'phonenumber' => 'nullable|string|max:9|min:9',
            'password' => 'required|min:8|confirmed'
        ]);

        $user = Auth::user();

        if(!$user->isAdmin){
            return redirect('/');
        }

        User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'phonenumber' => $request->phonenumber,
            'password' => Hash::make($request->password),
            'isdeactivated' => false,
        ]);

        if($request->input('user-type') == 'admin'){
            $data = [
                'id' => User::where('email',$request->email)->first()->id,
            ];

            DB::table('admin')->insert($data);
        }
    
        return redirect()->route('adminPage')->with('success','User created successfully');
    }

    public function deleteUser(Request $request){
        $userId = $request->input('userId');
        $user = User::findOrFail($userId);
        if($user->isAdmin){
            return redirect()->route('adminPage')->with('error','Cannot delete an admin');
        }

        if(!Auth::user()->isAdmin){
            return redirect('/');
        }

        DB::table('projectmembertask')->where('user_id', $user->id)->delete();
        DB::table('projectmember')->where('iduser', $user->id)->delete();
        DB::table('projectmemberinvitation')->where('iduser', $user->id)->delete();
        Post::where('author_id', $userId)->update(['author_id' => null]);
        PostComment::where('author_id', $userId)->update(['author_id' => null]);
        Message::where('sender_id', $userId)->update(['sender_id'=> null]);
        Message::where('receiver_id', $userId)->update(['receiver_id'=> null]);
        Changes::where('user_id', $userId)->update(['user_id'=> null]);
        DB::table('postupvote')->where('user_id', $user->id)->delete();
        DB::table('usernotification')->where('user_id', $user->id)->delete();
        TaskComments::where('user_id', $userId)->update(['user_id' => null]);
        
        $user->delete();

        return redirect()->route('adminPage')->with('success','User deleted successfully');
    }

    public function blockUser(Request $request){
        $userId = $request->input('userId');
        $user = User::findOrFail($userId);
        if($user->isAdmin){
            return redirect()->route('adminPage')->with('error','You cannot block another administrator.');
        }
        $user->isdeactivated = !$user->isdeactivated;
        $user->save();

        if($user->isdeactivated){
            return redirect()->route('adminPage')->with('success','User blocked successfully.');
        }

        return redirect()->route('adminPage')->with('success','User unblocked successfully.');
    }

}

?>