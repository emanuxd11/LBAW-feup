<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;
use App\Models\Project;
use App\Models\Notification;

use Illuminate\Support\Facades\Auth;

class NotificationPolicy
{
    public function __construct()
    {
        //
    }

    public function show(User $user, Notification $notification): bool
    {
        return Auth::check() && Auth::user()->username === $user->username;
    }

    public function check(User $user, $username): bool
    {
        return Auth::check() && Auth::user()->username === $username;
    }

}