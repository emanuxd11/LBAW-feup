<?php

namespace App\Http\Controllers;

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
        if(!$project->isMember(Auth::user())){
            return redirect("/projects");
        }
        return view("pages.task",compact('task'));
    }
    /**
     * Creates a new item.
     */
    public function create(Request $request, $project_id)
    {
        // Create a blank new task.
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
     * Updates the state of an individual item.
     */
    public function update(Request $request, $id)
    {
        // Find the item.
        $task = Task::find($id);

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
            $data = [
                'user_id' => $user->id,
                'task_id' => $task->id,
            ];
            
            DB::table('projectmembertask')->insert($data);
        }

        // Save the item and return it as JSON.
        $task->save();
        return redirect('/projects/' . $task->project_id);
    }

    /**
     * Deletes a specific item.
     */
    public function delete(Request $request, $id)
    {
        // Find the item.
        $task = Task::find($id);
        $project_id = $task->project_id;

        // Check if the current user is authorized to delete this item.
        $this->authorize('delete', $task);
        DB::table('projectmembertask')->where('task_id', $task->id)->delete();
        $task->delete();
        return redirect('/projects/' . $project_id);
    }
}
