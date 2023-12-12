<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'User';

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'phonenumber',
        'isdeactivated',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    protected $appends = ['isAdmin'];

    /**
     * Get the projects for a user.
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'projectmember', 'iduser', 'idproject')
            ->where('archived', FALSE);
    }

    // If I am right, this will won't go (what?😂) to the database everytime it needs to check if the user is an admin
    // This way it checks if the user is one or not and keeps the var stored in the class.
    public function getIsAdminAttribute()
    {
        return $this->admin ?? DB::table('admin')->where('id', $this->id)->exists();
    }

    /**
     * List a user's favorite projects.
     */
    public function favorite_projects()
    {
        return $this->belongsToMany(Project::class, 'projectmember', 'iduser', 'idproject')
            ->wherePivot('isfavorite', true)->get();
    }
}
