<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * Define the many-to-many relationship with users through the ProjectMember pivot table.
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'projectmember', 'idproject', 'iduser')
            ->withPivot(['iscoordinator', 'isfavorite']);
    }

    /**
     * Check if a user is a member of the project.
     */
    public function isMember(User $user): bool
    {
        return $this->members()->get()->contains('id', $user->id);
    }

    /**
     * Check if a user is a coordinator of the project.
     */
    public function isCoordinator(User $user): bool
    {
        return $this->members()
            ->where('iduser', $user->id)
            ->where('iscoordinator', true)
            ->exists();
    }
}
