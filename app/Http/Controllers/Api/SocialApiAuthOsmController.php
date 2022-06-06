<?php



namespace App\Http\Controllers\Api;

use App\Services\SocialOsmAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

/**
 * @group Account management
 *
 * APIs for managing osm user
 */
class SocialApiAuthOsmController extends BaseController
{

    /**
     * Osm Connect.
     *
     * @header Content-Type application/json
     * @bodyParam token string required the osm user token. Example: 1LSrNd58FhdnjEiVjekEJUdp7vokFquIG8NUerNpoDY
     */
    public function osmConnect(Request $request, SocialOsmAccountService $service)
    {
        $token = $request->token;

        $user = $service->createOrGetUser(Socialite::driver('openstreetmap')->userFromToken($token));

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
