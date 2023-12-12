<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\Notification;

class NotificationController extends Controller
{
    public function broadcastNotification(Request $request)
    {
        $data = $request->all();

        // Validate or process your data as needed

        // Trigger the NewNotification event
        event(new Notification([
            'message' => $data['message'],
            // Add any other data you want to send with the notification
        ]));

        return response()->json(['success' => true]);
    }
}