<?php

namespace App\Policies;

use App\Models\PostComment;
use App\Models\User;
use App\Models\Post;
use App\Models\Project;

use Illuminate\Support\Facades\Auth;

class PostCommentPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(User $user, Project $project): bool
    {
        return Auth::check() && ($project->isMember($user) || $user->isAdmin);
    }

    public function update(User $user, PostComment $postComment): bool
    {
        return $user->id == $postComment->author_id;
    }

    public function delete(User $user, PostComment $postComment): bool
    {
        return $user->id == $postComment->author_id || $user->isAdmin;
    }
}