<?php

namespace App\Http\ViewComposers;

use App\Couple;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class UserSettingsComposer
{
    public function compose(View $view)
    {
        $settings = Auth::user()->settings;
        $view->with('userSettings', $settings ? $settings->settings : null);
    }
}
