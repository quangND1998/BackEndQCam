<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{

    public function getNofifications($user)
    {
        return $user->notifications()->select('*', DB::raw('DATE(created_at) as date'))->orderBy('created_at', 'desc')->get()->groupBy('date');
    }


    public function notifications()
    {
        $user = Auth::user();
        $notifications = $this->getNofifications($user);
        return response()->json($notifications, 200);
    }

    public function getUnreadNotifications()
    {
        $user = Auth::user();
        $notifications = $user->unreadNotifications()->select('*', DB::raw('DATE(created_at) as date'))->orderBy('created_at', 'desc')->get()->groupBy('date');
        $response = [
            'data' => $notifications,
            'count' => count($user->unreadNotifications)
        ];
        return response()->json($response, 200);
    }

    public function readNotifications()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        $notifications = $this->getNofifications($user);
        return response()->json($notifications, 200);
    }

    public function deleteNotifications()
    {
        $user = Auth::user();
        $user->notifications()->delete();
        $notifications = $this->getNofifications($user);
        return response()->json($notifications, 200);
    }
}
