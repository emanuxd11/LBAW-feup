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

        $postComments = PostComment::where('post_id',$post->id)->get();

        return view("pages.post",compact('post','postComments'));
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
            'description' => 'nullable|string|max:255',
        ]);

        $post = Post::find($id);    
        $project = Project::find($post->project_id)->first();

        $this->authorize('update', $post);
        
        if($request->input('description')){
            $post->description = $request->input('description');
        }

        if($request->input('upvote') == 'down'){
            $post->upvotes--;
        }

        if($request->input('upvote') == 'up'){
            $post->upvotes++;
        }
    
        $post->save();
        return redirect('projects/' . $project->id . '/forum/post/' . $post->id)->with('success', 'Post updated successfully.');
    }

    public function delete(Request $request,$id){

        $user = Auth::user();
        $project = Project::find($request->input('project_id'))->first();
        $post = Post::find($id);

        $this->authorize('delete', $post);

        PostComment::where('post_id', $post->id)->delete();  
        $post->delete();
        
        return redirect('projects/' . $project->id . '/forum')->with('success', 'Post deleted successfully.');
    }

    
}

?>