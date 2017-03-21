<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class goalsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('goals')->insert([
            'name' => 'raiding',
            'type' => '0',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('goals')->insert([
            'name' => 'Escort',
            'type' => '0',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ])/

        DB::table('goals')->insert([
            'name' => 'Mining',
            'type' => '0',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('goals')->insert([
            'name' => 'EA : Racing',
            'type' => '0',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('goals')->insert([
            'name' => 'EA : Vanduul Swarm',
            'type' => '0',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('goals')->insert([
            'name' => 'EA : Squadron Battle',
            'type' => '0',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('goals')->insert([
            'name' => 'EA : Battle Royale',
            'type' => '0',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('goals')->insert([
            'name' => 'EA : Star Marine',
            'type' => '0',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
    }
}
