<?php
// ProjectController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Project;
use App\Models\User;

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
        // Create a blank new Project.
        $project = new Project([
            'name' => $request->input('name'),
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

    /**
     * Delete a project.
     */
    public function delete(Request $request, $id)
    {
        // Find the project.
        $project = Project::find($id);

        // Check if the current user is authorized to delete this project.
        $this->authorize('delete', $project);

        // Delete the project and return it as JSON.
        $project->delete();
        return response()->json($project);
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
            return response()->json(['error' => 'Cannot add an administrator to the project'], 400);
        }

        // Check if the user is already a member of the project
        $project = Project::find($projectId);
        if ($project->isMember(User::find($userId))) {
            return response()->json(['error' => 'User is already a member of the project'], 400);
        }

        // Add the user to the project
        $project->members()->attach($userId, ['iscoordinator' => false, 'isfavorite' => false]);

        return response()->json(['success' => true]);
    }
}
