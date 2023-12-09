<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $table = 'post';

    // Don't add create and update timestamps in database.
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'upvotes',
        'date',
        'isedited',
        'author_id',
        'project_id',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function user_upvoted(User $user): bool
    {
        $upvoteType = DB::table('postupvote')->where('user_id', $user->id)->where('post_id', $this->id)->first();
        return $upvoteType && $upvoteType->upvote_type == 'up';
    }

    public function user_downvoted(User $user): bool
    {
        $upvoteType = DB::table('postupvote')->where('user_id', $user->id)->where('post_id', $this->id)->first();
        return $upvoteType && $upvoteType->upvote_type == 'down';
    }
    
}
