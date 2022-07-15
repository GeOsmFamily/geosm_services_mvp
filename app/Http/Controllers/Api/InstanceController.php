<?php

namespace App\Http\Controllers\Api;

use App\Models\Instance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * @group Interface management
 *
 * APIs for managing interface
 */
class InstanceController extends BaseController
{
    /**
     * Get all instance.
     *
     * @header Content-Type application/json
     */
    public function index()
    {
        $instances = Instance::all();
        foreach ($instances as $instance) {
            $extent = DB::select("select min(ST_XMin(st_transform(geom,3857))) as a,min(ST_YMin(st_transform(geom,3857))) as b,max(ST_XMax(st_transform(geom,3857))) as c,max(ST_YMax(st_transform(geom,3857))) as d from instances where id='" . $instance->id . "'");

            if (!empty($extent)) {
                $bbox =  array(floatval($extent[0]->a), floatval($extent[0]->b), floatval($extent[0]->c), floatval($extent[0]->d));
                $instance->bbox = $bbox;
            }
        }
        $success['instances'] = $instances;

        return $this->sendResponse($success, 'Instances récupérés avec succès.');
    }

    /**
     * Add a instance.
     *
     * @authenticated
     * @bodyParam nom string required Name of instance Example: "Instance 1"
     * @bodyParam geom string Geometry of instance Example: "POLYGON((0 0,0 10,10 10,10 0,0 0))"
     * @bodyParam mapillary bool Active Mapillary Example: true
     * @bodyParam comparator bool Active Comparator Example: true
     * @bodyParam altimetrie bool Active Altimetrie Example: true
     * @bodyParam download bool Active Download Example: true
     * @bodyParam routing bool Active Routing Example: true
     * @bodyParam app_version string Version of app Example: "1.0.0"
     * @bodyParam app_github_url string Url of github Example: ""
     * @bodyParam app_name string Name of app Example: "App 1"
     * @bodyParam app_email string Email of app Example: ""
     * @bodyParam app_whatsapp string Whatsapp of app Example: ""
     * @bodyParam app_facebook string Facebook of app Example: ""
     * @bodyParam app_twitter string Twitter of app Example: ""
     *
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = Auth::user();

        $input = $request->all();

        if (!$user->hasRole('admin')) {

            return $this->sendError('Vous n\'avez pas les droits pour effectuer cette action.');
        } else {
            try {
                DB::beginTransaction();

                $instance = Instance::create($input);

                DB::commit();

                $success['instance'] = $instance;

                return $this->sendResponse($success, 'Instance créée avec succès.', 201);
            } catch (\Throwable $th) {
                DB::rollback();
                return $this->sendError('Erreur lors de la création de l\'instance.', $th->getMessage(), 400);
            }
        }
    }

    /**
     * Get a instance.
     *
     * @header Content-Type application/json
     * @urlParam id int required the instance id. Example: 1
     */
    public function show($id)
    {
        $instance = Instance::find($id);


        $extent = DB::select("select min(ST_XMin(st_transform(geom,3857))) as a,min(ST_YMin(st_transform(geom,3857))) as b,max(ST_XMax(st_transform(geom,3857))) as c,max(ST_YMax(st_transform(geom,3857))) as d from instances where id='" . $instance->id . "'");

        if (!empty($extent)) {
            $bbox =  array(floatval($extent[0]->a), floatval($extent[0]->b), floatval($extent[0]->c), floatval($extent[0]->d));
            $instance->bbox = $bbox;
        }

        $success['instance'] = $instance;

        return $this->sendResponse($success, 'Instance récupérée avec succès.');
    }

    /**
     * Update a instance.
     *
     * @authenticated
     * @bodyParam nom string Name of instance Example: "Instance 1"
     * @bodyParam geom string Geometry of instance Example: "POLYGON((0 0,0 10,10 10,10 0,0 0))"
     * @bodyParam mapillary bool Active Mapillary Example: true
     * @bodyParam comparator bool Active Comparator Example: true
     * @bodyParam altimetrie bool Active Altimetrie Example: true
     * @bodyParam download bool Active Download Example: true
     * @bodyParam routing bool Active Routing Example: true
     * @bodyParam app_version string Version of app Example: "1.0.0"
     * @bodyParam app_github_url string Url of github Example: ""
     * @bodyParam app_name string Name of app Example: "App 1"
     * @bodyParam app_email string Email of app Example: ""
     * @bodyParam app_whatsapp string Whatsapp of app Example: ""
     * @bodyParam app_facebook string Facebook of app Example: ""
     * @bodyParam app_twitter string Twitter of app Example: ""
     * @urlParam id int required the instance id. Example: 1
     *
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {

            return $this->sendError('Vous n\'avez pas les droits pour effectuer cette action.');
        } else {
            $instance = Instance::find($id);

            try {
                DB::beginTransaction();

                $instance->nom = $request->nom ?? $instance->nom;
                $instance->geom = $request->geom ?? $instance->geom;
                $instance->mapillary = $request->mapillary ?? $instance->mapillary;
                $instance->comparator = $request->comparator ?? $instance->comparator;
                $instance->altimetrie = $request->altimetrie ?? $instance->altimetrie;
                $instance->download = $request->download ?? $instance->download;
                $instance->routing = $request->routing ?? $instance->routing;
                $instance->app_version = $request->app_version ?? $instance->app_version;
                $instance->app_github_url = $request->app_github_url ?? $instance->app_github_url;
                $instance->app_name = $request->app_name ?? $instance->app_name;
                $instance->app_email = $request->app_email ?? $instance->app_email;
                $instance->app_whatsapp = $request->app_whatsapp ?? $instance->app_whatsapp;
                $instance->app_facebook = $request->app_facebook ?? $instance->app_facebook;
                $instance->app_twitter = $request->app_twitter ?? $instance->app_twitter;

                $instance->save();

                DB::commit();

                $success['instance'] = $instance;

                return $this->sendResponse($success, 'Instance mise à jour avec succès.', 201);
            } catch (\Throwable $th) {
                DB::rollback();
                return $this->sendError('Erreur lors de la mise à jour de l\'instance.', $th->getMessage(), 400);
            }
        }
    }

    /**
     * Delete a instance.
     *
     * @authenticated
     * @urlParam id int required the instance id. Example: 1
     *
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {

            return $this->sendError('Vous n\'avez pas les droits pour effectuer cette action.');
        } else {
            $instance = Instance::find($id);

            try {
                DB::beginTransaction();

                $instance->delete();

                DB::commit();

                $success['instance'] = $instance;

                return $this->sendResponse($success, 'Instance supprimée avec succès.', 201);
            } catch (\Throwable $th) {
                DB::rollback();
                return $this->sendError('Erreur lors de la suppression de l\'instance.', $th->getMessage(), 400);
            }
        }
    }
}
