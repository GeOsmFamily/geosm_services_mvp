<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

/**
 * @group Account management
 *
 * APIs for managing linkedin user
 */
class SocialApiAuthLinkedinController extends BaseController
{
    /**
     * Linkedin Connect.
     *
     * @header Content-Type application/json
     * @bodyParam token string required the linkedin user token. Example: AQW31QxCREIDyAhAn0G0G9NXlNcAjeDphtUOUKb5iVUu1oeTvLhD8m2P2_eG8sAt4JPIk0rFOZshJU6AiIWPBldEDrSaLDz1R9-534TycRYRRO8-1Gu6AiSjyGSVPjWqiZIfgPlAikcS1QUlOObd0JOjAt8SvzJf31T4pRdmzh4EC5V5W8p3gnxk63Q9mnsReP8B18tcP2-bd4u9ndnqOAFlstwJXBJCQ7vGF5StVYbAtpyL94_QYaeRN3MUq458BXJWceUDBdTH39vH_X27pPdbLfCCwkEEEnc4zPdztbcNEBJP7EkhN00S_c4uNcgqtmPUKdYaWUZeQOdJ5fHZJdl8LHdQiQ
     */
    public function linkedinConnect(Request $request)
    {
        $token = $request->token;

        $user = Socialite::driver('linkedin')->userFromToken($token);

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
