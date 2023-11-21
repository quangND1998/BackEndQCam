<?php

namespace App\Http\Controllers;

use App\Models\Notification as ModelsNotification;
use App\Notifications\TestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = ModelsNotification::get();
        // return $notifications;

        Notification::send(Auth::user(), new TestNotification());
    }

    public function getNotifications()
    {
    }
}
