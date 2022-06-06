<?php

namespace App\Http\Controllers\Api;

use App\Services\SocialFacebookAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

/**
 * @group Account management
 *
 * APIs for managing facebook user
 */
class SocialApiAuthFacebookController extends BaseController
{
    /**
     * Facebook Connect.
     *
     * @header Content-Type application/json
     * @bodyParam token string required the facebook user token. Example: EAAH1hpeusg4BAE4j1lEZAW193T51hYNgsYeLE0YddiTNztljk5viJlWubk3YyHiZBsc3dMACV9qBRrdqYZAyojkJxUzFZBZA1PHfknTqJXja7XQDkWlSjm4pfi5fwnQgghC7KZAqIow8Pz2uRzKHsOPSH1uHk3IPplRIHKQCT072Rbgzfra3zK7cIcL74JmUCgGQqZASic5pvouiwhFZBu7ZC
     */
    public function facebookConnect(Request $request, SocialFacebookAccountService $service)
    {
        $token = $request->token;

        $user = $service->createOrGetUser(Socialite::driver('facebook')->userFromToken($token));

        if ($user) {
            Auth::login($user);
            $token = $user->createToken('GeFacebook')->accessToken;
            $success['token'] = $token;
            $success['user'] = $user;
            $roles = $user->getRoleNames();
            $succes['roles'] = $roles;
            return $this->sendResponse($success, 'Connexion réussie.');
        } else {
            return $this->sendError('Connexion échouée.');
        }
    }
}
