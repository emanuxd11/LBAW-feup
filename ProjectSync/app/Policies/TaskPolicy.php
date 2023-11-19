<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;

class TaskPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if a user can create a task.
     */
    public function create(User $user, $task): bool
    {
        // Retrieve the project by its ID
        $project = Project::find($task->project_id);
        // Check if the user is a member of the project
        return $project && $project->isMember($user);
    }

    /**
     * Determine if a user can update a task.
     */
    public function update(User $user, Task $task): bool
    {
        $project = Project::find($task->project_id);
        // Check if the user is a member of the project
        return $project && $project->isMember($user);
    }

    /**
     * Determine if a user can delete an item.
     */
    public function delete(User $user, Task $task): bool
    {
        $project = Project::find($task->project_id);
        // Check if the user is a member of the project
        return $project && $project->isMember($user);
    }
}
