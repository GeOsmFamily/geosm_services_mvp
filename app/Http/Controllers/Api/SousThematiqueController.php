<?php

namespace App\Http\Controllers\Api;

use App\Models\SousThematique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * @group Sous Thematique management
 *
 * APIs for managing sous-thematique
 */
class SousThematiqueController extends BaseController
{
    /**
     * Get all sous-thematiques.
     *
     * @header Content-Type application/json
     */
    public function index()
    {
        $sousThematiques = SousThematique::all();
        foreach ($sousThematiques as $sousThematique) {
            $sousThematique->thematique = $sousThematique->thematique()->get();
        }
        $success['sous_thematiques'] = $sousThematiques;
        return $this->sendResponse($success, 'Sous-thematiques récupérés avec succès.');
    }

    /**
     * Add a sous-thematique.
     *
     * @authenticated
     * @header Content-Type application/json
     * @bodyParam thematique_id integer required the thematique id. Example: 1
     * @bodyParam nom string required the sous-thematique name. Example: Sous-thematique 1
     * @bodyParam nom_en string required the sous-thematique name in english. Example: Sous-thematique 1
     * @bodyParam image_src file the sous-thematique image.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return $this->sendError('Vous n\'avez pas les droits pour effectuer cette action.');
        } else {
            $validator =  Validator::make($request->all(), [
                'thematique_id' => 'required|integer',
                'nom' => 'required|string|max:255',
                'nom_en' => 'required|string|max:255',
                'image_src' => 'mimes:png,jpg,jpeg,svg|max:20000',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Erreur de validation.', $validator->errors());
            }

            $input = $request->all();

            if ($request->file()) {
                $fileName = time() . '_' . $request->image_src->getClientOriginalName();
                $filePath = $request->file('image_src')->storeAs('uploads/sous-thematiques/images/' . $request->nom, $fileName, 'public');
                $input['image_src'] = '/storage/' . $filePath;
            }

            try {
                DB::beginTransaction();

                $sousThematique = SousThematique::create($input);

                DB::commit();

                $success['sous_thematique'] = $sousThematique;
                return $this->sendResponse($success, 'Sous-thematique créé avec succès.', 201);
            } catch (\Throwable $th) {
                DB::rollback();
                return $this->sendError('Erreur lors de la création du sous-thematique.', $th->getMessage(), 400);
            }
        }
    }

    /**
     * Get a sous-thematique.
     *
     * @header Content-Type application/json
     * @urlParam id required The ID.
     */
    public function show($id)
    {
        $sousThematique = SousThematique::find($id);

        $sousThematique->thematique = $sousThematique->thematique()->get();

        $success['sous_thematique'] = $sousThematique;
        return $this->sendResponse($success, 'Sous-thematique récupéré avec succès.');
    }

    /**
     * Update a sous-thematique.
     *
     * @authenticated
     * @header Content-Type application/json
     * @urlParam id required The ID.
     * @bodyParam nom string required the sous-thematique name. Example: Sous-thematique 1
     * @bodyParam nom_en string required the sous-thematique name in english. Example: Sous-thematique 1
     * @bodyParam image_src file the sous-thematique image.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return $this->sendError('Vous n\'avez pas les droits pour effectuer cette action.');
        } else {
            $validator =  Validator::make($request->all(), [
                'nom' => 'required|string|max:255',
                'nom_en' => 'required|string|max:255',
                'image_src' => 'mimes:png,jpg,jpeg|max:20000',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Erreur de validation.', $validator->errors());
            }

            try {
                DB::beginTransaction();

                $sousThematique = SousThematique::find($id);

                $sousThematique->nom = $request->nom ?? $sousThematique->nom;
                $sousThematique->nom_en = $request->nom_en ?? $sousThematique->nom_en;

                if ($request->file()) {
                    $fileName = time() . '_' . $request->image_src->getClientOriginalName();
                    $filePath = $request->file('image_src')->storeAs('uploads/sous-thematiques/images/' . $request->nom, $fileName, 'public');
                    $sousThematique->image_src = '/storage/' . $filePath;
                }

                $sousThematique->save();

                DB::commit();

                $success['sous_thematique'] = $sousThematique;
                return $this->sendResponse($success, 'Sous-thematique modifié avec succès.', 201);
            } catch (\Throwable $th) {
                DB::rollback();
                return $this->sendError('Erreur lors de la modification du sous-thematique.', $th->getMessage(), 400);
            }
        }
    }

    /**
     * Delete a sous-thematique.
     *
     * @authenticated
     * @header Content-Type application/json
     * @urlParam id required The ID.
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return $this->sendError('Vous n\'avez pas les droits pour effectuer cette action.');
        } else {
            $sousThematique = SousThematique::find($id);


            try {
                DB::beginTransaction();

                foreach ($sousThematique->couches as $couche) {
                    $thematique = $sousThematique->thematique;
                    $table = $thematique->schema . '."' . $couche->schema_table_name . '"';

                    DB::select('DROP TABLE ' . $table);

                    $couche->delete();
                }

                $sousThematique->delete();

                DB::commit();

                return $this->sendResponse($sousThematique, 'Sous-thematique supprimé avec succès.', 201);
            } catch (\Throwable $th) {
                DB::rollback();
                return $this->sendError('Erreur lors de la suppression du sous-thematique.', $th->getMessage(), 400);
            }
        }
    }
}
