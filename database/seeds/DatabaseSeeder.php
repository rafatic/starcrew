<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(goalsTableSeeder::class);
        $this->call(languagesTableSeeder::class);
        $this->call(rolesTableSeeder::class);
        
    }
}
