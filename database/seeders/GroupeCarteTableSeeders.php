<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupeCarteTableSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groupe_cartes')->insert([
            [
                'nom' => 'Cartes de Base',
                'nom_en' => 'Base Maps',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Images Satellites',
                'nom_en' => 'Satellite Images',
                'created_at' => now(),
                'updated_at' => now(),
            ]

        ]);
    }
}
