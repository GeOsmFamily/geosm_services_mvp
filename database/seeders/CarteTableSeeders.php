<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarteTableSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cartes')->insert([

            [
                'groupe_carte_id' => 1,
                'nom' => 'OpenStreetMap',
                'url' => 'https://b.tile.openstreetmap.org/{z}/{x}/{y}.png',
                'image_url' => '/images/cartes/mapnik.png',
                'type' => 'xyz',
                'zmax' => '22',
                'zmin' => '1',
                'principal' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'groupe_carte_id' => 1,
                'nom' => 'MapBox Streets',
                'url' => 'https://api.mapbox.com/styles/v1/mapbox/streets-v9/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoibGFnaHJpc3NpIiwiYSI6ImNqMmxwOWFyZjAwMHYycXFrc3IydzNwanMifQ.SK90mbaIxLVKh4vSRxsHFA',
                'image_url' => '/images/cartes/mapbox.png',
                'type' => 'xyz',
                'zmax' => '22',
                'zmin' => '1',
                'principal' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'groupe_carte_id' => 1,
                'nom' => 'OSM Dark',
                'url' => 'https://tile.jawg.io/dark/{z}/{x}/{y}.png?api-key=KEzgT1q0xEDQ06n23POIRMJqrtuHZOoo4FPNm1GfrNEzEOcnaQxuznduTbaAvGg3',
                'image_url' => '/images/cartes/dark.png',
                'type' => 'xyz',
                'zmax' => '22',
                'zmin' => '1',
                'principal' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'groupe_carte_id' => 2,
                'nom' => 'MapBox Satellite',
                'url' => 'https://api.mapbox.com/styles/v1/mapbox/satellite-streets-v9/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoibGFnaHJpc3NpIiwiYSI6ImNqMmxwOWFyZjAwMHYycXFrc3IydzNwanMifQ.SK90mbaIxLVKh4vSRxsHFA',
                'image_url' => '/images/cartes/satellite.png',
                'type' => 'xyz',
                'zmax' => '22',
                'zmin' => '1',
                'principal' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'groupe_carte_id' => 2,
                'nom' => 'Esri Satellite',
                'url' => 'https://clarity.maptiles.arcgis.com/arcgis/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
                'image_url' => '/images/cartes/esri.png',
                'type' => 'xyz',
                'zmax' => '22',
                'zmin' => '1',
                'principal' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
