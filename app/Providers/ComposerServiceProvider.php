<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(
            [
                'dashboard.vendor.home',
                'dashboard.couple.home',
                'dashboard.couple.job-posts',
                'dashboard.couple.job-posts',
                'partials.dashboard.sidebar-menu.couple',
                'partials.dashboard.sidebar-menu.vendor',
                'partials/dashboard/header',
                'partials/header',
                'job-quotes.edit',
                'job-posts.show',
                'messages.index'
            ],
            'App\Http\ViewComposers\ProfileComposer'
        );
        View::composer(
            [
                'partials.dashboard.header',
            ],
            'App\Http\ViewComposers\NotificationsCountComposer'
        );
        View::composer(
            [
                'partials.dashboard.header',
            ],
            'App\Http\ViewComposers\MessagesCountComposer'
        );
        View::composer(
            [
                'job-posts.index',
                'job-posts.show',
                'dashboard.vendor.job-posts',
                'dashboard.vendor.saved-jobs',
            ],
            'App\Http\ViewComposers\VendorQuotedJobsComposer'
        );
        View::composer(
            [
                'dashboard.vendor.home',
                'dashboard.couple.home',
            ],
            'App\Http\ViewComposers\UserSettingsComposer'
        );
    }
}
