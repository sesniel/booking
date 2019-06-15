<?php

namespace App\Http\ViewComposers;

use Chat;
use App\User;
use App\Couple;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class MessagesCountComposer
{
    public function compose(View $view)
    {
        $view->with('messagesCount', Chat::for(Auth::user())->unreadCount());
    }
}
