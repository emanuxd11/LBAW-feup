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

class PostCommentController extends Controller{

    public function create(Request $request){

        $request->validate([
            'comment' => 'string|max:255',
        ]);

        $user = Auth::user();

        $postComment = new PostComment([
            'comment' => $request->input('comment'),
            'date' => date('Y/m/d'),
            'isEdited' => false,
            'author_id'=> $user->id,
            'parent_comment_id' => $request->input('parent_comment_id'),
            'post_id' => $request->input('post_id'),
        ]);

        $project = Project::find($request->input('project_id'));

        $this->authorize('create', $project);
    
        $postComment->save();
        
        return redirect('projects/' . $project->id . '/forum/post/' . $request->input('post_id'))->with('success', 'Comment created successfully.');
    }

    public function update(Request $request,$id){

        $request->validate([
            'comment' => 'string|max:255',
        ]);

        $postComment = PostComment::find($id);    
        $this->authorize('update', $postComment);

        $postComment->comment = $request->input('comment');
        $postComment->isedited = true;
        $post = $postComment->post;
        $project = Project::find($post->project_id)->first();
        $postComment->save();
        return redirect('projects/' . $project->id . '/forum/post/' . $post->id)->with('success', 'Comment updated successfully.');
    }

    public function delete(Request $request,$id){

        $project = Project::find($request->input('project_id'))->first();
        $postComment = PostComment::find($id);

        $this->authorize('delete', $postComment);
        $post = $postComment->post;
        $postComment->delete();
        
        return redirect('projects/' . $project->id . '/forum/post/' . $post->id)->with('success', 'Comment deleted successfully.');
    }

    
}

?>