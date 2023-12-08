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

class PostController extends Controller{

    public function show(Request $request, $project_id,$id){
        $user = Auth::user();
        $project = Project::find($project_id);

        $this->authorize('show', $project);

        $post = Post::find($id);

        return view("pages.post",compact('post'));
    }

    public function create(Request $request){

        $request->validate([
            'title' => 'string|max:30',
            'description' => 'string|max:255',
        ]);

        $user = Auth::user();

        $post = new Post([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'date' => date('Y/m/d'),
            'upvotes' => 0,
            'isEdited' => false,
            'author_id'=> $user->id,
            'project_id' => $request->input('project_id'),
        ]);

        $project = Project::where('id', $request->input('project_id'))->first();

        $this->authorize('create', $project);
    
        $post->save();
        
        return redirect('projects/' . $project->id . '/forum')->with('success', 'Post created successfully.');
    }

    public function update(Request $request,$id){

        $request->validate([
            'title' => 'nullable|string|max:30',
            'description' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $post = Post::find($id);    
        $project = Project::where('id', $post->project_id)->first();

        $this->authorize('update', $project);


    
        $post->save();
        return redirect('projects/' . $project->id . '/forum/post/' . $post->id)->with('success', 'Post updated successfully.');
    }

    public function delete(Request $request,$id){

        $request->validate([
            'title' => 'nullable|string|max:30',
            'description' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        $post = new Post([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'date' => date('Y/m/d'),
            'upvotes' => 0,
            'isEdited' => false,
            'author_id'=> $user->id,
            'project_id' => $request->input('project_id'),
        ]);

        $project = Project::where('id', $request->input('project_id'))->first();

        $this->authorize('delete', $project);
    
        $post->save();
        
        return redirect('projects/' . $project->id . '/forum')->with('success', 'Post deleted successfully.');
    }

    
}

?>