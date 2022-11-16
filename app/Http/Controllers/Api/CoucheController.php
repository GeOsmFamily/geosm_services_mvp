<?php

namespace App\Http\Controllers\Api;

use App\Models\Couche;
use App\Models\Instance;
use App\Models\SousThematique;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

/**
 * @group Group Couche management
 *
 * APIs for managing couche
 */
class CoucheController extends BaseController
{
    /**
     * Get all couches
     *
     * @header Content-Type application/json
     */
    public function index()
    {
        $couches = Couche::all();
        foreach ($couches as $couche) {
            $couche->sousThematique = $couche->sousThematique()->get();
            foreach ($couche->sousThematique as $sousThematique) {
                $sousThematique->thematique = $sousThematique->thematique()->get();
            }
            $couche->metadatas;
            $couche->tags;
        }
        $success['couches'] = $couches;
        return $this->sendResponse($success, 'Couches récupérés avec succès.');
    }

    /**
     * Add a couche
     *
     * @authenticated
     * @header Content-Type application/json
     * @bodyParam sous_thematique_id int required the couche sous thematique id. Example: 1
     * @bodyParam nom string required the couche name. Example: Couche
     * @bodyParam nom_en string required the couche name in english. Example: Couche
     * @bodyParam geometry string the couche geometry. Example: point
     * @bodyParam remplir_color string the couche fill color. Example: #000000
     * @bodyParam contour_color string the couche contour color. Example: #000000
     * @bodyParam opacite string the couche opacity. Example: 0.5
     * @bodyParam service_carto string the couche service carto. Example: wms
     * @bodyParam bbox string the couche bbox. Example: 0,0,1,1
     * @bodyParam wms_type string the couche wms type. Example: osm
     * @bodyParam logo file the couche logo.
     * @bodyParam condition string the couche tag. Example: OR
     * @bodyParam mode_sql bool the couche mode sql. Example: false
     * @bodyParam sql_complete string the couche sql complete. Example: 10
     * @bodyParam data_src file the couche data source(geojson,kml,gpkg,shp).
     * @bodyParam data_qml file the couche data qml.
     * @bodyParam key string the osm key for the couche. Example: amenity
     * @bodyParam value string the osm value for the couche. Example: restaurant
     * @bodyParam operateur int the osm operateur for the couche. Example: 0
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return $this->sendError('Vous n\'avez pas les droits pour effectuer cette action.');
        } else {
            $validator =  Validator::make($request->all(), [
                'sous_thematique_id' => 'required|integer',
                'nom' => 'required|string',
                'nom_en' => 'required|string',
                'geometry' => 'string',
                'remplir_color' => 'string',
                'contour_color' => 'string',
                'opacite' => 'string',
                'service_carto' => 'string',
                'bbox' => 'string',
                'wms_type' => 'string',
                'logo' => 'file',
                'condition' => 'string',
                'data_src' => 'file',
                'data_qml' => 'file',
                'key' => 'string',
                'value' => 'string',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Erreur de validation.', $validator->errors());
            }

            $input = $request->all();

            $sousThematique = SousThematique::find($input['sous_thematique_id']);
            $schema = $sousThematique->thematique->schema . ' ' . $input['nom'];

            $input['schema_table_name'] = strtolower(preg_replace('/[^A-Za-z0-9]/', '', $schema));

            $input['identifiant'] = strtolower(preg_replace('/[^A-Za-z0-9]/', '', $input['nom']));

            $instance = Instance::find(1);


            if ($request->file('logo')) {
                $fileName = time() . '_' . $request->logo->getClientOriginalName();
                $filePath = $request->file('logo')->storeAs('uploads/couches/logos/' . $instance->nom . '/' . $request->nom, $fileName, 'public');
                $input['logo'] = '/storage/' . $filePath;
                $logo_path = '/storage/app/public/' . $filePath;
            }

            if ($request->file('data_src')) {
                $fileName = time() . '_' . $request->data_src->getClientOriginalName();
                $filePath = $request->file('data_src')->storeAs('uploads/couches/datas/' . $instance->nom . '/' . $request->nom, $fileName, 'public');
                $data_src = '/storage/app/public/' . $filePath;
            }

            if ($request->file('data_qml')) {
                $fileName = time() . '_' . $request->data_qml->getClientOriginalName();
                $filePath = $request->file('data_qml')->storeAs('uploads/couches/qml/' . $instance->nom . '/' . $request->nom, $fileName, 'public');
                $data_qml = '/storage/app/public/' . $filePath;
            }

            try {
                DB::beginTransaction();

                $couche = Couche::create($input);


                if ($request->wms_type == 'osm') {

                    DB::select(' CREATE TABLE ' . $sousThematique->thematique->schema . '.' . $input['schema_table_name'] . ' ()with(OIDS=FALSE)');
                    Tag::create([
                        'couche_id' => $couche->id,
                        'key' => $request->key,
                        'value' => $request->value,
                        'operateur' => $request->operateur,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                    $result =  $this->generateSqlForLayer($couche, 1, env('INTERSECTION'));

                    if (!$result['status']) {

                        return $this->sendError('Erreur lors de la création de la couche.', $result, 400);
                    }


                    $qgis_project_name = $instance->nom . $sousThematique->thematique->id;

                    if ($request->file('data_qml')) {
                        $response = Http::timeout(500)->post(env('CARTO_URL') . '/creategpkg', [
                            'sql' => $result['sql'],
                            'instance' => $instance->nom,
                            'couche_name' => $couche->nom,
                            'couche_id'  => $couche->id,
                            'qgis_project_name' => $qgis_project_name,
                            'path_qgis' => '/var/www/html/src/qgis/' . $instance->nom,
                            'geometry' => $couche->geometry,
                            'identifiant' =>  $couche->identifiant,
                            'path_qml' => '/var/www/html/src/geosm_mvp/' . $instance->nom . '/backend'  . $data_qml,
                        ]);


                        if ($response->successful()) {
                            $status = $response->json("status");
                            $layer = $response->json("layer");
                            if ($status) {
                                $path_project = str_replace('/var/www/html/src/qgis/', '', $layer['path_project']);

                                $qgis_url = env('URL_QGIS') . $path_project;
                                $bbox = $layer['bbox'];
                                $projection = $layer['scr'];
                                $features = $layer['features'];

                                $couche->sql = $result['sql'];
                                $couche->qgis_url = $qgis_url;
                                $couche->bbox = $bbox;
                                $couche->projection = $projection;
                                $couche->number_features = $features;
                                $couche->distance = $result['distance'];
                                $couche->surface = $result['surface'];
                                $couche->save();
                            } else {
                                return $this->sendError('Erreur lors de la création de la couche.', $response->body(), 400);
                            }
                        } else {
                            return $this->sendError('Erreur lors de la création de la couche.', "Request Failed", 400);
                        }
                    } else {
                        $response = Http::timeout(500)->post(env('CARTO_URL') . '/creategpkg', [
                            'sql' => $result['sql'],
                            'instance' => $instance->nom,
                            'couche_name' => $couche->nom,
                            'couche_id'  => $couche->id,
                            'qgis_project_name' => $qgis_project_name,
                            'path_qgis' => '/var/www/html/src/qgis/' . $instance->nom,
                            'geometry' => $couche->geometry,
                            'identifiant' =>  $couche->identifiant,
                            'path_logo' => '/var/www/html/src/geosm_mvp/' . $instance->nom . '/backend'  . $logo_path,
                            'color' => $couche->remplir_color
                        ]);

                        if ($response->successful()) {
                            $status = $response->json("status");
                            $layer = $response->json("layer");
                            if ($status) {
                                $path_project = str_replace('/var/www/html/src/qgis/', '', $layer['path_project']);

                                $qgis_url = env('URL_QGIS') . $path_project;
                                $bbox = $layer['bbox'];
                                $projection = $layer['scr'];
                                $features = $layer['features'];

                                $couche->sql = $result['sql'];
                                $couche->qgis_url = $qgis_url;
                                $couche->bbox = $bbox;
                                $couche->projection = $projection;
                                $couche->number_features = $features;
                                $couche->distance = $result['distance'];
                                $couche->surface = $result['surface'];
                                $couche->save();
                            } else {
                                return $this->sendError('Erreur lors de la création de la couche.', $response->body(), 400);
                            }
                        } else {
                            return $this->sendError('Erreur lors de la création de la couche.', "Request Failed", 400);
                        }
                    }
                } else if ($request->wms_type == 'data') {

                    $qgis_project_name = $instance->nom . $sousThematique->thematique->id;

                    if ($request->file('data_qml')) {
                        $response = Http::timeout(500)->post(env('CARTO_URL') . '/addotherlayer', [
                            'qgis_project_name' => $qgis_project_name,
                            'path_qgis' => '/var/www/html/src/qgis/' . $instance->nom,
                            'path_data' => '/var/www/html/src/geosm_mvp/' . $instance->nom . '/backend' .   $data_src,
                            'geometry' => $couche->geometry,
                            'identifiant' =>  $couche->identifiant,
                            'path_qml' => '/var/www/html/src/geosm_mvp/' . $instance->nom . '/backend' .  $data_qml,
                        ]);


                        if ($response->successful()) {
                            $status = $response->json("status");
                            $layer = $response->json("layer");
                            if ($status) {
                                $path_project = str_replace('/var/www/html/src/qgis/', '', $layer['path_project']);

                                $qgis_url = env('URL_QGIS') . $path_project;
                                $bbox = $layer['bbox'];
                                $projection = $layer['scr'];
                                $features = $layer['features'];

                                $couche->qgis_url = $qgis_url;
                                $couche->bbox = $bbox;
                                $couche->projection = $projection;
                                $couche->number_features = $features;
                                $couche->save();
                            } else {
                                return $this->sendError('Erreur lors de la création de la couche.', $response->json("error"), 400);
                            }
                        } else {
                            return $this->sendError('Erreur lors de la création de la couche.', "Request Failed", 400);
                        }
                    } else {
                        $response = Http::timeout(500)->post(env('CARTO_URL') . '/addotherlayer', [

                            'qgis_project_name' => $qgis_project_name,
                            'path_qgis' => '/var/www/html/src/qgis/' . $instance->nom,
                            'path_data' => '/var/www/html/src/geosm_mvp/' . $instance->nom . '/backend' .  $data_src,
                            'geometry' => $couche->geometry,
                            'identifiant' =>  $couche->identifiant,
                            'path_logo' => '/var/www/html/src/geosm_mvp/' . $instance->nom . '/backend' .  $couche->logo,
                            'color' => $couche->remplir_color
                        ]);

                        if ($response->successful()) {
                            $status = $response->json("status");
                            $layer = $response->json("layer");
                            if ($status) {
                                $path_project = str_replace('/var/www/html/src/qgis/', '', $layer['path_project']);

                                $qgis_url = env('URL_QGIS') . $path_project;
                                $bbox = $layer['bbox'];
                                $projection = $layer['scr'];
                                $features = $layer['features'];

                                $couche->qgis_url = $qgis_url;
                                $couche->bbox = $bbox;
                                $couche->projection = $projection;
                                $couche->number_features = $features;
                                $couche->save();
                            } else {
                                return $this->sendError('Erreur lors de la création de la couche.', $response->json("message"), 400);
                            }
                        } else {
                            return $this->sendError('Erreur lors de la création de la couche.', "Request Failed", 400);
                        }
                    }
                } else if ($request->wms_type == 'qgis') {
                    $couche->identifiant = $request->identifiant;
                    $couche->qgis_url = $request->qgis_url;
                    $couche->bbox = $request->bbox;
                    $couche->projection = $request->projection;
                    $couche->number_features = $request->number_features;
                    $couche->save();
                }

                DB::commit();
                $success['couche'] = $couche;
                return $this->sendResponse($success, 'Couche créée avec succès.', 201);
            } catch (\Throwable $th) {
                DB::rollback();
                return $this->sendError('Erreur lors de la création de la couche.', $th->getMessage(), 400);
            }
        }
    }

    /**
     * Get a couche
     *
     * @header Content-Type application/json
     * @urlParam id required The couche id. Example: 1
     */
    public function show($id)
    {
        $couche = Couche::find($id);

        $couche->sousThematique = $couche->sousThematique()->get();
        foreach ($couche->sousThematique as $sousThematique) {
            $sousThematique->thematique = $sousThematique->thematique()->get();
        }
        $couche->metadatas;
        $couche->tags;
        $success['couche'] = $couche;
        return $this->sendResponse($success, 'Couche récupérée avec succès.');
    }

    /**
     * Update a couche
     *
     * @authenticated
     * @header Content-Type application/json
     * @urlParam id required The couche id. Example: 1
     * @bodyParam nom string the couche name. Example: Couche1
     * @bodyParam nom_en string the couche name in english. Example: Couche1
     * @bodyParam geometry string the couche geometry. Example: Polygon
     * @bodyParam remplir_color string the couche fill color. Example: #000000
     * @bodyParam contour_color string the couche contour color. Example: #000000
     * @bodyParam opacite string the couche opacity. Example: 0.5
     * @bodyParam service_carto string the couche service carto. Example: wms
     * @bodyParam qgis_url string the couche qgis url. Example: http://localhost:8080/geoserver/wms
     * @bodyParam identifiant string the couche identifiant. Example: couche_1
     * @bodyParam bbox string the couche bbox. Example: [0,0,1,1]
     * @bodyParam projection string the couche projection. Example: EPSG:4326
     * @bodyParam wms_type string the couche wms type. Example: osm
     * @bodyParam number_features int the couche number features. Example: 10
     * @bodyParam logo file the couche logo.
     * @bodyParam surface string the couche surface. Example: 10
     * @bodyParam distance string the couche distance. Example: 10
     * @bodyParam sql string the couche sql. Example: 10
     * @bodyParam condition string the couche tag. Example: 10
     * @bodyParam mode_sql bool the couche mode sql. Example: true
     * @bodyParam sql_complete string the couche sql complete. Example: 10
     * @bodyParam vues bool the couche view count. Example: true
     * @bodyParam telechargement bool the couche download count. Example: true
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return $this->sendError('Vous n\'avez pas les droits pour effectuer cette action.');
        } else {
            $couche = Couche::find($id);

            $input = $request->all();

            $validator = Validator::make($input, [
                'nom' => 'string',
                'nom_en' => 'string',
                'geometry' => 'string',
                'remplir_color' => 'string',
                'contour_color' => 'string',
                'opacite' => 'string',
                'service_carto' => 'string',
                'qgis_url' => 'string',
                'identifiant' => 'string',
                'bbox' => 'string',
                'projection' => 'string',
                'wms_type' => 'string',
                'number_features' => 'integer',
                'logo' => 'file',
                'surface' => 'string',
                'distance' => 'string',
                'sql' => 'string',
                'condition' => 'string',
                'mode_sql' => 'string',
                'sql_complete' => 'string',
                'vues' => 'boolean',
                'telechargement' => 'boolean',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Erreur de validation.', $validator->errors());
            }

            try {
                DB::beginTransaction();

                $couche->nom = $input['nom'] ?? $couche->nom;
                $couche->nom_en = $input['nom_en'] ?? $couche->nom_en;
                $couche->image_src = $input['image_src'] ?? $couche->image_src;
                $couche->geometry = $input['geometry'] ?? $couche->geometry;
                $couche->remplir_color = $input['remplir_color'] ?? $couche->remplir_color;
                $couche->contour_color = $input['contour_color'] ?? $couche->contour_color;
                $couche->service_carto = $input['service_carto'] ?? $couche->service_carto;
                $couche->qgis_url = $input['qgis_url'] ?? $couche->qgis_url;
                $couche->identifiant = $input['identifiant'] ?? $couche->identifiant;
                $couche->wms_type = $input['wms_type'] ?? $couche->wms_type;
                $couche->logo = $input['logo'] ?? $couche->logo;
                $couche->sql = $input['sql'] ?? $couche->sql;
                $couche->condition = $input['condition'] ?? $couche->condition;
                $couche->mode_sql = $input['mode_sql'] ?? $couche->mode_sql;
                $couche->sql_complete = $input['sql_complete'] ?? $couche->sql_complete;
                $couche->opacite = $input['opacite'] ?? $couche->opacite;
                $couche->qgis_url = $input['qgis_url'] ?? $couche->qgis_url;
                $couche->bbox = $input['bbox'] ?? $couche->bbox;
                $couche->projection = $input['projection'] ?? $couche->projection;
                $couche->number_features = $input['number_features'] ?? $couche->number_features;
                $couche->surface = $input['surface'] ?? $couche->surface;
                $couche->distance = $input['distance'] ?? $couche->distance;

                $couche->save();


                if ($input['vues']) {
                    $couche->vues = $couche->vues + 1;
                }
                if ($input['telechargement']) {
                    $couche->telechargement = $couche->telechargement + 1;
                }

                DB::commit();


                $success['couche'] = $couche;
                return $this->sendResponse($success, 'Couche modifiée avec succès.', 201);
            } catch (\Throwable $th) {
                DB::rollback();
                return $this->sendError('Erreur lors de la modification de la couche.', $th->getMessage(), 400);
            }
        }
    }

    /**
     * Delete a couche.
     *
     * @authenticated
     * @header Content-Type: application/json
     * @urlParam id int required the couche id. Example: 1
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return $this->sendError('Vous n\'avez pas les droits pour effectuer cette action.');
        } else {
            $couche = Couche::find($id);

            try {
                DB::beginTransaction();

                $sousThematique = SousThematique::find($couche->sous_thematique_id);
                $thematique = $sousThematique->thematique;
                $table = $thematique->schema . '."' . $couche->schema_table_name . '"';

                DB::select('DROP TABLE ' . $table);

                $couche->tags()->delete();

                $couche->delete();

                DB::commit();

                $success['couche'] = $couche;
                return $this->sendResponse($success, 'Couche supprimée avec succès.', 201);
            } catch (\Throwable $th) {
                DB::rollback();
                return $this->sendError('Erreur lors de la suppression de la couche.', $th->getMessage(), 400);
            }
        }
    }

    /**
     * Generate sql for couche.
     *
     *
     */

    public function generateSqlForLayer($couche, $instance_id, $intersection)
    {
        $instance = "instances";
        $geomColum = "geom";
        $surface = 0;
        $distance = 0;
        try {

            $completeOsm = $this->getConditionOsm($couche->id);

            $geometry_type = $completeOsm['geom'];
            $where = $completeOsm['where'];
            $select = $completeOsm['select'];

            if ($select != null) {
                $select = "A.osm_id,A.name,A.amenity,hstore_to_json(A.tags)," . $select;
            } else {
                $select = "A.osm_id,A.name,A.amenity,hstore_to_json(A.tags)";
            }

            if ($geometry_type == 'point') {
                if ($intersection) {
                    $sql = 'select ' . $select . ',ST_TRANSFORM(A.way,4326) as geometry from planet_osm_point as A ,' . $instance . ' as B where B.id = ' . $instance_id . ' and (ST_Intersects( ST_TRANSFORM(A.way,4326), ST_TRANSFORM(B.' . $geomColum . ',4326) )) AND ( ' . $where . ' ) union all select ' . $select . ',ST_Centroid(ST_TRANSFORM(A.way,4326)) as geometry from planet_osm_polygon as A ,' . $instance . ' as B where B.id = ' . $instance_id . ' and (ST_Contains( ST_TRANSFORM(B.' . $geomColum . ',4326), ST_TRANSFORM(A.way,4326) )) AND ( ' . $where . ' ) ';
                } else {
                    $sql = 'select ' . $select . ',ST_TRANSFORM(A.way,4326) as geometry from planet_osm_point as A ,' . $instance . ' as B where B.id = ' . $instance_id . ' and  ( ' . $where . ' ) union all select ' . $select . ',ST_Centroid(ST_TRANSFORM(A.way,4326)) as geometry from planet_osm_polygon as A ,' . $instance . ' as B where B.id = ' . $instance_id . ' and ( ' . $where . ' ) ';
                }
            } else if ($geometry_type == 'polygon') {
                if ($intersection) {
                    $surface = DB::select('select count(*) as count, sum(ST_NPoints(A.way)) AS nbre_pt,sum(A.way_area)/1000000 as surface from planet_osm_polygon  as A ,' . $instance . ' as B where  (B.id = ' . $instance_id . ' and (ST_Contains ( ST_TRANSFORM(ST_Buffer(B.' . $geomColum . '::geography,10)::geometry,4326), ST_TRANSFORM(A.way,4326) ))) AND ( ' . $where . ' )');
                    $sql = 'select ' . $select . ', ST_TRANSFORM(A.way,4326) as geometry from planet_osm_polygon  as A ,' . $instance . ' as B where  (B.id = ' . $instance_id . ' and (ST_Contains ( ST_TRANSFORM(ST_Buffer(B.' . $geomColum . '::geography,10)::geometry,4326), ST_TRANSFORM(A.way,4326) ))) AND ( ' . $where . ' )';
                } else {
                    $surface = DB::select("select count(*) as count, sum(ST_NPoints(A.way)) AS nbre_pt,sum(A.way_area)/1000000 as surface from planet_osm_polygon  as A , $instance  as B where  B.id =  $instance_id  AND  $where");
                    $sql = "select " . $select . ", ST_TRANSFORM(A.way,4326) as geometry from planet_osm_polygon  as A , $instance  as B where  B.id =  $instance_id  AND  $where";
                }
            } else if ($geometry_type == 'linestring') {
                if ($intersection) {
                    $distance = DB::select('select count(*) as count, sum(ST_NPoints(A.way)) AS nbre_pt, sum(ST_length( geography(ST_TRANSFORM(A.way,4326)) )) / 1000 as distance from planet_osm_line  as A ,' . $instance . ' as B where  (B.id = ' . $instance_id . ' and (ST_Intersects ( ST_TRANSFORM(B.' . $geomColum . ',4326), ST_TRANSFORM(A.way,4326) ))) AND ( ' . $where . ' )');
                    $sql = 'select ' . $select . ',ST_TRANSFORM(A.way,4326) as geometry from planet_osm_line as A ,' . $instance . ' as B where (B.id = ' . $instance_id . ' and (ST_Intersects( ST_TRANSFORM(A.way,4326), ST_TRANSFORM(B.' . $geomColum . ',4326) ))) AND ( ' . $where . ' ) ';
                } else {
                    $distance = DB::select("select count(*) as count, sum(ST_NPoints(A.way)) AS nbre_pt, sum(ST_length( geography(ST_TRANSFORM(A.way,4326)) )) / 1000 as distance from planet_osm_line  as A , $instance  as B where  B.id =  $instance_id  AND  $where");
                    $sql = "select " . $select . ",ST_TRANSFORM(A.way,4326) as geometry from planet_osm_line as A ,  $instance  as B where B.id =  $instance_id  AND  $where";
                }
            }

            // $sousThematiqueInstance = SousThematiqueInstance::where('sous_thematique_id', $couche->sous_thematique_id, 'instance_id', $instance_id)->first();
            $sousThematique = SousThematique::find($couche->sous_thematique_id);
            $thematique = $sousThematique->thematique;
            $schema = $thematique->schema;
            $table = $couche->schema_table_name;

            $this->createOsmTable($schema, $table, $sql);


            $success['sql'] = $sql;
            $success['couche'] = $couche;
            $success['surface'] = $surface;
            $success['distance'] = $distance;
            $success['status'] = true;
            return $success;
        } catch (\Throwable $th) {
            $success['status'] = false;
            $success['error'] = $th->getMessage();
            return $success;
        }
    }

    public function getConditionOsm($couche_id)
    {
        $couche = Couche::find($couche_id);
        $tags = Tag::where('couche_id', $couche_id)->get();

        $where = '';

        $getOperateur = function ($keyO) {

            if ($keyO == 0) {
                $operateur = '=';
            } else if ($keyO == 1) {
                $operateur = '!=';
            } else if ($keyO == 2) {
                $operateur = 'is not null';
            } else if ($keyO == 3) {
                $operateur = 'is null';
            }

            return $operateur;
        };

        $geometry_type = $couche->geometry;

        if ($couche->mode_sql) {
            $where = $couche->sql_complete;
        } else {
            foreach ($tags as $tag) {
                if (count($tags) > 1) {
                    if ($tag->operateur != 2 && $tag->operateur != 3) {
                        if ($where == '') {
                            $where = $tag->key . ' ' . $getOperateur($tag->operateur) . " '" . $tag->value . "'";
                        } else {
                            $where = $where . ' ' . $couche->condition . ' ' . $tag->key . ' ' . $getOperateur($tag->operateur) . " '" . $tag->value . "'";
                        }
                    } else {
                        if ($where == '') {
                            $where = $tag->key . ' ' . $getOperateur($tag->operateur);
                        } else {
                            $where = $where . ' ' . $couche->condition . ' ' . $tag->key . ' ' . $getOperateur($tag->operateur);
                        }
                    }
                } else {
                    if ($tag->operateur != 2 && $tag->operateur != 3) {
                        $where = $tag->key . ' ' . $getOperateur($tag->operateur) . " '" . $tag->value . "'";
                    } else {
                        $where = $tag->key . ' ' . $getOperateur($tag->operateur);
                    }
                }
            }
        }

        return ['where' => $where, 'geom' => $geometry_type, 'select' => $couche->select];
    }

    public function createOsmTable($schema, $table, $sql)
    {
        try {
            DB::beginTransaction();

            DB::select("CREATE SCHEMA IF NOT EXISTS $schema");
            DB::select('DROP TABLE IF EXISTS ' . $schema . '."' . $table . '"');
            DB::select('CREATE TABLE IF NOT EXISTS ' . $schema . '."' . $table . '"' . ' AS ' . $sql);

            DB::select('ALTER TABLE ' . $schema . '."' . $table . '"' . ' RENAME COLUMN geometry TO geom');
            DB::select('CREATE INDEX ' . $table . '_geometry_idx ON ' . $schema . '."' . $table . '"' . '   USING GIST(geom)');

            DB::commit();

            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Search Couche.
     *
     * @header Content-Type application/json
     * @queryParam q string required search value. Example: boulangerie
     */
    public function searchCouche(Request $request)
    {
        $q = $request->input('q');
        $couches = Couche::search($q)->get();
        foreach ($couches as $couche) {
            $couche->sousThematique = $couche->sousThematique()->get();
            foreach ($couche->sousThematique as $sousThematique) {
                $sousThematique->thematique = $sousThematique->thematique()->get();
            }
        }
        $success['couches'] = $couches;
        return $this->sendResponse($success, 'Couches récupérés avec succès.');
    }
}
