<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;

class TaskCommentsPolicy
{
    
    public function __construct()
    {
        //
    }

    public function create(User $user, $task): bool
    {
        // Retrieve the project by its ID
        $project = Project::find($task->project_id);
        // Check if the user is a member of the project
        return $project && $project->isMember($user);
    }

    public function update(User $user, Task $task): bool
    {
        $project = Project::find($task->project_id);
        // Check if the user is a member of the project
        return $project && $project->isMember($user);
    }

    public function delete(User $user, Task $task): bool
    {
        $project = Project::find($task->project_id);
        // Check if the user is a member of the project
        return $project && $project->isMember($user);
    }
}
