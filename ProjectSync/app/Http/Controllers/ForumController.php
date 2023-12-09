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

class ForumController extends Controller{
    
    public function show($projectId)
    {
        if (!Auth::check()){
            return redirect("/login");
        }

        $project = Project::findOrFail($projectId);

        if(!$project->isMember(Auth::user()) && !Auth::user()->isAdmin){
            return redirect("/projects");
        }

        $forumPosts = Post::where('project_id', $projectId)->get();

        return view('pages.forumPage', compact('forumPosts'));
    }

    public function search(Request $request, $projectId)
    {
        if (!Auth::check()){
            return redirect("/login");
        }

        $project = Project::findOrFail($projectId,);

        if(!$project->isMember(Auth::user()) && !Auth::user()->isAdmin){
            return redirect("/projects");
        }

        $query = $request->input('query');

        if($request->input('best')){
            $forumPosts = Post::where('project_id', $projectId)->where('title','ilike','%'.$query.'%')->orderby('upvotes','desc')->get();
        }
        else if($request->input('newest')){
            $forumPosts = Post::where('project_id', $projectId)->where('title','ilike','%'.$query.'%')->orderby('date','asc')->get();
        }
        else if($request->input('oldest')){
            $forumPosts = Post::where('project_id', $projectId)->where('title','ilike','%'.$query.'%')->orderby('date','desc')->get();
        }
        else{
            $forumPosts = Post::where('project_id', $projectId)->where('title','ilike','%'.$query.'%')->get();
        }

        return view('pages.forumPage', compact('forumPosts'));
    }
    
}

?>