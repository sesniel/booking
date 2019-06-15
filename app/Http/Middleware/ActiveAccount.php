<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ActiveAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->status === 'suspended') {
            return abort(403, 'Your account has been suspended, you can contact hello@booking.com to have this reactivated.');
        }

        if (Auth::user()->status === 'pending') {
            return abort(403, 'You are unable to view this page while your account is pending approval. A member of our team is reviewing your account and will have you live very soon.');
        }

        return $next($request);
    }
}
