<?php

namespace App\Http\Controllers\Api;

use App\Models\Carte;
use App\Models\Instance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * @group Carte management
 *
 * APIs for managing carte
 */
class CarteController extends BaseController
{
    /**
     * Get all cartes.
     *
     * @header Content-Type application/json
     */
    public function index()
    {
        $cartes = Carte::all();
        foreach ($cartes as $carte) {
            $carte->groupeCarte = $carte->groupeCarte()->get();
        }
        $success['cartes'] = $cartes;
        return $this->sendResponse($success, 'Cartes récupérés avec succès.');
    }

    /**
     * Add a carte.
     *
     * @authenticated
     * @header Content-Type application/json
     * @bodyParam groupe_carte_id int required the group carte id. Example: 1
     * @bodyParam nom string required the carte name. Example: Carte 1
     * @bodyParam url string required the carte url. Example: http://www.carte1.com
     * @bodyParam image_url file the carte image.
     * @bodyParam type string the carte type. Example: image
     * @bodyParam identifiant string the carte identifiant. Example: carte1
     * @bodyParam bbox string the carte bbox. Example: [0,0,1,1]
     * @bodyParam projection string the carte projection. Example: EPSG:4326
     * @bodyParam zmax int the carte zmax. Example: 10
     * @bodyParam zmin int the carte zmin. Example: 1
     * @bodyParam commentaire string the carte commentaire. Example: Carte 1
     * @bodyParam instance_id int the carte instance id. Example: 1
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return $this->sendError('Vous n\'avez pas les droits pour effectuer cette action.');
        } else {
            $validator =  Validator::make($request->all(), [
                'groupe_carte_id' => 'required|integer',
                'nom' => 'required|string|max:255',
                'url' => 'required|string|max:255',
                'image_url' => 'mimes:png,jpg,jpeg|max:20000',
                'type' => 'string',
                'identifiant' => 'string',
                'bbox' => 'string',
                'projection' => 'string',
                'zmax' => 'integer',
                'zmin' => 'integer',
                'commentaire' => 'string',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Erreur de validation.', $validator->errors());
            }

            $input = $request->all();

            if ($request->file()) {
                $fileName = time() . '_' . $request->image_url->getClientOriginalName();
                $filePath = $request->file('image_url')->storeAs('uploads/cartes/images/' . $request->nom, $fileName, 'public');
                $input['image_url'] = '/storage/' . $filePath;
            }

            try {
                DB::beginTransaction();

                $carte = Carte::create($input);

                if ($request->instance_id) {
                    $instance = Instance::find($request->instance_id);

                    $instance->cartes()->attach($carte->id);
                }


                DB::commit();

                $success['carte'] = $carte;
                return $this->sendResponse($success, 'Carte créée avec succès.', 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->sendError('Erreur lors de la création de la carte.', $e->getMessage());
            }
        }
    }

    /**
     * Get a carte.
     *
     * @authenticated
     * @header Content-Type application/json
     * @urlParam carte required The carte id. Example: 1
     */
    public function show($id)
    {
        $carte = Carte::find($id);
        if (is_null($carte)) {
            return $this->sendError('Carte non trouvée.');
        }
        $carte->groupeCarte = $carte->groupeCarte()->get();
        $success['carte'] = $carte;
        return $this->sendResponse($success, 'Carte récupérée avec succès.');
    }

    /**
     * Update a carte.
     *
     * @authenticated
     * @header Content-Type application/json
     * @urlParam id required The carte id. Example: 1
     * @bodyParam nom string the carte name. Example: Carte 1
     * @bodyParam url string the carte url. Example: http://www.carte1.com
     * @bodyParam image_url file the carte image.
     * @bodyParam type string the carte type. Example: image
     * @bodyParam identifiant string the carte identifiant. Example: carte1
     * @bodyParam bbox string the carte bbox. Example: [0,0,1,1]
     * @bodyParam projection string the carte projection. Example: EPSG:4326
     * @bodyParam zmax int the carte zmax. Example: 10
     * @bodyParam zmin int the carte zmin. Example: 1
     * @bodyParam commentaire string the carte commentaire. Example: Carte 1
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return $this->sendError('Vous n\'avez pas les droits pour effectuer cette action.');
        } else {
            $validator =  Validator::make($request->all(), [
                'nom' => 'string|max:255',
                'url' => 'string|max:255',
                'image_url' => 'mimes:png,jpg,jpeg|max:20000',
                'type' => 'string',
                'identifiant' => 'string',
                'bbox' => 'string',
                'projection' => 'string',
                'zmax' => 'integer',
                'zmin' => 'integer',
                'commentaire' => 'string',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Erreur de validation.', $validator->errors());
            }

            $carte = Carte::find($id);
            if (is_null($carte)) {
                return $this->sendError('Carte non trouvée.');
            }

            $input = $request->all();

            if ($request->file()) {
                $fileName = time() . '_' . $request->image_url->getClientOriginalName();
                $filePath = $request->file('image_url')->storeAs('uploads/cartes/images/' . $request->nom, $fileName, 'public');
                $input['image_url'] = '/storage/' . $filePath;
            }

            try {
                DB::beginTransaction();

                $carte->nom = $input['nom'] ?? $carte->nom;
                $carte->image_url = $input['image_url'] ?? $carte->image_url;
                $carte->url = $input['url'] ?? $carte->url;
                $carte->type = $input['type'] ?? $carte->type;
                $carte->identifiant = $input['identifiant'] ?? $carte->identifiant;
                $carte->bbox = $input['bbox'] ?? $carte->bbox;
                $carte->projection = $input['projection'] ?? $carte->projection;
                $carte->zmax = $input['zmax'] ?? $carte->zmax;
                $carte->zmin = $input['zmin'] ?? $carte->zmin;
                $carte->commentaire = $input['commentaire'] ?? $carte->commentaire;

                $carte->save();

                DB::commit();

                $success['carte'] = $carte;
                return $this->sendResponse($success, 'Carte modifiée avec succès.', 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->sendError('Erreur lors de la modification de la carte.', $e->getMessage());
            }
        }
    }

    /**
     * Delete a carte.
     *
     * @authenticated
     * @header Content-Type application/json
     * @urlParam id required The carte id. Example: 1
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return $this->sendError('Vous n\'avez pas les droits pour effectuer cette action.');
        } else {
            $carte = Carte::find($id);
            if (is_null($carte)) {
                return $this->sendError('Carte non trouvée.');
            }
            try {
                DB::beginTransaction();

                $carte->instances()->detach();

                $carte->delete();

                DB::commit();

                return $this->sendResponse($carte, 'Carte supprimée avec succès.', 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->sendError('Erreur lors de la suppression de la carte.', $e->getMessage());
            }
        }
    }
}
