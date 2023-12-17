<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;


class Project extends Model
{
    use HasFactory;

    protected $table = 'project';

    // Don't add create and update timestamps in database.
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'start_date',
        'delivery_date',
        'archived',
    ];

    /**
     * Define the many-to-many relationship with tasks through the ProjectMember pivot table.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id');
    }

    /**
     * Define the many-to-many relationship with users through the ProjectMember pivot table.
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'projectmember', 'idproject', 'iduser')
            ->withPivot(['iscoordinator', 'isfavorite']);
    }

    /**
     * Retrieve a list of users with a valid pending invitation for this project.
     */
    public function pending_users()
    {
        $oneWeekAgo = Carbon::now()->subWeek();

        $pendingUsers = DB::table('projectmemberinvitation')
            ->join('User', 'User.id', '=', 'projectmemberinvitation.iduser')
            ->where('idproject', $this->id)
            ->where('created_at', '>', $oneWeekAgo)
            ->select('User.*')
            ->distinct()
            ->get();

        return $pendingUsers;
    }

    /**
     * Check if a user is a member of the project.
     */
    public function isMember(User $user): bool
    {
        return $this->members->contains('id', $user->id);
    }

    /**
     * Get project coordinator.
     */
    public function getCoordinator()
    {
        return $this->members()
            ->wherePivot('iscoordinator', true)->get()->first();
    }

    /**
     * Check if a user is a coordinator of the project.
     */
    public function isCoordinator(User $user): bool
    {
        return $user->id === $this->getCoordinator()->id;
    }

    /**
     * Check if a user is has marked a project as favorite.
     */
    public function isFavoriteOf(User $user): bool
    {
        return $this->members()
            ->where('iduser', $user->id)
            ->where('isfavorite', true)
            ->exists();
    }
}
