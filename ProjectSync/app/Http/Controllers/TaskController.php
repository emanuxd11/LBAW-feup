<?php

namespace App\Http\Controllers;

use App\Models\TaskComments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Task;
use App\Models\Notification;
use App\Models\Project;
use App\Models\User;
use App\Models\Changes;

use App\Events\NotificationEvent;

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
        if($task == null){
            return redirect("/projects/" . $project_id);
        }
        $taskComments = TaskComments::where('task_id',$task->id)->get();

        return view("pages.task",compact('task','taskComments'));
    }

    private function createNotification($receivers,$description)
    {
        $notification = Notification::create([
            'description' => $description,
            'date' => now(),
        ]);

        foreach($receivers as $user){
            $user->notifications()->attach($notification, ['ischecked' => false]);
        }
    }
    /**
     * Creates a new item.
     */
    public function create(Request $request, $project_id)
    {

        $request->validate([
            'name' => 'required|string|max:30',
            'description' => 'required|string|max:255',
            'delivery_date' => 'nullable|date|after:today',
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

        $user = Auth::user();

        $changeText = "Task {$task->name} created by $user->username.";
        $change = new Changes([
            'text' => $changeText,
            'date' => now()->format('Y-m-d H:i:s'),
            'project_id' => $task->project_id,
            'user_id' => Auth::id(),
        ]);

        $change->save();
    
        $task->save();
        return redirect('/projects/' . $project_id)->with('success', 'Task created successfully.');
    }
    

    /**
     * Updates the state of an individual task.
     */
    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        $request->validate([
            'name' => 'nullable|string|max:30',
            'description' => 'nullable|string|max:255',
            'delivery_date' => 'nullable|date|after:today',
            'status' => 'nullable|in:To Do,Doing,Done',
            'username' => 'nullable|string|max:255',
        ]);

        $changes_before = [
            'name' => $task->name,
            'description' => $task->description,
            'status' => $task->status,
            'delivery_date' => $task->delivery_date,
        ];


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

        $members = $task->members;

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
            $description ='You have been added to task ' . $task->name . ' from project ' . Project::find($task->project_id)->name;
            $this->createNotification([$user],$description);
            DB::table('projectmembertask')->insert($data);
        }

        $changes_after = [
            'name' => $task->name,
            'description' => $task->description,
            'status' => $task->status,
            'delivery_date' => $task->delivery_date,
        ];

        $user = Auth::user();
    
        // Compare changes and generate text
        $changeText = "Task $task->name updated by $user->username. Changes: ";
        $changedFields = [];
    
        foreach ($changes_after as $field => $after) {
            if ($changes_before[$field] !== $after) {
                if($field === 'description'){
                    $changedFields[] = "description updated";
                }
                else{
                    $before = "<strong>{$changes_before[$field]}</strong>";
                    $after = "<strong>{$after}</strong>";
                    $changedFields[] = "{$field} updated from {$before} to {$after}";
                }
                
            }
        }
    
        $changeText .= implode('<br>', $changedFields);

        $change = new Changes([
            'text' => $changeText,
            'date' => now()->format('Y-m-d H:i:s'),
            'project_id' => $task->project_id,
            'user_id' => Auth::id(),
        ]);

        $change->save();

        $description ='The task ' . $task->name . ' from project ' . Project::find($task->project_id)->name . ' has been updated.';
        $this->createNotification($members,$description);

        $task->save();

        return redirect()->route('show_task', ['project_id' => $task->project_id, 'id' => $task->id])
                    ->with('success', 'Task updated successfully.');
    }

    /**
     * Deletes a specific task.
     */
    public function delete(Request $request, $id)
    {
        $task = Task::find($id);
        $project_id = $task->project_id;

        $this->authorize('delete', $task);
        $description ='The task ' . $task->name . ' from project ' . Project::find($project_id)->name . ' has been deleted.';
        $this->createNotification($task->members,$description);
        DB::table('projectmembertask')->where('task_id', $task->id)->delete();
        TaskComments::where('task_id', $task->id)->delete();
        $task->delete();
        return redirect('/projects/' . $project_id)->with('success', 'Task removed successfully.');
    }

    public function removeUserFromTask(Request $request, $id){
        $task = Task::find($id);
        $this->authorize('update', $task);

        $user = User::find($request->input('user_id'));
        DB::table('projectmembertask')->where('task_id', $task->id)->where('user_id',$request->input('user_id'))->delete();

        $description ='You have been removed from task ' . $task->name . ' from project ' . Project::find($task->project_id)->name;
        $this->createNotification([$user],$description);

        return redirect()->route('show_task', ['project_id' => $task->project_id, 'id' => $task->id])
                    ->with('success', 'User removed successfully.');
    }

}
