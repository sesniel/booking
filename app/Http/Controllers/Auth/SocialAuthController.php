<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the OAuth Provider.
     *
     * @return Response
     */
    public function redirectToProvider($provider, $action)
    {
        session(['userAuthAction' => $action]);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in
     * our database by looking up their provider_id in the database. If the user
     * exists, log them in. Otherwise, create a new user then log them in.
     * After that redirect them to the authenticated users homepage.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();
        $authUser = $this->findOrCreateUser($user, $provider);

        Auth::login($authUser);

        return redirect('/dashboard');
    }

    /**
     * If a user has registered before using social auth,
     * return the user else, create a new user object.
     *
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        $authUser = User::whereEmail($user->email)->first();

        if ($authUser) {
            return $authUser;
        }

        return User::create([
            'email' => $user->email,
            'password' => bin2hex(random_bytes(10)),
            'provider' => $provider,
            'provider_id' => $user->id,
            'avatar' => $user->getAvatar()
        ]);
    }
}
