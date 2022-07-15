<?php

namespace App\Http\Controllers\Api;

use App\Models\GroupeCarte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * @group Group Carte management
 *
 * APIs for managing group carte
 */
class GroupeCarteController extends BaseController
{
    /**
     * Get all group carte.
     *
     * @header Content-Type application/json
     */
    public function index()
    {
        $groupes = GroupeCarte::all();
        foreach ($groupes as $groupe) {
            $groupe->cartes = $groupe->cartes()->get();
        }
        $success['groupes_cartes'] = $groupes;
        return $this->sendResponse($success, 'Groupes cartes récupérés avec succès.');
    }

    /**
     * Add a group carte.
     *
     * @authenticated
     * @header Content-Type application/json
     * @bodyParam nom string required the group carte name. Example: Groupe 1
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return $this->sendError('Vous n\'avez pas les droits pour effectuer cette action.');
        } else {
            $validator =  Validator::make($request->all(), [
                'nom' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Erreur de validation.', $validator->errors());
            }

            $input = $request->all();

            try {
                DB::beginTransaction();

                $groupe = GroupeCarte::create($input);

                DB::commit();

                $success['groupe_carte'] = $groupe;
                return $this->sendResponse($success, 'Groupe carte créé avec succès.', 201);
            } catch (\Throwable $th) {
                DB::rollback();
                return $this->sendError('Erreur lors de la création du groupe carte.', $th->getMessage(), 400);
            }
        }
    }

    /**
     * Get a group carte.
     *
     * @header Content-Type application/json
     * @urlParam id int required the group carte id. Example: 1
     */
    public function show($id)
    {
        $groupe = GroupeCarte::find($id);

        $groupe->cartes = $groupe->cartes()->get();

        $success['groupe_carte'] = $groupe;
        return $this->sendResponse($success, 'Groupe carte récupéré avec succès.');
    }

    /**
     * Update a group carte.
     *
     * @authenticated
     * @header Content-Type application/json
     * @urlParam id int required the group carte id. Example: 1
     * @bodyParam nom string the group carte name. Example: Groupe 1
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return $this->sendError('Vous n\'avez pas les droits pour effectuer cette action.');
        } else {
            $groupe = GroupeCarte::find($id);

            $validator =  Validator::make($request->all(), [
                'nom' => 'string|max:255',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Erreur de validation.', $validator->errors());
            }

            $input = $request->all();

            try {
                DB::beginTransaction();

                $groupe->nom = $input['nom'] ?? $groupe->nom;
                $groupe->save();

                DB::commit();

                $success['groupe_carte'] = $groupe;
                return $this->sendResponse($success, 'Groupe carte modifié avec succès.', 201);
            } catch (\Throwable $th) {
                DB::rollback();
                return $this->sendError('Erreur lors de la modification du groupe carte.', $th->getMessage(), 400);
            }
        }
    }

    /**
     * Delete a group carte.
     *
     * @authenticated
     * @header Content-Type application/json
     * @urlParam id int required the group carte id. Example: 1
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return $this->sendError('Vous n\'avez pas les droits pour effectuer cette action.');
        } else {
            $groupe = GroupeCarte::find($id);

            try {
                DB::beginTransaction();

                $groupe->cartes()->delete();

                $groupe->delete();

                DB::commit();

                $success['groupe_carte'] = $groupe;
                return $this->sendResponse($success, 'Groupe carte supprimé avec succès.', 201);
            } catch (\Throwable $th) {
                DB::rollback();
                return $this->sendError('Erreur lors de la suppression du groupe carte.', $th->getMessage(), 400);
            }
        }
    }
}
