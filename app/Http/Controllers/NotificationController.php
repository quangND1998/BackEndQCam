<?php

namespace App\Http\Controllers;

use App\Notifications\TestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        Notification::send($user, new TestNotification());
    }
}
