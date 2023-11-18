<?php
// ProjectController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\Project;

class ProjectController extends Controller
{
    /**
     * Show the project for a given id.
     */
    public function show(string $id): View
    {
        // Get the project.
        $project = Project::findOrFail($id);

        // Check if the current user can see (show) the project.
        $this->authorize('show', $project);  

        // Use the pages.project template to display the project.
        return view('pages.project', [
            'project' => $project
        ]);
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

        } else {
            // The user is logged in.

            // Get projects for user ordered by id.
            $projects = Auth::user()->projects()->orderBy('id')->get();

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
        $project = new Project();

        // Check if the current user is authorized to create this project.
        $this->authorize('create', $project);

        // Set project details.
        $project->name = $request->input('name');
        $project->user_id = Auth::user()->id;

        // Save the project and return it as JSON.
        $project->save();
        return response()->json($project);
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
}
