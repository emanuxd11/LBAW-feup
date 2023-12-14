<?php
// ProjectController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\QueryException;

use App\Models\Project;
use App\Models\User;
use App\Models\Task;

use App\Mail\ProjectInvitation;
use App\Mail\ResetPassword;

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

        // add logic to exclude users that already have pending invitations

        return response()->json($results);
    }

    /**
     * Adds user to project.
     */
    public function inviteUserToProject(Request $request, $projectId)
    {
        $userId = $request->input('userId');

        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => "User {$userId} could not be found."], 404);
        }

        $project = Project::find($projectId);
        if (!$project) {
            return response()->json(['error' => 'Project could not be found.'], 404);
        }

        $existingInvitation = DB::table('projectmemberinvitation')
            ->where('iduser', $user->id)
            ->where('idproject', $project->id)
            ->first();

        // add logic to handle checking if the invite is still valid 
        // (less than a week old or something)
        // and if it's not you may send another one

        if ($existingInvitation) {
            return response()->json(['error' => 'This user already has a pending invitation.']);
        }

        DB::table('projectmemberinvitation')->insert([
            'iduser' => $user->id,
            'idproject' => $project->id,
        ]);

        try {
            Mail::to($user->email)->send(new ProjectInvitation($project->id, $project->name, $user->id, $user->username));
        } catch (\Exception $e) {
            \Log::error('Error sending email: ' . $e->getMessage());
            return response()->json(['error' => 'Error sending invitation email.'], 500);
        }

        return response()->json(['success' => $user->name . ' invited to project.'], 200);
    }

    public function acceptInvitationRedirect($project_id, $user_id) {
        $user = User::find($user_id);
        $project = Project::find($project_id);

        if (!$user or !$project) {
            return view('home')->with('error', 'Invalid invitation.');
        }

        if (!$this->authorize('accept_invitation', $project)) {
            return view('home')->with('error', 'You are not allowed to accept this invitation.');
        }

        return view('pages.project_accepted', [
            'project_id' => $project_id,
            'user_id' => $user_id,
        ]);
    }

    public function acceptInvitation($project_id, $user_id) {
        $project = Project::find($project_id);
        $user = User::find($user_id);

        if (!$project or !$user) {
            return view('home')->with('error', 'Invalid invitation.');
        }

        // add logic to check if the invitation is still valid


        // add user to project
        $project->members()->attach($user_id, ['iscoordinator' => false, 'isfavorite' => false]);

        // delete invitation


        return redirect()->route('project.show', ['id' => $project_id]);
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
