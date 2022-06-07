<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleTableSeeders::class);
        $this->call(UserTableSeeders::class);
        $this->call(GroupeCarteTableSeeders::class);
        $this->call(CarteTableSeeders::class);
    }
}
