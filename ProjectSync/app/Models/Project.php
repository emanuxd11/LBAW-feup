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

    // Don't add create and update timestamps in database.
    public $timestamps = false;

    /**
     * Define the many-to-many relationship with users through the ProjectMember pivot table.
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'ProjectMember', 'idProject', 'idUser')
            ->withPivot(['isCoordinator', 'isFavorite'])
            ->withTimestamps();
    }

    /**
     * Check if a user is a member of the project.
     */
    public function isMember(User $user): bool
    {
        return $this->members()
            ->where('idUser', $user->id)
            ->exists();
    }

    /**
     * Check if a user is a coordinator of the project.
     */
    public function isCoordinator(User $user): bool
    {
        return $this->members()
            ->where('idUser', $user->id)
            ->where('isCoordinator', true)
            ->exists();
    }
}
