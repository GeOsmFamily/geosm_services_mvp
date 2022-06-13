<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * @group Tag management
 *
 * APIs for managing tag
 */
class TagController extends BaseController
{
    /**
     * Get all tag.
     *
     * @header Content-Type application/json
     */
    public function index()
    {
        $tags = Tag::all();
        foreach ($tags as $tag) {
            $tag->couche = $tag->couche()->get();
        }
        $success['tags'] = $tags;
        return $this->sendResponse($success, 'Tags récupérés avec succès.');
    }

    /**
     * Add a tag.
     *
     * @authenticated
     * @header Content-Type application/json
     * @bodyParam couche_id int required the tag couche id. Example: 1
     * @bodyParam key string required the tag name. Example: amenity
     * @bodyParam value string required the tag value. Example: resaurant
     * @bodyParam operateur string required the tag name. Example: 0
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return $this->sendError('Vous n\'avez pas les droits pour effectuer cette action.');
        } else {
            $validator =  Validator::make($request->all(), [
                'couche_id' => 'required|integer',
                'key' => 'required|string|max:255',
                'value' => 'required|string|max:255',
                'operateur' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Erreur de validation.', $validator->errors());
            }

            $input = $request->all();

            try {
                DB::beginTransaction();

                $tag = Tag::create($input);

                DB::commit();

                $success['tag'] = $tag;

                return $this->sendResponse($success, 'Tag ajouté avec succès.', 201);
            } catch (\Exception $e) {
                DB::rollback();
                return $this->sendError('Erreur lors de la création du tag.', $e->getMessage());
            }
        }
    }

    /**
     * Get a tag.
     *
     * @header Content-Type application/json
     * @urlParam id required The tag id. Example: 1
     */
    public function show($id)
    {
        $tag = Tag::find($id);
        if (is_null($tag)) {
            return $this->sendError('Tag non trouvé.');
        }
        $tag->couche = $tag->couche()->get();
        $success['tag'] = $tag;
        return $this->sendResponse($success, 'Tag récupéré avec succès.');
    }

    /**
     * Update a tag.
     *
     * @authenticated
     * @header Content-Type application/json
     * @urlParam id required The tag id. Example: 1
     * @bodyParam key string the tag name. Example: amenity
     * @bodyParam value string the tag value. Example: resaurant
     * @bodyParam operateur string the tag name. Example: 0
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return $this->sendError('Vous n\'avez pas les droits pour effectuer cette action.');
        } else {
            $validator =  Validator::make($request->all(), [
                'key' => 'string|max:255',
                'value' => 'string|max:255',
                'operateur' => 'string|max:255',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Erreur de validation.', $validator->errors());
            }

            $input = $request->all();

            try {
                DB::beginTransaction();

                $tag = Tag::find($id);
                if (is_null($tag)) {
                    return $this->sendError('Tag non trouvé.');
                }
                $tag->key = $input['key'] ?? $tag->key;
                $tag->value = $input['value'] ?? $tag->value;
                $tag->operateur = $input['operateur'] ?? $tag->operateur;

                $tag->save();

                DB::commit();

                $success['tag'] = $tag;

                return $this->sendResponse($success, 'Tag modifié avec succès.', 201);
            } catch (\Exception $e) {
                DB::rollback();
                return $this->sendError('Erreur lors de la modification du tag.', $e->getMessage());
            }
        }
    }

    /**
     * Delete a tag.
     *
     * @authenticated
     * @header Content-Type application/json
     * @urlParam id required The tag id. Example: 1
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return $this->sendError('Vous n\'avez pas les droits pour effectuer cette action.');
        } else {
            $tag = Tag::find($id);
            if (is_null($tag)) {
                return $this->sendError('Tag non trouvé.');
            }
            $tag->delete();
            return $this->sendResponse($tag, 'Tag supprimé avec succès.');
        }
    }
}
