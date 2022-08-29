<?php

namespace App\Http\Controllers\Api;

use App\Models\Draw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @group Draw management
 *
 * APIs for managing draw
 */
class DrawController extends BaseController
{
    /**
     * Get all draw.
     *
     * @header Content-Type application/json
     */
    public function index()
    {
        $draws = Draw::all();
        $success['draws'] = $draws;
        return $this->sendResponse($success, 'Draws récupérés avec succès.');
    }

    /**
     * Add a draw.
     *
     * @authenticated
     * @header Content-Type application/json
     * @bodyParam code string required the draw code. Example: Groupe 1
     * @bodyParam description string the draw description. Example: Groupe 1
     * @bodyParam geom string required the draw geometry. Example: Groupe 1
     * @bodyParam type string required the draw type. Example: Groupe 1
     * @bodyParam color string required the draw color. Example: Groupe 1
     */
    public function store(Request $request)
    {
        $draws = $request->draws;

        try {
            $draw = Draw::insert($draws);
            $success['draw'] = $draw;
            return $this->sendResponse($success, 'Draw créé avec succès.');
        } catch (\Exception $e) {
            return $this->sendError('Erreur lors de la création du draw.', $e->getMessage());
        }
    }

    /** Get Draw
     *
     * @urlParam id required The draw id.
     *
     */
    public function show($id)
    {
        $draw = Draw::find($id);
        if (is_null($draw)) {
            return $this->sendError('Draw non trouvé.');
        }
        $success['draw'] = $draw;
        return $this->sendResponse($success, 'Draw trouvé avec succès.');
    }

    /**
     * Update a draw.
     *
     * @authenticated
     * @header Content-Type application/json
     * @urlParam id required The draw id.
     * @bodyParam code string  the draw code. Example: Groupe 1
     * @bodyParam description string the draw description. Example: Groupe 1
     * @bodyParam geom string  the draw geometry. Example: Groupe 1
     * @bodyParam type string  the draw type. Example: Groupe 1
     * @bodyParam color string  the draw color. Example: Groupe 1
     */
    public function update(Request $request, $id)
    {
        $draw = Draw::find($id);
        if (is_null($draw)) {
            return $this->sendError('Draw non trouvé.');
        }
        $validator =  Validator::make($request->all(), [
            'code' => 'string|max:255',
            'description' => 'string|max:255',
            'geom' => 'string',
            'type' => 'string',
            'color' => 'string',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Erreur de validation.', $validator->errors());
        }
        $input = $request->all();

        try {
            $draw->code = $input['code'] ?? $draw->code;
            $draw->description = $input['description'] ?? $draw->description;
            $draw->geom = $input['geom'] ?? $draw->geom;
            $draw->type = $input['type'] ?? $draw->type;
            $draw->color = $input['color'] ?? $draw->color;
            $draw->save();
            $success['draw'] = $draw;
            return $this->sendResponse($success, 'Draw modifié avec succès.');
        } catch (\Throwable $th) {
            return $this->sendError('Erreur lors de la modification du draw.', $th->getMessage());
        }
    }

    /**
     * Delete a draw.
     *
     * @authenticated
     * @header Content-Type application/json
     * @urlParam id required The draw id.
     */
    public function destroy($id)
    {
        $draw = Draw::find($id);
        if (is_null($draw)) {
            return $this->sendError('Draw non trouvé.');
        }
        $draw->delete();
        return $this->sendResponse($id, 'Draw supprimé avec succès.');
    }

    /**
     * Get all draw by code.
     *
     * @authenticated
     * @header Content-Type application/json
     * @urlParam code required The code.
     */
    public function getAllDrawByCode($code)
    {
        $draws = Draw::where('code', $code)->get();
        $success['draws'] = $draws;
        return $this->sendResponse($success, 'Draws récupérés avec succès.');
    }
}
