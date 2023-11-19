<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Task;
use App\Models\Project;

class TaskController extends Controller
{
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

        if($request->input('status')){
            $task->status = $request->input('status');
        }

        if($request->input('delivery_date')){
            $task->status = $request->input('delivery_date');
        }

        if($request->input('description')){
            $task->status = $request->input('description');
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

        // Check if the current user is authorized to delete this item.
        $this->authorize('delete', $task);

        // Delete the item and return it as JSON.
        $task->delete();
        return redirect('/projects/' . $task->project_id);
    }
}
