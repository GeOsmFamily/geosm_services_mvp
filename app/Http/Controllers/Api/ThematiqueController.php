<?php

namespace App\Http\Controllers\Api;

use App\Models\Instance;
use App\Models\Thematique;
use App\Models\ThematiqueInstance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * @group Thematique management
 *
 * APIs for managing thematique
 */
class ThematiqueController extends BaseController
{
    /**
     * Get all thematiques.
     *
     * @header Content-Type application/json
     */
    public function index()
    {
        $thematiques = Thematique::all();
        $success['thematiques'] = $thematiques;
        return $this->sendResponse($success, 'Thematiques récupérés avec succès.');
    }

    /**
     * Add a thematique.
     *
     * @authenticated
     * @header Content-Type application/json
     * @bodyParam nom string required the thematique name. Example: Thematique 1
     * @bodyParam nom_en string required the thematique name in english. Example: Thematique 1
     * @bodyParam image_src file the thematique image.
     * @bodyParam color string required the thematique color. Example: #808080
     * @bodyParam instance_id int required the instance id. Example: 1
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return $this->sendError('Vous n\'avez pas les droits pour effectuer cette action.');
        } else {
            $validator =  Validator::make($request->all(), [
                'nom' => 'required|string|max:255',
                'nom_en' => 'required|string|max:255',
                'image_src' => 'mimes:png,jpg,jpeg|max:20000',
                'color' => 'required|string|max:255',
                'instance_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Erreur de validation.', $validator->errors());
            }

            $input = $request->all();

            $schema = strtolower(str_replace(" ", "_", preg_replace("/[^a-zA-Z]/", "", $input['nom'])));

            $input['schema'] = $schema;

            $thematiques = Thematique::all();

            if ($thematiques->count() > 0) {
                $lastThematique = $thematiques->last();
                $input['ordre'] = $lastThematique->ordre + 1;

                if ($request->instance_id) {
                    $lastThematiqueInstance = ThematiqueInstance::where('instance_id', $request->instance_id)->orderBy('ordre', 'desc')->first();
                    if ($lastThematiqueInstance) {
                        $input['ordre'] = $lastThematiqueInstance->ordre + 1;
                    } else {
                        $input['ordre'] = 1;
                    }
                }
            } else {
                $input['ordre'] = 1;
            }


            if ($request->file()) {
                $fileName = time() . '_' . $request->image_src->getClientOriginalName();
                $filePath = $request->file('image_src')->storeAs('uploads/thematiques/images/' . $request->nom, $fileName, 'public');
                $input['image_src'] = '/storage/' . $filePath;
            }

            try {
                DB::beginTransaction();

                $thematique = Thematique::create($input);

                DB::connection('pgsql_osm')->select('CREATE SCHEMA ' . $schema . ';');

                if ($input['instance_id']) {
                    $instance = Instance::find($input['instance_id']);

                    $instance->thematiques()->attach($thematique->id);
                }


                DB::commit();

                $success['thematique'] = $thematique;
                return $this->sendResponse($success, 'Thématique créée avec succès.', 201);
            } catch (\Throwable $th) {
                DB::rollback();
                DB::connection('pgsql_osm')->rollBack();
                return $this->sendError('Erreur lors de la création de la thématique.', $th->getMessage(), 400);
            }
        }
    }

    /**
     * Get a thematique.
     *
     * @urlParam id required The thematique id. Example: 1
     * @header Content-Type application/json
     */
    public function show($id)
    {
        $thematique = Thematique::find($id);

        if (is_null($thematique)) {
            return $this->sendError('Thématique non trouvée.', 404);
        }

        $success['thematique'] = $thematique;
        return $this->sendResponse($success, 'Thématique récupérée avec succès.');
    }

    /**
     * Update a thematique.
     *
     * @urlParam id required The thematique id. Example: 1
     * @authenticated
     * @header Content-Type application/json
     * @bodyParam nom string  the thematique name. Example: Thematique 1
     * @bodyParam nom_en string the thematique name in english. Example: Thematique 1
     * @bodyParam schema string the thematique schema. Example: thematique_1
     * @bodyParam image_src file the thematique image.
     * @bodyParam color string the thematique color. Example: #808080
     * @bodyParam ordre integer the thematique ordre. Example: 1
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return $this->sendError('Vous n\'avez pas les droits pour effectuer cette action.');
        } else {
            $validator =  Validator::make($request->all(), [
                'nom' => 'string|max:255',
                'nom_en' => 'string|max:255',
                'schema' => 'string|max:255',
                'image_src' => 'mimes:png,jpg,jpeg|max:20000',
                'color' => 'string|max:255',
                'ordre' => 'integer',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Erreur de validation.', $validator->errors());
            }

            $input = $request->all();

            $thematique = Thematique::find($id);

            if (is_null($thematique)) {
                return $this->sendError('Thématique non trouvée.', 404);
            }

            if ($request->file()) {
                $fileName = time() . '_' . $request->image_src->getClientOriginalName();
                $filePath = $request->file('image_src')->storeAs('uploads/thematiques/images/' . $request->nom, $fileName, 'public');
                $input['image_src'] = '/storage/' . $filePath;
            }

            try {
                DB::beginTransaction();

                $thematique->nom = $input['nom'] ?? $thematique->nom;
                $thematique->nom_en = $input['nom_en'] ?? $thematique->nom_en;
                $thematique->schema = $input['schema'] ?? $thematique->schema;
                $thematique->image_src = $input['image_src'] ?? $thematique->image_src;
                $thematique->color = $input['color'] ?? $thematique->color;
                $thematique->ordre = $input['ordre'] ?? $thematique->ordre;

                $thematique->save();

                DB::commit();

                $success['thematique'] = $thematique;
                return $this->sendResponse($success, 'Thématique modifiée avec succès.', 201);
            } catch (\Throwable $th) {
                DB::rollback();
                return $this->sendError('Erreur lors de la modification de la thématique.', $th->getMessage(), 400);
            }
        }
    }

    /**
     * Delete a thematique.
     *
     * @urlParam id required The thematique id. Example: 1
     * @authenticated
     * @header Content-Type application/json
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return $this->sendError('Vous n\'avez pas les droits pour effectuer cette action.');
        } else {
            $thematique = Thematique::find($id);

            if (is_null($thematique)) {
                return $this->sendError('Thématique non trouvée.', 404);
            }

            try {
                DB::beginTransaction();

                $thematique->instances()->detach();

                foreach ($thematique->sousThematiques as $sousThematique) {
                    $sousThematique->instances()->detach();

                    foreach ($sousThematique->couches as $couche) {
                        $thematique = $sousThematique->thematique;
                        $table = $thematique->schema . '."' . $couche->schema_table_name . '"';

                        DB::connection('pgsql_osm')->select('DROP TABLE ' . $table);

                        $couche->instances()->detach();

                        $couche->delete();
                    }
                }

                $thematique->sousThematiques()->delete();


                DB::connection('pgsql_osm')->select('DROP SCHEMA ' . $thematique->schema . ' CASCADE');

                $thematique->delete();


                DB::commit();

                $success['thematique'] = $thematique;

                return $this->sendResponse($success, 'Thématique supprimée avec succès.', 201);
            } catch (\Throwable $th) {
                DB::rollback();
                DB::connection('pgsql_osm')->rollBack();
                return $this->sendError('Erreur lors de la suppression de la thématique.', $th->getMessage(), 400);
            }
        }
    }
}
