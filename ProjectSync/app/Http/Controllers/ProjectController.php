<?php
// ProjectController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Models\Project;
use App\Models\User;
use App\Models\Task;

use App\Http\Controllers\AdminController;

class ProjectController extends Controller
{
    /**
     * Show the project for a given id.
     */
    public function show(string $id)
    {
        try {
            // Get the project.
            $project = Project::findOrFail($id);

            // Check if the current user can see (show) the project.
            $this->authorize('show', $project);

            // User is authorized, continue with displaying the project.
            return view('pages.project', [
                'project' => $project
            ]);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect('/projects');
        }
    }

    /**
     * Shows all projects.
     */
    public function list()
    {
        // Check if the user is logged in.
        if (!Auth::check()) {
            // Not logged in, redirect to login.
            return redirect('/login');

        }
        else if(Auth::user()->isAdmin){
            return redirect('/adminPage');
        } else {
            // The user is logged in.

            // Get projects for user ordered by id.
            $projects = Auth::user()->projects;

            // Check if the current user can list the projects.
            $this->authorize('list', Project::class);

            // The current user is authorized to list projects.

            // Use the pages.projects template to display all projects.
            return view('pages.projects', [
                'projects' => $projects
            ]);
        }
    }

    /**
     * Creates a new project.
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:30',
            'description' => 'required|string|max:3000',
            'delivery_date' => 'nullable|date|after:today',
        ]);
        // Create a blank new Project.
        $project = new Project([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'start_date' => date('Y/m/d'),
            'delivery_date' => $request->input('delivery_date'),
            'archived' => false,
        ]);

        // Check if the current user is authorized to create this project.
        $this->authorize('create', $project);

        // Save the project and return it as JSON.
        $project->save();

        // Add coordinator
        $data = [
            'iduser' => Auth::user()->id,
            'idproject' => $project->id,
            'iscoordinator' => true,
        ];
        DB::table('projectmember')->insert($data);
        
        return redirect('/projects');
    }

    public function update(Request $request,$project_id)
    {
        $request->validate([
            'description' => 'nullable|string|max:3000',
            'delivery_date' => 'nullable|date|after:today',
        ]);
        
        $project = Project::find($project_id);

        $this->authorize('update', $project);

        $madeChange = false;

        if($request->input('description')){
            $project->delivery_datew = $request->input('description');
            $madeChange = true;
        }

        if($request->input('delivery_date')){
            $project->delivery_datew = $request->input('delivery_date');
            $madeChange = true;
        }

        if(!$madeChange){
            return redirect('/projects/' . $project_id)->with('error', 'Nothing was changed because user did not provide anything to change');
        }

        $project->save();
        
        return redirect('/projects/' . $project_id)->with('success', 'Project updated');
    }

    /**
     * Archive a project.
     */
    public function archive($project_id)
    {
        $project = Project::findOrFail($project_id);

        $this->authorize('archive', $project);

        if ($project->archived) {
            return redirect('/projects')->with('error','This project is already archived.');
        }

        $project->archived = true;
        $project->save();

        return redirect('/projects')->with('success', '\"' . $project->name . '\" archived.');
    }

    /**
     * Searches users, based on their name or username,
     * that are available to join a project.
     */
    public function search_user(Request $request, $projectId)
    {
        $term = $request->input('term');

        // Get the project members
        $projectMembers = Project::findOrFail($projectId)->members()->pluck('id')->toArray();
        
        // Perform your user search based on the $term (case-insensitive) and exclude project members
        $results = User::where(function ($query) use ($term) {
            $query->where('name', 'ilike', '%' . $term . '%') // ilike for case-insensitive search
                ->orWhere('username', 'ilike', '%' . $term . '%');
        })
        ->whereNotIn('id', $projectMembers)
        ->whereNotIn('id', function ($query) {
            $query->select('id')->from('admin');
        })
        ->get();

        return response()->json($results);
    }

    /**
     * Adds user to project.
     */
    public function addUserToProject(Request $request, $projectId)
    {
        $userId = $request->input('userId');

        // Check if the user is an administrator
        $user = User::find($userId);
        if ($user && $user->isAdmin) {
            return response()->json(['error' => 'You cannot add an administrator to the project.'], 400);
        }

        // Check if the user is already a member of the project
        $project = Project::find($projectId);
        if ($project->isMember(User::find($userId))) {
            return response()->json(['error' => 'This user is already a member of the project.'], 400);
        }

        // Add the user to the project
        $project->members()->attach($userId, ['iscoordinator' => false, 'isfavorite' => false]);

        return response()->json(['success' => true]);
    }

    public function search_task(Request $request, $projectId)
    {
        $term = $request->input('term');

        // Get the project members
        $tasks = Project::findOrFail($projectId)->tasks()->pluck('id')->toArray();
        
        // Perform your user search based on the $term (case-insensitive) and exclude project members
        $results = Task::where(function ($query) use ($term) {
            $query->where('name', 'ilike', '%' . $term . '%') // ilike for case-insensitive search
                ->orWhere('status', 'ilike', '%' . $term . '%');
        })
        ->whereIn('id', $tasks)
        ->get();

        return response()->json($results);
    }

    public function remove_member($projectId, $userId)
    {
        $project = Project::findOrFail($projectId);

        $this->authorize('remove_member', [$project, Auth::user()]);

        $project->members()->detach($userId);

        // Remove the user from all tasks in the project
        foreach ($project->tasks as $task) {
            $task->members()->detach($userId);
        }

        return redirect()->back()->with('success', 'User removed successfully.');
    }

    public function member_leave($projectId, $userId)
    {
        $project = Project::findOrFail($projectId);

        $this->authorize('member_leave', [$project, Auth::user()]);

        $project->members()->detach($userId);

        // Remove the user from all tasks in the project
        foreach ($project->tasks as $task) {
            $task->members()->detach($userId);
        }

        return redirect()->back()->with('success', 'You are no longer part of \"' . $project->name . '\"!');
    }

    public function assignNewCoordinator(Request $request, $projectId){
        $project = Project::findOrFail($projectId);

        $request->validate([
            'old_id' => 'required',
            'new_id' => 'required',
        ]);

        $this->authorize('assign_new_coordinator', [$project, Auth::user()]);

        $project->members()->updateExistingPivot($request->input('old_id'), ['iscoordinator' => false]);

        $project->members()->updateExistingPivot($request->input('new_id'), ['iscoordinator' => true]);

        return redirect()->back()->with('success', 'Coordinator changed successfully.');
    }

    
    public function favorite(Request $request, $project_id)
    {
        $user = Auth::user();
        $project = Project::findOrFail($project_id);

        $this->authorize('favorite', [$project, $user]);

        $previous_status = $project->isFavoriteOf($user);

        DB::table('projectmember')
            ->updateOrInsert(
                ['iduser' => $user->id, 'idproject' => $project->id],
                ['isfavorite' => !$previous_status]
            );

        // Respond with a JSON indicating the new favorite status
        return response()->json(['is_favorite' => !$previous_status]);
    }
}
