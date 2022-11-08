<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SkinTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('skin_types')->insert([
            "skin_type" => "Any",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('skin_types')->insert([
            "skin_type" => "Normal",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('skin_types')->insert([
            "skin_type" => "Dry",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('skin_types')->insert([
            "skin_type" => "Oily",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('skin_types')->insert([
            "skin_type" => "Combination",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('skin_types')->insert([
            "skin_type" => "Sensitive",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
