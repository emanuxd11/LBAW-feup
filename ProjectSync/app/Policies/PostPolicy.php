<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;
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

    public function show(User $user, Project $project): bool
    {
        return Auth::check() && ($project->isMember($user) || $user->isAdmin);
    }

    public function create(User $user, Project $project): bool
    {
        return Auth::check() && ($project->isMember($user) || $user->isAdmin);
    }

    public function update(User $user, Post $post): bool
    {
        return $user->id == $post->author_id;
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->id == $post->author_id;
    }
}