<?php

namespace App\Http\ViewComposers;

use App\User;
use App\Couple;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class NotificationsCountComposer
{
    public function compose(View $view)
    {
        $view->with('notificationsCount', $this->notificationsCount());
    }

    public function notificationsCount()
    {
        if (Auth::user()->account === 'couple') {
            $couple = Couple::where('userA_id', Auth::user()->id)
                ->orWhere('userB_id', Auth::user()->id)
                ->first(['id', 'userA_id']);
            return User::whereId($couple->userA_id)->first(['id'])
                ->unreadNotifications()
                ->where('type', '<>', 'Musonza\\Chat\\Notifications\\MessageSent')
                ->count();
        } else {
            return Auth::user()->unreadNotifications()
                ->where('type', '<>', 'Musonza\\Chat\\Notifications\\MessageSent')
                ->count();
        }
    }
}
