<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstanceTableSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('instances')->insert([
            'nom' => 'cameroun',
            'mapillary' => true,
            'comparator' => true,
            'altimetrie' => true,
            'download' => true,
            'routing' => true,
            'app_version' => '1.5.0',
            'app_github_url' => '',
            'app_email' => '',
            'app_whatsapp' => '',
            'app_facebook' => '',
            'app_twitter' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
