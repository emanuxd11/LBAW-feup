<?php

namespace App\Http\Controllers;

use App\Models\TaskComments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;

class TaskController extends Controller
{

    public function show(Request $request, $project_id,$task_id){
        if (!Auth::check()){
            return redirect("/login");
        }
        $task = Task::find($task_id);
        $project = Project::find($project_id);
        if(!$project->isMember(Auth::user()) && !Auth::user()->isAdmin){
            return redirect("/projects");
        }
        $taskComments = TaskComments::where('task_id',$task->id)->get();

        return view("pages.task",compact('task','taskComments'));
    }
    /**
     * Creates a new item.
     */
    public function create(Request $request, $project_id)
    {

        $request->validate([
            'name' => 'required|string|max:30',
            'description' => 'required|string|max:255',
            'delivery_date' => 'nullable|date',
        ]);

        $task = new Task([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'start_date' => date('Y/m/d'),
            'delivery_date' => $request->input('delivery_date'),
            'status' => 'To Do',
            'project_id' => $project_id,
        ]);

        // Check if the current user is authorized to create this task.
        $this->authorize('create', $task);
    
        // Save the task and return it as JSON.
        $task->save();
        return redirect('/projects/' . $project_id);
    }
    

    /**
     * Updates the state of an individual task.
     */
    public function update(Request $request, $id)
    {
        // Find the item.
        $task = Task::find($id);

        $request->validate([
            'name' => 'nullable|string|max:30',
            'description' => 'nullable|string|max:255',
            'delivery_date' => 'nullable|date',
            'status' => 'nullable|in:To Do,Doing,Done',
            'username' => 'nullable|string|max:255',
        ]);

        // Check if the current user is authorized to update this item.
        $this->authorize('update', $task);

        if($request->input('name')){
            $task->name = $request->input('name');
        }

        if($request->input('status')){
            $task->status = $request->input('status');
        }

        if($request->input('delivery_date')){
            $task->delivery_date = $request->input('delivery_date');
        }

        if($request->input('description')){
            $task->description = $request->input('description');
        }

        if($request->input('username')){
            $user = User::where('username', $request->input('username'))->first();

            if(!$user){
                return redirect()->route('show_task', ['project_id' => $task->project_id,'id' => $task->id])->with('error', 'Failed to find this user');
            }

            $isInTask = DB::table('projectmembertask')->where('task_id', $task->id)->where('user_id', $user->id)->exists();
            $isInProject = Project::find($task->project_id)->isMember($user);

            if(!$isInProject || $isInTask){
                return redirect()->route('show_task', ['project_id' => $task->project_id,'id' => $task->id])->with('error', 'Failed to add this user');
            }

            $data = [
                'user_id' => $user->id,
                'task_id' => $task->id,
            ];
            
            DB::table('projectmembertask')->insert($data);
        }

        // Save the item and return it as JSON.
        $task->save();
        return redirect()->route('show_task', ['project_id' => $task->project_id, 'id' => $task->id])
                    ->with('success', 'Task updated successfully.');
    }

    /**
     * Deletes a specific task.
     */
    public function delete(Request $request, $id)
    {
        // Find the item.
        $task = Task::find($id);
        $project_id = $task->project_id;

        // Check if the current user is authorized to delete this item.
        $this->authorize('delete', $task);
        DB::table('projectmembertask')->where('task_id', $task->id)->delete();
        TaskComments::where('task_id', $task->id)->delete();
        $task->delete();
        return redirect('/projects/' . $project_id)->with('success', 'Task removed successfully.');
    }

    public function removeUserFromTask(Request $request, $id){
        $task = Task::find($id);
        $this->authorize('update', $task);

        DB::table('projectmembertask')->where('task_id', $task->id)->where('user_id',$request->input('user_id'))->delete();
        return redirect()->route('show_task', ['project_id' => $task->project_id, 'id' => $task->id])
                    ->with('success', 'User removed successfully.');
    }
}
