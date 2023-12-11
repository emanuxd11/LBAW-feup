<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;

use Illuminate\Support\Facades\Auth;

class ProjectPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if a given project can be shown to a user.
     * (user has to be project member)
     */
    public function show(User $user, Project $project): bool
    {
        // Only a project member can see a project.
        return $project->isMember($user) or $user->isAdmin;
    }

    /**
     * Determine if all projects can be listed by a user.
     */
    public function list(User $user): bool
    {
        // Any user can list their own projects.
        return Auth::check();
    }

    /**
     * Determine if a project can be created by a user.
     */
    public function create(User $user): bool
    {
        // Any user can create a new project.
        return Auth::check();
    }

    /**
     * Determine if a project can be archived by a user 
     * (the user has to be project coordinator and the 
     * project can't already be archived). 
     * (projects cannot be deleted, only archived)
     */
    public function archive(User $user, Project $project): bool
    {
        return $project->isCoordinator($user);
    //   return (Auth::user()->is_admin || $project->isCoordinator($user)) && !$project->archived;
    }

    /**
     * Determine if the current user can remove a member (must be project coorinator). 
     */
    public function remove_member(User $user, Project $project): bool
    {
        // Only a project coordinator can do this.
        return $project->isCoordinator($user);
    }

    /**
     * Determine if the current user can leave the project (has to be authenticated and part of the project). 
     */
    public function member_leave(User $user, Project $project): bool
    {
        return $project->isMember($user) && Auth::check();
    }
}
