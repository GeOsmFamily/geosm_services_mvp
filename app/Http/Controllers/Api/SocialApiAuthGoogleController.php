<?php

namespace App\Http\Controllers\Api;

use App\Services\SocialGoogleAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

/**
 * @group Account management
 *
 * APIs for managing google user
 */
class SocialApiAuthGoogleController extends BaseController
{
    /**
     * Google Connect.
     *
     * @header Content-Type application/json
     * @bodyParam token string required the google user token. Example: ya29.a0ARrdaM9HnvJ0X_Gh4sAI6oZeWHh3ibcv0NRC4rl3SR9gxrJ5Grv7CO9gWnjJ-AsFvKnqqZ4Ir-cSaxJ-bxeMarJm7wna9rNSHDuN50R2TI4jGXQMxieXCaIHB04eU_Poi2-OAtx9PKno6KTcLw7B0j09kXuFpPg
     */
    public function googleConnect(Request $request, SocialGoogleAccountService $service)
    {
        $token = $request->token;

        $user = $service->createOrGetUser(Socialite::driver('google')->userFromToken($token));

        if ($user) {
            Auth::login($user);
            $token = $user->createToken('GeOsm')->accessToken;
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
