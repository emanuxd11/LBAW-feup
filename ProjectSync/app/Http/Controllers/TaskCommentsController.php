<?php

namespace App\Http\Controllers;

use App\Models\TaskComments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;

class TaskCommentsController extends Controller
{
    public function create(Request $request, $id)
    {

        $request->validate([
            'comment' => 'required|string|max:30',
        ]);

        $taskComment = new TaskComments([
            'comment' => $request->input('comment'),
            'created_at' => date('Y/m/d'),
            'isedited' => false,
            'user_id' => Auth::user()->id,
            'task_id' => $id,
        ]);

        $task = Task::find($id);
        $this->authorize('create', $task);
    
        $taskComment->save();
        return redirect()->route('show_task', ['project_id' => $task->project_id, 'id' => $task->id])
        ->with('success', 'Comment created successfully.');
    }
    
    public function update(Request $request, $id)
    {

        $task = Task::find($id);

        $request->validate([
            'comment' => 'nullable|string|max:255',
        ]);

        $this->authorize('update', $task);

        $taskComment = TaskComments::find($request->input('id'));

        if(!$request->input('comment')){
            return redirect()->route('show_task', ['project_id' => $task->project_id, 'id' => $task->id])
                    ->with('error', 'New comment cannot be empty');
        }

        $taskComment->comment = $request->input('comment');
        $taskComment->save();
        return redirect()->route('show_task', ['project_id' => $task->project_id, 'id' => $task->id])
                    ->with('success', 'Comment updated successfully.');
    }

    public function delete(Request $request, $id)
    {
        $task = Task::find($id);

        $this->authorize('delete', $task);

        $taskComment = TaskComments::find($request->input('id'));
        $taskComment->delete();

        return redirect()->route('show_task', ['project_id' => $task->project_id, 'id' => $task->id])
                    ->with('success', 'Comment deleted successfully.');
    }

}
