<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HairTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hair_types')->insert([
            "hair_type" => "Any",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('hair_types')->insert([
            "hair_type" => "Wavy",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('hair_types')->insert([
            "hair_type" => "Curly",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('hair_types')->insert([
            "hair_type" => "Thick Hair",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('hair_types')->insert([
            "hair_type" => "Thin Hair",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('hair_types')->insert([
            "hair_type" => "Fine Hair",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('hair_types')->insert([
            "hair_type" => "Medium",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
