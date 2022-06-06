<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    /**
     * Register a new user.
     *
     * @header Content-Type application/json
     * @group Account management
     * @bodyParam last_name string required the name of the user. Example: GeOsm
     * @bodyParam first_name string required the first name of the user. Example: Family
     * @bodyParam titre string  the email of the user. Example: Titre
     * @bodyParam osm_changeset string  The phone number of the user. Example:10
     * @bodyParam email string required the email of the user. Example: user@geosm.org
     * @bodyParam password string required the password of the user. Example: password
     * @bodyParam phone string The phone number of the user. Example:+237699999999
     * @bodyParam profile_picture file The profile picture of the user.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'last_name' => 'string|max:255',
            'first_name' => 'string|max:255',
            'titre' => 'string|max:255',
            'osm_changeset' => 'string|max:255',
            'email' => 'email|unique:users,email',
            'password' => 'string|between:6,20',
            'profile_picture' => 'mimes:png,jpg,jpeg|max:20000'
        ]);


        if ($validator->fails()) {
            return $this->sendError('Erreur de paramètres.', $validator->errors(), 400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        if ($request->file()) {
            $fileName = time() . '_' . $request->profile_picture->getClientOriginalName();
            $filePath = $request->file('profile_picture')->storeAs('uploads/users/profils', $fileName, 'public');
            $input['profile_picture'] = '/storage/' . $filePath;
        } else {
            $input['profile_picture'] = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($input['email']))) . '?s=200&d=mm';
        }



        try {
            DB::beginTransaction();

            $user = User::create($input);

            $user->assignRole('user');
            $user->sendEmailVerificationNotification();
            $success['token'] = $user->createToken('GeOsm')->accessToken;
            $success['user'] = $user;
            DB::commit();
            return $this->sendResponse($success, 'User registered successfully.', 201);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->sendError('Erreur lors de la création de l\'utilisateur.', $th->getMessage(), 500);
        }
    }

    /**
     * Login a new user.
     *
     * @header Content-Type application/json
     * @group Account management
     * @bodyParam email string required if phone not found the email of the user. Example: infos@geo.sm
     * @bodyParam password string required the password of the user. Example: secret
     */
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'phone', 'password']);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->hasVerifiedEmail()) {
                $success['token'] = $user->createToken('GeOsm')->accessToken;
                $success['user'] = $user;

                return $this->sendResponse($success, 'Connexion réussie.');
            } else {
                return $this->sendError("Email not verified.", ['error' => 'Unauthorised'], 400);
            }
        } else {
            return $this->sendError('Pas autorisé.', ['error' => 'Login Error'], 400);
        }
    }

    /**
     * Logout a user.
     *
     * @header Content-Type application/json
     * @authenticated
     * @group Account management
     */
    public function logout()
    {
        $user = Auth::user();
        $token = $user->token();
        $revoque = $token->revoke();

        if ($revoque) {
            return $this->sendResponse("", 'Deconnexion réussie.');
        } else {
            return $this->sendError('Pas autorisé.', ['error' => 'Echec de deconnexion'], 400);
        }
    }

    /**
     * Refresh Token.
     *
     * @header Content-Type application/json
     * @authenticated
     * @group Account management
     */
    public function refresh()
    {
        $user = Auth::user();
        $token = $user->token();
        $newToken = $token->refresh();

        if ($newToken) {
            return $this->sendResponse($newToken, 'Token refreshed.');
        } else {
            return $this->sendError('Pas autorisé.', ['error' => 'Echec de rafraichissement'], 400);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @header Content-Type application/json
     * @authenticated
     * @group Account management
     */
    public function me()
    {
        $user = Auth::user();
        if ($user) {
            $success['user'] = $user;
            return $this->sendResponse($success, 'User retrieved successfully.');
        } else {
            return $this->sendError('Pas autorisé.', ['error' => 'Echec de récupération'], 401);
        }
    }

    /**
     * Update the authenticated User.
     *
     * @header Content-Type application/json
     * @authenticated
     * @group Account management
     * @urlParam id int required the id of the admin. Example: 1
     * @bodyParam last_name string  the name of the user. Example: GeOsm
     * @bodyParam first_name string  the first name of the user. Example: Family
     * @bodyParam titre string  the email of the user. Example: Titre
     * @bodyParam osm_changeset string  The phone number of the user. Example:10
     * @bodyParam phone string The phone number of the user. Example:+237699999998
     * @bodyParam profile_picture file The profile picture of the user.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->hasRole('admin') || $user->id == $id) {
            $validator = Validator::make($request->all(), [
                'last_name' => 'string|max:255',
                'first_name' => 'string|max:255',
                'titre' => 'string|max:255',
                'osm_changeset' => 'string',
                'phone' => 'string|max:255',
                'profile_picture' => 'mimes:png,jpg,jpeg|max:20000'
            ]);

            if ($validator->fails()) {
                return $this->sendError('Erreur de paramètres.', $validator->errors(), 400);
            }



            try {
                DB::beginTransaction();

                $user->last_name = $request->last_name ?? $user->last_name;
                $user->first_name = $request->first_name ?? $user->first_name;
                $user->phone = $request->phone ?? $user->phone;
                $user->titre = $request->titre ?? $user->titre;
                $user->osm_changeset = $request->osm_changeset ?? $user->osm_changeset;

                if ($request->file()) {
                    $fileName = time() . '_' . $request->profile_picture->getClientOriginalName();
                    $filePath = $request->file('profile_picture')->storeAs('uploads/users/profils', $fileName, 'public');
                    $user->profile_picture = '/storage/' . $filePath;
                }

                $user->save();
                $success['user'] = $user;

                DB::commit();
                return $this->sendResponse($success, 'User updated successfully.', 201);
            } catch (\Throwable $th) {
                DB::rollback();
                return $this->sendError('Erreur lors de la mise à jour de l\'utilisateur.', $th->getMessage(), 500);
            }
        } else {
            return $this->sendError('Pas autorisé.', ['error' => 'Echec de mise à jour'], 401);
        }
    }

    /**
     * Delete user account.
     *
     * @header Content-Type application/json
     * @authenticated
     * @group Account management
     * @urlParam id int required the id of the admin. Example: 1
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if ($user->hasRole('admin') || $user->id == $id) {
            try {
                DB::beginTransaction();

                $user->delete();
                $success['user'] = $user;

                DB::commit();
                return $this->sendResponse($success, 'User deleted successfully.', 201);
            } catch (\Throwable $th) {
                DB::rollback();
                return $this->sendError('Erreur lors de la suppression de l\'utilisateur.', $th->getMessage(), 500);
            }
        } else {
            return $this->sendError('Pas autorisé.', ['error' => 'Echec de suppression'], 401);
        }
    }

    /**
     * Forgot Password
     *
     * @header Content-Type application/json
     * @group Account management
     * @bodyParam email string required the email of the user. Example: user@geosm.org
     */
    public function forgot()
    {
        $credentials = request()->validate(['email' => 'required|email']);

        Password::sendResetLink($credentials);

        return $this->sendResponse("", "Un lien de reinitialisation vous a été envoyé par mail.", 200);
    }

    /**
     * Reset Password
     *
     * @header Content-Type application/json
     * @group Account management
     * @bodyParam email string required the email of the user. Example: user@geosm.org
     * @bodyParam token string required token give in mail.
     * @bodyParam password string required the new password of the user. Example: password
     * @bodyParam password_confirmation string required the password confirmation of the user. Example: password
     */
    public function reset(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Erreur de paramètres.', $validator->errors(), 400);
        }

        $credentials = $request->only(['email', 'token', 'password']);

        $reset_password_status = Password::reset($credentials, function ($user, $password) {
            $user->password = bcrypt($password);
            $user->save();
        });

        if ($reset_password_status == Password::INVALID_TOKEN) {
            return $this->sendError("Invalid token provided", ['error' => 'Unauthorised']);
        }

        return $this->sendResponse("", "Password has been successfully changed", 201);
    }

    /**
     * Get the list of all users.
     *
     * @header Content-Type application/json
     * @authenticated
     * @group Account management
     */
    public function allusers()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $users = User::all();
            return $this->sendResponse($users, 'Users retrieved successfully.');
        } else {
            return $this->sendError('Pas autorisé.', ['error' => 'Echec de récupération'], 401);
        }
    }

    /**
     * Change Password
     * @authenticated
     * @group Account management
     * @bodyParam old_password string required the old password of the user. Example: password
     * @bodyParam new_password string required the new password of the user. Example: password
     * @bodyParam password_confirmation string required the password confirmation of the user. Example: password
     */
    public function changepassword(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string|confirmed'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Erreur de paramètres.', $validator->errors(), 400);
        }

        if (Hash::check($request->old_password, $user->password)) {
            $user->password = bcrypt($request->new_password);
            $user->save();
            return $this->sendResponse("", "Password has been successfully changed", 201);
        } else {
            return $this->sendError('Erreur de paramètres.', ['error' => 'Echec de changement'], 400);
        }
    }
}
