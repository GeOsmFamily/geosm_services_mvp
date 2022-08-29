<?php

namespace App\Http\Controllers\Api;

use App\Models\Commentaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @group Comments management
 *
 * APIs for managing comments
 */
class CommentaireController extends BaseController
{

    /**
     * Get all comments.
     *
     * @header Content-Type application/json
     */
    public function index()
    {
        $comments = Commentaire::all();
        foreach ($comments as $comment) {
            $comment->user;
        }
        $success['comments'] = $comments;
        return $this->sendResponse($success, 'Comments récupérés avec succès.');
    }

    /**
     * Add a comment.
     *
     * @authenticated
     * @header Content-Type application/json
     * @bodyParam commentaire string required the comment. Example: Commentaire 1
     * @bodyParam longitude string required the longitude. Example: -1.5
     * @bodyParam latitude string required the latitude. Example: 45.5
     * @bodyParam image_url string the comment image.
     * @bodyParam video_url string the comment video.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $validator =  Validator::make($request->all(), [
            'commentaire' => 'required|string',
            'longitude' => 'required|string|max:255',
            'latitude' => 'required|string|max:255',
            'image_url' => 'mimes:png,jpg,jpeg|max:20000',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();

        if ($request->file()) {
            $fileName = time() . '_' . $request->image_url->getClientOriginalName();
            $filePath = $request->file('image_url')->storeAs('uploads/commentaires/images/' . $user->last_name, $fileName, 'public');
            $input['image_url'] = '/storage/' . $filePath;
        }

        /*  if ($request->file()) {
            $fileName = time() . '_' . $request->video_url->getClientOriginalName();
            $filePath = $request->file('video_url')->storeAs('uploads/commentaires/videos/' . $user->last_name, $fileName, 'public');
            $input['video_url'] = '/storage/' . $filePath;
        }*/

        $input['user_id'] = $user->id;

        try {
            $commentaire = Commentaire::create($input);
            $commentaire->user;
            $success['commentaire'] = $commentaire;
            return $this->sendResponse($success, 'Commentaire créé avec succès.');
        } catch (\Exception $e) {
            return $this->sendError('Erreur lors de la création du commentaire.', $e->getMessage());
        }
    }

    /**
     * Get a comment.
     *
     * @urlParam id required The comment id.
     * @header Content-Type application/json
     */
    public function show($id)
    {
        $commentaire = Commentaire::find($id);
        if (is_null($commentaire)) {
            return $this->sendError('Commentaire non trouvé.');
        }
        $commentaire->user;
        $success['commentaire'] = $commentaire;
        return $this->sendResponse($success, 'Commentaire récupéré avec succès.');
    }

    /**
     * Update a comment.
     *
     * @urlParam id required The comment id.
     * @authenticated
     * @header Content-Type application/json
     * @bodyParam commentaire string  the comment. Example: Commentaire 1
     * @bodyParam longitude string  the longitude. Example: -1.5
     * @bodyParam latitude string  the latitude. Example: 45.5
     * @bodyParam image_url string the comment image.
     * @bodyParam video_url string the comment video.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $commentaire = Commentaire::find($id);
        if (is_null($commentaire)) {
            return $this->sendError('Commentaire non trouvé.');
        }
        $validator =  Validator::make($request->all(), [
            'commentaire' => 'string',
            'longitude' => 'string|max:255',
            'latitude' => 'string|max:255',
            'image_url' => 'mimes:png,jpg,jpeg|max:20000',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $input = $request->all();
        if ($request->file()) {
            $fileName = time() . '_' . $request->image_url->getClientOriginalName();
            $filePath = $request->file('image_url')->storeAs('uploads/commentaires/images/' . $user->last_name, $fileName, 'public');
            $input['image_url'] = '/storage/' . $filePath;
        }
        if ($request->file()) {
            $fileName = time() . '_' . $request->video_url->getClientOriginalName();
            $filePath = $request->file('video_url')->storeAs('uploads/commentaires/videos/' . $user->last_name, $fileName, 'public');
            $input['video_url'] = '/storage/' . $filePath;
        }

        try {

            $commentaire->commentaire = $input['commentaire'] ?? $commentaire->commentaire;
            $commentaire->longitude = $input['longitude'] ?? $commentaire->longitude;
            $commentaire->latitude = $input['latitude'] ?? $commentaire->latitude;
            $commentaire->image_url = $input['image_url'] ?? $commentaire->image_url;
            $commentaire->video_url = $input['video_url'] ?? $commentaire->video_url;
            $commentaire->save();
            $commentaire->user;
            $success['commentaire'] = $commentaire;
            return $this->sendResponse($success, 'Commentaire modifié avec succès.');
        } catch (\Throwable $th) {
            return $this->sendError('Erreur lors de la modification du commentaire.', $th->getMessage());
        }
    }

    /**
     * Delete a comment.
     *
     * @urlParam id required The comment id.
     * @authenticated
     * @header Content-Type application/json
     */
    public function destroy($id)
    {
        $commentaire = Commentaire::find($id);
        if (is_null($commentaire)) {
            return $this->sendError('Commentaire non trouvé.');
        }
        $commentaire->delete();
        return $this->sendResponse($id, 'Commentaire supprimé avec succès.');
    }
}
