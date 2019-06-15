<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function index()
    {
        Auth::user()->unreadNotifications->markAsRead();

        $notifications = Auth::user()->notifications()
            ->where('type', '<>', 'Musonza\\Chat\\Notifications\\MessageSent')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }
}
