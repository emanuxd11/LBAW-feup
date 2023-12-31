<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    use HasFactory;

    protected $table = 'task';

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'delivery_date',
        'status',
        'project_id',
    ];

    /**
     * Get the task's project.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'projectmembertask','task_id','user_id');
    }

    public function members_not_in_task(){
        $ids_from_members_not_in_task = $this->members()->pluck('id');
        return $this->project->members()->whereNotIn('id', $ids_from_members_not_in_task);
    }

    public function changes()
    {
        return $this->hasMany(Changes::class, 'project_id', 'project_id');
    }

}
