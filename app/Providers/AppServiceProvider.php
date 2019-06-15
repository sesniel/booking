<?php

namespace App\Providers;

use App\User;
use App\Couple;
use App\Vendor;
use App\Invoice;
use App\JobPost;
use App\Payment;
use App\JobQuote;
use App\Observers\UserObserver;
use App\Observers\CoupleObserver;
use App\Observers\VendorObserver;
use App\Observers\InvoiceObserver;
use App\Observers\JobPostObserver;
use App\Observers\PaymentObserver;
use App\Observers\JobQuoteObserver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Couple::observe(CoupleObserver::class);
        Vendor::observe(VendorObserver::class);
        JobPost::observe(JobPostObserver::class);
        JobQuote::observe(JobQuoteObserver::class);
        Invoice::observe(InvoiceObserver::class);
        Payment::observe(PaymentObserver::class);

        Blade::if('couple', function () {
            return Auth::user() && Auth::user()->account === 'couple';
        });

        Blade::if('vendor', function () {
            return Auth::user() && Auth::user()->account === 'vendor';
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
