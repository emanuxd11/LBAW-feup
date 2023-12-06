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

        if(!$project->isMember(Auth::user())){
            return redirect("/projects");
        }

        $forumPosts = Post::where('project_id', $projectId)->get();

        return view('pages.forumPage', compact('forumPosts'));
    }
    
}

?>