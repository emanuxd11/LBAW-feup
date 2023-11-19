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
     * Determine if a project can be closed by a user (user has to be project coorinator). 
     * (projects cannot be deleted, only archived or "closed")
     */
    public function close(User $user, Project $project): bool
    {
      // Only a project coordinator can close it.
      return $project->isCoordinator($user);
    }
}
