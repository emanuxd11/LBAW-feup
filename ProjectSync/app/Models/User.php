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

    public $timestamps = false;

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
        'profile_pic',
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

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'projectmember', 'iduser', 'idproject')
            ->where('archived', FALSE);
    }

    public function archived_projects()
    {
        return $this->belongsToMany(Project::class, 'projectmember', 'iduser', 'idproject')
            ->where('archived', TRUE)->where('iscoordinator', true);
    }

    public function getIsAdminAttribute()
    {
        return $this->admin ?? DB::table('admin')->where('id', $this->id)->exists();
    }

    public function favorite_projects()
    {
        return $this->belongsToMany(Project::class, 'projectmember', 'iduser', 'idproject')
            ->wherePivot('isfavorite', true)->get();
    }

    public function unfavorite_projects()
    {
        return $this->belongsToMany(Project::class, 'projectmember', 'iduser', 'idproject')
            ->wherePivot('isfavorite', '!=', true)->get();
    }

    public function getCoordinatedProjects(){
        return $this->belongsToMany(Project::class, 'projectmember', 'iduser', 'idproject')
            ->wherePivot('iscoordinator', true)->get();
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'usernotification', 'user_id', 'notification_id');
    }

    public function unseen_notifications()
    {
        return $this->belongsToMany(Notification::class, 'usernotification', 'user_id', 'notification_id')
            ->wherePivot('ischecked', false);
    }

}
