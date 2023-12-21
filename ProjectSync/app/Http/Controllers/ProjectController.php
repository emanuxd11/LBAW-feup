<?php
// ProjectController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;

use App\Models\Project;
use App\Models\Notification;
use App\Models\User;
use App\Models\Task;
use App\Models\Changes;

use App\Mail\ProjectInvitation;
use App\Mail\ResetPassword;

use App\Http\Controllers\AdminController;

use App\Events\NotificationEvent;

use Carbon\Carbon;


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
            'isfavorite' => false,
            'iscoordinator' => true,
        ];
        DB::table('projectmember')->insert($data);
        
        return redirect('/projects' . '/' . $project->id);
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
            $project->description = $request->input('description');
            $madeChange = true;
        }

        if($request->input('delivery_date')){
            $project->delivery_date = $request->input('delivery_date');
            $madeChange = true;
        }

        if(!$madeChange){
            return redirect('/projects/' . $project_id)->with('error', 'Nothing was changed because user did not provide anything to change');
        }

        $user = Auth::user();

        $changeText = "Project updated by $user->username.";
        $change = new Changes([
            'text' => $changeText,
            'date' => now()->format('Y-m-d H:i:s'),
            'project_id' => $project_id,
            'user_id' => Auth::id(),
        ]);

        $change->save();

        $description ='The project ' . $project->name . ' was updated.';
        $members = $project->members;
        $this->createNotification($members,$description);

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

        $description ='The project ' . $project->name . ' was archived.';
        $members = $project->members;
        $this->createNotification($members,$description);

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

        $pendingInvitations = DB::table('projectmemberinvitation')
            ->where('idproject', $projectId)
            ->pluck('iduser')
            ->toArray();

            
        $results->each(function ($user) use ($pendingInvitations) {
            $user->hasPendingInvitation = in_array($user->id, $pendingInvitations);
        });
        
        return response()->json($results);
    }

    /**
     * Invites user to project.
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

        $token = Str::random(32);

        DB::table('projectmemberinvitation')->insert([
            'iduser' => $user->id,
            'idproject' => $project->id,
            'created_at' => now(),
            'invitation_token' => $token,
        ]);

        $description = 'Invited user ' . $user->username . ' to project.';
        event(new NotificationEvent($request->id,$description));

        try {
            Mail::to($user->email)->send(new ProjectInvitation($token, $project->id, $project->name, $user->id, $user->username));
        } catch (\Exception $e) {
            \Log::error('Error sending email: ' . $e->getMessage());
            return response()->json(['error' => 'Error sending invitation email.'], 500);
        }

        return response()->json(['success' => $user->name . ' invited to project.'], 200);
    }

    public function acceptInvitationRedirect($project_id, $user_id, $token) {
        if (!Auth::user()) {
            return redirect()->route('login')->with('error', 'You must be logged in to accept this invitation.');
        }

        return view('pages.project_accepted', [
            'project_id' => $project_id,
            'user_id' => $user_id,
            'token' => $token,
        ]);
    }

    public function acceptInvitation($project_id, $user_id, $token) {
        $project = Project::find($project_id);
        $user = User::find($user_id);
        $oneWeekAgo = Carbon::now()->subWeek();
        $invitation = DB::table('projectmemberinvitation')
            ->where('iduser', $user->id)
            ->where('idproject', $project->id)
            ->where('created_at', '>', $oneWeekAgo)
            ->where('invitation_token', $token)
            ->first();

        if (!$project or !$user or !$invitation or $user_id != Auth::user()->id) {
            return redirect()->route('home')->with('error', 'Invalid project invitation.');
        }

        // add user to project
        $project->members()->attach($user_id, ['iscoordinator' => false, 'isfavorite' => false]);

        // delete invitation
        DB::table('projectmemberinvitation')
            ->where('iduser', $invitation->iduser)
            ->where('idproject', $invitation->idproject)
            ->delete();

        $description ='The user ' . $user->username . ' accepted the invite to join project ' . $project->name  . '.';
        $member = $project->getCoordinator();
        $this->createNotification([$member],$description);

        $new_user = User::find($user_id);

        $changeText = "User {$new_user->username} joined project.";
        $change = new Changes([
            'text' => $changeText,
            'date' => now()->format('Y-m-d H:i:s'),
            'project_id' => $project_id,
            'user_id' => Auth::id(),
        ]);

        $change->save();

        return redirect()->route('project.show', ['id' => $project_id])->with('success', 'You\'re now part of "' . $project->name . '"!');
    }

    /**
     * Deletes all invitations for a specific user from the database.
     */
    public function revokeInvitations($project_id, $user_id) {
        $project = Project::findOrFail($project_id);
        if (!$project or !$user_id) {
            return redirect()->back()->with('error', 'Error cancelling invitation.');
        }

        $this->authorize('revoke_invitations', [$project, Auth::user()]);

        // delete all invitations for the user
        try {
            DB::table('projectmemberinvitation')
                ->where('iduser', $user_id)
                ->where('idproject', $project_id)
                ->delete();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error cancelling invitation.');

        }

        return redirect()->back()/*->with('success', 'Invitation cancelled.')*/;
    }

    public function search_task(Request $request, $projectId)
    {
        $term = $request->input('term');

        $tasks = Project::findOrFail($projectId)->tasks()->pluck('id')->toArray();
        
        $results = Task::where(function ($query) use ($term) {
            $query->where('name', 'ilike', '%' . $term . '%')
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

        $removedUser = User::find($userId);

        $project->members()->detach($userId);

        // Remove the user from all tasks in the project
        foreach ($project->tasks as $task) {
            $task->members()->detach($userId);
        }

        $author = Auth::user();

        $changeText = "User {$removedUser->username} removed from project by $author->username.";
        $change = new Changes([
            'text' => $changeText,
            'date' => now()->format('Y-m-d H:i:s'),
            'project_id' => $projectId,
            'user_id' => Auth::id(),
        ]);

        $description ='You have been removed from project ' . $project->name . '.';
        $member = User::find($userId);
        $this->createNotification([$member],$description);

        return redirect()->back()/* ->with('success', 'User removed successfully.') */;
    }

    public function member_leave($projectId, $userId)
    {
        $project = Project::findOrFail($projectId);

        $this->authorize('member_leave', [$project, Auth::user()]);

        $leavingUser = User::find($userId);

        $project->members()->detach($userId);

        // Remove the user from all tasks in the project
        foreach ($project->tasks as $task) {
            $task->members()->detach($userId);
        }

        $changeText = "User {$leavingUser->username} left project.";
        $change = new Changes([
            'text' => $changeText,
            'date' => now()->format('Y-m-d H:i:s'),
            'project_id' => $projectId,
            'user_id' => Auth::id(),
        ]);

        $change->save();

        $user = User::find($userId);

        $description ='The user ' . $user->username . ' left from project ' . $project->name . '.';
        $member = $project->getCoordinator();
        $this->createNotification([$member],$description);

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

        $description ='The project ' . $project->name . ' has a new coordinator.';
        $members = $project->members;
        $this->createNotification($members,$description);

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

    public function showProjectChanges($project_id)
    {
        $project = Project::find($project_id);

        // Check if the user is a member of the project or an admin
        if (!$project || (!$project->isMember(Auth::user()) && !Auth::user()->isAdmin)) {
            return redirect("/projects");
        }

        $changes = Changes::with('project')
        ->where('project_id', $project_id)
        ->orderBy('date', 'desc')
        ->get();

        return view('pages.changes', compact('project', 'changes'));
    }
}
