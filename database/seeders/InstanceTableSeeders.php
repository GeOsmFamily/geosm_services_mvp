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
            'nom' => env('INSTANCE_NAME'),
            'mapillary' => true,
            'comparator' => true,
            'altimetrie' => false,
            'download' => true,
            'routing' => true,
            'app_version' => '1.5.0',
            'app_github_url' => 'https://github.com/GeOsmFamily',
            'app_email' => 'infos@geo.sm',
            'app_whatsapp' => '237 694 69 86 07',
            'app_facebook' => 'https://www.facebook.com/GeOsm.Family/',
            'app_twitter' => 'https://twitter.com/GeOsm_Family',
            'mapillary_api_key' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
