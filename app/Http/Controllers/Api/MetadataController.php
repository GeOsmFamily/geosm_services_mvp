<?php

namespace App\Http\Controllers\Api;

use App\Models\Metadata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @group Group Metadatas management
 *
 * APIs for managing metadatas
 */
class MetadataController extends BaseController
{
    /**
     * Get all metadatas
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $metadatas = Metadata::all();

        return $this->sendResponse($metadatas, 'Metadatas retrieved successfully.');
    }

    /**
     * Add a new metadata
     * @authenticated
     *
     * @bodyParam carte_id int  Carte id
     * @bodyParam couche_id int  Couche id
     * @bodyParam resume string  Resume
     * @bodyParam description string  Description
     * @bodyParam zone string  Zone
     * @bodyParam epsg string  EPSG
     * @bodyParam langue string  Langue
     * @bodyParam echelle string  Echelle
     * @bodyParam licence string  Licence
     *
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'carte_id' => 'integer',
            'couche_id' => 'integer',
            'resume' => 'string|max:255',
            'description' => 'string|max:255',
            'zone' => 'string|max:255',
            'epsg' => 'string|max:255',
            'langue' => 'string|max:255',
            'echelle' => 'string|max:255',
            'licence' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();

        try {
            $metadata = Metadata::create($input);
            $success['metadata'] = $metadata;
            return $this->sendResponse($success, 'Metadata added successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Error creating metadata.', $e->getMessage());
        }
    }

    /**
     * Show a metadata
     * @urlParam id required The id of the metadata.
     */
    public function show($id)
    {
        $metadata = Metadata::find($id);

        if (is_null($metadata)) {
            return $this->sendError('Metadata not found.');
        }

        return $this->sendResponse($metadata, 'Metadata retrieved successfully.');
    }

    /**
     * Update a metadata
     * @authenticated
     * @urlParam id required The id of the metadata.
     * @bodyParam carte_id int  Carte id
     * @bodyParam couche_id int  Couche id
     * @bodyParam resume string  Resume
     * @bodyParam description string  Description
     * @bodyParam zone string  Zone
     * @bodyParam epsg string  EPSG
     * @bodyParam langue string  Langue
     * @bodyParam echelle string  Echelle
     * @bodyParam licence string  Licence
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'carte_id' => 'integer',
            'couche_id' => 'integer',
            'resume' => 'string|max:255',
            'description' => 'string|max:255',
            'zone' => 'string|max:255',
            'epsg' => 'string|max:255',
            'langue' => 'string|max:255',
            'echelle' => 'string|max:255',
            'licence' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $metadata = Metadata::find($id);

        if (is_null($metadata)) {
            return $this->sendError('Metadata not found.');
        }

        try {

            $metadata->carte_id = $request->carte_id ?? $metadata->carte_id;
            $metadata->couche_id = $request->couche_id ?? $metadata->couche_id;
            $metadata->resume = $request->resume ?? $metadata->resume;
            $metadata->description = $request->description ?? $metadata->description;
            $metadata->zone = $request->zone ?? $metadata->zone;
            $metadata->epsg = $request->epsg ?? $metadata->epsg;
            $metadata->langue = $request->langue ?? $metadata->langue;
            $metadata->echelle = $request->echelle ?? $metadata->echelle;
            $metadata->licence = $request->licence ?? $metadata->licence;
            $metadata->save();

            $success['metadata'] = $metadata;

            return $this->sendResponse($success, 'Metadata updated successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error updating metadata.', $th->getMessage());
        }
    }

    /**
     * Delete a metadata
     * @authenticated
     * @urlParam id required The id of the metadata.
     */
    public function destroy($id)
    {
        $metadata = Metadata::find($id);

        if (is_null($metadata)) {
            return $this->sendError('Metadata not found.');
        }

        try {
            $metadata->delete();
            return $this->sendResponse($id, 'Metadata deleted successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error deleting metadata.', $th->getMessage());
        }
    }
}
