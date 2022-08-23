<?php

namespace App\Http\Controllers\Api;

use App\Models\Ouvrage;
use Illuminate\Http\Request;

class OuvrageController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ouvrages = Ouvrage::all();


        return $this->sendResponse($ouvrages, 'Ouvrages retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ouvrage  $ouvrage
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $ouvrage = Ouvrage::find($id);

        if (is_null($ouvrage)) {
            return $this->sendError('Ouvrage not found.');
        }

        return $this->sendResponse($ouvrage, 'ouvrage retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ouvrage  $ouvrage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ouvrage $ouvrage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ouvrage  $ouvrage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ouvrage $ouvrage)
    {
        //
    }

    public function searchBySyndicat(Request $request)
    {
        $nomsyndicat = $request->nomsyndicat;

        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)->get();

        return $this->createGeojson($ouvrages);
    }

    public function searchByDepartement(Request $request)
    {
        $nomdepartement = $request->nomdepartement;

        $ouvrages = Ouvrage::where('nomdepartement', $nomdepartement)->get();


        return $this->createGeojson($ouvrages);
    }

    public function searchByCommune(Request $request)
    {
        $nomcommune = $request->nomcommune;

        $ouvrages = Ouvrage::where('nomcommunebe', $nomcommune)
            ->orWhere('nomcommunemr', $nomcommune)
            ->orWhere('nomcommuneml', $nomcommune)
            ->get();


        return $this->createGeojson($ouvrages);
    }

    public function globalSearch(Request $request)
    {
        $nomsyndicat = $request->nomsyndicat;
        $nomdepartement = $request->nomdepartement;
        $nomcommune = $request->nomcommune;
        $typeOuvrage = $request->typeouvrage;
        $typePointEau = $request->typepointeau;

        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
            ->where('nomdepartement', $nomdepartement)
            ->where('nomcommuneml', $nomcommune)
            ->orWhere('nomcommunemr', $nomcommune)
            ->orWhere('nomcommunebe', $nomcommune)
            ->where('typeouvrage', $typeOuvrage)
            ->orWhere('typepointeau', $typePointEau)
            ->get();


        return $this->createGeojson($ouvrages);
    }

    public function createGeojson($datas)
    {
        $response = array();
        foreach ($datas as $data) {
            $lon = floatval(str_replace(",", ".", $data->longitude));
            $lat = floatval(str_replace(",", ".", $data->latitude));
            $geometry = [
                "type" => "Point",
                "coordinates" => [$lon, $lat]
            ];

            $response[] = [
                "type" => "Feature",
                "geometry" => $geometry,
                "properties" => $data
            ];
        }

        $name = [
            "name" => "urn:ogc:def:crs:OGC:1.3:CRS84"
        ];

        $crs = [
            "type" => "name", "properties" => $name
        ];


        $geojson = [
            "type" => "FeatureCollection",
            "name" => "sql_statement",
            "crs" =>  $crs,
            "features" => $response
        ];

        return response()->json($geojson, 200);
    }
}
