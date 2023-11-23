<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function notifications(){
        $user = Auth::user();
        $notifications = $user->notifications;
        return response()->json($notifications, 200);
    }

    public function getUnreadNotifications(){
        $user = Auth::user();
        $notifications = $user->unreadNotifications ;
        return response()->json($notifications, 200);
    }

    public function readNotifications(){
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        return response()->json($user->notifications, 200);
    }

    public function deleteNotifications(){
        $user = Auth::user();
        $user->notifications()->delete();
        return response()->json($user->notifications, 200);
    }
}
