<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Notification;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\Message;
use App\Models\Changes;

class NotificationController extends Controller{

    public function show(Request $request,$username){
        $user = User::where('username',$username)->first();

        if($username != Auth::user()->username){
            return redirect()->back();
        }

        $notifications = $user->unseen_notifications;

        return view("pages.notifications",compact('notifications'));
    }

    public function check(Request $request,$username){

        $user = User::where('username',$username)->first();  
        
        $this->authorize('check', [Notification::class ,$username]);

        $user->unseen_notifications()->updateExistingPivot($request->input('notification_id'), ['ischecked' => true]);
    
        return redirect($username . '/notifications')->with('success', 'Notification cleared.');
    }

    public function checkAll(Request $request,$username){

        $user = User::where('username',$username)->first();

        $this->authorize('check', [Notification::class ,$username]);

        foreach ($user->unseen_notifications as $notification) {
            $user->unseen_notifications()->updateExistingPivot($notification->id, ['ischecked' => true]);
        }
    
        return redirect($username . '/notifications')->with('success', 'Notification cleared.');
    }
}

?>